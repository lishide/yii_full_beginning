<?php

/**
 * This is the model class for table "sms".
 *
 * The followings are the available columns in table 'sms':
 * @property integer $id
 * @property string $type
 * @property string $server_code
 * @property integer $sms
 * @property string $phone
 * @property integer $time
 * @property string $client_ip
 * @property string $extra
 */
class Sms extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, phone, time', 'required'),
			array('sms, time', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>10),
			array('server_code', 'length', 'max'=>20),
			array('phone', 'length', 'max'=>11),
			array('client_ip', 'length', 'max'=>15),
			array('extra', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, server_code, sms, phone, time, client_ip, extra', 'safe', 'on'=>'search'),
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
			'type' => '验证码类型',
			'server_code' => '服务器状态码',
			'sms' => '验证码内容',
			'phone' => '电话号码',
			'time' => 'API返回时间',
			'client_ip' => '客户端IP',
			'extra' => '其他信息',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('server_code',$this->server_code,true);
		$criteria->compare('sms',$this->sms);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('client_ip',$this->client_ip,true);
		$criteria->compare('extra',$this->extra,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
