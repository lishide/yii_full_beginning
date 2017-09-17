<?php

/**
 * Created by PhpStorm.
 * User: Hapon
 * Date: 15/10/05
 * Time: 下午10:52
 */
class ImageUpload extends CComponent
{
    public function check_file_exist($file_name)
    {
        return empty($_FILES[$file_name]) == false;
    }

    public function upload($file_name, $type, $uid)
    {
        if ($this->check_file_exist($file_name) == false)
            return ['errorcode' => 100, 'errormsg' => '文件不存在'];

        if ($_FILES[$file_name]['size'] > 1024 * 1024 * 2)
            return ['errorcode' => 104, 'errormsg' => ''];

        $extension = $this->get_extension($file_name);

        if ($this->check_extension($extension) == false)
            return ['errorcode' => 105, 'errormsg' => ''];

        $res = $this->save_file($file_name, $type, $extension, $uid);

        if ($res)
            return ['errorcode' => 1, 'path' => $res, 'extension' => $extension, 'size' => $_FILES[$file_name]['size']];
        else
            return ['errorcode' => 9, 'errormsg' => ''];
    }

    private function save_file($file_name, $type, $extension, $uid)
    {
        //检查目录是否存在
        $basePath = Yii::app()->params->uploadFileDir;
        $filePath = '';
        switch($type){
            case 'avatar':
                $filePath = sprintf("user/%d/", $uid);
                break;
            case 'shop':
                $filePath = sprintf("shop/%d/", $uid);
                break;
            case 'goods':
                $filePath = sprintf("shop/%d/goods/", $uid);
                break;
            case 'service':
                $filePath = sprintf("service/%d/", $uid);
                break;
            case 'ad':
                $filePath = sprintf("ad/", $uid);
                break;
        }

        if (!file_exists($basePath . $filePath))
            mkdir($basePath . $filePath, 0777, true);

        //尝试三次随机文件名
        for ($i = 0; $i < 3; $i++) {
            $fileName = $type . '_' . md5(time() . mt_rand(10000, 99999)) . '.' . $extension;
            $filePath .= $fileName;

            if (move_uploaded_file($_FILES[$file_name]['tmp_name'], $basePath . $filePath)) {
                return $filePath;
            }
        }
        return false;
    }

    private function get_extension($fileKey)
    {
        $fileName = $_FILES[$fileKey]['name'];
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    private function check_extension($extension)
    {
        $ext_arr = array('png', 'jpg', 'jpeg');

        foreach ($ext_arr as $row)
            if ($extension == $row)
                return true;
        return false;
    }
}
