<?php $form=$this->beginWidget('CActiveForm'); ?>
<div class="table_full">
    <table width="100%">
        <tbody>
            <tr>
                <th><?php echo Rights::t('core', 'Add Child'); ?></th>
            
                <td>
                    <?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); ?>
                </td>
                <td>
                    <?php echo $form->error($model, 'itemname'); ?>
                </td>
            </tr>
        </tbody>
    </table>
	
    <div class="btn_wrap">
        <div class="btn_wrap_pd">
            <?php echo CHtml::submitButton(Rights::t('core', 'Add'), array('class' => 'btn btn_submit mr20')); ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>