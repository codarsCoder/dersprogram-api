<?php

////////// DİKKAT  2 NUMARALI İŞLEMLER -Delete2 gibi- PREPARE KULLANILAYAN İŞLEMLER

class Database 

{
  private $MYSQL_HOST='localhost'; 
  private $MYSQL_USER='root'; // mysql kullanıcı adınız  
  private $MYSQL_PASS='';  // mysql şifreniz
  private $MYSQL_DB='dersprogram'; //kendi database adınızı yazın
  private $CHARSET='UTF8';
  private $COLLATION='utf8_general_ci';
  private $pdo=null;
  private $stmt=null;



  private function ConnectDB(){

    //database bağlantısı

    $SQL="mysql:host=".$this->MYSQL_HOST.";dbname=".$this->MYSQL_DB; 

    try{

      $this->pdo=new PDO($SQL,$this->MYSQL_USER,$this->MYSQL_PASS);//pdo yukarıda  private tanımlanan değişken(özellik)

      $this->pdo->exec("SET NAMES '".$this->CHARSET."' COLLATE '".$this->COLLATION."'");

      $this->pdo->exec("SET CHARACTER SET '".$this->CHARSET."'");

      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

    }catch(PDOException $e){

      die("PDO ile veritabanına ulaşılamadı".$e->getMessage());

    }

  }



  public function __construct(){ 

    //bağlantıyı aç

    $this->ConnectDB();

  }



  private function myQuery($query,$params=null){//temel query işlemi yapmış

    //diğer metodlardaki tekrarlı verileri bitirmek için kullanılan metod(tekrarlayan fonksiyonları teke indirmiş video137)

    if(is_null($params)){

      $this->stmt=$this->pdo->query($query);  //burada $query bizim sql komutumuz select ad from uyeler where id=2 gibi

    }else{

      $this->stmt=$this->pdo->prepare($query);

      $this->stmt->execute($params); // params sonradan göndereceğimiz dizi değişkeni, güvenlik için where id=2 yerin where id =? yazıp array içinde 2 yolluyoruz ve prepare işlemi sağlamış oluyoruz

    }

    return $this->stmt;

  }



  private function myQuery2($query){//temel query işlemi yapmış **************** PREPARE KULLANMAMAK İÇİN MYQUERY2 VE GETROWS2 AYARLADIK

    //diğer metodlardaki tekrarlı verileri bitirmek için kullanılan metod(tekrarlayan fonksiyonları teke indirmiş video137)

      $this->stmt=$this->pdo->query($query);  //burada $query bizim sql komutumuz select ad from uyeler where id=2 gibi

    return $this->stmt;

  }



  public function getRows2($query){

    //çoklu satır verilerini çekmek için

     try{

        return $this->myQuery2($query)->fetchAll();//yukarıdaki temel query i çağırıp fetcall vermiş

     }catch(PDOException $e){

      die($e->getMessage());

     }

  }

  public function getRows($query,$params=null){

    //çoklu satır verilerini çekmek için

     try{

        return $this->myQuery($query,$params)->fetchAll();//yukarıdaki temel query i çağırıp fetcall vermiş

     }catch(PDOException $e){

      die($e->getMessage());

     }

  }



  public function getRow($query,$params=null){

    //tek satır veri çekmek  için

    try{

       return $this->myQuery($query,$params)->fetch();

    }catch(PDOException $e){

     die($e->getMessage());

    }

 }

  public function getRow2($query){

    //tek satır veri çekmek  için

    try{

       return $this->myQuery2($query)->fetch();

    }catch(PDOException $e){

     die($e->getMessage());

    }

 }



 public function getColumn($query,$params=null){

    //tek satırın sütun verisini çekmek için nokta veri alışı

    try{

      return $this->myQuery($query,$params)->fetchColumn();

    }catch(PDOException $e){

    die($e->getMessage());

    }

  }



  public function Insert($query,$params=null){

    //kayıt eklemek için

    try{

       $this->myQuery($query,$params);

     return $this->pdo->lastInsertId();

    

    }catch(PDOException $e){

    die($e->getMessage());

    }

  }

  public function Say($query,$params=null){

   

    try{

      return $this->myQuery($query,$params)->rowCount();  

    }catch(PDOException $e){

    die($e->getMessage());

    }

  }

  public function Update($query,$params=null){

    //kayıt güncellemek için

    try{

      return $this->myQuery($query,$params);   //$this->myQuery($query,$params)->rowCount();

    }catch(PDOException $e){

    die($e->getMessage());

    }

  }

  public function Update2($query){

    //kayıt güncellemek için

    try{

      return $this->myQuery2($query);   //$this->myQuery($query,$params)->rowCount();

    }catch(PDOException $e){

    die($e->getMessage());

    }

  }



  public function Delete($query,$params=null){

    //kayıt Silmek için

      return $this->Update($query,$params);

  }

  public function Delete2($query){

    //kayıt Silmek için

      return $this->Update2($query);

  }

	

   public function Limit($query,$p1=1,$p2=null){

	   //limit kayıtlarını pdo ile çekmek için

      $this->stmt=$this->pdo->prepare($query);

      $this->stmt->bindValue(1, $p1, PDO::PARAM_INT);

      if(!is_null($p2))

      $this->stmt->bindValue(2, $p2, PDO::PARAM_INT);

      

      $this->stmt->execute();

    return $this->stmt->fetchAll();

  }

  public function __destruct(){

    //bağlantıyı kapat

    $this->pdo=NULL;

  }



  public function CreateDB($query){ 

    //veritabanı oluşturmak için

    $myDB=$this->pdo->query($query.' CHARACTER SET '.$this->CHARSET.' COLLATE '.$this->COLLATION);

    return $myDB;

 }



 public function TableOperations($query){ 

   //tablo operasyonları için

   $myTable=$this->pdo->query($query);

   return $myTable;

 }



 public function Maintenance(){ 

   //tabloların bakımı için

  $myTable=$this->pdo->query("SHOW TABLES");

  $myTable->setFetchMode(PDO::FETCH_NUM);

  if($myTable){

    foreach($myTable as $items){ 

    $check=$this->pdo->query("CHECK TABLE ".$items[0]);

    $analyze=$this->pdo->query("ANALYZE TABLE ".$items[0]);

    $repair=$this->pdo->query("REPAIR TABLE ".$items[0]);

    $optimize=$this->pdo->query("OPTIMIZE TABLE ".$items[0]);

      if($check == true && $analyze == true && $repair == true && $optimize == true){

        echo $items[0].' adlı Tablonuzun bakımı yapıldı<br>';

      }else{

        echo 'Bir hata oluştu';

      }

    }

  }

}



}	

?>