<?php /** Created by Krishan Marco Madan on 01/03/2016. */


function prettyVarDump($o) { highlight_string("<?php.php\n\$data =\n" . var_export($o, true) . ";\n?>"); }
function prettyVarDumpReturn($o) { return highlight_string("<?php.php\n\$data =\n" . var_export($o, true) . ";\n?>", true); }



function getRandomString($inMin, $inMax) { return str_replace('.', '', substr(uniqid(mt_rand(), true), -rand($inMin, $inMax))); }
function setOnlyIfNotSet($value, $setTo) { return isset($value) ? $value : $setTo; }


function ifKeyExists($key, $array, $default = null) {
    if (array_key_exists($key, $array))
        return $array[$key];
    return $default;
}


function is_phone_number($value) {
    $value = to_phone_number($value);
    return is_numeric($value) && strlen($value) > 3;
}

function to_phone_number($value) {
    $value = str_replace(' ', '', $value);
    $value = str_replace('+', '', $value);
    return $value;
}

function objectAssign($obj1, $obj2) {
    return (object) array_merge((array) $obj1, (array) $obj2);
}

function emptyObject($object) {
    foreach ($object as $key => $value) {
        $object->$key = null;
    }
    return $object;
}

function clearObject($object) {
    foreach ($object as $key => $value) {
        unset($object->$key);
    }
    return $object;
}