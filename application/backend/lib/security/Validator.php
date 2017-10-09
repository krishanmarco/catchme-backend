<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 26/08/2017 - Fithancer © */

namespace Security;
use Api\ApiRules;
use R;

class Validator {
    
    const NO_ERROR = R::return_ok;
        

    public function __construct(array $inputData, $apiDefInputClass) {
        $this->inputData = $inputData;
        $this->apiDefInputClass = $apiDefInputClass;
        $this->validationParams = new $this->apiDefInputClass;
    }


    /** @var array $inputData */
    private $inputData;

    /** @var string $apiDefInputClass */
    private $apiDefInputClass;




    /** @var object $validationParams */
    private $validationParams;

    /** @var array $errors */
    private $errors = [];

    public function getResult() {

        if ($this->hasError)
            return $this->errors;

        return $this->validationParams;
    }




    /** @var boolean $hasError */
    private $hasError = false;

    public function getHasError() {
        return $this->hasError;
    }




    private function resetValidationErrors() {

        // Reset the errors array and the has error flag
        $this->errors = [];
        $this->hasError = false;

        // Set all fields of the error accumulator to no-error
        // only the fields with errors will get overridden
        foreach ($this->inputData as $fieldKey => $fieldValue)
            $this->errors[$fieldKey] = self::NO_ERROR;

    }



    public function validateObject() {

        // Reset the whole object to its inital state
        $this->resetValidationErrors();

        // For each key (field) in $expectedApiDefinition,
        // apply that key to the corrisponding item in $data
        foreach ($this->validationParams as $key => $validationArray) {

            // Validate the field
            $fieldIsValid = $this->validateField($this->inputData[$key], $validationArray);

            if ($fieldIsValid != self::NO_ERROR) {
                $this->errors[$key] = $fieldIsValid;
                $this->hasError = true;
                continue;
            }

            // The result is valid, overwrite the
            // $validationArray with the inputted data
            $this->validationParams->{$key} = $this->inputData[$key];
        }

        // Check for any error, if there was an error return
        return !$this->hasError;
    }



    public function validateField($fieldValue, $validators) {

        foreach ($validators as $method => $args) {

            // Only validate a field if it is set because
            // isset is a validator in itself
            if (!isset($fieldValue) && !in_array(ApiRules::ruleIsset, array_keys($validators)))
                continue;

            // Execute the sub function
            $result = ApiRules::$method($fieldValue, ...$args);

            // Check for sub validator failure
            if ($result != self::NO_ERROR)
                return $result;

        }

        return self::NO_ERROR;
    }
}




