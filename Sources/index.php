<?php

session_start();

if (isset($_SESSION["newsession"]))
	header('Location: indexCo.php');
else
	header('Location: indexDeco.php');

?>