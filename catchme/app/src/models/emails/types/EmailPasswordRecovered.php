<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace Models\Email;
use User as DbUser;

class EmailPasswordRecovered extends EmailBase { // todo lang

    public function __construct(DbUser $dbUser, $newPassword) {
        parent::__construct($dbUser->getEmail(), true);
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
        return 'CATCHME PASSWORD RECOVERED';
    }

    protected function getEmailTitle() {
        return $this->getSubject();
    }

    protected function getHeaderTitle() {
        return $this->dbUser->getName();
    }

    protected function getHeaderText() {
        return 'You required a password recovery';
    }

    protected function getContentHtml() {
        return strtr(file_get_contents(__DIR__ . '/../../../html/emails/email_password_recovered/content.html'), [
            '{new_password}' => $this->newPassword
        ]);
    }
}