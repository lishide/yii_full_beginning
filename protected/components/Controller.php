<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $cs = null;

    // SEO INFOMATION
    public $seoTitle = null;
    public $seoKeywords = null;
    public $seoDescription = null;

    public $searchType = 'piece';
    public $searchText = '';

    public $sjdomainCount = 0;

    public function init()
    {
        parent::init();
        $this->cs = Yii::app()->clientScript;
        // $this->seoTitle 		 = Configs::getByKey('seo_title', Yii::app()->name);
        // $this->seoKeywords 	 = Configs::getByKey('seo_keywords');
        // $this->seoDescription = Configs::getByKey('seo_description');

        $sessionHandler = new CHttpSession();
        $sessionHandler->open();
        if (isset($sessionHandler['searchType'])) {
            $this->searchType = $sessionHandler['searchType'];
        }
    }
}