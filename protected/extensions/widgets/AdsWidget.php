<?php

class AdsWidget extends CWidget
{
    private $model = null;
    private $cs = null;
    private $assetUrl = '';

    public $posid  = null;
    public $wpId = null;

    public function init()
    {
        if(!$this->wpId)
            $this->wpId = $this->getId();
        if(!$this->posid) {
            echo CHtml::tag('div', array('id' => $this->wpId), '未指定要显示的广告位');
            return;
        }
        $this->model = Adsposi::model()->findByPk($this->posid);
        if(!$this->model) {
            echo CHtml::tag('div', array('id' => $this->wpId), '指定的广告位不存在');
            return;
        }
    }

    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'pid=:pid and status=1';
        $today = date('Y-m-d');
        $criteria->addCondition('start<=:st and end>=:et');
        $criteria->params = array(
            ':pid' => $this->model->id,
            ':st' => $today,
            ':et' => $today,
        );
        $criteria->limit = $this->model->num;
        $criteria->order = 'sort asc, id desc';

        $ads = Ads::model()->findAll($criteria);

        switch ($this->model->type) {
            case 0: //单张
                $list = '';
                foreach($ads as $ad) {
                    $img = CHtml::tag('img', array('style' => 'border:none;', 'width' => $this->model->width, 'height' => $this->model->height, 'src' => $ad->pic));
                    $link = CHtml::tag('a', array('href' => Yii::app()->controller->createUrl('ads/show', array('id' => $ad->id)), 'target' => '_blank'), $img);
                    $list .= CHtml::tag('li', array(), $link);
                }
                $ul = CHtml::tag('ul', array(), $list);
                echo CHtml::tag('div',array('id' => $this->wpId), $ul);
            break;

            case 1: //幻灯
                $list = '';
                foreach($ads as $ad) {
                    $img = CHtml::tag('img',array('style' => 'border:none;', 'src' => $ad->pic, 'alt' => $ad->title,'text'=>$ad->description));
                    $link = CHtml::tag('a', array('href' => Yii::app()->controller->createUrl('ads/show', array('id' => $ad->id)), 'target' => '_blank'), $img);
                    $list .= CHtml::tag('li',array(),$link);
                }
                $ul = CHtml::tag('ul', array(), $list);
                $pic = CHtml::tag('div', array('class' => 'pic'), $ul);
                $load = CHtml::tag('div', array('class' => 'loading'),'');


                echo CHtml::tag('div', array('id' => $this->wpId, 'style' => 'width:'.$this->model->width.'px; height:'.$this->model->height.'px; overflow:hideen;'), $load . $pic);

                $this->regFiles();
                $this->cs->registerScript('slide_ads_'.$this->wpId,"
                    myFocus.set({
                            id:'".$this->wpId."',
                            path:'".$this->assetUrl."/mf-pattern/',
                            pattern:'mF_kdui',
                            time:3,
                            trigger:'click',
                            width:".$this->model->width.",
                            height:".$this->model->height."
                    });
                ",2);
            break;

            case 2: //头部伸缩
                $list = '';

                foreach($ads as $k => $ad) {
                    $img = CHtml::tag('img', array('style' => 'border:none;', 'width' => $this->model->width, 'src' => $ad->pic));
                    $link = CHtml::tag('a', array('href' => Yii::app()->controller->createUrl('ads/show', array('id' => $ad->id)), 'target' => '_blank'), $img);
                    $list .= CHtml::tag('div', array('class' => 'stretch', 'style' => 'display:none;'), $link);
                }
                echo CHtml::tag('div',array('id' => $this->wpId), $list);
                Yii::app()->clientScript->registerScript('stretch_ads_'.$this->wpId,"
                    var first = $('#".$this->wpId." div.stretch:first');
                    var last = $('#".$this->wpId." div.stretch:last');
                    setTimeout(function(){first.slideUp(1000);last.slideDown(1000);},1000);
                    setTimeout(function(){last.slideUp(1000,function (){first.slideDown(1000);});},6000);
                ",3);
            break;

            case 3: //左右对联
                $links = array();
                foreach($ads as $ad) {
                    $img = CHtml::tag('img', array('style' => 'border:none;', 'width' => $this->model->width, 'height' => $this->model->height, 'src' => $ad->pic));
                    $links[] = CHtml::tag('a', array('href' => Yii::app()->controller->createUrl('ads/show', array('id' => $ad->id)), 'target' => '_blank'), $img);
                }
                $left = CHtml::tag('div', array('class' => $this->wpId.'_left'), $links[0]);
                $right = CHtml::tag('div', array('class' => $this->wpId.'_right'), $links[1]);
                echo $left.$right;

                Yii::app()->clientScript->registerCss($this->wpId, "
                    .{$this->wpId}_left, .{$this->wpId}_right {position:fixed; top:100px; z-index:1000;}
                    .{$this->wpId}_left {left:0;}
                    .{$this->wpId}_right {right:0;}
                ");
            break;

            case 4: //底部弹窗
                $links = array();
                foreach($ads as $ad) {
                    $img = CHtml::tag('img', array('style' => 'border:none;', 'width' => $this->model->width, 'height' => $this->model->height, 'src' => $ad->pic));
                    $links[] = CHtml::tag('a', array('href' => Yii::app()->controller->createUrl('ads/show', array('id' => $ad->id)), 'target' => '_blank'), $img);
                }
                
                $bot = CHtml::tag('div', array('id' => $this->wpId), $links[0]);
                echo $bot;

                Yii::app()->clientScript->registerCss($this->wpId, "
                    #{$this->wpId} {position:fixed; bottom:0; right:0; z-index:1000;}
                ");
            break;
            case 5://文字广告
            $links=array();

            foreach ($ads as $k=> $ad) {
                $text='<img src="/images/gicon2.png" border="0" align="absmiddle" />&nbsp;' .trim($ad->title);
                $links[]=CHtml::tag('a',array('href'=>Yii::app()->controller->createUrl('ads/show',array('id'=>$ad->id)),'target'=>'_blank'),$text);
                $bot.=CHtml::tag('div',array('id'=>$this->wpId),$links[$k]);
            }

            echo $bot;
            break;
        }

    }

    protected function regFiles()
    {
        //$this->assetUrl = Yii::app()->assetManager->publish(dirname(__FILE__).'/ads');
        $this->cs = Yii::app()->clientScript;
        $this->cs->registerScriptFile(Yii::app()->baseUrl.'/js/myFocus/myfocus-2.0.4.min.js');
       // $this->cs->registerScriptFile(Yii::app()->baseUrl.'/js/myFocus/mf-pattern/mF_YSlider.js');
       // $this->cs->registerCssFile(Yii::app()->baseUrl.'/js/myFocus/mf-pattern/mF_YSlider.css');
    }
}