<?php
/**
 * Cümle başlarını büyük yapar, geri kalan harfleri küçültür.
 * @param type $migir
 * @return type
 */
function migirDuzelt($migir) {
    $migir = mb_convert_case($migir, MB_CASE_LOWER, 'UTF-8');
    $ilkkarakter = substr_unicode($migir, 0, 1);
    if (turkcekarakter($ilkkarakter)) {
        $migir = trtoupper($ilkkarakter) . substr_unicode($migir, 1);
    }
    else
        $migir = strtoupper(substr($migir, 0, 1)) . substr($migir, 1);
    $migir = preg_replace('/(?:\<br \/\>\s*)/', "<br />", $migir);
    $p = preg_match_all('/(?:\<br \/\>+)([a-zıöçüşğ]+)/u', $migir, $matches);
    if ($p) {
        $kelime = $matches[0];
        for ($i = 0; $i < $p; $i++) {
            $harf = substr_unicode($matches[1][$i], 0, 1);
            if (turkcekarakter($harf))
                $ih = trtoupper($harf);
            else
                $ih = strtoupper($harf);
            $migir = str_replace($kelime[$i], '<br />' . $ih . substr_unicode($matches[1][$i], 1), $migir);
        }
    }
    return $migir;
}

function turkcekarakter($input) {
    $karakterler = array('ı', 'i', 'ğ', 'ü', 'ş', 'ö', 'ç');
    return in_array($input, $karakterler);
}

function substr_unicode($str, $s, $l = null) {
    return join("", array_slice(preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
}

function trtoupper($veri) {
    return strtoupper(str_replace(array('ı', 'i', 'ğ', 'ü', 'ş', 'ö', 'ç'), array('I', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'), $veri));
}

function unistrlen($inp) {
    /**
     * ASCII hariç karakter içeren input'ların uzunluğunu döndürür.
     */
    $chars = preg_split('//u', $inp, -1, PREG_SPLIT_NO_EMPTY);
    return count($chars);
}
/**
 * Generates number Salt for registration
 * @param type $input
 * @return type
 */
function generateSalt($input) {
    $l = unistrlen($input);
    $i = 0;
    $ttl = 0;
    while ($i < $l) {
        $ch = substr_unicode($input, $i, 1);
        $chnumber = utf8_to_unicode($ch);
        $ttl += pow($chnumber[0], 2) / 2;
        $i++;
    }
    $ttl = $ttl % 10000;
    return $ttl;
}
/**
 * Session ID sniff Prevention.
 * @return string
 */
function generateRandomString() {
    $length = 15;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $str = "";
    for ($i = 0; $i<$length;$i++) {
        $str .= $characters[mt_rand(0,strlen($characters)-1)];
    }
    return $str;
}

/*
 * function from http://randomchaos.com/documents/?source=php_and_unicode
 */

function utf8_to_unicode($str) {

    $unicode = array();
    $values = array();
    $lookingFor = 1;

    for ($i = 0; $i < strlen($str); $i++) {

        $thisValue = ord($str[$i]);

        if ($thisValue < 128)
            $unicode[] = $thisValue;
        else {

            if (count($values) == 0)
                $lookingFor = ( $thisValue < 224 ) ? 2 : 3;

            $values[] = $thisValue;

            if (count($values) == $lookingFor) {

                $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
                        ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

                $unicode[] = $number;
                $values = array();
                $lookingFor = 1;
            }
        }
    }

    return $unicode;
}

?>
