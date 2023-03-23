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
                $data = ["Token" => $token];
                $response = data(True, 'Kullanıcı giriş işlemi başarılı', $data);
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Kullanıcı giriş işlemi başarısız');
                print_r(json_encode($response));
            }

        } else {
            $response = successresponse(False, 'Kullanıcı giriş işlemi başarısız2');
            print_r(json_encode($response));
        }

        break;

        case ($query == "insert" && $service == "kategori"):
                if(authorizeRequest()){
                    $kategori = $vt->Insert(
                        "INSERT INTO kategori  SET   user_id=?, kategori_adi=? ,alt_kategori=?",
                        array(
                            authorizeRequest(),
                            htmlGizle($gelen_data->kategori_adi),
                            htmlGizle($gelen_data->alt_kategori),
                        )
                    );
                    if ($kategori) {
                        $data = ["Kategori" => $gelen_data->kategori_adi , "Alt Kategori" => $gelen_data->alt_kategori  ];
                        $response = data(True, 'Kategori ekleme işlemi başarılı', $data);
                        print_r(json_encode($response));
                    } else {
                        $response = successresponse(False, 'Kategori ekleme işlemi başarısız');
                        print_r(json_encode($response));
                    }
                } else  {
                    $response = successresponse(False, 'Kategori ekleme işlemi başarısız, authorization hatası!');
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