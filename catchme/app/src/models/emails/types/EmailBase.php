<?php /** Created by Krishan Marco Madan on 05-Jun-18. */

namespace Models\Email;

use I18n\I18n;

abstract class EmailBase extends EmailTemplate {

    public function __construct($to, $langId, $cannotFail) {
        parent::__construct($to, $langId, $cannotFail, true);
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

        return I18n::strReplace($this->getLangId(), strtr(
            $this->getEmailStr('email_base/email_base'),
            array_merge($params, $this->getBaseParams())
        ));
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