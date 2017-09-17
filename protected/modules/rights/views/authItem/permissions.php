<?php $this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Permissions'),
); ?>

<div id="permissions" class="table_list">

	<div class="h_a"><?php echo Rights::t('core', 'Permissions'); ?></div>

	<div class="prompt_text">
        <ul>
        	<li>在这里您可以查看和管理分配给每个角色的权限。</li>
        	<li>授权项中可以对角色、项目、操作进行管理。</li>
		</ul>
	</div>

	<div class="mb10"><?php echo CHtml::link('生成操作节点', array('authItem/generate'), array(
	   	'class'=>'btn',
	)); ?></div>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'template'=>'{items}',
		'emptyText'=>Rights::t('core', 'No authorization items found.'),
		'htmlOptions'=>array('class'=>''),
		'columns'=>$columns,
	)); ?>

	<p class="info">*) <?php echo Rights::t('core', 'Hover to see from where the permission is inherited.'); ?></p>

	<script type="text/javascript">

		/**
		* Attach the tooltip to the inherited items.
		*/
		jQuery('.inherited-item').rightsTooltip({
			title:'<?php echo Rights::t('core', 'Source'); ?>: '
		});

		/**
		* Hover functionality for rights' tables.
		*/
		$('#rights tbody tr').hover(function() {
			$(this).addClass('hover'); // On mouse over
		}, function() {
			$(this).removeClass('hover'); // On mouse out
		});

	</script>

</div>
