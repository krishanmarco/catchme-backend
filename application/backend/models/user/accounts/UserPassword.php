<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] - Fithancer v1.0 © */

namespace Models\User\Accounts;

use \Propel\Runtime\Exception\PropelException;
use Slim\Exception\Api400;
use User as DbUser;
use UserQuery;
use R;
use Api\FormChangePassword as ApiFormChangePassword;

class UserPassword {

    public static function change(ApiFormChangePassword $form) {
        $up = new UserPassword(UserQuery::create()->findOneByEmail($form->email));
        return $up->changePassword($form);
    }


    private function __construct(DbUser $user) {
        $this->user = $user;
    }

    /** @var DbUser $user */
    private $user;

    public function getUser() {
        return $this->user;
    }

    public function changePassword(ApiFormChangePassword $form) {
        // Check if the old password is correct
        $ulv = new UserLoginValidations($this->user->getEmail());
        $ulv->checkExistsAndPassword($form->passwordPrevious);

        // The old-password is correct
        // Check if new password is == new password confirm
        if ($form->passwordNext != $form->passwordConfirmNext)
            throw new Api400(R::return_error_passwords_not_equal);

        // The new password is == new password confirm
        // Change the password
        $this->user = UserAccountUtils::setUserPassword($this->user, $form->passwordNext);

        try {
            $this->user->save();

        } catch (PropelException $exception) {
            // Unknown Exception
            throw new Api400();
        }

        return R::return_ok;
    }

}