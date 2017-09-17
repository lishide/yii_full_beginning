<?php
Yii::import('zii.widgets.grid.CGridView');
class MyGridView extends CGridView
{
    public $countTxt = '';

    public function renderCount()
    {
        if ($this->countTxt != '')
            echo CHtml::tag('p', 
                array('style'=>'clear:both;line-height:28px;text-align:right;color:#333; background:#e6e6e6; padding:0 15px; margin-bottom:8px; border-bottom:1px solid #ddd;'), 
                $this->countTxt
            );
    }
}