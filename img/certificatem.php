<?php

//Это скрипт для массовой генерации сертификатов за мероприятие

require_once '../application/vendors/rb-mysql.php';
require_once '../application/core/db_conn.php';

$number = '№'.sprintf("%'.04d", $_GET['id']).'-'.sprintf("%'.05d", $_GET['n']);


$first_name = $_GET['fn'];
$last_name = $_GET['ln'];

$event = R::load('eventslog', $_GET['id']);

$date = $event['date'];
if ($_GET['l'] == 'ru') {
    $label = $event['text1'];
    $label1 = $event['text2'];
    $label2 = $event['text3'];
    $name = $last_name.' '.$first_name;
    $file = 'templates/temporary.png';
} else {
    $label = $event['text1_en'];
    $label1 = $event['text2_en'];
    $label2 = $event['text3_en'];
    $name = $first_name.' '.$last_name;
    $file = 'templates/temporary_en.png';
}

$font = $event['font'];
$im = ImageCreateFromPNG($file);
$delta_label = 0.45;
$delta_label1 = 0.50;
$delta_label2 = 0.55;

$name_color = $event['name_color'];
$label_color = $event['label_color'];
$small_color = $event['small_color'];

  //получаем размеры изображеня
  list($width, $height) = getimagesize($file);
  $center = round($width / 2);

//вычисляем размеры шрифта относительно высоты изобрадения
$label_size = round($height / 35.4);
$small_size = round($height / 55.1);

  //Выводим надпись
  $font_file = '../fonts/'.$font.'-Regular.ttf';
  $color = sscanf($label_color, "#%02x%02x%02x");
  if (strpos($label, '{name}') !== false) {
      $label = str_replace('{name}', $name, $label);
      $color = sscanf($name_color, "#%02x%02x%02x");
      $font_file = '../fonts/'.$font.'-Bold.ttf';
  }
  
  $text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
  $font_size = $label_size;
  $box = imagettfbbox($font_size, 0, $font_file, $label);
  $x = $center-round(($box[2]-$box[0])/2);
  $y = round($height * $delta_label);
  imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $label);

  if (isset($label1)) {
      $font_file = '../fonts/'.$font.'-Regular.ttf';
      $color = sscanf($label_color, "#%02x%02x%02x");
      if (strpos($label1, '{name}') !== false) {
          $label1 = str_replace('{name}', $last_name.' '.$first_name, $label1);
          $font_file = '../fonts/'.$font.'-Bold.ttf';
          $color = sscanf($name_color, "#%02x%02x%02x");
      }
      $text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
      $box = imagettfbbox($font_size, 0, $font_file, $label1);
      $x = $center-round(($box[2]-$box[0])/2);
      $y = round($height * $delta_label1);
      imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $label1);
  }

  if (isset($label2)) {
      $font_file = '../fonts/'.$font.'-Regular.ttf';
      $color = sscanf($label_color, "#%02x%02x%02x");
      if (strpos($label2, '{name}') !== false) {
          $label2 = str_replace('{name}', $last_name.' '.$first_name, $label2);
          $font_file = '../fonts/'.$font.'-Bold.ttf';
          $color = sscanf($name_color, "#%02x%02x%02x");
      }
      $text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
      $box = imagettfbbox($font_size, 0, $font_file, $label2);
      $x = $center-round(($box[2]-$box[0])/2);
      $y = round($height * $delta_label2);
      imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $label2);
  }

  $font_file = '../fonts/'.$font.'-Regular.ttf';
  $color = sscanf($small_color, "#%02x%02x%02x");
  //Вывод даты
  $text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
  $font_size = $small_size;
  $box = imagettfbbox($font_size, 0, $font_file, $date);
  $x = $width * 0.11;
  $y = round($height * 0.87);
  imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $date);

  //Вывод номера
  $font_size = 45;
  $box = imagettfbbox($font_size, 0, $font_file, $number);
  $x = $width * 0.7;
  $y = round($height * 0.87);
  imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $number);
  imagealphablending($im, false);
  imagesavealpha($im, true);

  //сжимаем изображение при необходимости
  if ($_GET['type'] == 'min') {
      $new_width = 500;
      $new_height = round($height * $new_width / $width);
      $im_new = imagecreatetruecolor($new_width, $new_height);
      imagecopyresampled($im_new, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
      $im = $im_new;
  }
  // Вывод изображения
  header('Content-Type: image/png');

  imagepng($im);

  imagedestroy($im);
