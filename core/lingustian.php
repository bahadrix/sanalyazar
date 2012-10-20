<?php



class Lingustian {

	public static $unluler = array('a','ı','o','u', 'e','i','ö','ü');
	public static $kalin_unluler = array('a','ı','o','u');
	public static $ince_unluler = array('e','i','ö','ü');
	public static $sert_unsuzler = array('p', 'ç', 't', 'k');
	public static $yumusayan_unsuzler = array('p' => 'b', 'ç' => 'c', 't' =>'d', 'k' => 'ğ');
	public static $daralan_unluler = array('e'=>'i', 'a'=> 'ı', 'o'=>'u', 'ö'=>'ü');

	

	
	public static function yumusama($kelime) {
		$son_harf = substr($kelime, -1);
		return in_array($son_harf, self::$sert_unsuzler) ? substr($kelime, 0,-1) . self::$yumusayan_unsuzler[$son_harf] : $kelime;
		
	}
	

	public static function mastarKaldir($mastarliFiil) {
		
		return preg_replace('/m[ae]k$/', '', $mastarliFiil);
		
		
	}
	
	public static function daraltma($kelime) {

		$son_unlu = self::sonUnluNe($kelime);
		$son_unlu_genis = in_array(
				$son_unlu,
				array_keys(self::$daralan_unluler)
		);
		$son_unlu_dar = in_array(
				$son_unlu,
				array_values(self::$daralan_unluler)
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
				$son_unlu,
				array_keys(self::$daralan_unluler)
		);
		$son_unlu_dar = in_array(
				$son_unlu,
				array_values(self::$daralan_unluler)
		);
		
		$genisleyenUnluler = array_flip(self::$daralan_unluler);
		
		$ek = "";
		if ($son_unlu_genis) 
			$ek = $son_unlu;
		else if ($son_unlu_dar) // darsa genislet
			$ek = $genisleyenUnluler[$son_unlu];
			
		
		return $ek;
	}
	
	public static function sonUnluNe($kelime) {
		
		$index = 0;
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
		preg_match_all('/\{\w+\}\-?/u', $migirSablon, $matches); //db olmadığından şu an deneyemedim, yanlış olabilir. orjinali: /{[}\w-]*/u - bu tam doğru değil gibi geldi o yüzden değiştiriyim dedim.
		
		
		$ogeler = array();
		
		$i = 0;
		
		foreach ($matches[0] as $match) {
			$i++;
			$oge = array();
			$ek = array();
		
			//$match = urldecode($match);
			preg_match('/(?<={).*?(?=})/u',$match,$oge);
			preg_match_all('/\-[\w]*/u',$match,$ek);
		
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
		echo "bu: " . $values[1] . "]";
		$i = 0;
		foreach ($matches[0] as $match) { $i++;
			
			$opts = explode("|", $match);

			$sablon = self::str_replaceFirst("[$match]", 
					$opts[
								$values != null ? 
								$values[$i] : 
								rand(0, count($opts) - 1)
					], 
					$sablon);	
		}
		return $sablon;
		
		
	}
	
	private function str_replaceFirst($s,$r,$str)
	{
		$l = strlen($str);
		$a = strpos($str,$s);
		$b = $a + strlen($s);
		$temp = substr($str,0,$a) . $r . substr($str,$b,($l-$b));
		return $temp;
	}
	
	
	
}

?>