<?php
class UploadAction extends CAction {
    public $basedir = '';
    public $is_cover = false;    //是否允许缩略图覆盖原图，一般用于上传头像等
    public $sub_dir = null;      //自定义存储目录，为null是表示自动按年月日创建目录
    public $model_name = 'Pictures';        //保存的模型名称

    /**
     * @var array $thumbs 可指定多组宽高用来生成缩略图，可以只指定宽或高，未指定的自动计算
     */
    public $thumbs = array();

    protected $handler = null;  //上传句柄

    protected $ext = '';
    protected $upload_dir = '';
    protected $thumb_dir = '';
    protected $year = '';
    protected $m = '';
    protected $d = '';

    protected $result = array('success' => false, 'message' => '', 'thumbs' => array(), 'source' => '');

    protected function getUploadInstance() {
        $this->handler = CUploadedFile::getInstanceByName('filedata');
        if (!$this->handler) {
            $this->handler = CUploadedFile::getInstanceByName('Filedata');
        }
        if (!$this->handler) {
            $this->result['message'] = '上传异常，请检查上传控件是否加载成功！';
            echo json_encode($this->result);
            Yii::app()->end();
        }
    }

    public function run() {
        $this->getUploadInstance();

        //创建唯一文件名
        $ext = $this->handler->getExtensionName();
        $rand = rand(10000, 99999);
        $salt = strtoupper(md5(time() . $rand));
        $filename = $salt . '.' . $ext;

        $this->createFullPath();

        $fullpath = $this->upload_dir . DS . $filename;

        $this->handler->saveAs($fullpath);
        $this->handler->reset();

        $subdir = str_replace(DS, '/', $this->sub_dir);
        $thumbs = array();

        if (is_array($this->thumbs) && count($this->thumbs)) {
            include Yii::app()->basePath . DS . 'vendor'. DS . 'phpthumb'. DS .'ThumbLib.inc.php';
            foreach($this->thumbs as $it) {
                $iwidth = isset($it['width']) ? intval($it['width']) : 0;
                $iheight = isset($it['height']) ? intval($it['height']) : 0;
                if($iwidth > 0 || $iheight > 0) {
                    //创建缩略图
                    $thumb = PhpThumbFactory::create($fullpath);

                    $thumbname = $this->thumb_dir . DS . $salt . '_' . $iwidth .'x'. $iheight . '.' . $ext;
                    if ($this->is_cover) //覆盖原图
                        $thumbname = $fullpath;

                    if ($iwidth == 0 || $iheight == 0)
                        $thumb->resize($iwidth, $iheight)->save($thumbname);
                    else
                        $thumb->adaptiveResize($iwidth, $iheight)->save($thumbname);
                    if (!$this->is_cover)
                        $thumbs[] = Yii::app()->baseUrl . '/upload/' . $subdir . '/thumb/' . $salt . '_'. $iwidth . 'x' . $iheight . '.' . $ext;
                    else {
                        $thumbs[] = Yii::app()->baseUrl . '/upload/' . $subdir . '/' . $filename;
                        break;
                    }
                }
            }
        }

        $this->result['success'] = true;
        $this->result['message'] = '上传成功';
        $this->result['source'] = Yii::app()->baseUrl . '/upload/' . $subdir . '/' . $filename;

        $this->result['thumbs'] = $thumbs;

        $forid = isset($_POST['forid']) ? trim($_POST['forid']) : '';
        $table = isset($_POST['table']) ? trim($_POST['table']) : '';
        if ($forid && $table) {
            $model = new $this->model_name;
            $model->forid = $forid;
            $model->table = $table;
            $model->pic   = $this->result['source'];
            $model->thumb = $this->result['thumbs'][0];
            $model->intro = '';
            $model->sort  = 100;
            $model->save();
            $model->refresh();
            $this->result['id'] = $model->id;
            $this->result['sort'] = $model->sort;
        }

        echo json_encode($this->result);
        Yii::app()->end();
    }

    /**
     * 按当前日期创建完整目录路径
     * 若目录不存在，则自动创建
     */
    protected function createFullPath() {
        $basedir = $this->upload_dir = ($this->basedir ? $this->basedir : Yii::app()->basePath . DS . '..' . DS . 'upload');

        if (!$this->sub_dir) {
            $this->sub_dir = date('Y') . DS . date('m') . DS . date('d');
        }

        $subdir = $this->sub_dir;

        $this->upload_dir .= DS . $this->sub_dir;
        $this->thumb_dir = $this->upload_dir;
        if (!$this->is_cover) {
            $this->thumb_dir .= DS . 'thumb';
            $subdir .= DS . 'thumb';
        }

        $tmp = explode(DS, $subdir);
        $count = count($tmp);
        $cur_dir = DS;
        for ($i = 0; $i < $count; $i++) {
            $cur_dir .= $tmp[$i] . DS;

            if (file_exists($basedir . $cur_dir) && is_dir($basedir . $cur_dir)) {
                continue;
            }

            @mkdir($basedir . $cur_dir, 0777);
            file_put_contents($basedir . $cur_dir . 'index.html', 'Access Denied!'); //创建目录安全文件
        }

    }

}
