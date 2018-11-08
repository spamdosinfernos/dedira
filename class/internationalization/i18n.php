<?php
require_once __DIR__ . "/../../lib/vendor/autoload.php";

class I18n {

	public static function init(string $locale, string $locales_dir, string $textdomain = "LC_MESSAGES") {
		setlocale ( LC_ALL, $locale );
		setlocale ( LC_CTYPE, $locale );
		bindtextdomain ( $textdomain, $locales_dir );
		bind_textdomain_codeset ( $textdomain, 'UTF-8' );
		textdomain ( $textdomain );
	}
}