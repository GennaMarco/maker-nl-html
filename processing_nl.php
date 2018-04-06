<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 06/04/2018
 * Time: 12:18
 */

//percorso della cartella dove mettere i file caricati dagli utenti
$uploaddir = 'files/';

//Recupero il percorso temporaneo del file
$userfile_tmp = $_FILES['FileToUpload']['tmp_name'];

//recupero il nome originale del file caricato
$userfile_name = $_FILES['FileToUpload']['name'];

//copio il file dalla sua posizione temporanea alla mia cartella upload
if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name))
{
    //Se l'operazione è andata a buon fine...
    echo 'File inviato con successo.';
}else
{
    //Se l'operazione è fallta...
    echo 'Upload NON valido!';
}