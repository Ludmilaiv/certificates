<?php
require 'compressor.php';
$dir = "templates";
$files = scandir($dir);
array_splice($files, 0, 4);
$dir = 'templates/100';
if (!is_dir($dir)) {
    mkdir($dir);
}
$dir = 'templates/500';
if (!is_dir($dir)) {
    mkdir($dir);
}
foreach ($files as $file) {
    compressor('templates/'.$file, 100, 0);
    compressor('templates/'.$file, 500, 0);
}
