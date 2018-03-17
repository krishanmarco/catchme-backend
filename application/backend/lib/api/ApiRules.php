<?php /** Created by Krishan Marco Madan [krishanmarco@outlook.com] on 15/09/2017 - Fithancer © */

namespace Api;
use Respect\Validation\Validator as v;
use R;
use EGender;
use Security\Validator;


abstract class ApiRules {


    const ruleIsset = 'validateIsset';

    public static function validateIsset($fieldValue) {

        if (!v::notOptional()->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleId = 'validateId';

    public static function validateId($fieldValue) {

        if (!(v::intVal()->min(0)->validate($fieldValue)))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleIds = 'validateIds';

    public static function validateIds($fieldValue) {

        if (!v::arrayVal()->each(v::intVal()->min(0))->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleBool = 'validateBool';

    public static function validateBool($fieldValue) {

        if (!v::boolVal()->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleInt = 'validateInt';

    public static function validateInt($fieldValue, $min = null, $max = null) {

        if (!v::intVal()->between($min, $max)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const rulePercentage = 'validatePercentage';

    public static function validatePercentage($fieldValue) {
        return self::validateInt($fieldValue, 0, 100);
    }




    const rulePhone = 'validatePhone';

    public static function validatePhone($fieldValue) {

        if (!v::phone()->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleEmail = 'validateEmail';

    public static function validateEmail($fieldValue) {

        if (!v::email()->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleEmails = 'validateEmails';

    public static function validateEmails($fieldValue) {

        if (!v::arrayVal()->each(v::email())->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleMediumString = 'validateMediumString';

    public static function validateMediumString($fieldValue, $min = 3, $max = 70) {

        if (!v::stringType()->validate($fieldValue))
            return false;

        if (!v::length($min, $max)->validate($fieldValue)) {
            return false;
        }

        return R::return_ok;
    }




    const ruleLongString = 'validateLongString';

    public static function validateLongString($fieldValue, $min = 3, $max = 1000) {

        if (!v::stringType()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length($min, $max)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const rulePassword = 'validatePasword';

    public static function validatePasword($fieldValue, $min = 6, $max = 30) {

        if (!v::length($min, $max)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleTimestamp = 'validateTimestamp';

    public static function validateTimestamp($fieldValue) {

        if (!v::intVal()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length(10, 10)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleUrl = 'validateUrl';

    public static function validateUrl($fieldValue) {

        if (!v::url()->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleUrls = 'validateUrls';

    public static function validateUrls($fieldValue) {

        if (!v::arrayVal()->each(v::url())->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleCountry = 'validateCountry';

    public static function validateCountry($fieldValue) {

        if (!v::countryCode()->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleCityTownState = 'validateCityTownState';

    public static function validateCityTownState($fieldValue, $min = 3, $max = 255) {

        if (!v::stringType()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length($min, $max)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const rulePostcode = 'validatePostcode';

    public static function validatePostcode($fieldValue, $min = 0, $max = 255) {

        if (!v::stringType()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length($min, $max)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleAddress = 'validateAddress';

    public static function validateAddress($fieldValue, $min = 3, $max = 255) {

        if (!v::stringType()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length($min, $max)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleLatLng = 'validateLatLng';

    public static function validateLatLng($fieldValue) {

        if (!v::arrayVal()->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleGooglePlaceId = 'validateGooglePlaceId';

    public static function validateGooglePlaceId($fieldValue) {

        if (!v::alnum()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length(3, 255)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleSocialUserId = 'validateSocialUserId';

    public static function validateSocialUserId($fieldValue) {

        if (!v::alnum()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length(3, 255)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleTokenPasswordReset = 'validateTokenPasswordReset';

    public static function validateTokenPasswordReset($fieldValue) {

        if (!v::alnum()->validate($fieldValue))
            return R::return_error_generic;

        if (!v::length(32, 32)->validate($fieldValue))
            return R::return_error_generic;

        return R::return_ok;
    }




    const ruleArrayOf = 'validateArrayOf';

    public static function validateArrayOf($fieldValue, $type) {

        if (!v::arrayVal()->validate($fieldValue))
            return R::return_error_generic;

        // All items must pass validation
        $anyFailed = false;

        for ($i = 0; $i < sizeof($fieldValue); $i++) {

            // Validate each single element
            $result = self::validateOf($fieldValue[$i], $type);

            if ($result != R::return_ok) {
                $fieldValue[$i] = $result;
                $anyFailed = true;
            }
        }

        if ($anyFailed)
            return $fieldValue;

        return R::return_ok;
    }




    const ruleOf = 'validateOf';

    public static function validateOf($fieldValue, $type) {

        $validator = new Validator($fieldValue, $type);

        if (!$validator->validateObject())
            return $validator->getResult();

        return R::return_ok;

    }




    const ruleEGender = 'validateEGender';

    public static function validateEGender($fieldValue,
                                            $first = EGender::female,
                                            $last = EGender::unknown) {
        return self::validateInt($fieldValue, $first, $last);
    }




    const ruleESystemTempVar = 'validateESystemTempVar';

    public static function validateESystemTempVar($fieldValue,
                                                   $first = ESystemTempVar::passwordRecovery,
                                                   $last = ESystemTempVar::passwordRecovery) {
        return self::validateInt($fieldValue, $first, $last);
    }




    const ruleETimeState = 'validateETimeState';

    public static function validateETimeState($fieldValue,
                                               $first = ETimeState::undefined,
                                               $last = ETimeState::valid) {
        return self::validateInt($fieldValue, $first, $last);
    }




    const ruleEUploadType = 'validateEUploadType';

    public static function validateEUploadType($fieldValue,
                                                $first = EUploadType::backupFile,
                                                $last = EUploadType::jsonDataFile) {
        return self::validateInt($fieldValue, $first, $last);
    }




}