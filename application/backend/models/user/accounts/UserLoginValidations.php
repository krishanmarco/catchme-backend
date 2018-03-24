<?php

namespace Models\User\Accounts;
use Slim\Exception\Api400;
use UserQuery;
use User as DbUser;
use R;


class UserLoginValidations {

    public function __construct($email) {
        $this->user = UserQuery::create()->findOneByEmail(strtolower($email));
    }


    /** @var DbUser $user */
    private $user;

    public function getUser() {
        return $this->user;
    }



    public function checkExistsBannedAndPassword($inPassword) {
        $this->checkExistsAndBanned();
        $this->checkPassword($inPassword);
    }


    public function checkExistsAndBanned() {
        $this->checkExists();
        $this->checkBanned();
    }



    private function checkExists() {
        if (is_null($this->user))
            throw new Api400(R::return_error_user_not_found);
    }

    private function checkBanned() {
        if ($this->user->isBan())
            throw new Api400(R::return_error_user_banned);
    }

    private function checkPassword($password) {
        $inputPassword = UserAccountUtils::hashPassword($password, $this->user->getPassSalt());
        $databasePassword = $this->user->getPassSha256();

        if ($inputPassword != $databasePassword)
            throw new Api400(R::return_error_incorrect_password);
    }

}