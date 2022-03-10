<?php
require_once '../application/vendors/rb-mysql.php';
require_once '../application/core/db_conn.php';

$settings = [
    'gift_3.png' => [
        'colors'=>['name_color' => [0x18, 0x46, 0xB4],
        'label_color' => [0x18, 0x46, 0xB4],
        'small_color' => [0x00, 0x00, 0x00]],
        'font' => 'Comfortaa'
    ],
    'gift_4.png' => [
      'colors'=>['name_color' => [0xD4, 0x1C, 0x1C],
        'label_color' => [0xD4, 0x1C, 0x1C],
        'small_color' => [0x00, 0x00, 0x00]],
        'font' => 'Comfortaa'
    ],
];

foreach ($settings as $key => $val) {
    $label = R::dispense('templates');
    $label->template = $key;
    $label->colors = json_encode($val['colors']);
    $label->font = $val['font'];
    R::store($label);
}
