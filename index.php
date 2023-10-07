<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


if (isset($_POST['submit']) or isset($_POST['submit1']) or isset($_POST['submit2'])) {

  function resizeAndRounder($in, $out)
  {
    $image = new Imagick(__DIR__ . '/uploads/' . $in);
  
    $image->resizeImage(1000, 550, 1, 1);
    $image->setImageFormat("png");
    $image->roundCorners(40, 40);
    $image->writeImage(__DIR__ . '/uploads/' . $out);
  }


  $num = 10;                                                                                // عدد النصوص المرسلة
  for ($i=1; $i<=$num; $i++){
  ${"text".$i} = $_POST["name".$i];
 }
  $index_image = imagecreatefrompng(__DIR__ . '/index.png');   


  $texts = explode("\n",$_POST['text']);
  $lines = count($texts);
                             //مسار البطاقة
  require_once "functions.php";                                                             // استدعاء دوال مساعدة
  if($text6 == ''){
    $index_image = imagecreatefrompng(__DIR__ . '/index2.png');     
  }
  $White  = imagecolorallocate($index_image, 255, 255, 255);                               //لون الخط RGB
  $Green  = imagecolorallocate($index_image, 0, 200, 0);                               //لون الخط RGB
  $Black  = imagecolorallocate($index_image, 0, 0, 0);                               //لون الخط RGB

  $fontsize = 28;                                                                          //مقاس الخط
  $font_path = __DIR__ . '/GE_SS_Two_Bold.otf';                                              //مسار الخط المستخدم
  $font_path2 = __DIR__ . '/Tajawal-Bold.ttf';                                              //مسار الخط المستخدم

  

  $inc = 1350;
  for ($i = 0; $i < $lines;$i++){
    textPrintRTL($Arabic," - ".$texts[$i],$index_image,$fontsize+5,$font_path,1430,$inc,$Black);             // دالة طباعة النص  على البطاقة منتصف 
    $inc += 85;
  }

  textPrint($Arabic,$text1,$index_image,$fontsize+3,$font_path,1400,200,$White);
  textPrint($Arabic,$text2,$index_image,$fontsize+3,$font_path,1400,310,$White);

  textPrintRTL($Arabic,$text3,$index_image,$fontsize+8,$font_path,1960,1091,$Black);
  textPrintRTL($Arabic,$text7,$index_image,$fontsize+5,$font_path,2045,1250,$Black);
  textPrintRTL($Arabic,$text8,$index_image,$fontsize+5,$font_path,2045,1418,$Black);
  textPrintRTL($Arabic,$text9,$index_image,$fontsize+5,$font_path,1960,1590,$Black);
  textPrintRTL($Arabic,$text10,$index_image,$fontsize+8,$font_path,2045,1762,$Black);


  textPrint($Arabic,$text4,$index_image,$fontsize+35,$font_path,2480,825,$White);

  textPrintRTL($Arabic,$text5,$index_image,$fontsize+20,$font_path,2300,3430,$White);
  imagettftext($index_image, $fontsize+20, 0, 300, 3430, $White, $font_path2, $text6);


  /////////////////الصورة الاولى
  $file_up = time()."1.png";
  $file_resize = time()."11.png";
  upload($file_up,"fileToUpload",1000,550);
  if (file_exists('uploads/'.$file_up)){
  resizeAndRounder($file_up,$file_resize);
  $png = imagecreatefrompng(__DIR__ . '/uploads/'.$file_resize);
  $x=215;
  $y=2010;
  imagecopyresampled($index_image,$png, $x, $y, 0, 0, 1000, 550, 1000,550 );
  @unlink(__DIR__ . '/uploads/'.$file_up);
  @unlink(__DIR__ . '/uploads/'.$file_resize);
  }

/////////////////الصورة الثانية

$file_up = time()."2.png";
$file_resize = time()."22.png";
upload($file_up,"fileToUpload2",1000,550);
if (file_exists('uploads/'.$file_up)){
resizeAndRounder($file_up,$file_resize);
$png = imagecreatefrompng(__DIR__ . '/uploads/'.$file_resize);
$x=1265;
$y=2010;
imagecopyresampled($index_image,$png, $x, $y, 0, 0, 1000, 550, 1000,550 );
@unlink(__DIR__ . '/uploads/'.$file_up);
@unlink(__DIR__ . '/uploads/'.$file_resize);
}

/////////////////الصورة الثالثة

$file_up = time()."3.png";
$file_resize = time()."33.png";
upload($file_up,"fileToUpload3",1000,550);
if (file_exists('uploads/'.$file_up)){
resizeAndRounder($file_up,$file_resize);
$png = imagecreatefrompng(__DIR__ . '/uploads/'.$file_resize);
$x=215;
$y=2590;
imagecopyresampled($index_image,$png, $x, $y, 0, 0, 1000, 550, 1000,550 );
@unlink(__DIR__ . '/uploads/'.$file_up);
@unlink(__DIR__ . '/uploads/'.$file_resize);
}

/////////////////الصورة الرابعة

$file_up = time()."4.png";
$file_resize = time()."44.png";
upload($file_up,"fileToUpload4",1000,550);
if (file_exists('uploads/'.$file_up)){
resizeAndRounder($file_up,$file_resize);
$png = imagecreatefrompng(__DIR__ . '/uploads/'.$file_resize);
$x=1265;
$y=2590;
imagecopyresampled($index_image,$png, $x, $y, 0, 0, 1000, 550, 1000,550 );
@unlink(__DIR__ . '/uploads/'.$file_up);
@unlink(__DIR__ . '/uploads/'.$file_resize);
}


  ////////////////////////////////////////////////////////
  //زر عرض البطاقة 
  if (isset($_POST['submit'])){
      header("Content-Type: image/png; filename=card.png");
      header("Content-Disposition: inline; filename=card.png");
      imagepng($index_image);
    }
////////////////////////////////////////////////////////
  //زر تحميل البطاقة 
  if (isset($_POST['submit1'])){
      header('Content-type: image/png');
      header('Content-Disposition: attachment; filename="card.png"');
      imagepng($index_image);
      imagedestroy($index_image);
    }
    if (isset($_POST['submit2'])) {
      ob_start();
      imagejpeg($index_image);
  
      $file_path = 'cert.png';
      imagepng($index_image, $file_path);
      include 'fpdf.php';
      $pdf = new FPDF();
      $pdf->AddPage('P', 'A4', 0);
      $pdf->Image($file_path, 0, 0, 0, -300);
      // لازم يكون حجم الشهادة بحجم 2480*3508
  
      ob_end_clean();
      $pdf->Output();
      ob_end_flush();
      unlink($file_path);
  }
  
////////////////////////////////////////////////////////
//دالة زيادة العداد
  countMoment(); 

}



//استدعاء ملف الواجهة
require_once "interface.php";
?>