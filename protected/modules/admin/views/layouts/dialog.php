<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo Yii::app()->name; ?></title>
    <link href="<?php echo Yii::app()->baseUrl; ?>/css/admin_style.css" rel="stylesheet">
    <script>
        // 全局变量 Global Variables
        var GV = {
            JS_ROOT: "<?php echo Yii::app()->baseUrl;?>/js/admin/", // js目录
            JS_VERSION: "1.0.0",     // js版本号
            URL: {
                LOGIN: '<?php echo $this->createUrl("/admin/default/login");?>',            // 后台登录地址
                IMAGE_RES: '<?php echo Yii::app()->baseUrl;?>/images'
            }
        };
    </script>


</head>
<body class="body_none">

<?php echo $content; ?>

<?php
$this->cs->registerCoreScript('jquery');
$this->cs->registerScriptFile(Yii::app()->baseUrl . '/js/admin/wind.js', 2);
$this->cs->registerScriptFile(Yii::app()->baseUrl . '/js/admin/pages/admin/common/common.js', 2);
?>
</body>
</html>