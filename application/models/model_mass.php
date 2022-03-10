<?php

class Model_Mass extends Model
{
    public function get_data()
    {
        session_start();
        if (isset($_POST['table_id'])) {
            $id = $_POST['table_id']; //id таблички в гугле
            $gid = '0';   //номер листа

            //получаем файл сsv содержащий строки таблицы
            $table = fopen('https://docs.google.com/spreadsheets/d/' . $id . '/export?format=csv&gid=' . $gid, 'r');
            $array = [];
            //парсим csv файл, посторочно перебирая его
            while (($data = fgetcsv($table, 1000, ",")) !== false) {
                array_push($array, $data);
            }

            $_SESSION['array'] = $array;

            //сохраняем в базу данных
            $log = R::dispense('eventslog');
            $log->name_event = $_POST['event'];
            $date = date_create($_POST['date']);
            $log->date = date_format($date, 'd.m.Y');
            $log->text1 = $_POST['text1'];
            $log->text2 = $_POST['text2'];
            $log->text3 = $_POST['text3'];
            $log->text1_en = $_POST['text1_en'];
            $log->text2_en = $_POST['text2_en'];
            $log->text3_en = $_POST['text3_en'];
            $log->name_color = $_POST['name_color'];
            $log->label_color = $_POST['label_color'];
            $log->small_color = $_POST['small_color'];
            $log->font = $_POST['font'];
            $log->mail_text = $_POST['mail_text'];
            R::store($log);
            $_SESSION['event_id'] = $log->id;

            if ($_FILES && $_FILES['template']['error'] == UPLOAD_ERR_OK && $_FILES['template_en']['error'] == UPLOAD_ERR_OK) {
                $name = 'img/templates/temporary.png';
                move_uploaded_file($_FILES['template']['tmp_name'], $name);
                $name_en = 'img/templates/temporary_en.png';
                move_uploaded_file($_FILES['template_en']['tmp_name'], $name_en);

                //require_once( 'img/compressor.php' );

                //Это тут не нужно. Оставила для дальнейшего переноса в админку
                // compressor( 'img/templates/temporary.png', 100, 0 );
                // compressor( 'img/templates/temporary.png', 500, 0 );

                header('Location: /mass');
                exit();
            } else {
                echo('Ошибка загрузки файла');
                exit();
            }
        }
        if (isset($_SESSION['array'])) {
            $array = $_SESSION['array'];
            $src_for_show = 'img/certificatem.php?type=min&id='.$_SESSION['event_id'];
            $src_for_dovnload = 'img/certificatem.php?type=max&id='.$_SESSION['event_id'];
            unset($_SESSION['array']);
            unset($_SESSION['event_id']);
            //создаём zip для скачивания
            // include_once('application/core/createzip.php');
            // $createZip = new createZip;
            // $createZip -> addDirectory(iconv('utf-8', 'cp866', 'certificates') . '/');
            // $n=0;
            // foreach ($array as $cert) {
            //     $n++;
            //     $fileContents = file_get_contents('http://cert.itgen.io/'.$src_for_dovnload.'&ln='.$cert[0].'&fn='.$cert[1].'&l='.$cert[2].'&n='.$n);
            //     $createZip -> addFile($fileContents, iconv('utf-8', 'cp866', 'certificates' . '/' . 'certificate_'.sprintf("%'.05d", $n) . '.png'));
            //     $fileName = rawurlencode('certificates') . '.zip';
            //     $fd = fopen($fileName, "wb");
            //     $out = fwrite($fd, $createZip -> getZippedfile());
            //     fclose($fd);
            // }

            return ['array'=>$array, 'src_for_show'=>$src_for_show, 'src_for_dovnload'=>$src_for_dovnload ];
        } else {
            return [];
        }
    }
}
