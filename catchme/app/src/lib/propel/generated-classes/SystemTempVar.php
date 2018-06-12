<?php

use Base\SystemTempVar as BaseSystemTempVar;
use Models\RecoveryToken;

/**
 * Skeleton subclass for representing a row from the 'system_temp_var' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SystemTempVar extends BaseSystemTempVar {

    /** @return RecoveryToken */
    public static function saveRecoTkn(RecoveryToken $recoveryToken) {
        $tempVar = new SystemTempVar();
        $tempVar->setType(ESystemTempVar::PASSWORD_RECO);
        $tempVar->setExpiryTs(time() + USER_PASSWORD_RECO_TTL);
        $tempVar->setData($recoveryToken);
        $tempVar->save();
        $recoveryToken->systemTempVarId = $tempVar->getId();
        return $recoveryToken;
    }

}
