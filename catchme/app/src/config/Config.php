<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Catch Me 1.0 © */

// Important
// Fields tagged with @Development
// Have to be adapted when the application is pushed to the production server


// Development
define("DEVELOPMENT_MODE", true);
define("SERVER_URL", "http://www.catchme-v2.krishanmadan.website");
define("DB_NAME", "krisaxcv_catchme-v2");
define("DB_USER", "krisaxcv_catchme-v2");
define("DB_PASS", "w.kc5M!Q&J}f");


define("URL_API", SERVER_URL . "/api");
define("URL_IMAGES", SERVER_URL . "/img");
define("URL_LOGO_WHITE", URL_IMAGES . "/white-me.png");
define("CATCHME_CONTACT_PHONE", "+39 334 701 49 35");
define("CATCHME_CONTACT_EMAIL", "catchme@catchme.com");
define("URL_FACEBOOK", "https://www.facebook.com");
define("URL_TWITTER", "https://www.twitter.com");
define("URL_GOOGLE_PLUS", "https://www.google.com");
define("URL_TERMS", SERVER_URL);
define("URL_PRIVACY", SERVER_URL);
define("URL_UNSUBSCRIBE", SERVER_URL);

define("AUTH_TOKEN_TIME_TO_LIVE_SECONDS", 24 * 60 * 60);

define(

    "CATCHME_API_PRIVATE_KEY",

    "-----BEGIN RSA PRIVATE KEY-----\n" .
    "MIICXAIBAAKBgQDUZuHa+7qfwtsCknfVNJ5GBfKWYZWK7o5nFGiSOXQS1jBYKVLQ\n" .
    "VLdnyK+5bb/cNeHPDNRGwfDP2kqPZqhCLX8VKtWA11GxkUI6TI3cpLfIYDRNQQVV\n" .
    "7SXL9OjkGrqYdO2SyxcgXTR9eIoaSKEjnLcx7r4P6RT4Njzw5f3zJNeZwQIDAQAB\n" .
    "AoGAMgfN2XQEAI+4YMG5YkoTofDStGNmAySv/E3NV+wakDlNh+ar8BCUZujZo3bb\n" .
    "g3ZZqxidg9E49Oy5NU/8ACKRVS/wiXaM/EH4yfh6lCPaRm6c5oNiBCvja27+JmCz\n" .
    "2vFkXB3dwrAm7UFzxouT6e8wi5xfote5sMEnHmTidIBqIUUCQQD6dI0OTLLo6ok/\n" .
    "YjTvL4ry7Mghi5ANyd39yv8vcYcXshCaEjxrBECa7O1CNp4PBveuU8qTPJbBjRiB\n" .
    "zJwGGLwTAkEA2RqqIuUTGxVPBZDv49wU4cot+McLAsWPspm74JXOQ1tsZZ/Fe8nT\n" .
    "4rifJabglCoi64DaoYHOgwAmd3LfPWolWwJBAJb7tB0ut5wZ52tCdM7MRmNzwqIW\n" .
    "VF07mIvq2DNtqRbrzX5UCAArrBa5Rb5o1pgQhzecY76nA+rieCenhVdXiekCQEKP\n" .
    "30vVidcK4HBncHUe27QiJZgZhnGyGo16ftSreVLDa+d4Zba/OVxQmFKV6FLk3FHx\n" .
    "7pYH00XsvN5wdKtCqPMCQFnuNWRLgvSnXoJUgq7r80TOlyyMiY4fARVSxmSZWTCU\n" .
    "v5eBmt77DJamvBi570U6fvy0TpGtTewdgBIZB32zyRg=\n" .
    "-----END RSA PRIVATE KEY-----"

);

define(

    "CATCHME_API_PUBLIC_KEY",

    "-----BEGIN PUBLIC KEY-----\n" .
    "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDUZuHa+7qfwtsCknfVNJ5GBfKW\n" .
    "YZWK7o5nFGiSOXQS1jBYKVLQVLdnyK+5bb/cNeHPDNRGwfDP2kqPZqhCLX8VKtWA\n" .
    "11GxkUI6TI3cpLfIYDRNQQVV7SXL9OjkGrqYdO2SyxcgXTR9eIoaSKEjnLcx7r4P\n" .
    "6RT4Njzw5f3zJNeZwQIDAQAB\n" .
    "-----END PUBLIC KEY-----"

);








define("FIREBASE_API_KEY", "AIzaSyAou7dzdwfMFwmMqcxSs09I9RPohvr2jlI");
define("FIREBASE_DATABASE_URI", "https://catch-me-179514.firebaseio.com");
define("FIREBASE_PROJECT_ID", "catch-me-179514");


