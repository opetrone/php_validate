# php_validate
Autore: Petrone Orlando Mauro
Licenza: rilasciato sotto licenza MIT
versione: 1.0

Una semplice libreria per la validazione sicura dei dati in PHP.

Esempio Utilizzo:

<?php
	
	$errore="";
	
	require_once('php_validate.php');
	
	$cap=valida_input( array(
		"origine"=>"post",
		"campo"=>"cap",
		"etichetta"=>"CAP",
		"tipo"=>"naturale",
		"obbligatorio"=>"si",
		"lunghezza_min"=>"5",
		"lunghezza_max"=>"5",
		"valore_min"=>"",
		"valore_max"=>""
		), $errore);
	
	if ($errore!="") {
		print $cap;
	} else {
		print $errore;
	}

?>
