<?php


switch (true) {
    case ($query == "insert" && $service == "schedule"):
        $id = authorizeRequest();
        if ($id) {
            $isSchedule = $vt->getRow("SELECT * FROM ders_programi where user_id=?", array($id));
            $schedule=null; //tanımladık
            if (!$isSchedule) {
                $schedule = $vt->Insert(
                    "INSERT INTO ders_programi  SET   user_id=?, icerik=?, is_update=? ",
                    array(
                        $id,
                        $gelen_data->schedule,
                       date("Y-m-d H:i:s"),
                    )
                );
            } else {
                $schedule = $vt->Update(
                    "UPDATE ders_programi  SET  user_id=?, icerik=?, is_update=? WHERE user_id=? ",
                    array(
                        $id,
                        $gelen_data->schedule,
                       date("Y-m-d H:i:s"),
                        $id,
                    )
                );
            }

            if ($schedule) {
                $data = ["schedule" => $gelen_data->schedule];
                $response = data(True, 'Ders Programı ekleme işlemi başarılı', $data);
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Ders Programı ekleme işlemi  başarısız');
                print_r(json_encode($response));
            }
        } else {
            $response = successresponse(False, 'Ders Programı ekleme işlemi  başarısız, authorization hatası!');
            print_r(json_encode($response));
        }

        break;

        case ($query == "select" && $service == "schedule"):
            $id = authorizeRequest();
            if ($id) {
                $isSchedule = $vt->getRow("SELECT * FROM ders_programi where user_id=?", array($id));
            
    
                if ($isSchedule) {
                    $data = ["schedule" => j2a($isSchedule["icerik"])];
                    $response = data(True, 'Ders Programı alındı', $data);
                    print_r(json_encode($response));
                } else {
                    $response = successresponse(False, 'Ders Programı alma işlemi  başarısız');
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