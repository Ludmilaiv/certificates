<?php
function compressor($i, $w, $h)
{
    if ($w != 0) {
        $new_width = $w;
    }
    if ($h != 0) {
        $new_height = $h;
    }
    $filename = $i;

    //определяем тип изображения
    $explode = explode(".", $filename);
    $filetype = $explode[1];
    //получаем размеры старого изображеня
    list($width, $height) = getimagesize($filename);

    //вычисляем размеры нового изображения
    if ($w == 0) {
        $new_width = round($width * $new_height / $height);
    } elseif ($h == 0) {
        $new_height = round($height * $new_width / $width);
    }
    //echo ($filename.' '.$new_width.' '.$new_height.'<br>');

    // Создание изображения
    $im_new = imagecreatetruecolor($new_width, $new_height);

    //получаем изображение из файла
    //и сжимаем изображение до новых размеров
    $path = explode('/', $filename);
    $name = array_pop($path);

    $save = implode('/', $path).'/'.strval($new_width).'/'.$name;

    if ($filetype == 'png') {
        $im_old = imagecreatefrompng($filename);
        imagealphablending($im_new, false);
        imagesavealpha($im_new, true);
        imagecopyresampled($im_new, $im_old, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        header('Content-Type: image/png');
        imagepng($im_new, $save);
    } elseif ($filetype == 'jpg' || $filetype == 'jpeg') {
        $im_old = imagecreatefromjpeg($filename);
        imagecopyresampled($im_new, $im_old, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        header('Content-Type: image/jpeg');
        imagejpeg($im_new, $save);
    }

    imagedestroy($im_new);
}
