<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Custom theme functions
	
	Note: we recommend you prefix all your functions to avoid any naming 
	collisions or wrap your functions with if function_exists braces.
*/

function numeral($number) {
	$test = abs($number) % 10;
	$ext = ((abs($number) % 100 < 21 and abs($number) % 100 > 4) ? '.' : (($test < 4) ? ($test < 3) ? ($test < 2) ? ($test < 1) ? '.' : '.' : '.' : '.' : '.'));
	return $number . $ext; 
}

function count_words($str) {
	return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
}

function pluralise($amount, $str, $alt = '') {
    return intval($amount) === 1 ? $str : $str . ($alt !== '' ? $alt : 's');
}

function relative_time($date) {
    $elapsed = time() - $date;
    
    if($elapsed <= 1) {
        return 'Gerade eben';
    }
    
    $times = array(
        31104000 => 'Jahr',
        2592000 => 'Monat',
        604800 => 'Woche',
        86400 => 'Tag',
        3600 => 'Stunde',
        60 => 'Minute',
        1 => 'Sekunde'
    );
    
    foreach($times as $seconds => $title) {
        $rounded = $elapsed / $seconds;
        
        if($rounded > 1) {
            $rounded = round($rounded);
            return $rounded . ' ' . pluralise($rounded, $title) . ' alt';
        }
    }
}
