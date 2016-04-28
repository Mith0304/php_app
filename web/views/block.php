<?php

/*
** Project Blockchain Hashes
** Php file for index.html
*/

require 'vendor/autoload.php';
use \Blockchain\Blockchain;

function base_convert_maison($number, $fromBase, $toBase) {
  $digits = '0123456789abcdef'; // Base HEXA max
  $length = strlen($number);
  $result = '';

  $nibbles = array();
  for ($i = 0; $i < $length; ++$i) {
      $nibbles[$i] = strpos($digits, $number[$i]);
  }

  do {
      $value = 0;
      $newlen = 0;
      for ($i = 0; $i < $length; ++$i) {
          $value = $value * $fromBase + $nibbles[$i];
          if ($value >= $toBase) {
              $nibbles[$newlen++] = (int)($value / $toBase);
              $value %= $toBase;
          }
          else if ($newlen > 0) {
              $nibbles[$newlen++] = 0;
          }
      }
      $length = $newlen;
      $result = $digits[$value].$result;
  }
  while ($newlen != 0);
  return $result;
}

function getHashes($B) {

  $t = time() - 7200;// TIMESTAMP for 2 hours ago
  $BArr = $B->Explorer->getBlocksForDay($t);
  $hashHex = array();
  $hashBin = array();
  $hs = array();
  $hs[0] = array();
  $hs[1] = array();

  foreach (array_slice($BArr, 0, 10) as $bl) {
    $hashHex[] = $bl->hash;
  }
  foreach ($hashHex as $hH) {
    $hashBin[] = base_convert_maison($hH, 16, 2);
  }
  for ($j = 0; $j < count($hashHex); ++$j) {
    $hs[0][$j] = $hashHex[$j];
  }
  for ($k = 0; $k < count($hashBin); ++$k) {
    $hs[1][$k] = $hashBin[$k];
  }
  return ($hs);
}

$Block = new Blockchain();
$Block->setTimeout(30);
$res = 2; #getHashes($Block);
$var = 'res';
include('index.twig');

/* dans res tu as 2 tableau
res[0][0->9] -> hashs hexa
res[1][0->9] -> hashs binaires
*/



?>
