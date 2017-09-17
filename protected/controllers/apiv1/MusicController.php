<?php

/**
 * Created by PhpStorm.
 * User: lishide
 * Date: 17-9-16
 * Time: 下午3:38
 */
class MusicController extends Controller
{
    public function actionAdd()
    {
        $emptyCheck[] = array('title', '请提交歌名');
        $emptyCheck[] = array('singer', '请提交歌手');
        $emptyCheck[] = array('album', '请提交专辑');

        $p = _getParams($emptyCheck);

        $mtitle = $p['title'];
        $music = Music::findByTitle($mtitle);
        if ($music)
            _error(108);
        else {
            // 添加
            $music = new Music();
            $music->title = $p['title'];
            $music->singer = $p['singer'];
            $music->album = $p['album'];
            $dbres = $music->save();

            if ($dbres) {
                $data[] = array(
                    'mid' => $music->id,
                    'title' => $music->title
                );
                _OK($data);
            }

            _error(9);
        }

    }
}