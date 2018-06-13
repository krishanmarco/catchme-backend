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

    /** @var DbUser */
    private $user;

    /** @var DbUser[] (int => DbUser) */
    private $accUsers = [];

    /** @var int[] */
    private $userFriendIds = [];

    /** @var int[] */
    private $userPendingIds = [];

    /** @var int[] */
    private $userRequestIds = [];

    /** @var int[] */
    private $userBlockedIds = [];

    /** @return DbUser[] (int => DbUser) */
    public function getAccDbUsers() {
        return $this->accUsers;
    }

    /** @return int[] */
    public function getUserFriendIds() {
        return $this->userFriendIds;
    }

    /** @return int[] */
    public function getUserPendingIds() {
        return $this->userPendingIds;
    }

    /** @return int[] */
    public function getUserRequestIds() {
        return $this->userRequestIds;
    }

    /** @return int[] */
    public function getUserBlockedIds() {
        return $this->userBlockedIds;
    }

    private function calculateUserConnections() {
        $leftAssoc = UserConnectionQuery::create()
            ->filterByUserId($this->user->getId())
            ->joinWithConnectionTo()
            ->find();

        foreach ($leftAssoc as $uf) {
            $connectionId = $uf->getConnectionId();
            $connectionTo = $uf->getConnectionTo();

            switch ($uf->getState()) {

                case EConnectionState::CONFIRMED:
                    $this->userFriendIds[] = $connectionId;
                    $this->accUsers[$connectionId] = $connectionTo;
                    break;

                case EConnectionState::BLOCKED:
                    $this->userBlockedIds[] = $connectionId;
                    $this->accUsers[$connectionId] = $connectionTo;
                    break;

                case EConnectionState::PENDING:
                    $this->userPendingIds[] = $connectionId;
                    $this->accUsers[$connectionId] = $connectionTo;
                    break;
            }
        }

        $rightAssoc = UserConnectionQuery::create()
            ->filterByConnectionId($this->user->getId())
            ->joinWithUser()
            ->find();

        foreach ($rightAssoc as $uf) {
            $connectionId = $uf->getUserId();
            $connectionTo = $uf->getUser();

            switch ($uf->getState()) {

                case EConnectionState::CONFIRMED:
                    $this->userFriendIds[] = $connectionId;
                    $this->accUsers[$connectionId] = $connectionTo;
                    break;

                case EConnectionState::PENDING:
                    $this->userRequestIds[] = $connectionId;
                    $this->accUsers[$connectionId] = $connectionTo;
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
