<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace I18n;

class I18n {

    /** @return string */
    public static function strReplace($langId, $str) {
        return strtr($str, self::getLangStrings($langId));
    }

    /** @return string */
    public static function str($langId, $strId) {
        return self::getLangStrings($langId)[$strId];
    }

    /** @return array Array<String => String> */
    private static function getLangStrings($langId) {
        $langId = 'en';
        $file = __DIR__ . "/lang/$langId.json";

        if (!file_exists($file))
            $file = __DIR__ . "/lang/en.json";

        return json_decode(file_get_contents($file), true);
    }

}