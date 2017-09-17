<?php

class ConfigController extends AdminController
 {

	/**
	 * Lists all models.
	 */
	public function actionList() {
        $model = new Config('search');
        $model->unsetAttributes();
        if (isset($_GET['Config']))
        $model->setAttributes($_GET['Config']);

        $this->render('list', array('model' => $model));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
        $model = null;
        $model = Config::model()->findByPk($id);

        $this->layout = 'dialog';
        $this->render('view', array('model' => $model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id = 0) {
        $model = null;

        if ($id) {
            $model = Config::model()->findByPk($id);
            if ($model) {
                $model->setScenario('edit');
            }
        }

        if (!$model) {
            $model = new Config('add');
        }

        $isnew = $model->isNewRecord;

        if (isset($_POST['Config'])) {
            $result = array(
                'state' => 'fail',
                'message' => '未知错误，请刷新页面重试',
            );

            $model->setAttributes($_POST['Config']);

            if ($model->validate() && $model->save()) {
                $result['state'] = 'success';
                $result['message'] = $isnew ? '添加成功' : '编辑成功';
            } else {
                $errors = $model->getErrors();
                $errors = array_values($errors);
                $result['message'] = $errors[0];
            }
            echo CJSON::encode($result);
            Yii::app()->end();
        }

        $this->render('update', array('model' => $model, 'isnew' => $isnew));
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id = 0) {
        $model = Config::model()->findByPk($id);
        $ret = array(
            'state' => 'fail',
            'message' => '未知错误，请刷新页面重试',
        );

        if (! $model) {
            $ret['message'] = '对不起，要删除的内容没有找到，请刷新页面重试';
        } else {
            $model->delete();
            $ret['state'] = 'success';
        }
        echo CJSON::encode($ret);
        Yii::app()->end();
	}
}
