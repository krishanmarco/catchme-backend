<?php /** Created by Krishan Marco Madan on 01/03/2016. */


function prettyVarDump($o) { highlight_string("<?php.php\n\$data =\n" . var_export($o, true) . ";\n?>"); }
function prettyVarDumpReturn($o) { return highlight_string("<?php.php\n\$data =\n" . var_export($o, true) . ";\n?>", true); }



function getRandomString($inMin, $inMax) { return str_replace('.', '', substr(uniqid(mt_rand(), true), -rand($inMin, $inMax))); }
function setOnlyIfNotSet($value, $setTo) { return isset($value) ? $value : $setTo; }
function uniqueName() { return bin2hex(random_bytes(12)); }
function filehash($filepath) { return hash_file('crc32b', $filepath); }

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

function array_set(&$array, $key, $value, $delimiter = '.') {
    if (is_null($key))
        return $array = $value;

    $keys = explode($delimiter, $key);
    while (count($keys) > 1) {
        $key = array_shift($keys);
        if (!isset($array[$key]) || !is_array($array[$key]))
            $array[$key] = array();

        $array =& $array[$key];
    }

    $array[array_shift($keys)] = $value;
    return $array;
}

function array_unflatten($collection, $delimiter = '.') {
    $collection = (array) $collection;

    $output = [];
    foreach ($collection as $key => $value) {
        array_set($output, $key, $value, $delimiter);
        if (is_array($value) && !strpos($key, $delimiter)) {
            $nested = array_unflatten($value, $delimiter);
            $output[$key] = $nested;
        }
    }

    return $output;
}

function deepArrayToObject($array) {
    return json_decode(json_encode($array), false);
}

// https://stackoverflow.com/questions/1653771/how-do-i-remove-a-directory-that-is-not-empty
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

function classFromArray(array $data) {
    return !is_null($data)
        ? json_decode(json_encode($data))
        : null;
}

function removeFromArrayWhere(array $array, Closure $deleteItems) {
    $indexesToRemove = [];

    for ($i = 0; $i < sizeof($array); $i++)
        if ($deleteItems($array[$i]))
            $indexesToRemove[] = $i;

    foreach ($indexesToRemove as $idx)
        unset($array[$idx]);

    return array_values($array);
}

function degToKm($deg) {
    return $deg * 111.32;
}

function kmToDeg($km) {
    return $km / 111.32;
}

function rollingAvgByOne($oldAvg, $newSampleAvg, $currentCount) {
    return $oldAvg * ($currentCount - 1) / $currentCount + ($newSampleAvg / $currentCount);
}

function jsonDie($d) {
    die(json_encode($d));
}