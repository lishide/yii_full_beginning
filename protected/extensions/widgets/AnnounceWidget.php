<?php
class AnnounceWidget extends CWidget
{
    public $limit = 5;
    public $titleLen = 10;
    public $class = '';

    public function init() {
        parent::init();
    }

    public function run() {
        $criteria = new CDbCriteria;
        $criteria->select = 'id, title, color, stime, etime';
        $criteria->condition = 'status=1 and stime <= :sday and etime >= :eday';
        $criteria->params = array(':sday' => date('Y-m-d'), ':eday' => date('Y-m-d'));
        $criteria->limit = $this->limit;
        $criteria->order = 'sort asc';

        $list = Announce::model()->findAll($criteria);
        if (!$list) {
            echo CHtml::tag('ul', array('class' => $this->class));
        } else {
            $li = '';
            foreach($list as $item) {
                $link = CHtml::link(cutstr($item->title, $this->titleLen), array('announce/show', 'id' => $item->id), array('style' => 'color:'.$item->color));
                $li .= CHtml::tag('li', array(), $link);
            }
            echo CHtml::tag('ul', array('class' => $this->class), $li);
        }
    }
}