<?php

/**
 * Created by PhpStorm.
 * User: Hapon
 * Date: 2017/1/27
 * Time: 上午12:26
 */
class UserController extends Controller
{
    public function actionBill()
    {
        $this->render('bill');
    }

    public function actionProfile()
    {
        $this->render('profile');
    }

    public function actionInvite()
    {
        $this->render('invite');
    }
}