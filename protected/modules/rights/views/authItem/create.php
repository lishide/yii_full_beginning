<?php $this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type']))),
); ?>

<div class="nav">
    <div class="return">
        <?php echo CHtml::link('返回列表', Yii::app()->user->rightsReturnUrl);?>
    </div>
</div>

<div class="table_full">

	<div class="h_a">

        <?php echo Rights::t('core', 'Create :type', array(
		  ':type'=>Rights::getAuthItemTypeName($_GET['type']),
	    )); ?>
    </div>

	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>

</div>