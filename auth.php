<?php

switch (true) {
    case ($query == "insert" && $service == "user"):

        $ekle = $vt->Insert(
            "INSERT INTO user  SET   kullanici_adi=? ,email=?, parola=?, ad=?, soyad=?, telefon=?",
            array(
                htmlGizle($gelen_data->kullanici_adi),
                htmlGizle($gelen_data->email),
                sifrele(htmlGizle($gelen_data->parola)),
                htmlGizle($gelen_data->ad),
                htmlGizle($gelen_data->soyad),
                htmlGizle($gelen_data->telefon)
            )
        );

        if ($ekle) {
            $response = data(True, 'Kullanıcı kayıt işlemi başarılı', "");
            print_r(json_encode($response));

        } else {
            $response = successresponse(False, 'Kullanıcı kayıt işlemi başarısız');
            print_r(json_encode($response));
        }
        break;

    case ($query == "select" && $service == "userlogin"):
        $token = sifrele(time() + $ip_address);
        $hesap = $vt->getRow(
            "SELECT * FROM user where email=? AND parola=?",
            array(
                htmlGizle($gelen_data->email),
                sifrele(htmlGizle($gelen_data->parola)),
            )
        );
        if ($hesap["user_id"]) {
            $vt->Delete("DELETE FROM session WHERE user_id=?", array($hesap["user_id"]));
            $session = $vt->Insert(
                "INSERT INTO session  SET   user_id=? ,token=?, token_bitis=?, ip=?",
                array(
                    htmlGizle($hesap["user_id"]),
                    $token,
                    time() + 7200,
                    $ip_address
                )
            );
            if ($session) {
                $data = ["Token" => $token, "email" => $gelen_data->email];
                $response = data(True, 'Kullanıcı giriş işlemi başarılı', $data);
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Kullanıcı giriş işlemi başarısız');
                print_r(json_encode($response));
            }

        } else {
            $response = successresponse(False, 'Geçersiz Token Anahtarı!');
            print_r(json_encode($response));
        }

        break;

    case ($query == "select" && $service == "userlogout"):

        $auth = authorizeRequest();
        if ($auth) {
            $del_session = $vt->Delete("DELETE FROM session WHERE user_id=?", array($auth));
            if ($del_session) {

                $response = data(True, 'Kullanıcı Çıkış işlemi başarılı',"" );
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Kullanıcı Çıkış işlemi başarısız');
                print_r(json_encode($response));
            }

        } else {
            $response = successresponse(False, 'Geçersiz Token Anahtarı!');
            print_r(json_encode($response));
        }

        break;



    case ($query == "insert" && $service == "ders_programi"):


        break;

    default:
        // işlemler
        break;
}



?>