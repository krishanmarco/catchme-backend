<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 09/10/2017 - Fithancer © */

namespace Models\Feed;
use Models\Calculators\UserModel;
use User;

class MultiFeedManager {


    public function __construct(User $user) {
        $this->userModel = UserModel::fromUser($user);
    }


    /** @var UserModel $userModel */
    private $userModel;




    /**
     * Gets the current users (based on $userModel)
     * friends that can be notified of an event
     * ----------------------------
     * @return int[]
     */
    public function getNotifiableFriendIds() {
        return [];
    }



}