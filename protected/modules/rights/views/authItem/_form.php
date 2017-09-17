

<?php $form=$this->beginWidget('CActiveForm'); ?>
<table width="100%">
	<tbody>
	<tr>
		<th><?php echo $form->labelEx($model, 'name'); ?></th>
		<td><?php echo $form->textField($model, 'name', array('maxlength'=>255, 'class'=>'input')); ?></td>
		<td>
			<?php echo $form->error($model, 'name'); ?>
			<div class="fun_tips"><?php echo Rights::t('core', 'Do not change the name unless you know what you are doing.'); ?></div>
		</td>
	</tr>

	<tr>
		<th><?php echo $form->labelEx($model, 'description'); ?></th>
		<td><?php echo $form->textField($model, 'description', array('maxlength'=>255, 'class'=>'input length_5')); ?></td>
		<td><?php echo $form->error($model, 'description'); ?>
			<div class="fun_tips"><?php echo Rights::t('core', 'A descriptive name for this item.'); ?></div>
		</td>
	</tr>

	<?php if( Rights::module()->enableBizRule===true ): ?>

		<tr>
			<th><?php echo $form->labelEx($model, 'bizRule'); ?></th>
			<td><?php echo $form->textField($model, 'bizRule', array('maxlength'=>255, 'class'=>'input length_5')); ?></td>
			<td><?php echo $form->error($model, 'bizRule'); ?>
				<div class="fun_tips"><?php echo Rights::t('core', 'Code that will be executed when performing access checking.'); ?></div>
			</td>
		</tr>

	<?php endif; ?>

	<?php if( Rights::module()->enableBizRule===true && Rights::module()->enableBizRuleData ): ?>

		<tr>
			<th><?php echo $form->labelEx($model, 'data'); ?></th>
			<td><?php echo $form->textField($model, 'data', array('maxlength'=>255, 'class'=>'text-field')); ?></td>
			<td><?php echo $form->error($model, 'data'); ?>
				<div class="fun_tips"><?php echo Rights::t('core', 'Additional data available when executing the business rule.'); ?></div>
			</td>
		</tr>

	<?php endif; ?>



	</tbody>
</table>

	<div class="btn_wrap">
		<div class="btn_wrap_pd">
		<?php echo CHtml::submitButton(Rights::t('core', 'Save'), array('class' => 'btn btn_submit mr20')); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

