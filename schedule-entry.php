<?php

switch (true) {
    case ($query == "insert" && $service == "scheduleEntry"):
        $id = authorizeRequest();
        if ($id) {
            $scheduleEntry=null; //tanımladık
            foreach ($gelen_data->entries as $entry) {

                $isEntry = $vt->getRow("SELECT * FROM sonuclar where user_id=? AND tarih=? AND ders=? ", array($id,$entry->tarih,$entry->ders));
                if (!$isEntry) {
                    $scheduleEntry = $vt->Insert(
                        "INSERT INTO sonuclar  SET   user_id=?, tarih=?,gün=?,ders=?,hedef_süre=?,hedef_adet=?,sonuc=?,is_update=?",
                        array(
                            $id,
                            $entry->tarih,
                            $entry->gün,
                            $entry->ders,
                            $entry->süre,
                            $entry->soru,
                            $entry->sonuc,
                            date("Y-m-d H:i:s"),
                        )
                    );
                } else {
                    $scheduleEntry = $vt->Update(
                        "UPDATE sonuclar  SET  sonuc=?,is_update=? WHERE user_id=? AND tarih=? AND ders=?  ",
                        array(
                            $entry->sonuc,
                            date("Y-m-d H:i:s"),
                            $id,
                            $entry->tarih,
                            $entry->ders,
                        )
                    );
                }
           
    
           
            }


            if ($scheduleEntry) {
                $data ="";
                $response = data(True, 'Ders soru ekleme işlemi başarılı', $data);
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Ders soru ekleme işlemi  başarısız');
                print_r(json_encode($response));
            }
        } else {
            $response = successresponse(False, 'Ders soru ekleme işlemi  başarısız, authorization hatası!');
            print_r(json_encode($response));
        }

        break;

        
        case ($query == "select" && $service == "scheduleEntry"):
            $id = authorizeRequest();
            if ($id) {
                $isSchedule = $vt->getRows("SELECT * FROM sonuclar where user_id=? AND tarih BETWEEN ? AND ?  ", array($id,$gelen_data->dates->Pazartesi,$gelen_data->dates->Pazar));
                // AND tarih IN($gelen_data->dates)
    
                if ($isSchedule) {
                    $data = ["scheduleEntry" => $isSchedule];
                    $response = data(True, 'Ders soruları alındı', $data);
                    print_r(json_encode($response));
                } else {
                    $response = successresponse(False, 'Ders soruları alma işlemi  başarısız');
                    print_r(json_encode($response));
                }
            } else {
                $response = successresponse(False, 'Authorization hatası!');
                print_r(json_encode($response));
            }
    
            break;

    default:
        # code...
        break;
}

?>