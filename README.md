PHPArgValidator
===============

A powerful PHP helper class to validate arrays.

The primary usage is to validate arguments passed to a web server (i.e. validating $_GET and $_POST vars) against a series of per variable checks.
For example:

```
$av->validateArgs(
	$_GET, //array to validate
	array( //array of what to validate
		"customerName"	=> array("string", "notblank"), //array of checks to perform on $_GET["customerName"]		
		"customerDOB"	=> array("regex /[0-9]{2}-[0-9]{2}-[0-9]{4}"), // array of checks to perform on $_GET["customerDOB"]
	)
);
```

It can be used for much more general purposes too. For instance, it can be used as part of a web services testing framework to validate responses to REST/JSON requests; Parsing the JSON into a PHP array using json_decode, one can then validate both the structure, and content of the resulting array thusly:

```
$av->validateArgs(
	$decodedJSON,
	array(
		"Customer" => array("array"), //check that $decodedJSON["Customer"] is itself an array
	
		//We can also deal with multi dimensional arrays, so if you have a customer object in you're JSON, you can validate it like:
		"/Customer/FirstName"	=> array("string", "regex /[a-zA-Z]*/"),
		"/Customer/LastName"	=> array("string", "regex /[a-zA-Z]*/"),

		//We can go deeper too, and deal with numeric arrays
		"/Customer/PreviousAddresses/0/HouseNumber"	=> array("int"),

		//What?! you want to validate all the elements of a numeric array without repeating yourself and knowing how long the array with be? OK, just do:
		"/Customer/PreviousAddresses/*/HouseNumber"	=> array("int"),
	)
);
```

This flexible path specification structure combined with the checks below (particularly the regex and clojure options) make this a powerful, yet easy to use validation tool.

When you setup you validator instance, you must give it a callback function to handle validation errors. See the examples for details
	
Exposed Methods
===============

validateArgs( array $arguments, array $constraints )
----------------------------------------------------

Parameters:
* $arguments	-	An array to validate, e.g. $_POST
* $constraints	-	An array of key -> properties, see example

Supported Constraints:

* int		-	must be an integer (implies 'numeric')
* numeric	-	must be numeric
* notzero	-	must not be zero (implies 'numeric')
* notblank	-	string must not be blank (implies 'string')
* string	-	must be string
* array		- 	must be array
* func		- 	provided Closure must return true. 
* lbound arg	-	must not be below arg (e.g. "lbound 2")
* ubound arg	- 	must not be above arg (e.g. "ubound 600")
* regex arg	- 	must match regex given be arg

See example usage below

getVersion()
------------
	returns the version number of this class

EXAMPLE USAGE:
===============

```	
/**
* callback function for argument validation - used by ArgValidator class
*/
function handleArgValidationError($msg, $argName="", $argValue="")
{
	echo "<pre>";
	echo "There has been a validation error"
	var_dump($msg);
	var_dump($argName);
	var_dump($argValue);
	echo "</pre>";
	exit;
}

//A closure can also be used instead of "handleArgValidationError"
$av = new ArgValidator("handleArgValidationError");

$apiargs = $av->validateArgs($_GET, array(
	"test1" => array("string", "notblank"),
	"test2" => array(function($a){return $a > 0;}),
	"test3" => array("lbound 5","ubound 500"),
	"test4" => array("regex /a/"),
	"test5" => array("notzero"),
	"test5" => array("array"),
	)
);
```

See Test-Examples Directory for more examples
