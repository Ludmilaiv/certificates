<?php
class Model_Generate extends Model
{
    public function get_data()
    {
        //генерируем уникальный номер
        $cert_id = $_GET['fam'].'-'.strval(mt_rand(1000, 9999));
        $cert = R::findOne('logs', 'cert_id = ?', [$cert_id]);
        while (isset($cert)) {
            $cert_id = $_GET['fam'].'-'.strval(mt_rand(1000, 9999));
            $cert = R::findOne('logs', 'cert_id = ?', [$cert_id]);
        }
        //сохраняем в базу данных
        $log = R::dispense('logs');
        $log->cert_id = $cert_id;
        $log->first_name = $_GET['fn'];
        $log->last_name = $_GET['ln'];
        $log->generation_date = $_GET['d'];
        $type = R::findOne('typesofcert', 'title = ?', [$_GET['cat']]);
        $log->id_type_of_cert = $type['id'];
        $log->lang = $_GET['l'];
        $log->count_of_hours_or_lessons = $_GET['c'];
        $log->family_id = $_GET['fam'];
        $log->template = $_GET['f'];
        // $log->type_of_cert = $_GET['cat'];
        if ($_GET['cat'] != 'gift') {
            $log->id_subject = $_GET['s'];
            $log->gender = $_GET['g'];
        } else {
            $log->duration_lessons = $_GET['dur'];
        }
        if (isset($_GET['mail'])) {
            $log->mail_to_sent = $_GET['mail'];
        }
        // Сохраняем объект в БД
        R::store($log);
        echo $cert_id;
    }
}
