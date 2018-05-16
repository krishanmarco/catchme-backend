<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use Models\Calculators\UserModel;
use User as User;
use UserConnectionQuery as UserConnectionQuery;
use EConnectionState;

class UserConnections {

    public function __construct(UserModel $UserModel) {
        $this->userModel = $UserModel;
    }

    /** @var UserModel $userModel */
    private $userModel;
    public function getUser() { return $this->userModel->getUser(); }




    public function execute() {
        $friends = [];
        $pending = [];
        $requests = [];
        $blocked = [];


        $leftAssoc = UserConnectionQuery::create()
            ->filterByUserId($this->getUser()->getId())
            ->joinWithConnectionTo()
            ->find();

        foreach ($leftAssoc as $uf) {
            switch ($uf->getState()) {

                case EConnectionState::CONFIRMED:
                    array_push($friends, $uf->getConnectionTo());
                    break;

                case EConnectionState::BLOCKED:
                    array_push($blocked, $uf->getConnectionTo());
                    break;

                case EConnectionState::PENDING:
                    array_push($pending, $uf->getConnectionTo());
                    break;

            }
        }


        $rightAssoc = UserConnectionQuery::create()
            ->filterByConnectionId($this->getUser()->getId())
            ->joinWithUser()
            ->find();

        foreach ($rightAssoc as $uf) {
            switch ($uf->getState()) {

                case EConnectionState::CONFIRMED:
                    array_push($friends, $uf->getUser());
                    break;

                case EConnectionState::PENDING:
                    array_push($requests, $uf->getUser());
                    break;

                case EConnectionState::BLOCKED:
                    // Don't add anything to the blocked array because
                    // If the current user sent a request to the
                    // {connectionId} user and the {connectionId} user denied it
                    // This user shouldn't have the possibility 'unblock'
                    break;

            }
        }

        return new UserConnectionsResult($friends, $pending, $requests, $blocked);
    }





}




class UserConnectionsResult {

    /**
     * UserConnectionsResult constructor.
     * @param User[] $friends
     * @param User[] $requests
     * @param User[] $blocked
     */
    public function __construct(array $friends, array $pending, array $requests, array $blocked) {
        $this->friends = $friends;
        $this->pending = $pending;
        $this->requests = $requests;
        $this->blocked = $blocked;
    }


    /** @var User[] $friends */
    private $friends;
    public function getFriends() { return $this->friends; }

    /** @var User[] $requests */
    private $pending;
    public function getPending() { return $this->pending; }

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