define('FIREBASE_LOGIN_SERVICE_CLIENT_EMAIL', 'id-catchme-firebase-login-serv@catch-me-179514.iam.gserviceaccount.com');
define('FIREBASE_LOGIN_SERVICE_PRIVATE_KEY', "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDlXTx8BJRrfPUk\nwLqYGz5qDvZkQsklpeSc46bJqGWCmYEUH0Nx9uKJ6zxCjUcn9aFmxXMUGtnsN5/c\n4TQdwhB56ohXlOT8Z+e8rTnLAFmpTm2NRb4oihH33nOkxdKAo16bhG76Q9+xmV4a\nJIPO0tiDwRgXvrWmQzCXVqPAjADii8PIvyCa+oV2ZWMiiKJ7gP36mexfgI5kRCCI\nIn+9XPSZDC9R2blzUhWdwdzfu62YMwJl/bd5DC4NtAjlRVGYsnui8qZAfUVXl9u1\nPcfUtJNq4YNS8lzQKMR65DdpbofYsXDhAZcGFhPsU2BtkV54s0anQsa84a+gxAV3\nd2uySYOlAgMBAAECggEAEdNhz9J1soQYpucvphvf77nP+ATUwZwXnQKrkMEJep5X\n6mqizdwKyTGpRXoNAK1vaNK82eAoXA2kQZqWRIXNMzYOEjh1/3cX18SvjCsvzLlV\n4rfiSoZIiGoXTl2guYysEQwe3LM8hf7OBUKECyWJ3EHvXGqrwj5qK8J5B/R/94Gq\nZPtwhW3qrBMrCRZIOLNCxVb+07iw7XN5xgZ/Ie8obUBEPOTQgcD/fKsT99CRNp4T\nbO46XFxrrn3eQd5QeWFlpl5UOYnbcpk6wZHcOHsRD/jnX7QPfCQ+7+u6lx/WXQRE\nWpVon+q4/s3ml/ebc4k9vqsOXiixQ34AccNjkw0m9QKBgQD3CNNN92E5CFUgTBrM\ns//9/+x4qu2ZS9+kHWXAWX4Uo62D/ucsyoM/cZlS1G8jeUui60vrLRXuETeGk4CF\nCPvtA2FELdOM+2VLUk9dux8QVn3J+5IMUGw6sc3DMcfRZWokWGDcbKcHZok/DrF5\nk0f6O5OBwof5adqgDqAXPmxiLwKBgQDtsDzt0608sEaHCggAJnu1eNrRv4PWHgL1\nRQ7bNo+I2wIYBU0rk5zTIiXp7Xovbik+N1T2DeFb+eAUM1JtgLYscelpKAUTUeZw\n17UJbj/hRu8PgjUAACrMD73RY4cdOLZ1O4ydLiOUy1N2xVjPuA8Qtwk8K+KAVdli\n2RUk506mawKBgQC1Lujj/zN0hBMDXC4vwzlXTxReMGeRjp+Zm+IcIMcjViUWcaeW\nJ3X521Sr9pkI+JasCE2nUGsML958usSBTmSPonH9cmr9tQjHJLiHM44GCpm5weSN\nWZL3vZ7/sgwvHWWrAJMSODKNb/vrntg2JfqdooJ+onHeUXADApcSVTtjCwKBgEOC\nSOvrsUqJbp6wNLGGPKDAYLYuRQ2tnH15TczpZD8kpSWZa9+yn1pAWrBkaM3L5h7r\nrE/uhVGQWRqjsQe1BQj+maLqYPapzl/ChILXM4GSmhe3jcIgSgeHeQxdzmR4VSpa\n7Yc/MY/zaBNV3fGxf2Xp9s+GT2DQAVxX9+9xWx9dAoGBAKpVS6tPx4su5bJhRExQ\nQ6uqIMDa4Qfg9LArFxvYjYvDx434XDfX2AlC2cc8HGSI/5N2dnHwKhpioC+eYVrq\nHaCVmvi6HRBJriC9dM60EhB5jY6uoKRmGQ3ierobM1Du+eWgzDSUiPS/DSKeEWiO\n5PUHHrfwCvFG3s1pajm/K6AE\n-----END PRIVATE KEY-----\n");


