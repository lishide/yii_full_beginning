<div class="table_full">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions' => array('class' => 'J_ajaxForm'))); ?>
	
    <table width="100%" style="margin-bottom: 0px; display: table;">
        <tbody>
            <th>
                请选择：
            </th>
            <td>
                <?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); ?>
            </td>
            <td>
                <?php echo $form->error($model, 'itemname'); ?>
            </td>
        </tbody>
	</table>

	<div class="btn_wrap">
        <div class="btn_wrap_pd">
		<?php echo CHtml::submitButton(Rights::t('core', 'Assign'), array('class' => 'btn btn_submit')); ?>
        </div>
	</div>

<?php $this->endWidget(); ?>

</div>