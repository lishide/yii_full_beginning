<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '网站名称',
    'language' => 'zh_cn',
    'timeZone' => 'Asia/Shanghai',
    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'application.extensions.PHPExcel.*',
    ),

    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),

        'admin',
        'web',

        'rights' => array(
            'superuserName' => 'Admin',
            'install' => false,
        ),
    ),

    'controllerMap' => array(
        'ueditor' => array(
            'class' => 'ext.baiduUeditor.UeditorController',
        ),
    ),

    // application components
    'components' => array(
        'user' => array(
            'class' => 'RWebUser',
            'loginUrl' => array('/site/login'),
            'allowAutoLogin' => true,
        ),

        'authManager' => array(
            'class' => 'RDbAuthManager',
        ),

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '/' => '/admin',
                '/admin/site/index/' => '/admin',
                '/apiv1/' => '/apiv1/default/index',
            ),
        ),

        'account' => array(
            'class' => 'application.components.Account',
        ),

        'cart' => array(
            'class' => 'application.components.PieceCart',
        ),

        'kws' => array(
            'class' => 'application.components.Keywords',
            'apitype' => 1,
        ),

        'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1;dbname=yii_full_beginning',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'enableParamLogging' => true
        ),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    'levels' => 'trace',
                    'categories' => 'system.db.*'
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
        /*'cache'=>array(
            'class'=>'CMemCache',
            'servers'=>array(
                array(
                    'host'=>'127.0.0.1',
                    'port'=>11211,
                ),
            'keyPrefix' => '',
            'hashKey' => false,
            'serializer' => false
            ),
        ),*/
        'cache' => array(
            'class' => 'CFileCache',
            'directoryLevel' => '2', // 缓存文件的目录深度
        ),
    ),

    'params' => array(
        'web_resource_version' => '1.0.1',        //网页资源版本号
        'unique_session_id' => md5(time() . mt_rand() . mt_rand()),
        'pagesize_api' => 10,               // API默认页面大小
        'pagesize_api_max' => 500,          // API最大页面大小
        'pagesize_admin' => 20,             // 后台默认页面大小
        'smscode_expire' => 600,            // 短信验证码有效期(秒)
        'token_expire' => 86400 * 180,      // 用户token过期时间(秒)   半年
        'shop_slides_max' => 8,             // 店铺轮播图最大数量
        'goods_slides_max' => 8,            // 商品轮播图最大数量
        'thumb_length' => 500,             // 当原始图片长宽超过此值时进行压缩
        'thumb_trigger_size' => 500,        // 当原始图片大小超过此值时进行压缩(kb)
        'thumb_quality' => 80,              // 缩略图品质

//        'picdomain' => 'http://mac/dinghuobao/upload/',
        'picdomain' => 'http://dhb.myshiningstone.com/upload/',

        // 网站文件根目录
//        'uploadFileDir' => '/Users/Hapon/Workspace/www/dinghuobao/upload/',
        'uploadFileDir' => '/var/www/sites/myshiningstone.com/dhb.myshiningstone.com/www/upload/',

        // 图片上传目录
        'uploadDir' => '/upload/system/', // 一定以/开头(网站根目录开始)，/结尾

        /** -------------- 通用 --------------- **/
        'boolean' => array(
            0 => '否',
            1 => '是',
        ),

        'haveornot' => array(
            0 => '无',
            1 => '有',
        ),
    )
);
