<?php

class DefaultController extends AdminController
{
    public function allowedActions()
    {
        return 'login';
    }

    public function actionIndex()
    {
        $allMenu = array(
            'system' => array(
                'name' => '管理首页',
                'items' => array(
                    array('name' => '管理首页', 'id' => 'admin_welcome', 'node' => 'Admin.Default.welcome'),
                    array('name' => '参数设置', 'id' => 'admin_setting', 'node' => 'Admin.System.setting'),
                    array('name' => '关于页面', 'id' => 'admin_about', 'node' => 'Admin.System.aboutupdate'),
                    array('name' => '修改密码', 'id' => 'admin_repass', 'node' => 'Admin.Default.repass'),
                ),
            ),

            'user' => array(
                'name' => '用户管理',
                'items' => array(
                    array('name' => '用户列表', 'id' => 'admin_userlist', 'node' => 'Admin.User.list'),
                ),
            ),

            'power' => array(
                'name' => '系统管理',
                'items' => array(
                    array('name' => '授权管理', 'id' => 'assign', 'node' => 'Rights.Assignment.view'),
                     array('name' => '权限管理', 'id' => 'permission', 'node' => 'Rights.AuthItem.permissions'),
                     array('name' => '角色管理', 'id' => 'role', 'node' => 'Rights.AuthItem.roles'),
                     array('name' => '任务管理', 'id' => 'task', 'node' => 'Rights.AuthItem.tasks'),
                     array('name' => '操作管理', 'id' => 'operate', 'node' => 'Rights.AuthItem.operations'),
                ),
            ),
        );

        $allRoles = _getAssignedRoles();

        if(!$allRoles['Admin'])
        {
            unset($allMenu['system']['items'][1]);
            unset($allMenu['system']['items'][2]);

            unset($allMenu['user']);

            unset($allMenu['power']);
        }

        if (YII_DEBUG || !Yii::app()->user->hasState('menu')) { //开启DEBUG时不缓存菜单，方便开发
            $menuArr = array();
            foreach ($allMenu as $k => $v) {
                $sublink = array();

                foreach ($v['items'] as $node) {
                    $sublink[] = $node;
                }

                if (count($sublink)) {
                    $menuArr[$k]['name'] = $v['name'];
                    $menuArr[$k]['id'] = $k;
                    $menuArr[$k]['parent'] = 'root';
                    $items = array();
                    foreach ($sublink as $sl) {
                        $nodes = explode('.', $sl['node']);
                        $nodestr = '/' . lcfirst($nodes[0]) . '/' . lcfirst($nodes[1]);
                        if (isset($nodes[2]))
                            $nodestr .= '/' . lcfirst($nodes[2]);

                        $params = array();
                        if (isset($sl['params']))
                            $params = $sl['params'];
                        $href = $this->createUrl($nodestr, $params);

                        $items[$sl['id']] = array(
                            'id' => $sl['id'],
                            'name' => $sl['name'],
                            'parent' => $k,
                            'url' => $href,
                        );
                    }
                    $menuArr[$k]['items'] = $items;
                }

            }

            //var_dump($menuArr);
            Yii::app()->user->setState('menu', $menuArr, array());
        }

        $this->layout = false;
        $this->render('index', array('menu' => CJSON::encode(Yii::app()->user->getState('menu'))));
    }

    public function actionLogin()
    {
        if (!Yii::app()->user->isGuest)
            $this->redirect(array('site/index'));

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $this->redirect($this->createUrl("/admin/default/index"));
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function actionWelcome()
    {
        $this->render('welcome', array());
    }

    public function actionRepass()
    {
        $model = User::model()->findByPk(App()->user->id);
        $model->setScenario('repass');
        App()->user->setState('oldpw', $model->password);
        $model->password = '';
        if (isset($_POST['User'])) {
            $result = array(
                'state' => 'fail',
                'message' => '未知错误，请刷新页面重试',
            );

            $model->setAttributes($_POST['User']);
//            _var($_POST['User'], true);

            if ($model->validate() && $model->save()) {
                $model->password = md5($_POST['User']['password']);
                $model->password_text = $_POST['User']['password'];
                $model->setScenario('edit');
                $model -> save();
                $result['state'] = 'success';
                $result['message'] = '修改成功';
            } else {
                $errors = $model->getErrors();
                $errors = array_values($errors);
                $result['message'] = $errors[0];
            }
            echo CJSON::encode($result);
            Yii::app()->end();
        }
        $this->render('repass', array(
            'model' => $model,
        ));
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else {
                if ($error['code'] == 405) {
                    $this->render('popErr', $error);
                } else {
                    // $ref = Yii::app()->request->urlReferrer;
                    // $error['url'] = $ref ? $ref : '/admin/default/welcome';
                    $error['url'] = $this->createUrl('/admin/default/welcome');
                    $this->render('error', $error);
                }
            }
        }
    }
}