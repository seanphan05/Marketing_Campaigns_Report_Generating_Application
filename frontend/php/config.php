<?php 
/** 
* Configuration for database connection
*
*/
$host 		= "localhost";
$username 	= "root";
$password   = "mysql";
$dbname     = "mc_ga_db";
$dsn        = "mysql:host=$host; dbname=$dbname";
$options    = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
	);