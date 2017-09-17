<?php

/**
 * This is the model class for table "pic".
 *
 * The followings are the available columns in table 'pic':
 * @property string $id
 * @property string $name
 * @property string $path
 * @property string $thumb_path
 * @property string $extension
 * @property integer $size
 * @property string $remark
 * @property string $ip
 * @property string $createtime
 * @property string $createby
 * @property integer $thumb_size
 */
class Pic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('path', 'required'),
			array('size, thumb_size', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('path, thumb_path, remark', 'length', 'max'=>200),
			array('extension', 'length', 'max'=>10),
			array('ip', 'length', 'max'=>15),
			array('createby', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, path, thumb_path, extension, size, remark, ip, createtime, createby', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '附件名',
			'path' => '原图地址',
			'thumb_path' => '缩略图地址',
			'extension' => '扩展名',
			'size' => '大小',
            'thumb_size' => '缩略图大小',
			'remark' => '备注',
			'ip' => 'IP',
			'createtime' => '上传时间',
			'createby' => '上传用户id',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('thumb_path',$this->thumb_path,true);
		$criteria->compare('extension',$this->extension,true);
		$criteria->compare('size',$this->size);
        $criteria->compare('thumb_size',$this->thumb_size);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('createby',$this->createby,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function checkFile(){
        $path = App()->params['uploadFileDir'] . $this->path;
        $thumbPath = App()->params['uploadFileDir'] . $this->path;

        if($this->path && file_exists($path) && is_file($path)){
            $this->extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $this->size = filesize($path);
        }else{
            $this->size = 0;
        }

        if($this->thumb_path && file_exists($thumbPath) && is_file($thumbPath)){
            $this->thumb_size = filesize($thumbPath);
        }else{
            $this->thumb_size = 0;
        }

        if($this->size != 0 && ($this->thumb_size == 0 || $this->path == $this->thumb_path)){
            $imageResize = Yii::createComponent('application.extensions.ImageResize.ImageResize');
            $res = $imageResize->resize($path, '', App()->params['thumb_length'], App()->params['thumb_trigger_size'], App()->params['thumb_quality']);
            if($res){
                $this->thumb_path = str_replace(App()->params['uploadFileDir'], '', $res['targetPath']);
                $this->thumb_size = $res['targetSize'];
            }else{
                $this->thumb_path = $this->path;
                $this->thumb_size = $this->size;
            }
        }
    }
}
