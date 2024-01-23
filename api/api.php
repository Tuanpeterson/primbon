<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json;');
require_once "model.php";

// penggunaan
$request = $_SERVER['REQUEST_METHOD'];
$test = new Model(
    "localhost",
    "root",
    "",
    "db_ijowo",
    "hasil_weton"
);

switch ($request) {
    case 'GET':
        $ambilParam = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        $hasil = $ambilParam[1];
        switch ($ambilParam[0]) {
            case 'weton':
                $test->join("SELECT hasil_weton.hasil,tb_jodoh.weton_jodoh,tb_jodoh.deskripsi
                 FROM hasil_weton JOIN tb_jodoh ON 
                 hasil_weton.id_kategori = tb_jodoh.id_jodoh 
                 WHERE hasil_weton.hasil =",$hasil);
                break;
           
        }
        break;
        
    case 'POST':
        $ambilParam = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        $perintah = $ambilParam[0];
        if ($perintah == 'input') {
            $test->input($_POST);
        } elseif($perintah == 'upload') {
            $test->upload($_FILES, $_POST, 'upload/');
        }
        break;
    case 'PUT':
        parse_str(file_get_contents("php://input"), $PUT);
        
        $test->edit($PUT['id'], $PUT, "id");
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $DELETE);
        $test->hapus($DELETE['id'], $DELETE['kolom']);
        break;
}
