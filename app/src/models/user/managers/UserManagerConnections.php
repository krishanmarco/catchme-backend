<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 - Fithancer © */

namespace Models\User\Accounts;

use UserConnection;
use UserConnectionQuery;
use EConnectionState;
use User as DbUser;
use Firebase\FeedManager;
use Firebase\FeedItems\FeedItemFriendshipAccept;
use Firebase\FeedItems\FeedItemFriendshipRequest;

class UserManagerConnections {

    public function __construct(DbUser $authUser, $connectionUid) {
        $this->authUser = $authUser;
        $this->connectionUid = $connectionUid;
        $this->userConnection = UserConnectionQuery::create()
            ->filterByConnectionIds($this->authUser->getId(), $connectionUid)
            ->findOne();
    }

    /** @var DbUser */
    private $authUser;

    /** @var int */
    private $connectionUid;

    /** @var UserConnection */
    private $userConnection;


    public function add() {

        // If the connection doesn't exist and
        // authUid is trying to add it, create
        if (is_null($this->userConnection)) {
            $this->create(EConnectionState::PENDING)->save();
            $this->onFriendRequest();
            return;
        }

        // If the current user is the left user
        if ($this->userConnection->getUserId() == $this->authUser->getId()) {

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
            $this->userConnection->setState(EConnectionState::CONFIRMED)->save();
            $this->onRequestAccept();
            return;
        }
    }

    public function del() {

        // If the connection doesn't exist and $authId is
        // trying to delete it, create it in a blocked state
        if (is_null($this->userConnection)) {
            $this->create(EConnectionState::BLOCKED)->save();
            return;
        }

        // If the current user is the left user
        if ($this->userConnection->getUserId() == $this->authUser->getId()) {

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
            $this->userConnection->delete();
            $this->create(EConnectionState::BLOCKED)->save();
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
        $this->userConnection->setUserId($this->authUser->getId());
        $this->userConnection->setConnectionId($this->connectionUid);
        $this->userConnection->setState($connectionState);
        return $this->userConnection;
    }

    private function onFriendRequest() {
        // Add the notification item to firebase
        FeedManager::build($this->authUser)
            ->postSingleFeed(new FeedItemFriendshipRequest($this->authUser), $this->connectionUid);
    }

    private function onRequestAccept() {
        // Add the notification item to firebase
        FeedManager::build($this->authUser)
            ->postSingleFeed(new FeedItemFriendshipAccept($this->authUser), $this->connectionUid);
    }
}