define("FIREBASE_ADMIN_SERVICE_CLIENT_ID", "106470506194480246689");
define("FIREBASE_ADMIN_SERVICE_CLIENT_EMAIL", "firebase-adminsdk-k8jlz@catch-me-179514.iam.gserviceaccount.com");
define("FIREBASE_ADMIN_SERVICE_PRIVATE_KEY", "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDBgTKg73Gfoufk\n6MNwX8Li9rG40lzoQK9Wy830etd+BV6SpBpGjoAQ+rScq2xM6sNNR5x5kGUDLKy2\nYPL0t+NWpgH+aQsSvzWOBg8xt/YkbjLPBsvGbTu9wDMLLYK1jbTeY0Ugz4dhaGKR\nddQj6CrmOxcwhQbBfDHTuS50G70sENQm1S3IgNLztg5kV3SbdVkx8rnb9lSmBHTE\nCRth0F9zvv/gyI5EgEUukjB6HwTYKED31LssTQcoCRDwy2+KNSistw16tcxn1/YJ\n/ZcDlTGReCaqRrFFYlS5xeRiPyhUX3BauyiNzLmWwpy4LvRBXt1s3cg+99yZk/bo\nMHDgVhgzAgMBAAECggEAGls9KKOEj/BR8qFroV5BvsVIPrrUccQBvepDkrW6rU0Q\n7RtSAuM8+VMUj7Rfq3hcgqWq3/n1cEHBApRg80kqu24gHmVzXynRIxebMTPz4FFs\nNTuhPNU7CmTn7vFTeOQkyxetXkM2FuPvbQ2mCAAC+9n3liHAYlFGviZed+0hTqN9\nhph3xyqgLgu77uHnytk+pqoIG2WSuts85zK1l4CmKvfpY4FJaq5eUENqNFIftC5p\nHavH/Mhb/BK6Y2+RGLBfJ+63JhYiLRw+ZXZRT8goWDy+vMnqieAx71+qeYiX6lC4\nVVRglHj9LQbTELyjg6bozrw9TuBiLalfJVCcUE33ZQKBgQDiBvkmZKZiRuyHk3RG\n5bztw/TMg1iWimScfaIUgYaAOcSB19C1B5K8HuFrpPEjODr+G+SKps3VioAg4W5b\nvCndBYQ5UrSiVpyEjwAfPlvFcMviyMDy/9rdE1Z8HycJxNpwuh706skDQv42HSPc\njfO/yRU/tprp8lFQENWSP2vUzQKBgQDbKitt6ZH86J0EmOH0ZxFwLbfBzLdvX2hF\nwfJllNBTs5LduUeMfq4Kf6U6BXNIcXp7DXgfLvL2PPfAzqbH0+jqDQpGWJP90tC+\ngWEanomls/pt90VSVMPiZ4o9kXTpHt2rLoGP1B/tgSaxukS+anZNNqjim+bvAodb\nYeE9YqGg/wKBgGYMSe74oFpctSvc+jGMRgl/YIX5g0SshRJWcpgZhrVb34nT+4nQ\nOoKu2o20MZ76I0BZLY0gFGymFIVD4oSOZsRAWltbKOzmd9ItkhuJFvwEmjjw9JSB\nybnoojJ2CjUR6KHell1zp3/OfmHGNEu+118ulWZntneLjQS+JP7bnX69AoGActkD\nTyKx3O1ssucGqVOFgNqES+tiSNufQcbjpjW4gdQ9ZzFbfBU6UMgZJnGKuvC0dV1W\nkEjDCyRh1Ei2f0rd6CCTPM/YJY/e0aXs480mZo4M8qk8S2ueBrupZqAdurfxA25o\neJ15abVfOI1azsntWoMFN1LpTmBt3AZLySYxerMCgYEAkUGllXNzqxQIRfXBQEIG\nqBax2TwtCmP/i6KC4M+d6gLO62uGpvjYpe3xCu1TkeIxsFHjbqEqYxYVIpQrXNV0\n+/WOB+7Qm1tcHaBUyFh9yBg2KybeWVIyVplLLnLrWnjzSRh3wNnuVcKEEEUDxevm\ncLailTf71OGgW/0zjk2rstE=\n-----END PRIVATE KEY-----\n");









define("GOOGLE_CLIENT_ID", '458235769872-g7l24birc1n6d2cbqundeb8nupbb15sh.apps.googleusercontent.com');
define("GOOGLE_CLIENT_MOBILE_ID", '110773368710093854228');
define("FACEBOOK_CLIENT_ID", '371102913345579');
define("FACEBOOK_CLIENT_APPLICATION_NAME", 'Catchme');


define("___APP___", __DIR__ . '/../..');
define("__PRIVATE_DATA__", __DIR__ . '/../../data');
define("__PUBLIC_DATA__", __DIR__ . '/../../../public/data');
define("__TEMP__", __DIR__ . '/../../temp');


// Location constants
define('LOCATION_MEDIA_DIR_TPL', __PRIVATE_DATA__ . '/locations/{LID}/media');
define('LOCATION_MEDIA_TPL', LOCATION_MEDIA_DIR_TPL . '/{IMAGE_ID}');
define('LOCATION_MEDIA_TTL', 24 * 60 * 60);
define('LOCATION_MEDIA_APPLY_TTL', false);
define('LOCATION_MEDIA_IMAGE_404', 'http://via.placeholder.com/350x150/000000?text=404');


define('LOCATION_AVATAR_DIR_TPL', __PUBLIC_DATA__ . '/locations/{LID}/avatar');
define('LOCATION_AVATAR_TPL', LOCATION_AVATAR_DIR_TPL . '/{UNIQUE_NAME}');
define('LOCATION_AVATAR_URL_TPL', SERVER_URL . '/data/locations/{LID}/avatar/{UNIQUE_NAME}');


define('USER_AVATAR_DIR_TPL', __PUBLIC_DATA__ . '/users/{UID}/avatar');
define('USER_AVATAR_TPL', USER_AVATAR_DIR_TPL . '/{UNIQUE_NAME}');
define('USER_AVATAR_URL_TPL', SERVER_URL . '/data/users/{UID}/avatar/{UNIQUE_NAME}');
define('USER_PASSWORD_RECO_TTL', 30 * 60);

define('USER_LOCATIONS_MAX_TOP_ITEMS', 5);
define('SUGGEST_LOCATIONS_MAX_RANDOM', 20);

