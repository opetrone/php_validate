# php_validate
Autore: Petrone Orlando Mauro - Licenza: rilasciato sotto licenza MIT - Versione: 1.0

Una semplice libreria per la validazione sicura dei dati in PHP.
Questa libreria nasce con un unico e semplice concetto: l'essere il più semplice possibile, onde consentirne l'utilizzo a TUTTI. Per questo motivo, nella sua implementazione, sono state fatte delle scelte (magari discutibili) atte a ridurre al massimo le conoscenze necessarie al suo utilizzo e a standardizzare al massimo il valore dei parametri, che sono tutti delle semplici stringhe.

Esempio Utilizzo:

<?php
	
	$errore="";
	
	require_once('php_validate.php');
	
	$cap=valida_input( array(
		"origine"=>"post",
		"campo"=>"cap",
		"etichetta"=>"CAP",       //opzionale
		"tipo"=>"naturale",
		"obbligatorio"=>"si",     //opzionale, il valore predefinito è "no"
		"lunghezza_min"=>"5",     //opzionale
		"lunghezza_max"=>"5",     //opzionale
		"valore_min"=>"",         //opzionale
		"valore_max"=>""          //opzionale
		), $errore);
	
	if ($errore!="") {
		print $cap;
	} else {
		print $errore;
	}

?>
