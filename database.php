<?php
$server = 'localhost';
$username = 'Kireta';
$password = 'lmdpdr2468';
$database = 'authVote';

try{
	$conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
} catch(PDOException $e){
	die( "Connection failed: " . $e->getMessage());
}