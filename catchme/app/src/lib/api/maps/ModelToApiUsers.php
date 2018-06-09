<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 15/09/2017 */

namespace Api\Map;
use User as DbUser;


class ModelToApiUsers {

    public static function single(DbUser $dbUser) {
        return new ModelToApiUser($dbUser);
    }

    public static function multiple() {
        return new ModelToApiUsers();
    }



    private function __construct() {
        // No parameters needed
    }



    /** @var DbUser $requestingUser */
    private $requestingUser;


    /** @return ModelToApiUsers */
    public function applyPrivacyPolicy(DbUser $requestingUser) {
        $this->requestingUser = $requestingUser;
        return $this;
    }



    public function users(array $dbUsers) {
        return array_map([$this, 'user'], $dbUsers);
    }


    public function user(DbUser $dbUser) {
        $modelToApiUser = self::single($dbUser);

        $modelToApiUser->withEmail();
        $modelToApiUser->withPhone();

        if (!is_null($this->requestingUser)) {
            $modelToApiUser->applyPrivacyPolicy($this->requestingUser);
        }

        return $modelToApiUser->get();
    }
    
}