<?php

require_once '../application/vendors/rb-mysql.php';
require_once '../application/core/db_conn.php';

$templates = R::findAll('templates');
$settings = [];
foreach ($templates as $temp) {
    $temp_f = $temp['template'];
    $colors = json_decode($temp['colors']);
    $settings[$temp_f] = [
        'name_color' => $colors->name_color,
        'label_color' => $colors->label_color,
        'small_color' => $colors->small_color,
        'font' => $temp['font']
    ];
}

$number = '№'.$_GET['fam'].'-0000';
//Это для миниатюры
if ($_GET['type'] != 'min') {
    //А это уже для скачивания
    $cert_id = $_GET['cert_id'];
    $number = '№'.strval($cert_id);
}
$date = $_GET['d'];

$forms = array();

if ($_GET['cat'] == 'gift' && $_GET['l'] == 'ru') {
    $forms = [
        'igf' => 'в индивидуально-групповом формате',
        'gf' => 'в групповом формате',
        'if' => 'в индивидуальном формате'
    ];
    $name = $_GET['ln'].' '.$_GET['fn'];
    $count = $_GET['c'];
    if (intdiv($count, 10) == 1 || $count % 10 == 0) {
        $countPref = 'занятий';
    } elseif ($count % 10 == 1) {
        $countPref = 'занятие';
    } elseif ($count % 10 <= 4) {
        $countPref = 'занятия';
    } else {
        $countPref = 'занятий';
    }

    $label = $count.' '.$countPref.' (по '.$_GET['dur'].' мин)';
    $label2 = $forms[$_GET['fm']];

    $filename_key = $_GET['f'];
    $filename = $filename_key;

    $delta_label = 0.635;
    $delta_label2 = 0.665;
} elseif ($_GET['cat'] == 'gift' && $_GET['l'] == 'en') {
    $forms = [
        'igf' => 'in individual format in groups',
        'gf' => 'in groups',
        'if' => 'in individual format'
    ];
    $name = $_GET['fn'].' '.$_GET['ln'];
    $count = $_GET['c'];
    if ($count == 1) {
        $countPref = 'lesson';
    } else {
        $countPref = 'lessons';
    }

    $label = 'for '.$count.' '.$countPref;
    $label2 = '('.$_GET['dur'].' minutes length each)';
    $label3 = $forms[$_GET['fm']];

    $filename_key = $_GET['f'];
    $filename_arr = explode('.', $filename_key);
    $filename = $filename_arr[0].'_en.'.$filename_arr[1];

    $delta_label = 0.59;
    $delta_label2 = 0.625;
    $delta_label3 = 0.66;
} elseif ($_GET['cat'] == 'total' && $_GET['l'] == 'ru') {
    $subj = R::load('subjects', $_GET['s']);

    $name = $_GET['ln'].' '.$_GET['fn'];
    $count = $_GET['c'];
    if ($_GET['g'] == 'm') {
        $label1 = 'успешно завершил обучение по курсу';
    } else {
        $label1 = 'успешно завершила обучение по курсу';
    }
    $label = '"'.$subj['description'].'"';
    $label2 = 'количество часов: '.$count;

    $filename_key = $_GET['f'];
    $filename = $filename_key;

    $delta_label = 0.57;
    $delta_label1 = 0.52;
    $delta_label2 = 0.68;
} elseif ($_GET['cat'] == 'total' && $_GET['l'] == 'en') {
    $subj = R::load('subjects', $_GET['s']);

    $name = $_GET['fn'].' '.$_GET['ln'];
    $count = $_GET['c'];

    $label = '"'.$subj['description_en'].'"';
    $label1 = 'has successfully completed the course';
    $label2 = 'lasting '.$count.' hours';

    $filename_key = $_GET['f'];
    $filename_arr = explode('.', $filename_key);
    $filename = $filename_arr[0].'_en.'.$filename_arr[1];

    $delta_label = 0.57;
    $delta_label1 = 0.52;
    $delta_label2 = 0.68;
} elseif ($_GET['cat'] == 'middle' && $_GET['l'] == 'ru') {
    $subj = R::load('subjects', $_GET['s']);

    $name = $_GET['ln'].' '.$_GET['fn'];
    $count = $_GET['c'];
    $label1 = 'обучается на курсе';
    $label = '"'.$subj['description'].'"';
    $label2 = 'количество пройденных часов: '.$count;

    $filename_key = $_GET['f'];
    $filename = $filename_key;

    $delta_label = 0.57;
    $delta_label1 = 0.52;
    $delta_label2 = 0.68;
} elseif ($_GET['cat'] == 'middle' && $_GET['l'] == 'en') {
    $subj = R::load('subjects', $_GET['s']);

    $name = $_GET['fn'].' '.$_GET['ln'];
    $count = $_GET['c'];

    $label = '"'.$subj['description_en'].'"';
    $label1 = 'has been studying the course';
    $label2 = 'for '.$count.' hours';

    $filename_key = $_GET['f'];
    $filename_arr = explode('.', $filename_key);
    $filename = $filename_arr[0].'_en.'.$filename_arr[1];

    $delta_label = 0.57;
    $delta_label1 = 0.52;
    $delta_label2 = 0.68;
}

$file = 'templates/'.$filename;
$im = ImageCreateFromPNG($file);

//получаем размеры изображеня
list($width, $height) = getimagesize($file);

//вычисляем размеры шрифта относительно высоты изобрадения
$name_size = round($height / 16.5);
$label_size = round($height / 43);
$small_size = round($height / 55.1);

$center = round($width / 2);

//Выводим имя
$font_file = '../fonts/'.$settings[$filename_key]['font'].'-Regular.ttf';
$font_size = $name_size;
$color = $settings[$filename_key]['name_color'];
$text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
$box = imagettfbbox($font_size, 0, $font_file, $name);
while ($box[2]-$box[0] > $width * 0.7) {
    $font_size = $font_size - 2;
    $box = imagettfbbox($font_size, 0, $font_file, $name);
}
$x = $center-round(($box[2]-$box[0])/2);
$y = round($height * 0.45);

imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $name);

//Выводим надпись
$font_file = '../fonts/'.$settings[$filename_key]['font'].'-Bold.ttf';
$color = $settings[$filename_key]['label_color'];
$text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
$font_size = $label_size;
$box = imagettfbbox($font_size, 0, $font_file, $label);
$x = $center-round(($box[2]-$box[0])/2);
$y = round($height * $delta_label);
imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $label);

if (isset($label1)) {
    $font_file = '../fonts/'.$settings[$filename_key]['font'].'-Regular.ttf';
    $box = imagettfbbox($font_size, 0, $font_file, $label1);
    $x = $center-round(($box[2]-$box[0])/2);
    $y = round($height * $delta_label1);
    imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $label1);
}

if (isset($label2)) {
    $font_file = '../fonts/'.$settings[$filename_key]['font'].'-Regular.ttf';
    $box = imagettfbbox($font_size, 0, $font_file, $label2);
    $x = $center-round(($box[2]-$box[0])/2);
    $y = round($height * $delta_label2);
    imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $label2);
}

if (isset($label3)) {
    $font_file = '../fonts/'.$settings[$filename_key]['font'].'-Regular.ttf';
    $box = imagettfbbox($font_size, 0, $font_file, $label3);
    $x = $center-round(($box[2]-$box[0])/2);
    $y = round($height * $delta_label3);
    imagefttext($im, $font_size, 0, $x, $y, $text_color, $font_file, $label3);
}

//Вывод даты
$font_file = '../fonts/'.$settings[$filename_key]['font'].'-Regular.ttf';
$color = $settings[$filename]['small_color'];
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
