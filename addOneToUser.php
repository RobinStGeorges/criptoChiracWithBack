<?php
require 'database.php';
$candidat = $_POST["Name"];
$lastNameGotten= $_POST["lastNameGotten"];



    // on crée la requete SQL
    $update_votes = $conn->prepare('UPDATE users SET count = count + 1 WHERE email = ?');
    $updateLastnameGotten = $conn->prepare('UPDATE users SET lastNameGotten = ? WHERE email= ?');

    try {
        // On envois la requète
        $success = $update_votes->execute(array($candidat));
        $yep = $updateLastnameGotten->execute(array($lastNameGotten, $candidat));

    } catch( Exception $e ){
        echo 'Erreur de requète : ', $e->getMessage();
    }



?>