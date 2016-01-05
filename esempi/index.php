<!DOCTYPE html>
    <title>Pagina esempio libreria PHP_VALIDATE</title>
 

<?php
/*
 *  Attenzione: questo è un semplice codice di esempio che mostra l'utilizzo
 *  della libreria: non utilizzatelo direttamente nei vostri progetti.
*/

require_once("../php_validate.php");
if (!empty($_POST)) {
    $errore="";

    $partita_iva=valida_input( array(
                        "origine"=>"post",
                        "campo"=>"partita_iva",
                        "etichetta"=>"Partita IVA",
                        "tipo"=>"piva",
                        "obbligatorio"=>"si"	
                    ), $errore);
    $codice_fiscale=valida_input( array(
                        "origine"=>"post",
                        "campo"=>"codice_fiscale",
                        "etichetta"=>"Codice Fiscale",
                        "tipo"=>"cf",
                        "obbligatorio"=>"si"	
                    ), $errore);
        
    if ($errore!="") {
        print $errore;
    } else {
        print 'Partita IVA: '.$partita_iva.'<br/>'.
              'Codice Fiscale: '.$codice_fiscale.'<br/>';
    }
    print '<a href="index.php">torna indietro</a>';
} else {
?>
        <form method="POST" action="index.php">
              <label for="partita_iva">Partita IVA</label><input type="text" name="partita_iva" id="partita_iva" value=""><br/>
              <label for="partita_iva">Codice Fiscale</label><input type="text" name="codice_fiscale" id="codice_fiscale" value=""><br/>
              <input type="submit" value="salva">
        </form>
<?php }
