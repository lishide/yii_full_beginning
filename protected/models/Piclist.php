<?php

/**
 * This is the model class for table "piclist".
 *
 * The followings are the available columns in table 'piclist':
 * @property integer $id
 * @property integer $target_id
 * @property integer $target_type
 * @property integer $pic_id
 * @property string $link
 * @property string $createtime
 * @property integer $createby
 * @property string $edittime
 * @property integer $editby
 */
class Piclist extends CActiveRecord
{
    public $picpath, $picpath_hd;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'piclist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('target_id, target_type, pic_id', 'required'),
			array('target_id, target_type, pic_id, createby, editby', 'numerical', 'integerOnly'=>true),
			array('link', 'length', 'max'=>500),
			array('edittime, picpath, picpath_hd', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, target_id, target_type, pic_id, link, createtime, createby, edittime, editby', 'safe', 'on'=>'search'),
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
            'pic' => array(self::BELONGS_TO, 'Pic', 'pic_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'target_id' => '目标ID',
			'target_type' => '目标类型(1:店铺 2:商品)',
			'pic_id' => '图片ID',
			'link' => '链接',
			'createtime' => '创建时间',
			'createby' => '创建人',
			'edittime' => '修改时间',
			'editby' => '修改人',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('target_id',$this->target_id);
		$criteria->compare('target_type',$this->target_type);
		$criteria->compare('pic_id',$this->pic_id);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('createby',$this->createby);
		$criteria->compare('edittime',$this->edittime,true);
		$criteria->compare('editby',$this->editby);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Piclist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
