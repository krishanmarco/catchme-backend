<?php /** Created by Krishan Marco Madan on 07-Jun-18. */

namespace Context;

use I18n\L;

class Context {

    private static $requestLocale = L::defaultLocale;

    // https://tools.ietf.org/html/draft-thomson-geopriv-http-geolocation-00
    // eg: 'geo:53.12,10.15;cgen=gp'
    private static $geolocation = null;

    public static function setRequestLocale($locale = L::defaultLocale) {
        self::$requestLocale = $locale;
    }

    public static function getRequestLocale() {
        return self::$requestLocale;
    }


    public static function setGeolocation($geolocation = null) {
        self::$geolocation = $geolocation;
    }

    public static function getGeolocation() {
        return self::$geolocation;
    }

}