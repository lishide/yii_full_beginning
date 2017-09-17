<?php

class SystemController extends AdminController
{
    public function actionSetting()
    {
        $configs = require(Yii::app()->basePath . DS . 'config' . DS . 'sysconf.php');
        $keys = array();
        $models = array();

        foreach ($configs as $t => $c) {
            $keys = array_merge($keys, array_keys($c['options']));
        }

        foreach ($keys as $key) {
            $models[$key] = Configs::getByKey($key, 'model');
        }

        if (isset($_POST['Configs'])) {
            $result = array();
            $result['state'] = 'success';
            $result['message'] = '编辑成功！';

            foreach ($keys as $key) {
                if (isset($_POST['Configs'][$key]))
                    $models[$key]->setAttributes($_POST['Configs'][$key]);
                if ($models[$key]->validate()) {
                    $models[$key]->save();
                }
            }

            echo CJSON::encode($result);
            Yii::app()->end();
        }

        $this->render('setting', array('models' => $models, 'configs' => $configs));
    }

    public function actionAboutupdate()
    {
        $model = null;
        $model = Configs::model()->find([
            'condition' => "t.key=:key",
            'params' => [':key' => 'about']
        ]);

        if ($model)
            $model->setScenario('edit');

        $isnew = $model->isNewRecord;

        $result = array();
        $result['state'] = 'success';
        $result['message'] = $model->isNewRecord ? '录入成功！' : '编辑成功！';

        if (isset($_POST['Configs'])) {
            $model->setAttributes($_POST['Configs']);
            if ($model->validate() && $model->save()) {
                $result['referer'] = $this->createUrl('/admin/system/aboutupdate');
                echo CJSON::encode($result);
            } else {
                $result = array();
                $result['state'] = 'fail';
                $errors = $model->getErrors();
                $errors = array_values($errors);
                $result['message'] = $errors[0];
                echo CJSON::encode($result);
            }
            Yii::app()->end();
        }

        $this->render('aboutupdate', array('model' => $model, 'isnew' => $isnew));
    }

}