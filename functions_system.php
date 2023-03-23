<?php 


    /*** ŞİFRELEME/GİZLEME ***/ 

    	// RASTGELE SAYI: session vs güvenliği için

	$sayi = "XdhkfhdfdfhsduhuhFUDCBSUCsgfo8944**__";

	define('_SAYI_', $sayi);

    // Veriyi Şifrele/Geri getirilebilir:   

    function veriGizle($veri, $sifre=false) 
    {
        $sifre = $sifre ? $sifre : _SAYI_;
        return ($veri) ? openssl_encrypt($veri, "AES-128-ECB", $sifre) : $veri;
    }

    // Veriyi Göster/ŞifreÇöz:

    function veriGoster($veri, $sifre=false) 
    {  
        $sifre = $sifre ? $sifre : _SAYI_;
        return openssl_decrypt($veri, "AES-128-ECB", $sifre);

    }

    // Veriyi şifrele (kalıcı):

    function sifrele($veri)
    {
        $sonuc= md5(htmlGizle($veri));
        return $sonuc;        
    }
   
    /********************************** LOGİN İŞLEMLERİ **********************************/

    /*** OTURUM (SESSIONS/COOKIES) VERİSİ ***/

    // SESSION:

    function ses($degisken) 
    {
        return isset($_SESSION[$degisken]) ? veriGoster($_SESSION[$degisken]) : 0;
    }

    // SESSION KAYDET:

    function session($degisken, $veri) 
    {
        $_SESSION[$degisken] = veriGizle($veri);
        return true;
    }

    // COOKIE:

    function coo($degisken) 
    { // göster
        return isset($_COOKIE[$degisken]) ? veriGoster($_COOKIE[$degisken], 'CEREZ_GIZLI') : 0;  // altta CEREZ_GIZLI kullanarak şifrelemişti onunla tekrar çözdü.
    }

    // COOKIE KAYDET:

    function cookie($degisken, $veri) 
    {
        return setcookie($degisken, veriGizle($veri, 'CEREZ_GIZLI'), (_TIME_+86400*3), _URL_, _HOST_, false, true);
    }


/********************************** YÖNLENDİRME İŞLEMLERİ **********************************/    

    function git($url,$time=0)
    {
        if ($time != 0) {
            header("Refresh:$time;url=$url");
        } else {
            header("Location:$url");
        }
    }

    function geriGit($time=0)
    {
        $url=$_SERVER["HTTP_REFERER"];
        if ($time != 0) {
            header("Refresh:$time;url=$url");
        } else {
            header("Location:$url");
        }
    }

/********************************** #YÖNLENDİRME İŞLEMLERİ **********************************/    


/*** SEO LİNK ***/

    function seo_url($text) 
    {
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $text = strtolower(str_replace($find, $replace, $text));
        $text = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $text);
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $text = str_replace(' ', '-', $text);
        return $text;
    }



   /*** VERİ DÖNÜŞTÜR ***/


    // Array->JSON:

    function a2j($veri) 
    {
        $veri = str_replace(["\n","\r"], ' ', $veri);
        return json_encode($veri, JSON_UNESCAPED_UNICODE);
    }

    // JSON->Array:

    function j2a($veri) 
    {
        return json_decode($veri, true);
    }
 

    /*** HTML TEMİZLE ***/

    function htmlGizle($degisken) 
    {
        if (is_array($degisken)) {
            foreach ($degisken as $i => $degisken) $veri[$i] = htmlGizle($degisken); 
        } else {
            $veri = stripslashes(htmlspecialchars(trim($degisken),ENT_QUOTES));
        }
        return $veri;
    }


    // HTML (Özel karakterler) GÖSTER:

    function htmlGoster($degisken) 
    {
        return htmlspecialchars_decode($degisken,ENT_QUOTES);
    }

    /*** GELEN VERİ ***/

    // GET:

    function g($degisken) 
    {
        return isset($_GET[$degisken]) ? htmlGizle($_GET[$degisken]) : '';
    }

    // POST:

    function p($degisken) 
    {
        return isset($_POST[$degisken]) ? htmlGizle($_POST[$degisken]) : '';
    }


?>