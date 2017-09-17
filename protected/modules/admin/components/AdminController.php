<?php
class AdminController extends RController
{
    public $cs = null;
    
    public function init()
    {
        parent::init();
//        _var(Yii::app()->user->isGuest);
//        _var(Yii::app()->user->checkAccess('Admin.Default.*'), true);
        if(!Yii::app()->user->isGuest && !Yii::app()->user->checkAccess('Admin.Default.*'))
            $this->redirect(array('/site/index'));

        Yii::app()->user->loginUrl = array('/admin/default/login');
        Yii::app()->errorHandler->errorAction = '/admin/default/error';
        $this->layout = 'main';

        $this->cs = Yii::app()->clientScript;
    }

    /**
     * 插入新tag标签
     * @param  string $tags  
     * @param  integer $forid 
     * @param  string $table 
     * @return [type]        [description]
     */
    protected function insertTags($tags, $forid, $table) {
        $tags = trim($tags);
        $forid = intval($forid);
        $tags = str_replace(',', ' ', $tags);
        $tags = str_replace('，', ' ', $tags);
        $tags = str_replace('　', ' ', $tags);
        $tags = explode(' ', $tags);
        $model = new Tags;
        foreach($tags as $tag) {
            $model->setIsNewRecord(true);
            $model->tag = $tag;
            $model->forid = $forid;
            $model->table = $table;
            $model->save();
            $model->primaryKey++;
        }
    }

    /**
     * 默认显示列表
     * @param  string $class 模型名称
     * @param  string $view  视图名称
     * @return [type]        [description]
     */
    protected function showList($class, $view = '') {
        $model = new $class('search');
        $model->unsetAttributes();

        if (isset($_GET[$class])) {
            $model->setAttributes($_GET[$class]);
        }

        if (!$view) {
            $view = strtolower($this->action->id);
        }

        $this->render($view, array('model' => $model));
    }

    protected function loadModel($class, $id = 0, $on = '') {
        $model = null;
        $id = intval($id);
        if($id>0){
            //$meta = call_user_func(array($class,'model'));
            //$model = $meta->findByPk($id);
            $model = CActiveRecord::model($class)->findByPk($id);

            if(!$model)
                throw new CHttpException (404,'Not Found!');

            if ($on != '')
                $model->setScenario($on);
        }else{
            if($on != '')
                $model = new $class($on);
            else
                $model = new $class;
        }

        return $model;
    }

    /**
     * 更新状态值（自动切换0、1）
     * @param  string  $className 表对应的类名
     * @param  integer $id    ID
     * @param  string  $field 字段名
     * @return integer        
     */
    public function actionTogglestatus($className, $id, $field = 'status') {
        $model = CActiveRecord::model($className)->findByPk($id);
        $model->$field = abs($model->$field - 1);
        $data = array('success' => 0);
        if($model->save()) {
            $data = array('success' => 1, 'result' => $model->$field);
        }
        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionGetkws() {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $data = $_POST['data'];
        $kws = Yii::app()->kws->getKws($data, 8);
        echo $kws;
        Yii::app()->end();
    }

    public function actionGetarea() {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $pid = intval($_POST['pid']);

        echo CHtml::tag('option', array('value' => ''), '请选择', true);
        
        $data = Area::model()->findAll('pid=:pid', array(':pid' => $pid));
        if (!count($data)) {
            Yii::app()->end();
        } else {
            $data = CHtml::listData($data, 'id', 'name');
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
            Yii::app()->end();
        }
    }
   
    public function filters() {
        return array(
            'rights',
        );
    } 
}