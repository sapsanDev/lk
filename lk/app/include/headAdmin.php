<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <title><?=$title?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <link rel="shortcut icon" href="pub/img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="pub/css/prel.css">
  <link rel="stylesheet" href="pub/css/main.css">
  <link rel="stylesheet" href="pub/css/bootstrap.css">
  <script src="pub/js/modernizr.js"></script>
  <script src="pub/js/jquery.min.js"></script>
  <script src="pub/js/popper.js"></script>
  <script src="pub/js/bootstrap.js"></script>
  <script src="pub/js/framaterial.js"></script>
  <script src="pub/js/admin.js"></script>
  <script src="pub/js/sweetalert2.all.js"></script>
  <script>$(window).load(function() {$('.prel').find('div').fadeOut().end().delay(0.1).fadeOut('slow');});</script>
</head>
<body>
    <div class="prel">
      <div class="containers">
        <div class="📦"></div><div class="📦"></div><div class="📦"></div><div class="📦"></div><div class="📦"></div>
      </div>
  </div>
<div class="material-design-layout">
  <div class="material-navigation-left-lightblue-fixed">
    <div class="navigation-inner"> 
      <div class="navigation-content">
       <?php if(isset($_SESSION['lk_admin'])):?> <a href="#" class="icon-menu" data-toggle="sidebar"><span class="m-icon mdi-menu"></span></a><?php endif?>
        <a href="/lk" class="brand-logo fm">Personal Area</a>
      </div>
    </div>
  </div> 
<?php if(isset($_SESSION['lk_admin'])):?>
  <nav class="material-sidebar-left-out-lightblue" data-state="open"> 
    <ul>
     <li><a href="addkass.php" class="material-btn-flat-white"><span class="m-icon mdi-cash-100 left"></span>добавить кассу</a></li>
     <li><a href="promos.php" class="material-btn-flat-white"><span class="m-icon mdi-code-equal left"></span>промокоды</a></li>
     <li><a href="admin.php" class="material-btn-flat-white"><span class="m-icon mdi-chart-bar-stacked left"></span>статистика</a></li>
     <li><a href="players.php" class="material-btn-flat-white"><span class="m-icon mdi-account-group left"></span>Игроки</a></li>
     <li><a href="logs.php" class="material-btn-flat-white"><span class="m-icon mdi-note-text left"></span>Логи</a></li>        
    </ul>
    <span class="divider"></span>
    <ul> 
      <li><a href="?admlogout" class="material-btn-flat-white"> <span class="m-icon mdi-logout-variant left"></span>Выход</a></li>
    </ul>
     <div class="footer"><div id="copy" class="footer-copyright py-3">© 2019 <a href="https://hlmod.ru/resources/lk-web-dlja-lk-ot-impulse.820/">LK WEB</a> Created by <a href="https://hlmod.ru/members/sapsan.83356/">SAPSAN</a></div></div>
  </nav>
<?php endif?>
  <div class="material-container">