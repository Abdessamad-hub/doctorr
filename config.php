<?php

 $servername = "localhost";
 $username = "abdo";
 $password = "0000";
 $bdname ="projectf";
 $conn;

//data base connection
	try {

		$conn = new PDO("mysql:host=$servername;dbname=projectf", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
return $conn;

  ?>