<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionAboutus()
    {
        $this->render('aboutus');
    }
    
    public function actionNotice()
    {
        $this->render('notice');
    }
}