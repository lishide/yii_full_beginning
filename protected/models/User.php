<?php

class User extends CActiveRecord
{
    public $repassword;
    public $opassword;
    public $verifycode;
    public $ordersCountDone, $ordersCountDoneMin, $ordersCountDoneMax;
    public $ordersCountReturned, $ordersCountReturnedMin, $ordersCountReturnedMax;
    public $lastOrdersDoneTime;
    public $ordersCountDoneLastMonth, $ordersCountDoneLastMonthMin, $ordersCountDoneLastMonthMax;
    public $shopowner;
    public $shopname;

//    public $_realname, $_sex, $_age, $_job, $_school_id;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    // 添加时间戳
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
//                'updateAttribute' => 'updateddate',
                'createAttribute' => 'createddate'
            ),
            'RegisterBehavior' => array(
                'class' => 'application.models.behaviors.RegisterBehavior',
            ),
        );
    }

    // 密码加密方式
    public function encryptPwd($pwd)
    {
        return md5($pwd);
    }

    // 保存之前
    public function beforeSave()
    {
//        $this->password = $this->encryptPwd($this->password);
        return true;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username', 'required'),
            array('username', 'unique'),
            array('phone', 'unique'),
            array('username, password', 'length', 'max' => 50),
            array('password_text, avatar, phone, point, addr, contact, tel, shopid, createdtime, lastlogin', 'safe'),

            array('opassword', 'required', 'on' => 'repass'),
            array('opassword', 'checkComapre', 'on' => 'repass'),
            array('password', 'required', 'on' => 'add'),
            array('password, repassword', 'required', 'on' => 'repass, reset'),
            array('password, repassword', 'length', 'min' => 6, 'on' => 'invite_reg, reg, add, repass, reset'),
            array('repassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的登录密码不一致', 'on' => 'repass, reset'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, lastlogin, password, shopname, shopowner, ordersCountDone, ordersCountReturned, lastOrdersDoneTime, ordersCountDoneLastMonth, ordersCountDoneLastMonthMin, ordersCountDoneLastMonthMax, ordersCountDone, ordersCountDoneMin, ordersCountDoneMax, ordersCountReturned, ordersCountReturnedMin, ordersCountReturnedMax', 'safe', 'on' => 'search'),
        );
    }

    public function checkComapre($attribute, $params)
    {
        if ($this->encryptPwd($this->$attribute) !== Yii::app()->user->getState('oldpw')) {
            $this->addError($attribute, '原登录密码不正确，请重新输入');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'shop' => array(self::BELONGS_TO, 'Shop', 'shopid'),
            'avatarModel' => array(self::BELONGS_TO, 'Pic', 'avatar'),
            'orders' => array(self::HAS_MANY, 'Orders', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => '用户名',
            'password' => $this->scenario == 'reset' ? '重设密码' : '登录密码',
            'password_text' => '密码明文',
            'repassword' => '确认密码',
            'opassword' => '旧密码',
            'lastlogin' => '最后登录时间',
            'avatar' => '头像',
            'phone' => '手机号',
            'point' => '积分',
            'addr' => '收货地址',
            'contact' => '收货联系人',
            'tel' => '收货电话',
            'shopid' => '店铺ID',
            'createddate' => '注册日期',
            'ordersCountDone' => '总完成单数',
            'ordersCountDoneLastMonth' => '30天完成单数',
            'ordersCountReturned' => '退单数',
            'lastOrdersDoneTime' => '上次购买',
            'shopname' => '店名',
        );
    }

    public function search($all = false, $noticeID = 0)
    {
        $criteria = new CDbCriteria;

        // sub query to retrieve the count of posts
        $tableName = Orders::model()->tableName();
        $monthAgo = _getTimeString(time() - 86400 * 30);
        $ordersCountDoneSQL = "(select count(*) from $tableName tb where tb.uid = t.id and tb.status = 4)";
        $ordersCountDoneLastMonthSQL = "(select count(*) from $tableName tb where tb.uid = t.id and tb.status = 4 and tb.createtime > '$monthAgo')";
        $ordersCountReturnedSQL = "(select count(*) from $tableName tb where tb.uid = t.id and tb.status = 6)";
        $lastOrdersDoneTimeSQL = "(select tb.createtime from $tableName tb where tb.uid = t.id and tb.status = 4 limit 0,1)";

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.username', $this->username, true);
        $criteria->compare('t.password', $this->password, true);
        $criteria->compare('t.lastlogin', $this->lastlogin, true);
        $criteria->compare('t.phone', $this->phone, true);
        $criteria->compare('t.point', $this->point, true);
        $criteria->compare('t.contact', $this->contact, true);
        $criteria->compare('t.tel', $this->tel, true);
        $criteria->compare('shop.name', $this->shopname, true);

        if($this->shopowner != ''){
            $criteria->compare('t.shopid', $this->shopowner ? '>0' : '=0');
        }

        $criteria->compare($ordersCountDoneSQL, '>='.$this->ordersCountDoneMin);
        $criteria->compare($ordersCountDoneSQL, '<='.$this->ordersCountDoneMax);
        $criteria->compare($ordersCountDoneLastMonthSQL, '>='.$this->ordersCountDoneLastMonthMin);
        $criteria->compare($ordersCountDoneLastMonthSQL, '<='.$this->ordersCountDoneLastMonthMax);
        $criteria->compare($ordersCountReturnedSQL, '>='.$this->ordersCountReturnedMin);
        $criteria->compare($ordersCountReturnedSQL, '<='.$this->ordersCountReturnedMax);
        $criteria->compare($lastOrdersDoneTimeSQL, $this->lastOrdersDoneTime, true);
        $criteria->with = ['shop', 'avatarModel', 'orders'];
        $criteria->select = [
            '*',
            $ordersCountDoneSQL . ' as ordersCountDone',
            $ordersCountReturnedSQL . ' as ordersCountReturned',
            $lastOrdersDoneTimeSQL . ' as lastOrdersDoneTime',
            $ordersCountDoneLastMonthSQL . ' as ordersCountDoneLastMonth',
        ];

        if($noticeID){
            $noticeDistributionList = Noticedistribution::model()->findAll([
                'condition' => "t.notice_id = $noticeID"
            ]);
            $uidList = [];
            foreach($noticeDistributionList as $distribution){
                $uidList[] = $distribution->uid;
            }

            $criteria->addNotInCondition('t.id', $uidList);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => $all ? 't.id asc' : 't.id desc',
                'attributes' => [
                    'ordersCountDone' => [
                        'asc' => 'ordersCountDone ASC',
                        'desc' => 'ordersCountDone DESC',
                    ],
                    'ordersCountReturned' => [
                        'asc' => 'ordersCountReturned ASC',
                        'desc' => 'ordersCountReturned DESC',
                    ],
                    'lastOrdersDoneTime'=>[
                        'asc' => 'lastOrdersDoneTime ASC',
                        'desc' => 'lastOrdersDoneTime DESC',
                    ],
                    'ordersCountDoneLastMonth'=>[
                        'asc' => 'ordersCountDoneLastMonth ASC',
                        'desc' => 'ordersCountDoneLastMonth DESC',
                    ],
                    'shop.name',
                    '*',
                ],
            ),
            'pagination' => array(
                'pageSize' => $all ? PHP_INT_MAX : App()->params['pagesize_admin'],
            ),
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function findByPhone($phone)
    {
        return User::model()->find('phone=:phone', array(':phone' => $phone));
    }
}
