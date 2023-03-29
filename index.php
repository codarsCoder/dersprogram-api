<?php
spl_autoload_register(function ($class) {
    include $class . ".php";
});
include("functions_system.php");
error_reporting(1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Headers: X-gelen_dataed-With');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

$vt = new Database();

$gelen_json = file_get_contents("php://input"); //gelen query nin bosy kısmını okuduk
$gelen_data = json_decode($gelen_json); // jsonu çevirdik
$query = $gelen_data->query; //query ve service sabit olduğu için burada tanımladık
$service = $gelen_data->service;
$ip_address = $_SERVER['REMOTE_ADDR'];
$headers = getallheaders();


function data($status, $msg, $response_data)
{
    return array(
        'status' => $status,
        'message' => $msg,
        'data' => $response_data
    );
}

function successresponse($error, $errorMsg)
{
    return array(
        'status' => $error,
        'message' => $errorMsg
    );
}

function authorizeRequest()
{ //tokn ve ip eşleşiyormu bunu bir fonksiyon haline getirdik ki sürekli yazmayalım 
    global $headers;
    global $ip_address;

    if (isset($headers['Authorization'])) {
        $token = $headers['Authorization'];
    }

    $db = new Database(); // veritabanı bağlantısını sağla

    // tokeni veritabanında kontrol et
    $row = $db->getRow("SELECT * FROM session WHERE token=? AND ip=?", array($token, $ip_address));
    if (!$row) {
        return false;
    } else {
        return $row["user_id"];
    }

}


include("auth.php");
include("categories.php");
include("schedule.php");
include("schedule-entry.php");









//  $headers = getallheaders();

// if (isset($headers['Authorization'])) {
//     $auth_header = $headers['Authorization'];
//     $auth_header_parts = explode(' ', $auth_header);
//     $auth_type = $auth_header_parts[0]; // "Token"
//     $auth_token = $auth_header_parts[1]; // "12ksdjsadjasdj"

// } 















// $hesap = $vt -> getRow("SELECT * FROM hesap where id=?",array($k_id));

// echo $vt->Say("SELECT * FROM hesap_silinen"); 
//   $update = $vt -> Update("UPDATE media SET dosya_adi=?,guncelleme_t=?,guncelleyen_id=? where id=? ",array($dosya_adi,$tarih,$id,$m_id));

//    $aktar = $vt->Insert("INSERT INTO hesap_silinen (ad ,soyad,yetki,email,parola,eposta_aktif,telefon,son_giris_tarihi,cevirimci,kayit_tarihi,guncelleme_t,silinebilir,aciklama,aktif_mi) SELECT ad ,soyad,yetki,email,parola,eposta_aktif,telefon,son_giris_tarihi,cevirimci,kayit_tarihi,guncelleme_t,silinebilir,aciklama,aktif_mi  FROM hesap where id=?",array($h_id));
//  $sil = $vt->Delete("DELETE FROM $tablo WHERE $sutun=?",array($deger));




?>