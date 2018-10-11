#!/usr/bin/php
<?php

//// FUNCTION error handler 
function customError($errno, $errstr, $errfile, $errline)
  {
  echo "\n--------------------------------------------------------\n";
  echo "::ERROR: [$errno] \n$errstr \n$errfile : line $errline";
  echo "\n\n Please report this problem to script admin.\n";
  echo "\n--------------------------------------------------------\n\n";
  die();
  }
//set error handler
set_error_handler("customError");

$startTime = date('h:i:s');

// Check num args after input file (1st arg)
$numArgs = $_SERVER["argc"] - 1;
//////////////////////////////////////////////////////////////////////
// Convert args to an array 
// ARGS: 0=self, so it's ignored
// ARGS: 1=inputfile
for($i=1;$i<=$numArgs;$i++){
 $arrArg[$i] = $_SERVER["argv"][$i];
}

if (isset($arrArg[1])) {
 $arg1 = $arrArg[1];

}
else {
 echo "\n::ERROR: Must supply one argument. ";
 echo "\n   Usage: php <script> <arg1>";
 echo "\n    arg1 = input file";
 echo "\n\n";
 die();
}


//SET FILE NAMES TO INCLUDE PATH
$workDir = getcwd();
$fileIn = "$workDir/$arg1";

//ALTERNATE FILE NAMES WITHOUT PATH
// $fileIn = "$arrArg[1]";
// $fileOut = "$arrArg[2]";

// Explode input file lines into new arrary
$rawfile = file_get_contents("$fileIn", true);
$tok = explode("\n",$rawfile);
$tokLength = sizeof($tok);

//Build and store Object Network names array
$arrObjects = array();
$c = 0;
for ($x=0;$x<$tokLength;$x++)
{
 if (preg_match("/^object network/",$tok[$x])) {
  $ObjectName = str_replace("object network ", "",$tok[$x]);
  $arrObjects[$c] = $ObjectName;
  $c += 1;
 }
}



// Create & Build Unused Object Name array
$arrUnused = array();
$y = 0;
$z = 0;
$arrObjectsLength = sizeof($arrObjects);

for ($c=0;$c<$arrObjectsLength;$c++) {
 $objName = trim($arrObjects[$c]);
	for ($x=0;$x<$tokLength;$x++) {
	 $string = $tok[$x];
	 $pos = strpos($string,$objName);
	 if ( $pos !== false) {
	  $y += 1;
	  //echo "\n HIT   $objName    $y";
	 }
	}
  if ($y < 2) {
   $arrUnused[$z] = $objName;
   $z += 1;
  }
 $c += 1;
 $y = 0;
}



// BUILD AND PRINT RESULTS
$arrUnusedLength = sizeof($arrUnused);
echo "\nThe following objects should be safe to remove:\n";
echo "----------------------------------------\n";
for ($x=0;$x<$arrUnusedLength;$x++) {
 echo "no object network " . $arrUnused[$x] . "\n";
}

echo "----------------------------------------\n";
echo "Objects found in config: \t $arrObjectsLength\n";
echo "Objects found unused: \t\t $arrUnusedLength\n";



//DEBUG: Print raw results  
//print_r($arrUnused);


?>
 
