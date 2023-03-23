
# UYGULAMA:

	// Bilgiler:

	define( "_HOST_",		$_SERVER["HTTP_HOST"] );

	define( "_DIR_",		rtrim($_SERVER["DOCUMENT_ROOT"], '/') );

	define( "_URL_", 		str_replace(['\\',_DIR_], ['/',null], __DIR__).'/' ); // bu dosyanın olduğu dizin URL (/müsteri)

	define( "_IP_",			(isset($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["HTTP_X_REAL_IP"] : $_SERVER["REMOTE_ADDR"]) );

	define( "_HTTP_", 		(isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) ? $_SERVER["HTTP_X_FORWARDED_PROTO"] : $_SERVER["REQUEST_SCHEME"]).'://'._HOST_ );

	define( "_URL_UP_",		_URL_."images/"._HOST_."/" ); // Yüklenenler (Upload) Dizini

	define( "_ANASAYFA_",	_HTTP_."/musteri/" ); // Anasayfa

	define( "_ANA_DİZİN_",	_DIR_."/musteri/" ); // Anasayfa  dır.url kullanılabilir

	

	// Zaman:

	define( "_TODAY_",		date("Y-m-d") );

	define( "_NOW_",		date("Y-m-d H:i:s") );

	define( "_TIME_",		time() );
