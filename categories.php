<?php 


switch (true) {
    case ($query == "insert" && $service == "kategori"):
        $id = authorizeRequest();
        if($id){
            foreach ($gelen_data->kategori_adi as  $value) {
                 $kategori = $vt->Insert(
                "INSERT INTO kategori  SET   user_id=?, kategori_adi=? ,alt_kategori=?",
                array(
                    $id,
                    htmlGizle($value),
                    "",
                )
            );
            }
           
            if ($kategori) {
                $kategoriler = $vt-> getRows("SELECT * FROM kategori WHERE user_id = $id");
                $data = ["kategoriler" =>$kategoriler ];
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
    case ($query == "select" && $service == "kategori"):
        $id = authorizeRequest();
        if($id){
            $kategoriler = $vt-> getRows("SELECT * FROM kategori WHERE user_id = $id");
            
            if ($kategoriler) {
                $data = ["kategoriler" => $kategoriler ];
                $response = data(True, 'Kategori çekme işlemi başarılı', $data);
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Kategori çekme işlemi başarısız');
                print_r(json_encode($response));
            }
        } else  {
            $response = successresponse(False, 'Kategori çekme işlemi başarısız, authorization hatası!');
            print_r(json_encode($response));
        }
  
    break;

    case ($query == "delete" && $service == "kategori"):
        $id = authorizeRequest();
        if($id){
            $sil = $vt->Delete("DELETE FROM kategori WHERE user_id=? AND id=?",array($id,$gelen_data->id));

            
            if ($sil) {
                $kategoriler = $vt-> getRows("SELECT * FROM kategori WHERE user_id = $id");
                $data = ["kategoriler" => $kategoriler ];
                $response = data(True, 'Kategori silme işlemi başarılı', $data);
                print_r(json_encode($response));
            } else {
                $response = successresponse(False, 'Kategori silme işlemi başarısız');
                print_r(json_encode($response));
            }
        } else  {
            $response = successresponse(False, 'Kategori silme işlemi başarısız, authorization hatası!');
            print_r(json_encode($response));
        }
  
    break;
    default:
        # code...
        break;
}

?>