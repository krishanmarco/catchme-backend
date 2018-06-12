<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 26/08/2017 */

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

    /** @var array */
    private $inputData;

    /** @var string */
    private $apiDefInputClass;

    /** @var object */
    private $validationParams;

    /** @var array */
    private $errors = [];

    /** @var boolean */
    private $hasError = false;

    public function getResult() {
        if ($this->hasError)
            return $this->errors;

        return $this->validationParams;
    }

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

    public function validateObject($removeNulls = true) {

        // Reset the whole object to its initial state
        $this->resetValidationErrors();

        // For each key (field) in $expectedApiDefinition,
        // apply that key to the corresponding item in $data
        foreach ($this->validationParams as $key => $validationArray) {
            $fieldValue = null;
            if (array_key_exists($key, $this->inputData))
                $fieldValue = $this->inputData[$key];

            // Validate the field
            $fieldIsValid = $this->validateField($fieldValue, $validationArray);

            if ($fieldIsValid != self::NO_ERROR) {
                $this->errors[$key] = $fieldIsValid;
                $this->hasError = true;
                continue;
            }

            // The result is valid, get the value
            if ($removeNulls && $fieldValue === null) {
                unset($this->validationParams->{$key});
                continue;
            }

            // Overwrite the $validationArray with the inputted data
            $this->validationParams->{$key} = deepArrayToObject($fieldValue);
        }

        // Check for any error, if there was an error return
        return !$this->hasError;
    }

    private function validateField($fieldValue, $validators) {

        foreach ($validators as $method => $args) {

            // Only validate a field if it is set because
            // isset is a validator in itself
            $shouldBeValidated = isset($fieldValue);
            if (is_string($fieldValue))
                $shouldBeValidated &= strlen($fieldValue);
            else if ($fieldValue instanceof \Countable)
                $shouldBeValidated &= sizeof($fieldValue);

            if (!$shouldBeValidated && !in_array(ApiRules::ruleIsset, array_keys($validators)))
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




