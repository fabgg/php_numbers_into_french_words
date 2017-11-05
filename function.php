<?php
/**
 * User: fabrice
 * Date: 05/11/2017
 * Time: 18:14
 */

function intergerIntoFrenchWords($int)
{
    if(intval($int) == 0) return 'zéro';
    elseif (intval($int)> 999999999) return false;
    $units = array(
        1=>'un',
        2=>'deux',
        3=>'trois',
        4=>'quatre',
        5=>'cinq',
        6=>'six',
        7=>'sept',
        8=>'huit',
        9=>'neuf'
    );
    $exceptions = array(
        11=>"onze",
        12=>'douze',
        13=>'treize',
        14=>'quatorze',
        15=>'quinze',
        16=>'seize'
    );
    $thousands = array(
        1=>"mille",
        2=>'million',
        3=>'milliard'
    );
    $teens = array(
        1=> "dix",
        2 => "vingt",
        3=> "trente",
        4 => "quarante",
        5 => "cinquante",
        6 => "soixante",
        7 => "soixante",
        8 => "quatre-vingt",
        9 => "quatre-vingt",
    );

    $return ='';

    $number = number_format($int);
    $splitNumber = explode(',', $number);
    $nbThousandPacket = count($splitNumber);

    for($i=0; $i<$nbThousandPacket; $i++)
    {
        $value = $splitNumber[$i];
        $unit = intval(substr($value, -1));
        $dix = intval(substr($value, -2));
        $dixaine = intval(floor($dix/10));
        $cent = intval(floor($value/100));
        $forException = ($dixaine == 7 ||  $dixaine == 9) ? $dix - (($dixaine -1)*10) : null;

        if($cent>0){
            $return .=  ($cent > 1) ? $units[$cent].' cents ':' cent ';
        }
        if($dix > 9){
            if(array_key_exists($dix, $exceptions)) $return .= $exceptions[$dix];
            else {
                $return .= $teens[$dixaine];
                if ($dixaine == 7 ||  $dixaine == 9) {
                    if($forException && array_key_exists($forException, $exceptions)) {
                        if($forException == 11 && $dixaine == 7) $return .= ' et ';
                        else $return .= '-';
                        $return .= $exceptions[$forException];
                    }
                    else $return .= '-dix';
                }
            }
        }
        if($unit == 1 && strlen($return) > 0 && $dixaine < 7 && $dixaine > 1) $return .= ' et '.$units[$unit];
        elseif ($unit > 0 && $dixaine == 8 ) $return .= '-'.$units[$unit];
        //elseif ($unit > 0 && ($dixaine == 7 || $dixaine == 9) && !(($forException && array_key_exists($forException, $exceptions)) ||  array_key_exists($dix, $exceptions))) $return .= '-'.$units[$unit];
        elseif ($unit > 0 && $dixaine != 0 && !(($forException && array_key_exists($forException, $exceptions)) ||  array_key_exists($dix, $exceptions))) $return .= '-'.$units[$unit];
        elseif ($unit > 0 && $dixaine == 0) $return .= ' '.$units[$unit];

        $thousandKey = $nbThousandPacket - ($i+1);
        if(array_key_exists($thousandKey, $thousands))  {
            $return .= ' '.$thousands[$thousandKey];
            if($thousandKey > 1 && $value >1)$return .= 's';
            $return .= ' ';
        }
    }
    return trim($return);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
for($i=1 ;$i<100000; $i++){
    echo convertitNombreEnLettres($i)."<br>";
}
$time_end = microtime_float();
echo 'Temps de traitement (en s) :'.$time_end - $time_start;


?>