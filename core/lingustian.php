<?php

class Lingustian {

    public static $unluler = array('a', 'ı', 'o', 'u', 'e', 'i', 'ö', 'ü', 'î', 'â');
    public static $kalin_unluler = array('a', 'ı', 'o', 'u');
    public static $ince_unluler = array('e', 'i', 'ö', 'ü');
    public static $sert_unsuzler = array('p', 'ç', 't', 'k');
    public static $yumusayan_unsuzler = array('p' => 'b', 'ç' => 'c', 't' => 'd', 'k' => 'ğ');
    public static $daralan_unluler = array('e' => 'i', 'a' => 'ı', 'o' => 'u', 'ö' => 'ü');

    public static function sub_uni($str, $s, $l = null) {
        return join("", array_slice(preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
    }

    /**
     * DENEME AMAÇLI FONKSİYON
     * bütün ler lar ekleri için çalışmalı fakat çok fazla denemedim.
     * şu an sadece -lerin ve -ler eki için kullanılıyor. (bkz: wordengine.php)
     * hataları var.
     * @param string $kelime
     * @param string $ek
     * @param boolean $si
     * @return string
     */
    public static function lerlar($kelime, $ek, $si = false) {
        
        $digerleri = array('ler' => 'lar', 'leri' => 'ları', 'lerin' => 'ların', 'lerini' => 'larını', 'lerine' => 'larına', 'lerinin' => 'larının');
        $ekler = array('ler' => 'i', 'leri' => '', 'lerin' => 'in', 'lerini' => '', 'lerine'  => '', 'lerinin' => '');
        $eklerdaral = array('i' => 'ı', 'in' => 'ın');

        $yenikelime = $kelime;
        mb_internal_encoding('UTF-8');
        if (strpos($yenikelime, 'ler') !== false) {
            $yenikelime = self::sub_uni($kelime, 0, mb_strpos($yenikelime, "ler")) . self::sub_uni($kelime, mb_strpos($kelime, "ler") + 3);
        } else if (strpos($yenikelime, 'lar') !== false) {
            $yenikelime = self::sub_uni($kelime, 0, mb_strpos($yenikelime, "lar")) . self::sub_uni($kelime, mb_strpos($kelime, "lar") + 3);
        }

        $son_harf = self::sub_uni($yenikelime, -1);

        if (in_array($son_harf, self::$unluler)) {
            if (!preg_match('/^[a-zığçüöşâî]+(?:\s[a-zığçüöşâî]+)*(?:\s[a-zığçüöşâî]+[iıuü])$/i', $kelime)) //isim tamlaması değilse dön
                if ($si)
                    return $yenikelime . $ek;
                else
                    return $yenikelime . $digerleri[$ek];
            else {
                $sondan_onceki_harf = self::sub_uni($kelime, -2, 1);
                
                if ($sondan_onceki_harf === "s") {
                    $yenikelime = self::sub_uni($yenikelime, 0, -2);
                } else {
                    $yenikelime = self::sub_uni($yenikelime, 0, -1);
                }
                $yeni_sondan_onceki_harf = self::sub_uni($yenikelime, -2, 1);
                $yeni_son_harf = self::sub_uni($yenikelime, -1);
                
                if ($yeni_son_harf === $yeni_sondan_onceki_harf) //"isim hakkı" gibi kelimeler için
                    $yenikelime = self::sub_uni($yenikelime, 0, -1);
                
                $sertlesen_unsuzler = array_flip(self::$yumusayan_unsuzler);
                if (in_array($yeni_son_harf, self::$yumusayan_unsuzler)) {
                    $yenikelime = self::sub_uni($yenikelime, 0, -1) . $sertlesen_unsuzler[$yeni_son_harf];
                }
                if ($si)
                    $yenikelime .= $ek . $ekler[$ek];
                else {
                    $yenikelime .= $digerleri[$ek];
                    if (in_array($ekler[$ek],array_flip($eklerdaral)))
                            $yenikelime .=  $eklerdaral[$ekler[$ek]];
                }
                return $yenikelime;
            }
        }
        if ($si)
            return $yenikelime . $ek;
        else
            return $yenikelime . $digerleri[$ek];
    }

    public static function yumusama($kelime) {
        $son_harf = self::sub_uni($kelime, -1);
        if (in_array($son_harf, self::$unluler)) { //ünlüyle bitiyorsa "yapmaa" falan oluyor.
            $sondan_onceki_harf = self::sub_uni($kelime, -2, 1);
            $isim_tamlamasimi = preg_match('/^[a-zığçüöşâî]+(?:\s[a-zığçüöşâî]+)+$/i', $kelime);
            if ($isim_tamlamasimi) {
                return $kelime . "n";
            } else if ($sondan_onceki_harf == "ğ" || $sondan_onceki_harf == "y" || $sondan_onceki_harf == "s" || $sondan_onceki_harf == 'ş') {
                return $kelime . "n";
            }
            else
                return $kelime . "y"; //ğ?
        }
        else
            return in_array($son_harf, self::$sert_unsuzler) ? self::sub_uni($kelime, 0, -1) . self::$yumusayan_unsuzler[$son_harf] : $kelime;
    }

    public static function mastarKaldir($mastarliFiil) {

        return preg_replace('/m[ae]k$/', '', $mastarliFiil);
    }

    public static function daraltma($kelime) {

        $son_unlu = self::sonUnluNe($kelime);
        $son_unlu_genis = in_array(
                $son_unlu, array_keys(self::$daralan_unluler)
        );
        $son_unlu_dar = in_array(
                $son_unlu, array_values(self::$daralan_unluler)
        );


        $ek = "";
        if ($son_unlu_genis) // genisse daralt
            $ek = self::$daralan_unluler[$son_unlu];
        else if ($son_unlu_dar)
            $ek = $son_unlu;

        return $ek;
    }

    public static function genisletme($kelime) {
        $son_unlu = self::sonUnluNe($kelime);
        $son_unlu_genis = in_array(
                $son_unlu, array_keys(self::$daralan_unluler)
        );
        $son_unlu_dar = in_array(
                $son_unlu, array_values(self::$daralan_unluler)
        );

        $genisleyenUnluler = array_flip(self::$daralan_unluler);

        $ek = "";
        if ($son_unlu_genis) {
            if ($son_unlu == 'o' || $son_unlu == 'â') {
                $ek = 'a';
            }
            else if ($son_unlu == "ö" || $son_unlu == 'î') {
                $ek = 'e';
            }
            else {
                $ek = $son_unlu;
            }
        } else if ($son_unlu_dar) { // darsa genislet
            if ($son_unlu == 'u')
                $ek = 'a';
            elseif ($son_unlu == "ü")
                $ek = 'e';
            else
                $ek = $genisleyenUnluler[$son_unlu];
        }


        return $ek;
    }

    public static function sonUnluNe($kelime) {

        $index = -1;
        $son_unlu = "";
        foreach (self::$unluler as $unlu) {

            $pos = strrpos($kelime, $unlu);
            if ($pos > $index) {
                $index = $pos;
                $son_unlu = $unlu;
            }
        }

        return $son_unlu;
    }

    public static function sonUnluInce($kelime) {

        $son_unlu = self::sonUnluNe($kelime);

        return in_array($son_unlu, self::$ince_unluler);
    }

    public static function migirCoz($migirSablon) {

        $matches = array();
        preg_match_all('/\{[\}\w\-]*/u', $migirSablon, $matches);

        $ogeler = array();

        $i = 0;
        foreach ($matches[0] as $match) {
            $i++;
            $oge = array();
            $ek = array();

            //$match = urldecode($match);
            preg_match('/(?<={).*?(?=})/u', $match, $oge);
            preg_match_all('/\-[\w]*/u', $match, $ek);

            $oge = count($oge) ? $oge[0] : "";

            $ek = $ek[0];

            $ogeler[$i]['tam'] = $match;
            $ogeler[$i]['oge'] = $oge;
            $ogeler[$i]['ekler'] = $ek;
        }

        return $ogeler;
    }

    public static function migirYurut($sablon, $values = null) {

        $matches = array();
        preg_match_all('/(?<=\[).*?(?=\])/um', $sablon, $matches);

        if ($values) {
            $values = explode(",", $values);
        }
        //echo "bu: " . $values[1] . "]";
        $i = 0;
        foreach ($matches[0] as $match) {
            $i++;

            $opts = explode("|", $match);

            $sablon = self::str_replaceFirst("[$match]", $opts[
                            $values != null ?
                                    $values[$i] :
                                    rand(0, count($opts) - 1)
                            ], $sablon);
        }
        return $sablon;
    }

    private function str_replaceFirst($s, $r, $str) {
        $l = strlen($str);
        $a = strpos($str, $s);
        $b = $a + strlen($s);
        $temp = substr($str, 0, $a) . $r . substr($str, $b, ($l - $b));
        return $temp;
    }

}

?>