<?php
class JplayerWidget extends CWidget
{
    //public $source = array();
    public $media = '';
    public $cover = '';
    public $width = 500;
    public $height = 300;
    public $cssClass = '';

    protected $cs = null;
    protected $assets = '';
    protected $wpId = null;

    public function init() {
        $this->cs = Yii::app()->clientScript;
        $this->assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'jplayer');
        $this->wpId = $this->getId();

        if (!$this->cssClass)
            $this->cssClass = 'jp-video-'.$this->wpId;
    }

    public function run() {
        $this->regFiles();
        
        $html = <<<EOF
        <div id="jp_container_{$this->wpId}" class="jp-video {$this->cssClass}">
            <div class="jp-type-single">
                <div id="jquery_jplayer_{$this->wpId}" class="jp-jplayer"></div>
                <div class="jp-gui">
                    <div class="jp-video-play">
                        <div class="jp-video-wp"></div>
                        <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
                    </div>
                    <div class="jp-interface">
                        <div class="jp-controls-holder">
                            <ul class="jp-controls">
                                <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                                <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                                <li><a href="javascript:;" class="jp-mute" tabindex="1" title="无声音">mute</a></li>
                                <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="无声音">unmute</a></li>
                                <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="最大音量">max volume</a></li>
                            </ul>
                            <div class="jp-volume-bar">
                                <div class="jp-volume-bar-value"></div>
                            </div>
                            <ul class="jp-toggles">
                                <li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="全屏播放">full screen</a></li>
                                <li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="恢复屏幕">restore screen</a></li>
                            </ul>
                        </div>

                        <div class="jp-progress">
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="jp-no-solution">
                    <span>Update Required</span>
                    To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                </div>
            </div>
        </div>
EOF;
        echo $html;
    }

    protected function regFiles() {
        // player
        $this->cs->registerCoreScript('jquery');
        $this->cs->registerScriptFile($this->assets.'/jquery.jplayer.min.js');
        $this->cs->registerCssFile($this->assets.'/skin/jplayer.blue.monday.css');

        $this->cs->registerCss('jpcss_'.$this->wpId, "
            div.jp-video-".$this->wpId." {
                width:".$this->width."px;
            }
            div.jp-video-".$this->wpId." div.jp-video-play {
                margin-top:-".$this->height."px;
                height:".$this->height."px;
            }
        ");

        $this->cs->registerScript('jplayer_'.$this->wpId, "
            $('#jquery_jplayer_".$this->wpId."').jPlayer({
                ready: function () {
                    $(this).jPlayer('setMedia', {
                        m4v: '".$this->media."',
                        poster: '".$this->cover."'
                    });
                },
                swfPath: '".$this->assets."',
                solution: 'flash, html',
                supplied: 'webmv, ogv, m4v',
                size: {
                    width: '".$this->width."px',
                    height: '".$this->height."px',
                    cssClass: '".$this->cssClass."'
                },
                cssSelectorAncestor: '#jp_container_".$this->wpId."',
                pause : function (e) {
                    $('.jp-video-play').show();
                },
                play : function (e) {
                    $(this).jPlayer('pauseOthers');
                    $('.jp-video-play').hide();
                },
                smoothPlayBar: true,
                keyEnabled: true
            });

            $('#jp_container_".$this->wpId."').hover(function () {
                $(this).find('div.jp-interface').fadeTo('fast', 0.8);
               $('.jp-controls').fadeTo('fast',0.8);
            }, function () {
                $(this).find('div.jp-interface').fadeTo('fast', 0);
                $('.jp-controls').fadeTo('fast',0);
            });
        ");
    }
}