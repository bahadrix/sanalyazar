<?php

include_once 'core/lingustian.php';

class WordTypes {
	/**
	 * Mastarsız, kök Fiil
	 * @example kokla
	 */
	const kfiil = 'k';
	const kfiil_adet = 6827;
	
	const sifat = 's';
	const sifat_adet = 10803;
	
	const isim = 'i';
	const isim_adet = 43179;
	
	const zarf = 'z';
	const zarf_adet = 0;
	
	const edat = 'e';
	const edat_adet = 0;

}

class Sozcuk {
	
	/**
	 * 
	 * @var WordTypes
	 */
	public $tip;
	/**
	 * 
	 * @var Boolean
	 */
	public $fiil = false;
	
	public $kok = "";
	
	public $sozcuk = "";
	
	private $hazircik = "";
	
	private $son_unlu = "";
	private $son_unlu_ince = false;
	private $mastarsiz = "";
	private $kaynasan = "";
	private $kaynasay = "";
	private $daralan = "";
	
	function __construct($sozcuk, $tip, $fiil = false) {

		$this->kok = $this->sozcuk = $tip == WordTypes::kfiil ? Lingustian::mastarKaldir($sozcuk) : $sozcuk;
		$this->tip = $tip;
		$this->fiil = $fiil;
		$this->kaynasan = $this->sozcuk;
		$this->kaynasay = $this->sozcuk;
		$this->hazirlan();
	}
	
	private function hazirlan() {
		
		
		
		$this->hazircik = $this->sozcuk;
		
		//$this->kaynasan= Lingustian::kaynastirma($this->hazircik);
		
		if ($this->tip == WordTypes::kfiil && in_array(substr($this->sozcuk,-1), Lingustian::$unluler)) {
			$this->kaynasan .= "n";
			$this->kaynasay .= "n";
		} 
		
		$this->daralan = Lingustian::daraltma($this->hazircik);
		
		
		$this->son_unlu = Lingustian::sonUnluNe($this->hazircik);
		$this->son_unlu_ince = Lingustian::sonUnluInce($this->hazircik);
	}
	
	public function Eklen($ek) {
		
		$this->sozcuk = $this->ek_getir($ek);
		$this->hazirlan();
	}
	
	
	private function ek_getir($ek) {
		
		// Bunlar kolay kullanım için
		$kelime =& $this->hazircik;
		$si =& $this->son_unlu_ince;
		
		
		if (in_array(substr($ek,1,0),Lingustian::$unluler))  { // ek ünlü ile başlıyosa yumusa 
			$kelime = Lingustian::yumusama($kelime);
		}
		
		switch ($ek) {
									// Neyi?
			case '-i':				return $kelime . Lingustian::daraltma($kelime);
									// Neye? 
			case '-e':				return Lingustian::yumusama($kelime) . Lingustian::genisletme($kelime); 
			
			case '-ne': 			return $kelime . ($si ?  "ne" : "na");
			case '-ecek':			return $this->kaynasan . ($si ? 'ecek' : 'acak');
			case '-meye':			return $this->kaynasay . ($si ? 'meye' : 'maya');
			
			case '-de':				return $kelime . ($si ? 'de' : 'da'); 
			case '-den':			return $kelime . ($si ? 'den' : 'dan'); 
			
			case '-ler': 			return Lingustian::lerlar($kelime,'ler',$si); //return $kelime . ($si ? 'ler' : 'lar'); 
			case '-leri': 			return Lingustian::lerlar($kelime,'leri',$si); //return $kelime . ($si ? 'leri' : 'ları');
			case '-lerin': 			return Lingustian::lerlar($kelime,'lerin',$si); //return $kelime . ($si ? 'lerin' : 'ların');
			case '-lerini': 		return Lingustian::lerlar($kelime,'lerini',$si); //return $kelime . ($si ? 'lerini' : 'larını');
			case '-lerine': 		return Lingustian::lerlar($kelime,'lerine',$si); //return $kelime . ($si ? 'lerine' : 'larına');
			
			case '-en': 			return $kelime . ($si ?  "en" : "an");
			case '-me':				return $kelime . ($si ? "me" : "ma");
			case '-mese':			return $kelime . ($si ? "mese" : "masa");
			case '-mez':			return $kelime . ($si ? "mez" : "maz");
			case '-meli':			return $kelime . ($si ? "meli" : "malı");
			case '-in':				return $kelime . ($si ? "in" : "ın");
				
			default: return $kelime . '*';
	
	
		}
	
	
	
	
	}
	
	
	
}

class WordEngine {

	private $DB;
	
	private $tipFields;
	
	
	function __construct(){
		
		$this->tipFields['kfiil'] = WordTypes::kfiil;
		$this->tipFields['sıfat'] = WordTypes::sifat;
		$this->tipFields['isim'] = WordTypes::isim;
		$this->tipFields['zarf'] = WordTypes::zarf;
		$this->tipFields['edat'] = WordTypes::edat;
		
		$this->DB = getPDO();
		$wt =array();
		
	}
	
