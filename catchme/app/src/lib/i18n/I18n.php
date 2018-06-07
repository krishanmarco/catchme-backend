<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace I18n;

class I18n {

    /** @return string */
    public static function strReplace($locale, $str) {
        return strtr($str, self::getLocaleStrings($locale));
    }

    /** @return string */
    public static function str($locale, $strId) {
        return self::getLocaleStrings($locale)[$strId];
    }

    /** @return array Array<String => String> */
    private static function getLocaleStrings($locale = 'en') {
        $file = __DIR__ . "/lang/$locale.json";

        if (!file_exists($file))
            $file = __DIR__ . '/lang/' . L::defaultLocale . '.json';

        return json_decode(file_get_contents($file), true);
    }

}