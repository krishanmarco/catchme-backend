<?php /** Created by Krishan Marco Madan on 12-May-18. */

namespace Security;

class DataEncrypter {

    public static function publicEncryptStr($string) {
        $encrypted = null;
        openssl_public_encrypt(
            $string,
            $encrypted,
            CATCHME_API_PUBLIC_KEY
        );
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }

    public static function privateDecryptStr($string) {
        $decrypted = null;
        openssl_private_decrypt(
            base64_decode($string),
            $decrypted,
            CATCHME_API_PRIVATE_KEY,
            OPENSSL_PKCS1_PADDING
        );
        return $decrypted;
    }


}