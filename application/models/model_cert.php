<?php

class Model_Cert extends Model
{
    public function get_data()
    {
        // $dir = "img/templates";
        // $files = scandir($dir);
        // array_splice($files, 0, 4);

        $params = ' ORDER BY template';
        $templates = R::findAll('templates', $params);

        $params = ' ORDER BY title';
        $subjects = R::findAll('subjects', $params);

        return ["templates"=>$templates, "subjects"=> $subjects];
    }
}
