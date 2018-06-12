<?php

namespace Models\User\Accounts;

use R;
use Slim\Exception\Api400;
use User as DbUser;
use UserQuery;


class UserLoginValidations {

    public function __construct($email) {
        $this->user = UserQuery::create()->findOneByEmail(strtolower($email));
    }


    /** @var DbUser $user */
    private $user;

    /** @return DbUser */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param string $password
     * @throws Api400
     */
    public function checkExistsAndPassword($password) {
        $this->checkExists();
        $this->checkPassword($password);
    }

    /**
     * @param string $inPassword
     * @throws Api400
     */
    public function checkExistsBannedAndPassword($inPassword) {
        $this->checkExistsAndBanned();
        $this->checkPassword($inPassword);
    }

    /** @throws Api400 */
    public function checkExistsAndBanned() {
        $this->checkExists();
        $this->checkBanned();
    }


    /** @throws Api400 */
    private function checkExists() {
        if (is_null($this->user))
            throw new Api400(R::return_error_user_not_found);
    }

    /** @throws Api400 */
    private function checkBanned() {
        if ($this->user->isBan())
            throw new Api400(R::return_error_user_banned);
    }

    /**
     * @param string $password
     * @throws Api400
     */
    private function checkPassword($password) {
        $inputPassword = UserAccountUtils::hashPassword($password, $this->user->getPassSalt());
        $databasePassword = $this->user->getPassSha256();

        if ($inputPassword != $databasePassword)
            throw new Api400(R::return_error_incorrect_password);
    }

}