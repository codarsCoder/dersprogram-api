<?php

switch (true) {

    case ($query == "select" && $service == "users"):

        $id = authorizeRequest();
        if ($id) {
            $isEntry = $vt->getRow("SELECT * FROM user where user_id=? ", array($id));
            if ($isEntry["statu"]===2) {
                $users = $vt->getRows("SELECT * FROM user");
                $data = ["users" => $users ];
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
            if ($isEntry && $isEntry["statu"] != 2) {
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