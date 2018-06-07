<?php /** Created by Krishan Marco Madan on 12-May-18. */

namespace Models\Email;
use \Slim\Exception\Api400;
use R;

abstract class EmailTemplate {

    public function __construct($to, $langId, $cannotFail = false, $isHtml = true) {
        $this->to = $to;
        $this->langId = $langId;
        $this->isHtml = $isHtml;
        $this->cannotFail = $cannotFail;
    }

    /** @var string */
    private $to;

    /** @var string */
    private $langId;

    /** @var boolean */
    private $isHtml;

    /** @var boolean */
    private $cannotFail;

    /** @return array Array<String => String> */
    protected abstract function getExtraHeaders();

    /** @return string */
    protected abstract function getSubject();

    /** @return string */
    protected abstract function getMessage();

    /** @return string */
    protected function getEmailStr($subPath) {
        return file_get_contents(__DIR__ . "/../emails/types/$subPath.html");
    }

    /** @return string */
    protected function getLocale() {
        return $this->langId;
    }

    public function send() {
        $headers = [];

        if ($this->isHtml) {
            $headers['MIME-Version'] = '1.0';
            $headers['Content-Type'] = 'text/html; charset=iso-8859-1';
        }

        $this->sendEmail($headers);
    }

    private function sendEmail($extraHeaders = []) {
        $success = mail(
            $this->to,
            $this->getSubject(),
            $this->getMessage(),
            $this->buildHeaders($extraHeaders)
        );

        if (!$success && $this->cannotFail)
            throw new Api400(R::return_error_failed_email_send);
    }

    private function buildHeaders($extraHeaders = []) {
        $headers = array_merge($extraHeaders, $this->getExtraHeaders(), [
            'From' => CATCHME_CONTACT_EMAIL,
            'Reply-To' => CATCHME_CONTACT_EMAIL,
            'X-Mailer' => 'PHP/' . phpversion()
        ]);

        $headersStr = '';
        foreach ($headers as $headerKey => $headerVal)
            $headersStr .= "$headerKey: $headerVal\r\n";

        return $headersStr;
    }


}