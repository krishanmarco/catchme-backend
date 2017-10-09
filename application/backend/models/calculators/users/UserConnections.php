<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Models\Calculators\UserModel;
use User as User;
use UserConnectionQuery as UserConnectionQuery;
use EConnectionState as UserFriendState;

class UserConnections {

    public function __construct(UserModel $UserModel) {
        $this->userModel = $UserModel;
    }

    /** @var UserModel $userModel */
    private $userModel;
    public function getUser() { return $this->userModel->getUser(); }




    public function execute() {
        $confirmedFriends = [];
        $pendingRequests = [];
        $blockedRequests = [];


        $leftAssoc = UserConnectionQuery::create()
            ->filterByUserId($this->getUser()->getId())
            ->joinWithConnectionTo()
            ->find();

        foreach ($leftAssoc as $uf) {
            switch ($uf->getState()) {

                case EUserFriendState::CONFIRMED:
                    array_push($confirmedFriends, $uf->getConnectionTo());
                    break;

                case EUserFriendState::BLOCKED:
                    array_push($blockedRequests, $uf->getConnectionTo());
                    break;

            }
        }




        $rightAssoc = UserConnectionQuery::create()
            ->filterByConnectionId($this->getUser()->getId())
            ->joinWithUser()
            ->find();

        foreach ($rightAssoc as $uf) {
            switch ($uf->getState()) {

                case EUserFriendState::CONFIRMED:
                    array_push($confirmedFriends, $uf->getUser());
                    break;

                case EUserFriendState::PENDING:
                    array_push($pendingRequests, $uf->getUser());
                    break;

            }
        }

        return new UserConnectionsResult($confirmedFriends, $pendingRequests, $blockedRequests);
    }





}




class UserConnectionsResult {

    /**
     * UserConnectionsResult constructor.
     * @param User[] $friends
     * @param User[] $requests
     * @param User[] $blocked
     */
    public function __construct(array $friends, array $requests, array $blocked) {
        $this->friends = $friends;
        $this->requests = $requests;
        $this->blocked = $blocked;
    }


    /** @var User[] $friends */
    private $friends;
    public function getFriends() { return $this->friends; }

    /** @var User[] $requests */
    private $requests;
    public function getRequests() { return $this->requests; }

    /** @var User[] $blocked */
    private $blocked;
    public function getBlocked() { return $this->blocked; }




    /** @var int[] $friendIds */
    private $friendIds;

    public function getFriendIds() {
        if (is_null($this->friendIds)) {

            $this->friendIds = [];

            foreach ($this->friends as $user)
                array_push($this->friendIds, $user->getId());
        }

        return $this->friendIds;
    }


}