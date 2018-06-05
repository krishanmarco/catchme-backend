<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace Models\Email;

abstract class EmailBase extends EmailTemplate {

    public function __construct($dbUser, $cannotFail) {
        parent::__construct($dbUser, $cannotFail, true);
    }

    /** @return null|string */
    protected abstract function getEmailTitle();

    /** @return null|string */
    protected abstract function getHeaderTitle();

    /** @return null|string */
    protected abstract function getHeaderText();

    /** @return null|string */
    protected abstract function getContentHtml();

    protected function getMessage() {
        $params = [];

        $emailTitle = $this->getEmailTitle();
        if (!is_null($emailTitle))
            $params['{email_title}'] = $emailTitle;

        $headerTitle = $this->getHeaderTitle();
        if (!is_null($headerTitle))
            $params['{header_title}'] = $headerTitle;

        $headerText = $this->getHeaderText();
        if (!is_null($headerText))
            $params['{header_text}'] = $headerText;

        $contentHtml = $this->getContentHtml();
        if (!is_null($contentHtml))
            $params['{content_html}'] = $contentHtml;

        $params = array_merge($params, $this->getBaseParams());
        return strtr(file_get_contents(__DIR__ . '/../../../html/emails/email_base/email_base.html'), $params);
    }


    private function getBaseParams() {
        return [
            '{email_logo}' => URL_LOGO_WHITE,
            '{email_contact_phone}' => CATCHME_CONTACT_PHONE,
            '{email_contact_email}' => CATCHME_CONTACT_EMAIL,
            '{email_link_facebook}' => URL_FACEBOOK,
            '{email_link_twitter}' => URL_TWITTER,
            '{email_link_google_plus}' => URL_GOOGLE_PLUS,
            '{email_link_terms}' => URL_TERMS,
            '{email_link_privacy}' => URL_PRIVACY,
            '{email_link_unsubscribe}' => URL_UNSUBSCRIBE
        ];
    }

}