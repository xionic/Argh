<?php

require '../PHPArgValidator.class.php';

$av = new ArgValidator(function($msg,$argName,$argValue){
	echo "<pre>";
	echo "There has been a validation error";
	var_dump($msg);
	var_dump($argName);
	var_dump($argValue);
	echo "</pre>";
	exit;
});

$apiargs = $av->validateArgs($_GET, array(
	"test1" => array("string", "notblank"),
	"test2" => array(function($a){return $a > 0;}),
	"test3" => array("lbound 5.0","ubound 500"),
	"test4" => array("regex /a/"),
	"test5" => array("notzero"),
	"test5" => array("array"),
	)
);


?>