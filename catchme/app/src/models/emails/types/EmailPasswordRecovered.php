<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace Models\Email;

use I18n\L;
use User as DbUser;
use I18n\I18n;

class EmailPasswordRecovered extends EmailBase {

    public function __construct(DbUser $dbUser, $newPassword) {
        parent::__construct($dbUser->getEmail(), $dbUser->getLocale(), true);
        $this->dbUser = $dbUser;
        $this->newPassword = $newPassword;
    }

    /** @var DbUser */
    private $dbUser;

    /** @var string */
    private $newPassword;

    protected function getExtraHeaders() {
        return [];
    }

    protected function getSubject() {
        return I18n::str($this->getLocale(), L::lang_pass_recovered_title);
    }

    protected function getEmailTitle() {
        return $this->getSubject();
    }

    protected function getHeaderTitle() {
        return I18n::str($this->getLocale(), L::lang_hi_user) . ', ' . $this->dbUser->getName();
    }

    protected function getHeaderText() {
        return I18n::str($this->getLocale(), L::lang_pass_recovered_text);
    }

    protected function getContentHtml() {
        return I18n::strReplace($this->getLocale(), strtr(
            $this->getEmailStr('email_password_recovered/content'),
            [
                '{new_password}' => $this->newPassword
            ]
        ));
    }
}