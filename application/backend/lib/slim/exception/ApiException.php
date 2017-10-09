<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 17/09/2017 - Fithancer © */

namespace Slim\Exception;
use Exception;
use R;

class ApiException extends Exception {

    public function __construct($code = R::return_error_generic, $previous = null) {
        parent::__construct(basename($this->getFile(), '.php') . " {$this->getLine()}", $code, $previous);
    }

}