<?php  
error_reporting(E_ERROR | E_PARSE);
$db = new mysqli("localhost", "root", "phpcoder" , "sawasawa");
	if($db->connect_errno){
		die('Sorry we have some problem with the Database!');
	}             
?>