<?php

use Base\User as BaseUser;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser {


    // When a location is saved/updated we need to automatically
    // save its search query in the LocationSearch table
    public function postInsert(\Propel\Runtime\Connection\ConnectionInterface $con = null) {
        parent::postInsert($con);
        SearchUser::buildForUser($this, new SearchUser())
            ->save($con);
    }



    // When a location is saved/updated we need to automatically
    // save its search query in the LocationSearch table
    public function postUpdate(\Propel\Runtime\Connection\ConnectionInterface $con = null) {
        parent::postUpdate($con);
        SearchUser::buildForUser($this, SearchUserQuery::create()->findPk($this->getId()))
            ->save($con);
    }




    /** @var int[] friendIds */
    private $friendIds;

    public function getFriendIds() {

        if (is_null($this->friendIds)) {
            $this->friendIds = \Models\Calculators\UserModel::fromUser($this)
                ->getUserConnectionsResult()
                ->getFriendIds();
        }

        return $this->friendIds;
    }

}
