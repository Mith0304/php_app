<?php

/*
** Project Blockchain Hashes
** Php file for index.html
** Authors Sylvain Beral & Kevin Loiseleur
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
$res = getHashes($Block);

echo '
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bitcoin hashes</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.6-dist/css/bootstrap.min.css">
  <script src="jquery-1.12.3.min.js"></script>
  <script src="boostrap-3.3.6-dist/js/bootstrap.min.js"></script>
  <style>

    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    .navbar-brand {
      padding-top: 7px;
      padding-left: 10px;
    }

    .body {
      padding-bottom: 70px;
    }

    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 100%;}

    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f7931a;
      height:2500px;
    }

    @media (max-width: 767px) {
        .navbar-brand {
            padding: 0;
        }

        .navbar-brand img {
            margin-top: 5px;
            margin-left: 5px;
        }
    }

    thead tr th:nth-child(1), #pollResult tbody tr td:nth-child(1), #pollResult tfoot tr td:nth-child(1) {
    width: 20%;
}

.navbar-center  {
  position: absolute;
  width: 100%;
  left: 0;
  text-align: center;
  padding-top: 5px;
  margin: auto;
  color:white;
  font-size: 200%;
}


.navbar-left {
  color: grey;
  padding-top: 10px;
  width: 70%;
  text-align: left;
  vertical-align: middle;
  font-size: 100%;
}

    </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img src="bitcoin_logo.png" rel="Bitcoin logo">
      </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <span class="navbar-center">Bitcoin Hashes</span>
    </div>
  </div>
</nav>

<div class="container-fluid text-center">

  <div class="row content">
    <div class="col-sm-1 sidenav">
    <button type="button" class="btn btn-danger" onClick="window.location.reload()">Refresh</button>
    </div>

    <div class="col-sm-10 text-left">
    <h3 style="text-align: left">Les 10 derniers blocks</h3>
    <br />
    <p> Voici une liste des 10 derniers blocks, avec leurs Hashs en écriture hexadécimal et binaire.<br /> Ils sont classés du plus récent au plus ancien.<br />
    Le site de référence est <a href="https://blockchain.info/fr/">Blockchain info</a>.<br /></p>
    ';


          for($i=0; $i < count($res[0]); ++$i) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-condensed" style="margin-bottom: 60px; margin-top: 20px;"><thead>';
            echo '<tr>';
              echo '<th>Index Block</td>';
              echo '<th style="vertical-align: middle;">#'.($i+1).'    <a href="https://www.blockchain.info/fr/block/'.$res[0][$i].'">(info)</a></td>';
            echo '</tr>';
            echo '<tbody><tr>';
              echo '<td>Hash Hexa</td>';
              echo '<td style="vertical-align: middle;">'.$res[0][$i].'</td>';
            echo '</tr>';
            echo '<tr>';
              echo '<td>Hash Bin</td>';
              echo '<td style="vertical-align: middle;">'.$res[1][$i].'</td>';
            echo '</tr>';
            echo '</tbody></table>';
            echo '</div>';
          }
    echo
  '</div>
    <div class="col-sm-1 sidenav">
    </div>
      <div style="clear:both"></div>
  </div>
</div>

<nav class="navbar navbar-inverse navbar-fixed-bottom">
  <div class="container">
  <span class="navbar-left">Copyright © Bitcoin Hashes  -  Made by <a href="mailto:sylvain009@hotmail.fr">Sylvain Beral</a></span>
  </div>
</nav>

</body>';


?>
