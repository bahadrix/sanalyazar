<?php
    function migirDuzelt($migir) {
        /**
         * cümle başlarını büyük yapar ve geriye kalan harfleri küçültür.
         */
        $migir = mb_convert_case($migir,MB_CASE_LOWER,'UTF-8');
        $ilkkarakter = substr_unicode($migir,0,1);
        if (turkcekarakter($ilkkarakter)) {
            $migir = trtoupper($ilkkarakter).substr_unicode($migir,1);
        }
        else
            $migir = strtoupper(substr($migir,0,1)).substr($migir,1);
        $migir = preg_replace('/(?:\<br \/\>\s*)/', "<br />",$migir);
        $p = preg_match_all('/(?:\<br \/\>+)([a-zıöçüşğ]+)/u',$migir,$matches);
        if ($p) {
            $kelime = $matches[0];
            for ($i=0;$i<$p;$i++) {
                $harf = substr_unicode($matches[1][$i],0,1);
                if (turkcekarakter($harf))
                    $ih = trtoupper($harf);
                else 
                    $ih = strtoupper($harf);
                $migir = str_replace($kelime[$i],'<br />'.$ih.substr_unicode($matches[1][$i],1),$migir);
            }
        }
        return $migir;
    }
    
    function turkcekarakter($input) {
        $karakterler = array('ı', 'i', 'ğ', 'ü', 'ş', 'ö', 'ç');
        return in_array($input, $karakterler);
    }
    
    function substr_unicode($str, $s, $l = null) {
        return join("", array_slice(
            preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
    }
    
    function trtoupper($veri) {
        return strtoupper (str_replace(array ('ı', 'i', 'ğ', 'ü', 'ş', 'ö', 'ç' ),array ('I', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç' ),$veri));
    }
 
?>
