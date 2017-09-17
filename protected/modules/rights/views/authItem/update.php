<?php $this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::getAuthItemTypeNamePlural($model->type)=>Rights::getAuthItemRoute($model->type),
	$model->name,
); ?>

<div class="nav">
    <div class="return">
        <?php echo CHtml::link('返回列表', Yii::app()->user->rightsReturnUrl);?>
    </div>
</div>

<div id="updatedAuthItem" class="table_full">

	<div class="h_a">

    <?php echo Rights::getAuthItemTypeName($model->type); ?>

    <?php echo Rights::t('core', 'Update :name', array(
		':name'=>$model->name,
		':type'=>Rights::getAuthItemTypeName($model->type),
	)); ?>
    </div>

	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>

	<div class="relations span-11 last">

		<div class="h_a"><?php echo Rights::t('core', 'Relations'); ?></div>

		<?php if( $model->name!==Rights::module()->superuserName ): ?>

			<div class="parents">

				<div class="h_a"><?php echo Rights::t('core', 'Parents'); ?></div>

				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$parentDataProvider,
					'template'=>'{items}',
					'hideHeader'=>true,
					'emptyText'=>Rights::t('core', 'This item has no parents.'),
					'htmlOptions'=>array('class'=>''),
					'columns'=>array(
    					array(
    						'name'=>'name',
    						'header'=>Rights::t('core', 'Name'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'name-column'),
    						'value'=>'$data->getNameLink()',
    					),
    					array(
    						'name'=>'type',
    						'header'=>Rights::t('core', 'Type'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'type-column'),
    						'value'=>'$data->getTypeText()',
    					),
    					array(
    						'header'=>'&nbsp;',
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'actions-column'),
    						'value'=>'',
    					),
					)
				)); ?>

			</div>

			<div class="children">

				<div class="h_a"><?php echo Rights::t('core', 'Children'); ?></div>

				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$childDataProvider,
					'template'=>'{items}',
					'hideHeader'=>true,
					'emptyText'=>Rights::t('core', 'This item has no children.'),
					'htmlOptions'=>array('class'=>''),
					'columns'=>array(
    					array(
    						'name'=>'name',
    						'header'=>Rights::t('core', 'Name'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'name-column'),
    						'value'=>'$data->getNameLink()',
    					),
    					array(
    						'name'=>'type',
    						'header'=>Rights::t('core', 'Type'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'type-column'),
    						'value'=>'$data->getTypeText()',
    					),
    					array(
    						'header'=>'&nbsp;',
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'actions-column'),
    						'value'=>'$data->getRemoveChildLink()',
    					),
					)
				)); ?>

			</div>




				<?php if( $childFormModel!==null ): ?>

					<?php $this->renderPartial('_childForm', array(
						'model'=>$childFormModel,
						'itemnameSelectOptions'=>$childSelectOptions,
					)); ?>

				<?php else: ?>

					<p class="info"><?php echo Rights::t('core', 'No children available to be added to this item.'); ?>

				<?php endif; ?>

			</div>

		<?php else: ?>

			<p class="info">
				<?php echo Rights::t('core', 'No relations need to be set for the superuser role.'); ?><br />
				<?php echo Rights::t('core', 'Super users are always granted access implicitly.'); ?>
			</p>

		<?php endif; ?>

	</div>

</div>