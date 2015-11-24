<?php
function valida_input($array_in,&$errore)
{
	/* Esempio Chiamata:
		valida_input( array(
			"origine"=>"post",
			"campo"=>"cap",
			"etichetta"=>"CAP",
			"tipo"=>"naturale",
			"obbligatorio"=>"si",
			"lunghezza_min"=>"5",
			"lunghezza_max"=>"5",
			"valore_min"=>"",
			"valore_max"=>"",	
		), $errore);
	
	*/
	$errore_int='';
	if ((isset($array_in["campo"]))&&(trim($array_in["campo"])!='')) {
		if (isset($array_in["etichetta"])) {
			$label=$array_in["etichetta"];
		} else {
			$label=$array_in["campo"];
		}
	} else {
		if (isset($array_in["etichetta"])) {
			$errore_int.="Nome di campo non assegnato per l'etichetta ".htmlspecialchars($label,ENT_QUOTES)."<br/>";
		} else {
			$errore_int.="Nome di campo non assegnato per uno dei campi<br />";
		}
	}
	
	if ($errore_int=='') {
		if ((isset($array_in["origine"]))&&(isset($array_in["tipo"]))) {
			switch (strtolower($array_in["origine"])) {
				case "post":
					$source=INPUT_POST;
					if ((isset($array_in["obbligatorio"])&&(strtoupper($array_in["obbligatorio"])=='SI'))) {
						if ((!(isset($_POST[$array_in["campo"]])))||(trim($_POST[$array_in["campo"]])==='')||($_POST[$array_in["campo"]]===NULL)) {
							$errore_int.="Campo ".htmlspecialchars($label,ENT_QUOTES)." obbligatorio non inserito o nullo<br/>";
						}
					}
					break;
				case "get":
					$source=INPUT_GET;
					if ((isset($array_in["obbligatorio"])&&(strtoupper($array_in["obbligatorio"])=='SI'))) {
						if ((!(isset($_GET[$array_in["campo"]])))||(trim($_GET[$array_in["campo"]])==='')||($_GET[$array_in["campo"]]===NULL)) {
						        $errore_int.="Campo ".htmlspecialchars($label,ENT_QUOTES)." obbligatorio non inserito o nullo<br/>";
						}
					}
					break;
				case "session":
					$source=INPUT_SERVER;
					if ((isset($array_in["obbligatorio"])&&(strtoupper($array_in["obbligatorio"])=='SI'))) {
						if ((!(isset($_SESSION[$array_in["campo"]])))||(trim($_SESSION[$array_in["campo"]])==='')||($_SESSION[$array_in["campo"]]===NULL)) {
							$errore_int.="Campo ".htmlspecialchars($label,ENT_QUOTES)." obbligatorio non inserito o nullo<br/>";
						}
					}
					break;
				case "cookie":
					$source=INPUT_COOKIE;
					if ((isset($array_in["obbligatorio"])&&(strtoupper($array_in["obbligatorio"])=='SI'))) {
						if ((!(isset($_COOKIE[$array_in["campo"]])))||(trim($_COOKIE[$array_in["campo"]])==='')||($_COOKIE[$array_in["campo"]]===NULL)) {
							$errore_int.="Campo ".htmlspecialchars($label,ENT_QUOTES)." obbligatorio non inserito o nullo<br/>";
						}
					}
					break;
				/*
				L'uso di ENV è deprecato, lo lascio solo come riferimento
				case "env":
					echo INPUT_ENV;
					if ((isset($array_in["obbligatorio"])&&(strtoupper($array_in["obbligatorio"])=='SI'))) {
						if ((!(isset($_ENV[$array_in["campo"]])))||(trim($_ENV[$array_in["campo"]])!=='')||($_ENV[$array_in["campo"]]!==NULL)) {
							$errore_int.="Campo ".htmlspecialchars($label,ENT_QUOTES)." obbligatorio non inserito o nullo<br/>";
						}
					}
					break;
				*/
				default:
				   $errore_int.="Errore nella sorgente dati sul campo: ".htmlspecialchars($label,ENT_QUOTES)."<br/>";
				   break;
			}
			if ($errore_int=='') {
				if (isset($array_in["tipo"])) {
					$tipo=trim($array_in["tipo"]);
					switch (trim(strtolower($array_in["tipo"]))) {
						case "numero":
							$validate=FILTER_VALIDATE_FLOAT;
							$validate_option=NULL;
							$sanitize=FILTER_SANITIZE_NUMBER_FLOAT;
							$sanitize_option=NULL;
							break;
						case "intero":
							$validate=FILTER_VALIDATE_INT;
							$validate_option=NULL;
							$sanitize=FILTER_SANITIZE_NUMBER_INT;
							$sanitize_option=NULL;
							break;
						case "naturale":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[0-9]{0,}\s{0,}$/"));
							$sanitize=FILTER_SANITIZE_NUMBER_INT;
							$sanitize_option=NULL;
							break;
						case "lista_naturali":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[0-9|]{0,}\s{0,}$/"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "lettere":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[a-z]+\s{0,}$/i"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "alfanumerico":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[a-z0-9]+\s{0,}$/i"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "parole":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[a-z0-9\s]+\s{0,}$/i"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "data":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[0-9]{2,4}[-\/]{1}[0-9]{2}[-\/]{1}[0-9]{2,4}\s{0,}$/"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "dataora":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[0-9]{2,4}[-\/]{1}[0-9]{2}[-\/]{1}[0-9]{2,4} [0-9]{2}:[0-9]{2}\s{0,}$/"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						/*
						La funzione boolean è buggata, è preferibile non utilizzarla. Si usi la validazione come stringa o come naturale al suo posto.
						case "boolean":
							$validate=NULL;
							$validate_option=NULL;
							$sanitize=FILTER_VALIDATE_BOOLEAN;
							$sanitize_option=NULL;
							break;
						*/
						case "stringa":
							$validate=NULL;
							$validate_option=NULL;
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=FILTER_FLAG_NO_ENCODE_QUOTES;
							break;
						case "email":
							$validate=FILTER_VALIDATE_EMAIL;
							$validate_option=NULL;
							$sanitize=FILTER_SANITIZE_EMAIL;
							$sanitize_option=NULL;
							break;
						case "url":
							$validate=FILTER_VALIDATE_URL;
							$validate_option=NULL;
							$sanitize=FILTER_SANITIZE_URL;
							$sanitize_option=NULL;
							break;
						case "cf":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[a-z0-9]+\s{0,}$/i"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "piva":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[a-z0-9]+\s{0,}$/i"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "cf_piva":
							$validate=FILTER_VALIDATE_REGEXP;
							$validate_option=array("options"=>array("regexp"=>"/^\s{0,}[a-z0-9]+\s{0,}$/i"));
							$sanitize=FILTER_SANITIZE_STRING;
							$sanitize_option=NULL;
							break;
						case "raw":
							$validate=NULL;
							$validate_option=NULL;
							$sanitize=FILTER_UNSAFE_RAW;
							$sanitize_option=NULL;
							break;
						default:
							$errore_int.="Errore nel tipo di validazione dei dati sul campo: ".htmlspecialchars($label,ENT_QUOTES)."<br/>";
							break;				
					}
				} else {
					$tipo="nd";
					$validate=NULL;
					$validate_option=FILTER_SANITIZE_STRING;
					$sanitize=NULL;
					$sanitize_option=NULL;
				}
			}
		
		}
	}
	
	if ($errore_int=='') {
		if ($validate!==NULL) {
                    if ($validate_option===NULL) {
                        	if (filter_input($source,$array_in["campo"],$validate)===FALSE) {
					$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." contiene dei caratteri non validi<br />";
				}
			} else {
				if (filter_input($source,$array_in["campo"],$validate,$validate_option)===FALSE) {
					$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." contiene dei caratteri non validi<br />";
				}
			}
		}
	}
	if ($errore_int=='') {
		if ($sanitize!==NULL) {
			if ($sanitize_option===NULL) {
				$valore=trim(filter_input($source,$array_in["campo"],$sanitize));
			} else {
				$valore=trim(filter_input($source,$array_in["campo"],$sanitize,$sanitize_option));
			}
		} else {
			$valore=trim(filter_input($source,$array_in["campo"]));
		}		
	} 
	
	if ($errore_int=='') {
		if ((strip_tags($valore)!=$valore)&&($tipo!='raw')) {
			$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." contiene del testo non consentito<br/>";
		}
	}
	
	if ($errore_int=='') {
		if ((isset($array_in["obbligatorio"]))&&(strtoupper($array_in["obbligatorio"])=='SI')) {
			if (($valore=='')or($valore===NULL)) {
				$errore_int.="Campo ".htmlspecialchars($label,ENT_QUOTES)." obbligatorio non inserito<br/>";
			}
		} elseif ((isset($array_in["obbligatorio"]))&&(strtoupper($array_in["obbligatorio"])=='STRICT')) {
			if (empty($valore)) {
				$errore_int.="Campo ".htmlspecialchars($label,ENT_QUOTES)." obbligatorio non inserito o nullo<br/>";
			}
		}
	}
	
	if (($errore_int=='')&&(($valore!=''))) {
		switch ($tipo) {
			case "cf":
				if (!(codiceFiscale($valore) or controllaPIVA($valore))) {
					$errore_int.="Codice fiscale non valido<br />";
				}
				break;
			case "piva":
				if (!(codiceFiscale($valore) or controllaPIVA($valore))) {
					$errore_int.="Partita IVA non valida<br />";
				}
				break;
			case "cf_piva":
				if (!(codiceFiscale($valore) or controllaPIVA($valore))) {
					$errore_int.="Codice fiscale/Partita IVA non valido<br />";
				}
				break;			
		}
	}
	
	if ($errore_int=='') {
		if (!(((!(isset($array_in["obbligatorio"])))||((isset($array_in["obbligatorio"]))&&(strtoupper($array_in["obbligatorio"])=='NO')))&&(strlen($valore)==0))) {
			if (((isset($array_in["lunghezza_min"]))&&(is_numeric($array_in["lunghezza_min"]))&&($array_in["lunghezza_min"]>0))&&((isset($array_in["lunghezza_max"]))&&(is_numeric($array_in["lunghezza_max"]))&&($array_in["lunghezza_max"]>0))) {
				if ((strlen($valore)<$array_in["lunghezza_min"])||(strlen($valore)>$array_in["lunghezza_max"])) {
					if ($array_in["lunghezza_min"]==$array_in["lunghezza_max"]) {
						$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." deve contenere ".$array_in["lunghezza_min"]." caratteri<br/>" ;
					} else {
						$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." deve contenere da ".$array_in["lunghezza_min"]." a ".$array_in["lunghezza_max"]." caratteri<br/>" ;
					}					
				}		
			} else {
				if ((isset($array_in["lunghezza_min"]))&&(is_numeric($array_in["lunghezza_min"]))&&($array_in["lunghezza_min"]>0)) {
					if (strlen($valore)<$array_in["lunghezza_min"]) {
						$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." deve contenere almeno ".$array_in["lunghezza_min"]." caratteri<br/>" ;
					}
				}
				if ((isset($array_in["lunghezza_max"]))&&(is_numeric($array_in["lunghezza_max"]))&&($array_in["lunghezza_max"]>0)) {
					if (strlen($valore)>$array_in["lunghezza_max"]) {
						$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." deve contenere al massimo  ".$array_in["lunghezza_min"]." caratteri<br/>" ;
					}
				}
			}
		}
	}
	
	if (($errore_int=='')&&($valore!='')) {
		$tipi = array('numero','intero','naturale');
		if ((isset($array_in["tipo"]))&&(in_array($array_in["tipo"],$tipi))) {
			if (((isset($array_in["valore_min"]))&&(is_numeric($array_in["valore_min"]))&&($array_in["valore_min"]>0))&&((isset($array_in["valore_max"]))&&(is_numeric($array_in["valore_max"]))&&($array_in["valore_max"]>0))) {
				if (($valore<$array_in["valore_min"])||($valore>$array_in["valore_max"])) {
					$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." deve essere compreso tra ".$array_in["valore_min"]." e ".$array_in["valore_max"]."<br/>" ;
				}		
			} else {
				if ((isset($array_in["valore_min"]))&&(is_numeric($array_in["valore_min"]))&&($array_in["valore_min"]>0)) {
					if ($valore<$array_in["valore_min"]) {
						$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." deve essere maggiore di ".$array_in["valore_min"]."<br/>" ;
					}
				}
				if ((isset($array_in["valore_max"]))&&(is_numeric($array_in["valore_max"]))&&($array_in["valore_max"]>0)) {
					if ($valore>$array_in["valore_max"]) {
						$errore_int.="Il campo ".htmlspecialchars($label,ENT_QUOTES)." deve essere minore di ".$array_in["valore_min"]."<br/>" ;
					}
				}
			}
		}
	}
		
	if ($errore_int!='') {
		$errore.=$errore_int;
		$valore=NULL;
	}
		
	return $valore;
}

