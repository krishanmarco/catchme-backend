<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 - Fithancer © */

namespace Models\User\Accounts;

use UserConnection;
use UserConnectionQuery;
use EConnectionState;

class UserManagerConnections2
{

    public function __construct($authUid, $connectionUid) {
        $this->authUid = $authUid;
        $this->connectionUid = $connectionUid;
        $this->userConnection = UserConnectionQuery::create()
            ->filterByConnectionIds($authUid, $connectionUid)
            ->findOne();
    }

    /** @var int */
    private $authUid;

    /** @var int */
    private $connectionUid;

    /** @var UserConnection */
    private $userConnection;


    public function add() {

        // If the connection doesn't exist and
        // authUid is trying to add it, create
        if (is_null($this->userConnection)) {
            $this->create(EConnectionState::PENDING)
                ->save();
            return;
        }

        // If the current user is the left user
        if ($this->userConnection->getUserId() == $this->authUid) {

            // This user is the reference frame for this connection
            if ($this->userConnection->getState() == EConnectionState::BLOCKED) {
                // $leftUser (this user) had previously blocked $rightUser
                // And is now undoing that operation
                $this->userConnection->delete();
                return;
            }

            // No other operation to handle in this case
            return;
        }

        // The current user is the right user
        if ($this->userConnection->getState() == EConnectionState::PENDING) {

            // $leftUser had previously requested a friendship from $rightUser (This user)
            // Accept the friendship
            $this->userConnection->setState(EConnectionState::CONFIRMED);
            $this->userConnection->save();
            return;
        }
    }

    public function del() {

        // If the connection doesn't exist and $authId is
        // trying to delete it, create it in a blocked state
        if (is_null($this->userConnection)) {
            $this->create(EConnectionState::BLOCKED)
                ->save();
            return;
        }

        // If the current user is the left user
        if ($this->userConnection->getUserId() == $this->authUid) {

            // This user is the reference frame for this connection
            if ($this->userConnection->getState() != EConnectionState::BLOCKED) {
                // $authUser (left) had previously added or confirmed $connectionUid
                // and is now deleting the friendship
                $this->userConnection->delete();
                return;
            }

            // No other operation to handle in this case
            return;
        }

        // The $authUid on the right of the connection

        // If the current state is pending, and a del was sent, swap and block
        if ($this->userConnection->getState() == EConnectionState::PENDING) {

            // $connectionUid (left) previously requested a friendship to $authUser (right)
            // $authUser (right) is now blocking, so the reference frame has to be swapped
            $this->userConnection->setUserId($this->authUid);
            $this->userConnection->setConnectionId($this->connectionUid);
            $this->userConnection->setState(EConnectionState::BLOCKED);
            $this->userConnection->save();
            return;
        }

        // If the current state is confirmed, and a del was sent, delete
        if ($this->userConnection->getState() == EConnectionState::CONFIRMED) {
            // $authUser (right) previously added $connectionUid
            // and is now deleting the friendship
            $this->userConnection->delete();
            return;
        }
    }


    private function create($connectionState) {
        $this->userConnection = new UserConnection();
        $this->userConnection->setUserId($this->authUid);
        $this->userConnection->setConnectionId($this->connectionUid);
        $this->userConnection->setState($connectionState);
        return $this->userConnection;
    }

}