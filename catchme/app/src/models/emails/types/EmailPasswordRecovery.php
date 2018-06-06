<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace Models\Email;

use I18n\L;
use User as DbUser;
use I18n\I18n;

class EmailPasswordRecovery extends EmailBase {

    public function __construct(DbUser $dbUser, $recoveryLink) {
        parent::__construct($dbUser, $dbUser->getLang(), true);
        $this->dbUser = $dbUser;
        $this->recoveryLink = $recoveryLink;
    }

    /** @var DbUser */
    private $dbUser;

    /** @var string */
    private $recoveryLink;

    protected function getExtraHeaders() {
        return [];
    }

    protected function getSubject() {
        return I18n::str($this->getLangId(), L::lang_pass_recovery_title);
    }

    protected function getEmailTitle() {
        return $this->getSubject();
    }

    protected function getHeaderTitle() {
        return I18n::str($this->getLangId(), L::lang_hi_user) . ', ' . $this->dbUser->getName();
    }

    protected function getHeaderText() {
        return I18n::str($this->getLangId(), L::lang_pass_recovery_text);
    }

    protected function getContentHtml() {
        return I18n::strReplace($this->getLangId(), strtr(
            $this->getEmailStr('email_password_recovery/content'),
            [
                '{recovery_link}' => $this->recoveryLink
            ]
        ));
    }

}