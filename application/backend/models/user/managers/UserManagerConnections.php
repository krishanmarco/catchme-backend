<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 - Fithancer © */

namespace Models\User\Accounts;

use Models\Queries\User\UserQueriesWrapper;
use Propel\Runtime\Exception\PropelException;
use Slim\Exception\Api400;
use UserConnection;
use UserConnectionQuery;
use User;
use R;
use EConnectionState;

class UserManagerConnections {

    public function __construct(User $user) {
        $this->user = $user;
    }

    /** @var User $user */
    private $user;


    public function add($uid) {
        $userConnection = new UserConnection();
        $userConnection->setUserId($this->user->getId());
        $userConnection->setConnectionId(intval($uid));
        $userConnection->setState(EConnectionState::PENDING);

        try {
            $userConnection->save();

        } catch (PropelException $exception) {
            switch ($exception->getCode()) {
                default:
                    throw new Api400(R::return_error_generic, $exception);
            }
        }
    }

    public function accept($uid) {
        // To accept a request the current user must be
        // in the connectionId column and the state must be 0
        $userConnection = UserConnectionQuery::create()
            ->filterByUserId($uid)
            ->filterByConnectionId($this->user->getId())
            ->findOne();

        if (!is_null($userConnection))
            $userConnection->setState(EConnectionState::CONFIRMED)->save();
    }


    public function block($uid) {
        $userConnection = UserConnectionQuery::create()
            ->filterByConnectionIds($this->user->getId(), $uid)
            ->findOne();

        $userConnection->setState(EConnectionState::BLOCKED)->save();
    }


// todo?
    private function del($uid, UserConnection $userConnection = null) {

        if (is_null($userConnection)) {
            $userConnection = UserConnectionQuery::create()
                ->filterByConnectionIds($this->user->getId(), $uid)
                ->findOne();
        }

        if (!is_null($userConnection))
            $userConnection->delete();
    }



}