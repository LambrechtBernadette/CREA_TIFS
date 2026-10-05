<?php
/*
./noyau/connexion.php
Création d'une instance PDO $connexion
*/

try {
    $connexion = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PWD);

} catch (PDOException $e) {
    throw $e;
}
