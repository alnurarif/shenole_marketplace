<?php

namespace Shenole_project\utils;

/**
 * Class RandomStringGenerator
 * @package Utils
 *
 */
class ImageUploader{
    private $imageFile = null;
    private $imageObject = null;
    private $root = '';
    private $path = '';
    private $level = '';
    public function __construct($imageFile,$imageObject){
        $this->imageFile = $imageFile;
        $this->imageObject = $imageObject;
    }
    public function setRoot($root = ''){
        $this->root = $root;
    }
    public function setPath($path = ''){
        $this->path = $path;
    }
    public function setLevel($level = ''){
        $this->level = $level;
    }
    public function imageUpload() {
        if($this->imageObject == null)
            return null;

        if ($this->imageFile['error'] != '0')
            return null;

        // $image_name_object->resize(1600, 1200, $allow_enlarge = True);
        if(!file_exists($this->level.$this->path)){
            mkdir($this->level.$this->path);
        }
        $imagePath = $this->uniqueFileName();

        if ($this->imageFile['error'] == '0') {
            $this->imageObject->save($this->root.$this->path.$imagePath);
            return $imagePath;
        } else {
            return $this->imageFile['error'];
        }
    }

    public function uniqueFileName() {
        $theFile = $this->imageFile['name'];
        $theFileNewName = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'),1,20);

        $file_ext = pathinfo($theFile, PATHINFO_EXTENSION);
        $thePath = $theFileNewName .'.'. $file_ext;
        $i = 0;
        $uniquePath = $thePath;
        while (file_exists($uniquePath) && $i < 10) {
            $i++;
            $uniquePath = str_ireplace('.' . $file_ext, '-' . $i . '.' . $file_ext, $thePath);
        }

        return $uniquePath;
    }

}