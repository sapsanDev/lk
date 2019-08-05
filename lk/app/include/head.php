<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <title><?=$title?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Пополнение баланса на игровых серверах">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="author" content="SAPSAN">
  <meta name="keywords" content="lk, личный кабинет, лк, автодонат, csgo, css, counter-strike, auto donate">
  <meta name="robots" content="index, follow">
  <link rel="shortcut icon" href="pub/img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="pub/css/prel.css">
  <link rel="stylesheet" href="pub/css/main.css">
  <link rel="stylesheet" href="pub/css/bootstrap.css">
  <script src="pub/js/modernizr.js"></script>
  <script src="pub/js/jquery.min.js"></script>
  <script src="pub/js/popper.js"></script>
  <script src="pub/js/bootstrap.js"></script>
  <script src="pub/js/framaterial.js"></script>
  <script src="pub/js/form.js"></script>
  <script src="pub/js/sweetalert2.all.js"></script> 
  <script> $(window).load(function(){$('.prel').find('div').fadeOut().end().delay(0.1).fadeOut('slow');});</script>
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
        <a href="javascript:void(0)" class="icon-menu" data-toggle="sidebar"><span class="m-icon mdi-menu"></span></a>
        <a href="/lk" class="brand-logo fm">Personal Area</a>
      </div>
    </div>
  </div> 
  <nav class="material-sidebar-left-out-lightblue" data-state="open"> 
 <?php if(!empty($_SESSION['steam_id'])):?> 
    <header data-image-url="https://puu.sh/difqU/fe10904560.png">
      <img class="img-circule" src="<?=$_SESSION['avatar_player']?>">
      <div class="material-balance-state"><span class="m-icon mdi-coins left"></span> БАЛАНС:<div class="material-balance"><?=$balans?><span class="m-icon mdi-currency-rub"></span></div></div>
      <div class="material-name-player"><?=$_SESSION['name_player']?></div>
    </header>
    <ul id="accordion">
     <li><a href="pays.php" class="material-btn-flat-white"><span class="m-icon mdi-coin left"></span>мои платежи</a></li>
     <li><a href="top.php" class="material-btn-flat-white"><span class="m-icon mdi-arrow-up-bold-hexagon-outline left"></span>Топ донатеров</span></a></li>
     <li><a href="?logout" class="material-btn-flat-white"><span class="m-icon mdi-logout left"></span>Выход</a></li>        
    <?php else:?>
      <ul> 
      <li><a href="?login" class="material-btn-flat-white"><span class="m-icon mdi-login left"></span>Авторизация <span class="m-icon mdi-steam"></span></a></li>
      <li><a href="top.php" class="material-btn-flat-white"><span class="m-icon mdi-arrow-up-bold-hexagon-outline left"></span>Топ донатеров</a></li>
      <li><a class="material-btn-flat-white"  id="infopriv" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12"><span class="m-icon mdi-information left"></span>ИНФО О ДОНАТЕ</a></li>
      <li id="collapse12" class="collapse" aria-labelledby="infopriv" data-parent="#accordion">
        <span class="divider"></span>
        <a href="#" class="material-btn-flat-white"><span class="m-icon mdi-star left"></span>VIP </span></a>
        <a href="#" class="material-btn-flat-white"><span class="m-icon mdi-star left"></span>ADMIN </span></a>
      </li>
      <li><a class="material-btn-flat-white"  id="contacts" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13"><span class="m-icon mdi-contacts left"></span>контакты</a></li>
      <li id="collapse13" class="collapse" aria-labelledby="contacts" data-parent="#accordion">
        <span class="divider"></span>
        <a href="skype:#?chat"><span class="m-icon mdi-skype left"></span>Skype</a>
        <a href="https://vk.com"><span class="m-icon mdi-vk-circle left"></span>Vk</a>
        <a href="#"><span class="m-icon mdi-telegram left"></span>Telegram</a>
        <a href="tel:#"><span class="m-icon mdi-phone-classic left"></span>Phone</a>
      </li>
    <?php endif?>
    </ul>
    <span class="divider"></span>
    <ul> 
      <li><a href="admin.php" class="material-btn-flat-white"> <span class="m-icon mdi-view-dashboard left"></span>Админ панель</a></li>
    </ul>
     <div class="footer"><a href="//www.free-kassa.ru/"><img style="width: 0;" src="//www.free-kassa.ru/img/fk_btn/17.png"></a>
      <div id="copy" class="footer-copyright py-3">© 2019 <a href="https://hlmod.ru/resources/lk-web-dlja-lk-ot-impulse.820/">LK WEB</a> Created by <a href="https://hlmod.ru/members/sapsan.83356/">SAPSAN</a></div></div>
  </nav>
  <div class="material-container">