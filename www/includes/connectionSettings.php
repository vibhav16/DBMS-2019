<?php
/*
Connection to Database
*/

$connection = oci_connect($username = 'gatorid',
								  $password = 'dbms pwd',
								  $connection_string = '//oracle.cise.ufl.edu/orcl');
?>
