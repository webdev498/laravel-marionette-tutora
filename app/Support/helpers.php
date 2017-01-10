<?php

if ( ! function_exists('environment')) {
    function environment() {
        return call_user_func_array([app(), 'environment'], func_get_args());
    }
}

if ( ! function_exists('str_uuid')) {
    /**
     * Generate a version 4 (random) UUID
     * @see https://github.com/ramsey/uuid
     * @param  Boolean $uuid4 Return a full length uuid4 string
     * @return string
     */
    function str_uuid($uuid4 = false) {
        do {
            $hash = Ramsey\Uuid\Uuid::uuid4()->toString();

            if ($uuid4 === false) {
                $number = preg_replace('/[^0-9]+/', '', $hash);
                // https://github.com/ivanakimov/hashids.php#big-numbers
                $short  = substr($number, 0, 9);
                $hash   = Hashids::encode($short);
            }
        } while ( ! $hash);

        return $hash;
    }
}

if ( ! function_exists('str_name')) {
    function str_name($string) {
        $splitters = [' ', '-', "O'", "L'", "D'", 'St.', 'Mc', 'Mac'];
        $lowercase = ['the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'of', 'and', "l'", "d'"];
        $uppercase = ['III', 'IV', 'VI', 'VII', 'VIII', 'IX'];

        $string = strtolower($string);
        foreach ($splitters as $delimiter) {
            $words    = explode($delimiter, $string);
            $newwords = array(); 

            foreach ($words as $word) { 
                if (in_array(strtoupper($word), $uppercase)) {
                    $word = strtoupper($word);
                } elseif ( ! in_array($word, $lowercase)) {
                    $word = ucfirst($word); 
                }

                $newwords[] = $word;
            }

            if (in_array(strtolower($delimiter), $lowercase)) {
                $delimiter = strtolower($delimiter);
            }

            $string = join($delimiter, $newwords);
        }

        return $string;
    }
}

if ( ! function_exists('str_unslug')) {

    function str_unslug($string)
    {
        return str_replace('-', ' ', ucwords($string, '-'));
    }
}

// Url helpers for search

if ( ! function_exists('location_to_url')) {

    function location_to_url($string)
    {
        return strtolower(str_slug((string) $string, '+'));
    }
}

if ( ! function_exists('location_to_str')) {

    function location_to_str($string)
    {
        return ucwords(str_unslug($string));
    }
}


if ( ! function_exists('array_contains')) {
    function array_contains($needle, array $haystack) {
        return array_search($needle, $haystack) !== false;
    }
}

if ( ! function_exists('array_equal')) {
    function array_equal(Array $a, Array $b) {
        return ! array_diff($a, $b) && ! array_diff($b, $a);
    }
}

if ( ! function_exists('array_merge_unique')) {
    function array_merge_unique() {
        $array = call_user_func_array('array_merge', func_get_args());
        return array_unique($array);
    }
}

if ( ! function_exists('array_extend')) {
    function array_extend() {
        $arrays  = func_get_args();

        if (count($arrays) < 2) {
            throw new InvalidArgumentException('array_extend expects at least two arguments');
        }

        $arrays = array_reverse($arrays);
        $array  = array_shift($arrays);

        foreach ($arrays as $a) {
            $keys    = array_keys($array);
            $missing = array_except($a, $keys);
            $array   = array_merge($array, $missing);
        }

        return $array;
    }
}

if ( ! function_exists('array_flatten')) {
    function array_flatten(Array $array) {
        $rai = new RecursiveArrayIterator($array);
        return (array) new RecursiveIteratorIterator($rai);
    }
}

if ( ! function_exists('array_forget_value')) {
    function array_forget_value(Array &$array, $value) {
        if (($key = array_search($value, $array)) !== false) {
            unset ($array[$key]);
        }
    }
}

if ( ! function_exists('array_to_object')) {
    function array_to_object(Array $array) {
        $obj = new App\Support\ArrayObject();

        foreach($array as $k => $v) {
            if(strlen($k)) {
                if(is_array($v)) {
                    $f = __FUNCTION__;
                    $v = $f($v);
                }

                $obj->{$k} = $v;
            }
        }

        return $obj;
    }
}

if ( ! function_exists('guard_against_array_of_invalid_arguments')) {
    function guard_against_array_of_invalid_arguments(
        $array = null,
        $classname
    ) {
        if ($array !== null) {
            if ( ! is_array($array) && ! $array instanceof ArrayAccess) {
                throw new InvalidArgumentException(sprintf(
                    'Argument 1 passed to guard_against_array_of_invalid_arguments
                    must be an array, or, an instance of ArrayAccess, %s given.',
                    gettype($array)
                ));
            }

            foreach ($array as $item) {
                if ( ! ($item instanceof $classname)) {
                    $backtrace = debug_backtrace();
                    $message   = '';

                    if (isset($backtrace[1])) {
                        $backtrace = $backtrace[1];
                        $message   = sprintf(
                            'Argument ? passed to %s::%s() must be an instance of '
                            .'%s, %s given, called in %s on line %s',
                            array_get($backtrace, 'class'),
                            array_get($backtrace, 'function'),
                            $classname,
                            gettype($item),
                            array_get($backtrace, 'file'),
                            array_get($backtrace, 'line')
                        );
                    }

                    throw new InvalidArgumentException($message);
                }
            }
        }
    }
}

if ( ! function_exists('pe')) {
    function pe($string) {
        $string = htmlspecialchars_decode($string);
        $string = htmlspecialchars($string, ENT_QUOTES);
        $string = '<p>'.$string.'</p>';
        $string = str_replace("\n\n", '</p><p>', $string);
        $string = str_replace("\n", '<br>', $string);

        return $string;
    }
}

if ( ! function_exists('strtodate')) {
    function strtodate($string) {
        /*
         * Dates in the m/d/y or d-m-y formats are disambiguated by looking at 
         * the separator between the various components: if the separator is 
         * a slash (/), then the American m/d/y is assumed; whereas if the 
         * separator is a dash (-) or a dot (.), then the European d-m-y 
         * format is assumed.
         *
         * ~ Notes on http://php.net/manual/en/function.strtotime.php
         */
        $string = str_replace('/', '-', $string);
        $time   = strtotime($string);
        return Carbon\Carbon::createFromTimestamp($time);
    }
}

if ( ! function_exists('strtoseconds')) {
    function strtoseconds($string) {
        sscanf($string, '%d:%d', $hours, $minutes);

        $minutes = isset($minutes)
            ? bcadd($minutes, bcmul($hours, 60, 2))
            : (string) $hours;

        return bcmul($minutes, 60, 0);
    }
}


if ( ! function_exists('relroute')) {
    /**
     * Generate a relative route
     */
    function relroute($name, $parameters = [], $route = null) {
        return route($name, $parameters, false, $route);
    }
}

if ( ! function_exists('str_normalize')) {
    /**
     * Normalize a string, returning only lowercase alphanumeric characters
     */
    function str_normalize($str) {
        return preg_replace('/[^a-z0-9]+/', '', strtolower($str));
    }
}

if ( ! function_exists('loginfo')) {
    function loginfo($message) {
        return Log::info($message);
    }
}

if ( ! function_exists('logerror')) {
    function logerror($message) {
        return Log::error($message);
    }
}

if ( ! function_exists('templ')) {
    function templ($string, $data) {
        foreach ($data as $key => $value) {
            $string = str_replace(":$key", $value, $string);
        }

        return preg_replace_callback('/:(\w+)/', function($matches) use ($data) {
            return isset($data[$matches[1]])
                ? $data[$matches[1]]
                : $matches[1];
        }, $string);
    }
}

if ( ! function_exists('before_bugsnag_notify')) {
    function before_bugsnag_notify($error) {
        if (auth()->check()) {
            $user = auth()->user();
            $error->setMetaData(array(
                "user" => array(
                    "name" => $user->first_name.' '.$user->last_name,
                    "email" => $user->email
                )
            ));
        }
    }
}

if ( ! function_exists('day_from_date')) {
    function day_from_date($date, $day, $part = null) {

        $comparison = new \Carbon\Carbon("next $day");
    
        if ($date->dayOfWeek !== $comparison->dayOfWeek) {
            $date->modify("next $day");
        }

        if ($part == 'am') {
            $date->hour = rand(9, 11);
        }
        if ($part == 'pm') {
            $date->hour = rand(12, 19);
        }

        if ($part == null) {
            $date->hour = rand(9, 19);
        }

        $date->minute = rand(0, 59);

        return $date;
    }
}
if ( ! function_exists('socialise_time')) {
    function socialise_time($date, $part = null) {

        if ($part == 'am') {
            $date->hour = rand(9, 11);
        }
        if ($part == 'pm') {
            $date->hour = rand(12, 19);
        }

        if ($part == null) {
            $date->hour = rand(9, 19);
        }

        $date->minute = rand(0, 59);

        return $date;
    }
}
