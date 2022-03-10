<?php
class Model_Savemail extends Model
{
    public function get_data()
    {
        $cert_id = $_GET['id'];
        $cert = R::findOne('logs', 'cert_id = ?', [$cert_id]);
        $cert->mail_to_sent = $_GET['mail'];
        // Сохраняем объект в БД
        R::store($cert);
        echo $cert_id;
    }
}
