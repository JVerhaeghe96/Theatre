<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 26-04-18
 * Time: 23:17
 */

namespace app\utils;

header("Content-type: application/json");

// contiens les informations des fichiers uploader
$result = array();
foreach($_FILES as $key => $file){
    $uploaddir = '../import/';
    $uploadfile = $uploaddir . basename($_FILES[$key]['name']);
    move_uploaded_file($_FILES[$key]['tmp_name'], $uploadfile);

    array_push($result, $_FILES[$key]);
}

echo json_encode($result);
