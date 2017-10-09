<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 14/09/2017 - Fithancer © */

namespace Models\User\Accounts;

use Models\Queries\User\UserQueriesWrapper;
use Propel\Runtime\Exception\PropelException;
use Slim\Exception\ApiException;
use UserConnection;
use UserConnectionQuery;
use User;
use R;

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
        $userConnection->setState(\EConnectionState::PENDING);


        try {
            $userConnection->save();

        } catch (PropelException $exception) {
            switch ($exception->getCode()) {
                default:
                    throw new ApiException(R::return_error_generic, $exception);
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
            $userConnection->setState(\EConnectionState::CONFIRMED)->save();
    }


    public function block($uid) {

        $userConnection = UserQueriesWrapper::findUsersConnection(
            $this->user->getId(),
            $uid
        );

        $userConnection->setState(\EConnectionState::BLOCKED)->save();
    }



    private function del($uid, UserConnection $userConnection = null) {

        if (is_null($userConnection))
            $userConnection = UserQueriesWrapper::findUsersConnection(
                $this->user->getId(),
                $uid
            );


        if (!is_null($userConnection))
            $userConnection->delete();
    }



}