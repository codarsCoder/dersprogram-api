<?php

switch (true) {
    case ($query == "insert" && $service == "user"):

        $ekle = $vt->Insert(
            "INSERT INTO user  SET   email=?, parola=?, adi=?, telefon=?",
            array(
                htmlGizle($gelen_data->email),
                sifrele(htmlGizle($gelen_data->parola)),
                htmlGizle($gelen_data->adi),
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
        if ($hesap["user_id"]) { //eski token varsa silelim
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
            if ($session) { //yeni token ekleyelim
                $data = ["Token" => $token, "email" => $gelen_data->email, "adi" => $hesap["adi"]];
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

    case ($query == "select" && $service == "userget"):

        $id = authorizeRequest();
        if ($id) {
            $isEntry = $vt->getRow("SELECT * FROM user where user_id=? ", array($id));
            if ($isEntry) {
                $data = ["user" => $isEntry ];
                $response = data(True, 'Kullanıcı çekme işlemi başarılı',$data );
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Kullanıcı çekme işlemi başarısız');
                print_r(json_encode($response));
            }

        } else {
            $response = successresponse(False, 'Geçersiz Token Anahtarı!');
            print_r(json_encode($response));
        }

        break;

    case ($query == "update" && $service == "changepassword"):

        $id = authorizeRequest();
        if ($id) {
            $isEntry = $vt->getRow("SELECT * FROM user where user_id=? AND parola=? ", array($id,sifrele(htmlGizle($gelen_data->parola))));
            if ($isEntry) {
                $scheduleEntry = $vt->Update(
                    "UPDATE user  SET  parola=? WHERE user_id=?",
                    array(
                        sifrele(htmlGizle($gelen_data->parola2)),
                        $id
                    )
                );
            
                $response = data(True, 'Şifre  değiştirme işlemi başarılı',"" );
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Şifre  değiştirme işlemi başarısız');
                print_r(json_encode($response));
            }

        } else {
            $response = successresponse(False, 'Geçersiz Token Anahtarı!');
            print_r(json_encode($response));
        }

        break;




    default:
        // işlemler
        break;
}



?>