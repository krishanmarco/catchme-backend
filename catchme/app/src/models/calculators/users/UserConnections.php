<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] */

namespace Models\Calculators\Users;

use EConnectionState;
use User as DbUser;
use UserConnectionQuery;

class UserConnections {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserConnections();
    }

    /** @var DbUser $user */
    private $user;

    /** @var DbUser[] */
    private $userFriends = [];

    /** @var DbUser[] */
    private $userPending = [];

    /** @var DbUser[] */
    private $userRequests = [];

    /** @var DbUser[] */
    private $userBlocked = [];

    /** @return DbUser[] */
    public function getUserFriends() {
        return $this->userFriends;
    }

    /** @return DbUser[] */
    public function getUserPending() {
        return $this->userPending;
    }

    /** @return DbUser[] */
    public function getUserRequests() {
        return $this->userRequests;
    }

    /** @return DbUser[] */
    public function getUserBlocked() {
        return $this->userBlocked;
    }

    private function calculateUserConnections() {

        $leftAssoc = UserConnectionQuery::create()
            ->filterByUserId($this->user->getId())
            ->joinWithConnectionTo()
            ->find();

        foreach ($leftAssoc as $uf) {
            switch ($uf->getState()) {

                case EConnectionState::CONFIRMED:
                    array_push($this->userFriends, $uf->getConnectionTo());
                    break;

                case EConnectionState::BLOCKED:
                    array_push($this->userBlocked, $uf->getConnectionTo());
                    break;

                case EConnectionState::PENDING:
                    array_push($this->userPending, $uf->getConnectionTo());
                    break;
            }
        }

        $rightAssoc = UserConnectionQuery::create()
            ->filterByConnectionId($this->user->getId())
            ->joinWithUser()
            ->find();

        foreach ($rightAssoc as $uf) {
            switch ($uf->getState()) {

                case EConnectionState::CONFIRMED:
                    array_push($this->userFriends, $uf->getUser());
                    break;

                case EConnectionState::PENDING:
                    array_push($this->userRequests, $uf->getUser());
                    break;

                case EConnectionState::BLOCKED:
                    // Don't add anything to the blocked array because
                    // If the current user sent a request to the
                    // {connectionId} user and the {connectionId} user denied it
                    // This user shouldn't have the possibility 'unblock'
                    break;
            }
        }

    }

}
