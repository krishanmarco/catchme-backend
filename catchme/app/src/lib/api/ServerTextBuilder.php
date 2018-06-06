<?php /** Created by Krishan Marco Madan on 06-Jun-18. */

namespace Api;

class ServerTextBuilder {
    const KEY_TEXT = 't';
    const KEY_I18N = 'i';
    const KEY_STYLE = 's';

    public static function build() {
        return new ServerTextBuilder();
    }

    /** @var array Array<Array> */
    private $strings = [];

    /** @return ServerTextBuilder */
    public function space() {
        return $this->text(' ');
    }

    public function textBold($text, $styleArray = null) {
        return $this->text($text, array_merge($styleArray, ['fontWeight' => 'bold']));
    }

    /** @return ServerTextBuilder */
    public function text($text, $styleArray = null) {
        $this->strings[] = classFromArray([
            self::KEY_TEXT => $text,
            self::KEY_STYLE => classFromArray($styleArray)
        ]);
        return $this;
    }

    /** @return ServerTextBuilder */
    public function i18n($i18n, $styleArray = null) {
        $this->strings[] = classFromArray([
            self::KEY_I18N => $i18n,
            self::KEY_STYLE => classFromArray($styleArray)
        ]);
        return $this;

    }

    public function get() {
        return $this->strings;
    }

}