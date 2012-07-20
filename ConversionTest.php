<?php 


require_once ("Conversion.php");


$res = ConversorNumerico::conversion("9900000", "7");
echo $res.PHP_EOL;

$res = ConversorNumerico::conversorAMinutos("0000000000059", 13);
echo $res.PHP_EOL;

$res = ConversorNumerico::conversorAMinutos("0000000000100", 13);
echo $res.PHP_EOL;

$res = ConversorNumerico::conversorAMinutos("0000000005801", 13);
echo $res.PHP_EOL;

$res = ConversorNumerico::conversorAMinutos("0000000020000", 13);
echo $res.PHP_EOL;
 ?>