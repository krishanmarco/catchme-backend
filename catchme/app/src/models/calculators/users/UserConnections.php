<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\Calculators\Users;

use User as DbUser;
use UserConnectionQuery;
use EConnectionState;
use Models\UserConnectionsResult;

class UserConnections {

    public function __construct(DbUser $user) {
        $this->user = $user;
        $this->calculateUserConnections();
    }

    /** @var DbUser $user */
    private $user;

    /** @var UserConnectionsResult $result */
    private $result;

    /** @return UserConnectionsResult */
    public function getResult() {
        return $this->result;
    }

    private function calculateUserConnections() {
        $friends = [];
        $pending = [];
        $requests = [];
        $blocked = [];

        $leftAssoc = UserConnectionQuery::create()
            ->filterByUserId($this->user->getId())
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
            ->filterByConnectionId($this->user->getId())
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

        $this->result = new UserConnectionsResult($friends, $pending, $requests, $blocked);
    }

}
