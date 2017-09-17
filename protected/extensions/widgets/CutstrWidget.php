<?php
class CutstrWidget extends CWidget
{
    public $string  = '';
    public $start = 0;
    public $length = 10;

    public function run()
    {
        $len = mb_strlen($this->string,'utf-8');
        $re = mb_substr($this->string,$this->start,$this->length,'utf-8');
        if($len > $this->length)
            $re .= '...';
        echo $re;
    }
}