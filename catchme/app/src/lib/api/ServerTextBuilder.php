<?php /** Created by Krishan Marco Madan on 06-Jun-18. */

namespace Api;

use Grpc\Server;

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

    /** @return ServerTextBuilder */
    public function textBold($text, $styleArray = []) {
        return $this->text($text, array_merge($styleArray, ['fontWeight' => 'bold']));
    }

    /** @return ServerTextBuilder */
    public function text($text, $styleArray = null) {
        $params = [self::KEY_TEXT => $text];

        if (!is_null($styleArray))
            $params[self::KEY_STYLE] = classFromArray($styleArray);

        $this->strings[] = classFromArray($params);

        return $this;
    }

    /** @return ServerTextBuilder */
    public function i18n($i18n, $styleArray = null) {
        $params = [self::KEY_I18N => $i18n];

        if (!is_null($styleArray))
            $params[self::KEY_STYLE] = classFromArray($styleArray);

        $this->strings[] = classFromArray($params);

        return $this;

    }

    public function get() {
        return $this->strings;
    }

}