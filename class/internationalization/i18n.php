<?php
require_once (__DIR__ . "/../../lib/php-gettext/gettext.inc");
class I18n {
	public static function init(string $locale, string $locales_dir, string $textdomain = "messages") {
		_setlocale ( LC_ALL, $locale );
		_setlocale ( LC_CTYPE, $locale );
		_bindtextdomain ( $textdomain, $locales_dir );
		_bind_textdomain_codeset ( $textdomain, 'UTF-8' );
		_textdomain ( $textdomain );
	}
}