function codiceFiscale($cf)
{
    if($cf=='')
		return false;
    if(strlen($cf)!= 16)
		return false;
    $cf=strtoupper($cf);
    if(!preg_match("/[A-Z0-9]+$/", $cf))
		return false;
    $s = 0;
    for($i=1; $i<=13; $i+=2)
		{
		$c=$cf[$i];
		if('0'<=$c and $c<='9')
			$s+=ord($c)-ord('0');
		else
			$s+=ord($c)-ord('A');
		}
    for($i=0; $i<=14; $i+=2)
		{
		$c=$cf[$i];
		switch($c)
			{
			case '0':  $s += 1;  break;
			case '1':  $s += 0;  break;
			case '2':  $s += 5;  break;
			case '3':  $s += 7;  break;
			case '4':  $s += 9;  break;
			case '5':  $s += 13;  break;
			case '6':  $s += 15;  break;
			case '7':  $s += 17;  break;
			case '8':  $s += 19;  break;
			case '9':  $s += 21;  break;
			case 'A':  $s += 1;  break;
			case 'B':  $s += 0;  break;
			case 'C':  $s += 5;  break;
			case 'D':  $s += 7;  break;
			case 'E':  $s += 9;  break;
			case 'F':  $s += 13;  break;
			case 'G':  $s += 15;  break;
			case 'H':  $s += 17;  break;
			case 'I':  $s += 19;  break;
			case 'J':  $s += 21;  break;
			case 'K':  $s += 2;  break;
			case 'L':  $s += 4;  break;
			case 'M':  $s += 18;  break;
			case 'N':  $s += 20;  break;
			case 'O':  $s += 11;  break;
			case 'P':  $s += 3;  break;
			case 'Q':  $s += 6;  break;
			case 'R':  $s += 8;  break;
			case 'S':  $s += 12;  break;
			case 'T':  $s += 14;  break;
			case 'U':  $s += 16;  break;
			case 'V':  $s += 10;  break;
			case 'W':  $s += 22;  break;
			case 'X':  $s += 25;  break;
			case 'Y':  $s += 24;  break;
			case 'Z':  $s += 23;  break;
			}
		}	
		if( chr($s%26+ord('A'))!=$cf[15] )
			return false;
		return true;
}

function controllaPIVA($piva)
{
    if($piva=='')
		return false;
	    if(strlen($piva)!=11)
	        return false;
	    if(!ereg("^[0-9]+$", $piva))
	        return false;
	    $primo=0;
	    for($i=0; $i<=9; $i+=2)
	            $primo+= ord($piva[$i])-ord('0');
	    for($i=1; $i<=9; $i+=2 ){
	        $secondo=2*( ord($piva[$i])-ord('0') );
	        if($secondo>9)
	            $secondo=$secondo-9;
	        $primo+=$secondo;
	    }
	    if( (10-$primo%10)%10 != ord($piva[10])-ord('0') )
	        return false;
	    return true;
}

?>
