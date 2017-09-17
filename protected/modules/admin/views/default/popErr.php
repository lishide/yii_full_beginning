<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>wind management</title>
    <link href="<?php echo Yii::app()->baseUrl; ?>/css/admin_style.css" rel="stylesheet">
    <script>
        // 全局变量 Global Variables
        var GV = {
            JS_ROOT: "<?php echo Yii::app()->baseUrl;?>/js/", // js目录
            JS_VERSION: "1.0.0",     // js版本号
            REGION_CONFIG: {},
            SCHOOL_CONFIG: {},
            URL: {
                LOGIN: '<?php echo $this->createUrl("site/login");?>',            // 后台登录地址
                IMAGE_RES: '<?php echo Yii::app()->baseUrl;?>/images'
            }
        };
    </script>


</head>
<body class="body_none">

<div class="error_cont">
    <ul style="padding-top:10px;">
        <li><?php echo CHtml::encode($message); ?></li>
    </ul>

</div>

<?php
$cs = Yii::app()->clientScript;

$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/wind.js', 2);
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/common.js', 2);
//$cs->registerScriptFile(Yii::app()->baseUrl.'/js/dialog.js');
//$cs->registerScriptFile(Yii::app()->baseUrl.'/js/ajaxForm.js');

Yii::app()->clientScript->registerCss('bodywidth', "
    .body_none {width: 400px;}
");
?>
</body>
</html>
