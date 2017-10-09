<?php

use Base\SearchUser as BaseSearchUser;

/**
 * Skeleton subclass for representing a row from the 'search_user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SearchUser extends BaseSearchUser {

    public static function buildForUser(User $user, SearchUser $searchUser) {
        $searchUser->setUserId($user->getId());
        $searchUser->setQuery(strtoupper(strtr(
            "{NAME} {EMAIL} {PHONE}",
            [
                '{NAME}' => $user->getName(),
                '{EMAIL}' => $user->getEmail(),
                '{PHONE}' => $user->getPhone()
            ]
        )));

        return $searchUser;
    }

}
