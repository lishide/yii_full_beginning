<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="<?php echo Yii::app()->baseUrl; ?>/css/base.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="<?php echo Yii::app()->baseUrl; ?>/css/layout.css" rel="stylesheet" type="text/css" media="all"/>
    <title><?php echo $this->seoTitle ? $this->seoTitle . '_' . Configs::getByKey('seo_title', Yii::app()->name) : Configs::getByKey('seo_title', Yii::app()->name); ?></title>
    <meta name="Keywords"
          content="<?php echo $this->seoKeywords ? $this->seoKeywords : Configs::getByKey('seo_keywords'); ?>"/>
    <meta name="Description"
          content="<?php echo $this->seoDescription ? $this->seoDescription : Configs::getByKey('seo_description'); ?>"/>
    <meta name="viewport"
          content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
</head>

<body>
<div id="header">
    <?php echo $content; ?>
</div>
</body>
</html>
