<?php
class MyDataColumn extends CDataColumn
{
    /**
     * Renders the data cell content.
     * This method evaluates {@link value} or {@link name} and renders the result.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row,$data)
    {
        $cart = App()->cart->get();

        if($this->value!==null)
            $value=$this->evaluateExpression($this->value,array('data'=>$data, 'cart' => $cart, 'row'=>$row));
        elseif($this->name!==null)
            $value=CHtml::value($data,$this->name);
        echo $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);
    }
}