	/**
	 * WordType tipinde kelimeler döndürür
	 * @param WordTypes $tip
	 * @param int $adet
	 * @return array
	 */
	public function kelimeAl($tip, $adet) {
		

		
		$table = "";
		$tableMaxID = 0;
		
		switch ($tip) {
			case $tip == WordTypes::kfiil:
				$tableMaxID = WordTypes::kfiil_adet;
							break;
			case $tip == WordTypes::isim:
				$tableMaxID = WordTypes::isim_adet;
							break;
			case $tip == WordTypes::sifat:
				$tableMaxID = WordTypes::sifat_adet;
							break;
			case $tip == WordTypes::edat:
				$tableMaxID = WordTypes::edat_adet;
							break;
			default:
				$tableMaxID = WordTypes::isim_adet;
		}
		
		$randIDS = array();
		
		for ($i = 0; $i <= $adet; $i++)
			$randIDS[] = rand(0,$tableMaxID);
		
		$inRand = implode(",",$randIDS);
		
		
		return $this->ezberdenAl($tip, $inRand);
		
	}
	
	public function ezberdenAl($tip, $liste) {
			
		$liste = mysql_real_escape_string($liste);
		
		switch ($tip) {
			case $tip == WordTypes::kfiil:	$table = "w_kfiiller"; break;
			case $tip == WordTypes::isim:	$table = "w_isimler";	break;
			case $tip == WordTypes::sifat:	$table = "w_sifatlar";	break;
			case $tip == WordTypes::edat:	$table = "w_edatlar";	break;
			default:						$table = "w_isimler"; FB::error($tip, "Kelime yok");
		}
		
		$query = "
			SELECT
			$table.ID,
			wordmult.HEAD_MULT AS WORD,
			contexts.CONTEXT AS CONTEXT,
			$table.VERBMI
			FROM
			$table
			INNER JOIN wordmult ON $table.WORD_ID = wordmult.WORD_ID AND $table.MULT_NO = wordmult.MULT_NO
			LEFT JOIN contexts ON $table.CONTEXT1_ID = contexts.CONTEXT_ID
			WHERE ID IN ($liste)
		";
		
			
		if (isset($query)) {
			$words = $this->DB->query($query)->fetchAll(PDO::FETCH_ASSOC);
			
			return $words;
		} else {
			throw new Exception("Bilinmeyen kelime tablosu: $table", 1001);
		}
		
	}
	
	public function sacmala($migirSablon, $yurut = true) {
		
		$migir = $migirSablon;
		$ogeler = Lingustian::migirCoz($migirSablon);
		
		$yogunluk = array();
		$words = array();
				
		foreach ($ogeler as $oge)			
			$yogunluk[$oge['oge']] = isset($yogunluk[$oge['oge']]) 
									? $yogunluk[$oge['oge']] + 1 
									: 1;
		

		foreach ($yogunluk as $oge => $adet) 	
			$words[$oge] = $this->kelimeAl($this->tipFields[$oge], $adet);

		foreach ($ogeler as $oge) {
			
			$sacma = array_pop($words[$oge['oge']]);
			$sacma = new Sozcuk($sacma['WORD'], $this->tipFields[$oge['oge']], $sacma['VERBMI']);
			
			
			foreach ($oge['ekler'] as $ek)			
				$sacma->Eklen($ek);


			
			$migir = str_replaceFirst($oge['tam'], $sacma->sozcuk, $migir);
		
		
			
		}
		if ($yurut)
			$migir = Lingustian::migirYurut($migir, null);
		return $migir;
		
	}
	
	public function ezbereSacmala($sablon, $params, $yurut = true) {
	
		$migir = $sablon;
		$ogeler = Lingustian::migirCoz($sablon);
		
		
		
		$yogunluk = array();
		$words = array();
		
		foreach ($ogeler as $oge)
			$yogunluk[$oge['oge']] = isset($yogunluk[$oge['oge']])
			? $yogunluk[$oge['oge']] + 1
			: 1;
		
		
		foreach ($yogunluk as $oge => $adet)
			$words[$oge] = $this->ezberdenAl($this->tipFields[$oge], $params[$this->tipFields[$oge]]);
		
		foreach ($ogeler as $oge) {
				
			$sacma = array_pop($words[$oge['oge']]);
			$sacma = new Sozcuk($sacma['WORD'], $this->tipFields[$oge['oge']], $sacma['VERBMI']);
				
		
			foreach ($oge['ekler'] as $ek)
				$sacma->Eklen($ek);
		
		
				
			$migir = str_replaceFirst($oge['tam'], $sacma->sozcuk, $migir);
		
			
				
		}
		
		if ($yurut) 
			$migir = Lingustian::migirYurut($migir, $params['p']);
		
		return $migir;
	
	
	
		// bi hata olursa rastgele sacmala
	
	}
	
}



function str_replaceFirst($s,$r,$str)
{
	$l = strlen($str);
	$a = strpos($str,$s);
	$b = $a + strlen($s);
	$temp = substr($str,0,$a) . $r . substr($str,$b,($l-$b));
	return $temp;
}
?>