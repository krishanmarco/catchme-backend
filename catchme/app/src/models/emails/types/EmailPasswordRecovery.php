<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace Models\Email;
use User as DbUser;

class EmailPasswordRecovery extends EmailBase { // todo lang

    public function __construct(DbUser $dbUser, $recoveryLink) {
        parent::__construct($dbUser, true);
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
        return 'CATCHME PASSWORD RECOVERY';
    }

    protected function getEmailTitle() {
        return $this->getSubject();
    }

    protected function getHeaderTitle() {
        return $this->dbUser->getName();
    }

    protected function getHeaderText() {
        return 'Your new password';
    }

    protected function getContentHtml() {
        return strtr(file_get_contents(__DIR__ . '/../../../html/emails/email_password_recovery/content.html'), [
            '{recovery_link}' => $this->recoveryLink
        ]);
    }

}