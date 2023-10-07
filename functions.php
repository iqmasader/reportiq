<?php

require 'I18N/Arabic.php';
$Arabic = new \ArPHP\I18N\Arabic();
function textPrint($Arabic,$text,$index_image,$fontsize,$font_path,$xa,$ya,$color)
{
  $text = $Arabic->utf8Glyphs($text);
  $image_width = imagesx($index_image);
  $text_box = imagettfbbox($fontsize, 0, $font_path, $text);
  $x = ceil(($xa - $text_box[2]) / 2);
  $y = $ya;
  imagettftext($index_image, $fontsize, 0, $x, $y, $color, $font_path, $text);
}

function textPrintRTL($Arabic,$text,$index_image,$fontsize,$font_path,$xa,$ya,$color)
{
  $text = $Arabic->utf8Glyphs($text);
  $dimensions = imagettfbbox($fontsize, 0, $font_path, $text);
  $textWidth = abs($dimensions[4] - $dimensions[0]);
  $x = $xa - $textWidth;
  $y = $ya;
  imagettftext($index_image, $fontsize, 0, $x, $y, $color, $font_path, $text);
}
function textPrintLTR($Arabic,$text,$index_image,$fontsize,$font_path,$xa,$ya,$color)
{
  $text = $Arabic->utf8Glyphs($text);
  $dimensions = imagettfbbox($fontsize, 0, $font_path, $text);
  $textWidth = abs($dimensions[6] - $dimensions[0]);
  $x = $xa - $textWidth;
  $y = $ya;
  imagettftext($index_image, $fontsize, 0, $x, $y, $color, $font_path, $text);
}


function countMoment()
{
  $use = file_get_contents('use.txt');
  $use++;
  $fp = fopen('use.txt', 'w');
  fwrite($fp, $use);
  fclose($fp);
}





header('Content-Type: image/png');

function upload($fname,$uname,$up_img_w,$up_img_h){
$target_dir = "uploads/";
$target_file = $target_dir . $fname;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$check = getimagesize($_FILES[$uname]["tmp_name"]);
if ($check !== false) {
  $uploadOk = 1;
} else {
  // echo "File is not an image.";
  $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
  // echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES[$uname]["size"] > 5000000000000) {
  // echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if (
  $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif"
) {
  // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  // echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES[$uname]["tmp_name"], $target_file)) {
    
    $image = imagecreatefromstring(file_get_contents($target_file));

    $newImg = imagecreatetruecolor($up_img_w, $up_img_h);
    imagealphablending($newImg, false);
    imagesavealpha($newImg, true);
    $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
    imagefilledrectangle($newImg, 0, 0, $up_img_w, $up_img_h, $transparent);
    list($src_w, $src_h) = getimagesize($target_file);
    imagecopyresampled($newImg, $image, 0, 0, 0, 0, $up_img_w, $up_img_h, $src_w, $src_h);
    @unlink($target_file);
    imagepng($newImg,$target_file);
   
  } 
  else {

  }
}
if($_FILES[$uname]["error"] == 0) {
  $uploadOk =11;
  }
  else{

  }
}
