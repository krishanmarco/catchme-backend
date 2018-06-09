<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 20/08/2017 */

class DbPasswordRecovery {

    public static function construct($email, $recoveryKey) {
        return new DbPasswordRecovery($email, $recoveryKey);
    }


    public function __construct($email, $recoveryKey) {
        $this->email = $email;
        $this->recoveryKey = $recoveryKey;
    }

    /** @var String $email */
    public $email;
    /** @var String $recoveryKey */
    public $recoveryKey;

}