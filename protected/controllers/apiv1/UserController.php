<?php

class UserController extends Controller
{
    /**
     * 注册账号
     */
    public function actionReg()
    {
        $emptyCheck[] = array('phone', '请提交手机号');
        $emptyCheck[] = array('password', '请提交密码');
        $emptyCheck[] = array('invitecode', '请提交邀请码');
        $emptyCheck[] = array('addr', '请提交收货地址');
        $emptyCheck[] = array('contact', '请提交收货联系人');

        $p = _getParams($emptyCheck);

        //检查手机号码格式
        if (!_checkPhone($p['phone'])) {
            _error(308);
        }

        //验证密码格式
        if (!_checkPassword($p['password'])) {
            _error(103);
        }

        $invitecode = Invitecode::model()->find('code=:code', array(':code' => $p['invitecode']));
        if (empty($invitecode) || $invitecode->uid != 0)
            _error(106);

        $shopname = '';
        if ($invitecode->type == 2) {
            if (!isset($p['shopname']))
                _error(100, '使用经销商邀请码注册需要提交店铺名');

            $shopname = $p['shopname'];
            $shop = Shop::findByName($shopname);
            if ($shop)
                _error(107);
        }

        //按照手机号码获取用户信息
        $user = User::findByPhone($p['phone']);

        //判断用户是否存在
        if ($user)
            _error(102);
        else {
            // 注册
            $user = new User();
            $user->username = $p['phone'];
            $user->phone = $p['phone'];
            $user->password = md5($p['password']);
            $user->password_text = $p['password'];
            $user->addr = $p['addr'];
            $user->contact = $p['contact'];
            $user->tel = $p['phone'];

            $dbres = $user->save();
            if ($dbres) {
                $invitecode->uid = $user->id;
                $invitecode->save();

                // 邀请码是经销商, 添加店铺
                if ($invitecode->type == 2) {
                    $shop = new Shop();
                    $shop->name = $shopname;
                    $shop->tel = $user->phone;
                    $shop->createby = $user->id;
                    $shop->save();

                    $user->shopid = $shop->id;
                    $user->save();

                    //添加权限
                    Rights::assign('Shop', $user -> id);
                }

                // 添加消息
                $time = _getTimeString();
                $noticeList = Notice::model()->findAll([
                    'condition' => "send_reg = 1 and (send_reg_before>:time or isnull(send_reg_before)) and enable = 1 and del = 0",
                    'params' => [':time' => $time]
                ]);

                foreach ($noticeList as $notice) {
                    $distribution = new Noticedistribution();
                    $distribution->uid = $user->id;
                    $distribution->notice_id = $notice->id;
                    $distribution->createby = $user->id;
                    $distribution->save();
                }

                $data[] = array(
                    'userid' => $user->id,
                    'username' => $user->username
                );
                _OK($data);
            }

            _error(9);
        }
    }

    /**
     * 登录(获取token和账号信息)
     */
    public function actionLogin()
    {
        $emptyCheck[] = array('phone', '请提交手机号', true);
        $emptyCheck[] = array('password', '请提交密码', true);

        $p = _getParams($emptyCheck);

        $user = User::findByPhone($p['phone']);

        if (empty($user)) {
            _error(101);
        } else if ($user->password != $p['password']) {
            _error(110);
        }

        $res = _convertModelToArray($user);
        $res = _unset($res, array('password', 'password_text', 'lastlogin', 'createddate'));

        $time = time();
//        $token = Token::model()->find('type=2 and aid=0 and uid=:uid and time>:time', array(':uid' => $user->id, ':time' => $time));
        $token = null;

        if (!$token) {
            Token::model()->deleteAll('uid=:uid and type=2 and aid=0', array(':uid' => $user->id));
            $token = new Token();
            $token->type = 2;
            $token->aid = 0;
            $token->uid = $user->id;
            $token->token = _generateToken($user->id);
            $token->time = '' . ($time + App()->params['token_expire']);
            $token->save();
        }

        $res['avatar'] = _getPicURLByModel($user->avatarModel);
        $res['token'] = $token->token;
        $res['token_expire_time'] = $token->time;

        _OK($res);
    }

    /**
     * 个人资料
     */
    public function actionProfile()
    {
        $user = _auth();

        $res = _convertModelToArray($user);
        $res = _unset($res, array('password', 'password_text', 'lastlogin', 'createddate'));
        $res['avatar'] = _getPicURLByModel($user->avatarModel);

        _OK($res);
    }
    
    /**
     * 个人积分
     */
    public function actionPoint()
    {
        $user = _auth();
        $res = array();
        $res['uid'] = $user->id;
        $res['point'] = $user->point;
        
        _OK($res);
    }

    /**
     * 修改个人资料
     */
    public function actionEditprofile()
    {
        $p = _getParams([
            ['username', '请提交用户名'],
            ['phone', '请提交手机号'],
            ['addr', '请提交收货地址'],
            ['contact', '请提交收货人'],
            ['tel', '请提交收货电话']
        ]);

        $user = _auth();
        $uid = $user->id;

        //检查用户名格式
        if (!_checkUsername($p['username'])) {
            _error(99, '用户名只能包含中英文、数字、下划线，4-20个字符');
        }

        //检查手机号码格式
        if (!_checkPhone($p['phone'])) {
            _error(308, '请提交正确的手机号');
        }

        //检查手机号码格式
        if (!_checkPhone($p['tel'], false)) {
            _error(308, '请提交正确的收货电话');
        }

        $user->username = $p['username'];
        $user->phone = $p['phone'];
        $user->addr = $p['addr'];
        $user->contact = $p['contact'];
        $user->tel = $p['tel'];

        $user->save();
        if ($user->hasErrors()) {
            _error(99, _getLastModelError($user));
        }

        $this->actionProfile();
    }

    /**
     * 修改头像
     */
    public function actionEditavatar()
    {
        $user = _auth();
        $uid = $user->id;

        $Image = Yii::createComponent('application.extensions.ImageUpload.ImageUpload');
        $res = $Image->upload('avatar', 'avatar', $user->id);
        if ($res['errorcode'] === 1) {
            $pic = new Pic();
            $pic->name = "用户 $uid 头像";
            $pic->path = $res['path'];
            $pic->extension = $res['extension'];
            $pic->size = $res['size'];
            $pic->ip = _getClientIP();
            $pic->createby = $uid;

            $pic->checkFile();

            $pic->save();

            $user->avatar = $pic->id;
            if ($user->save())
                $this->actionProfile();
            else {
                $res['errorcode'] = 9;
            }
        }

        _error($res['errorcode'], $res['errormsg']);
    }

    /**
     * 修改密码
     */
    public function actionChangepwd()
    {
        $p = _getParams([
            ['oldpwd', '请提交旧密码'],
            ['newpwd', '请提交新密码']
        ]);

        $user = _auth();
        $uid = $user->id;

        if ($p['oldpwd'] != $user->password) {
            _error(110, '旧密码不正确');
        }

        //验证密码格式
        if (!_checkPassword($p['newpwd'])) {
            _error(103);
        }

        $user->password = md5($p['newpwd']);
        $user->password_text = $p['newpwd'];

        if ($user->save()) {
            Token::model()->deleteAll([
                'condition' => "uid = $uid and type = 2 and aid = 0"
            ]);
            _OK(['msg' => '修改成功, 请重新登录']);
        } else {
            _error(9, _getLastModelError($user));
        }
    }
}