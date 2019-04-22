<?php
session_start();
  if (empty($_SESSION['_user']) or empty($_SESSION['id'])){
        
  }else{
      
    if (htmlspecialchars($_GET["whotsey"])=='exit')
    { 
      unset($_SESSION['_user']);
      unset($_SESSION['id']);
      session_destroy(); // разрушаем сессию
    }
  }

$dblite = new SQLite3('../db/webdb.db');

      
?>
<!--rgb(19,142,23) hsl(122, 76%, 32%) #138e17 gren -->
<!--#f25b20 rgb(242, 91, 32) hsl(17, 89%, 54%) orange-->

<!DOCTYPE html>
<html lang="en">
<head>

    <title>miror v0.3.19.23</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/font-awesome.min.css"><!--ICO-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
   <!--  <link rel="stylesheet" href="css/w3.css"> -->
    <link rel="stylesheet" href="css/templates.css"><!--TEMTLATE-->
    <link rel="stylesheet" href="css/logp.css"><!--TEMTLATE-->

</head>
<body id="mysasha" data-spy="scroll" data-target=".navbar" data-offset="1160">

<?php if (htmlspecialchars($_GET["whotsey"])=='error'){ ?>

<div>
  <div class="alert alert-danger fade in">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h3>Помилка</h3>
    <h4>Перевірте логін і пароль. Спробуйте ще :)</h4>
  </div>
</div>  

<?php } ?>

<?php if (empty($_SESSION['_user']) or empty($_SESSION['id'])){ ?> 

<!-- if not login -->
<div class="jumbotron text-center">
  <p><img src="mirorH.png"  width="219" height="219"><br></p>
  <div class="container">
      <div class="col-sm-4 col-xs-12 col-md-offset-4">
          <form  role="form" action="mod/login.php" method="post">
            <input name="login" type="text" class="form-control"  placeholder=" Логін" required autofocus>
            <input name="password" type="password" class="form-control"  placeholder="Пароль" required>
            <button class="btn" type="submit"> <i class="fa fa-sign-in" aria-hidden="true"></i> ОК</button>
            
          </form>
          
      </div>
    </div> <!-- /container -->
</div>

<?php 
  } else {  /* LOGIN IN  */
?> 

<!-- if login navigate -->
<nav class="navbar navbar-default navbar-fixed-top nav-bar-mysa">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle btn" data-toggle="collapse" data-target="#mysashaNav">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand logo-tips" href="#mysasha"><img src="mirorH.png"  width="42" height="42"></a>
    </div>
    <div class="collapse navbar-collapse" id="mysashaNav">
      <ul class="nav navbar-nav navbar-right">
        <li><a class="btn " href="?whotsey=main"> <i class="fa fa-home" aria-hidden="true"></i> головна</a></li>
       
        <li class="dropdown">
          <a  class="btn " href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart" aria-hidden="true"></i> звіти <b class="caret"></b></a>
          <ul class="dropdown-menu"> 
            <li class="dropdown-header"><i class="fa fa-info-circle" aria-hidden="true"></i> звіт за поточну зміну</li>
            <li><a class="btn " href="?whotsey=raportshift"> <i class="fa fa-pie-chart" aria-hidden="true"></i> звіт за зміну</a></li>
            <li class="dropdown-header"><i class="fa fa-info-circle" aria-hidden="true"></i> звіт по інвентаризації за попередню зміну</li>
            <li><a class="btn " href="?whotsey=inventary"> <i class="fa fa-line-chart" aria-hidden="true"></i> інвентаризація</a></li>
            <!--<li><a class="btn " href="#"> <i class="fa fa-line-chart" aria-hidden="true"></i> заявки</a></li>-->
            <!--<li><a class="btn " href="#">Action</a></li>
            <li><a class="btn " href="#">Another action</a></li>
            <li><a class="btn " href="#">Something else here</a></li>
            <li class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a class="btn " href="#">Separated link</a></li>
            <li><a class="btn " href="#">One more separated link</a></li>-->
          </ul>
        </li>

        <li><a class="btn " href="?whotsey=log"> <i class="fa fa-terminal" aria-hidden="true"></i> log</a></li>

        <li class="dropdown">
          <a  class="btn " href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-circle" aria-hidden="true"></i><?php echo ' '.$_SESSION['_user'].'  '?> <b class="caret"></b></a>
          <ul class="dropdown-menu"> 
            <li><a class="btn " href="?whotsey=exit"> <i class="fa fa-sign-out" aria-hidden="true"></i> вийти</a></li>
            <!--<li><a class="btn " href="#">Action</a></li>
            <li><a class="btn " href="#">Another action</a></li>
            <li><a class="btn " href="#">Something else here</a></li>
            <li class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a class="btn " href="#">Separated link</a></li>
            <li><a class="btn " href="#">One more separated link</a></li>-->
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class='content-bar-mysa'><!-- MARGIN -->

<?php if (htmlspecialchars($_GET["whotsey"]) == 'log'){ /* IF IS whotsey*/?>

  <?php include('mod/log.php'); /* LOAD LOG  */ ?> 

<?php } else if (htmlspecialchars($_GET["whotsey"]) == 'raportshift') {  /* MAIN IN  */ ?> 

  <?php include('mod/raport_shift.php'); /* LOAD LOG  */ ?> 

<?php } else if (htmlspecialchars($_GET["whotsey"]) == 'main') {  /* MAIN IN  */ ?> 

<!-- Container (About Section) -->
<div id="about" class="container-fluid logo-main">
  <div class="row">
    <div class="col-sm-4">
      <p><img src="mirorH.png"  width="219" height="219"><br></p>
    </div>
    <div class="col-sm-8 text-center">
      <h2>MIROR v0.3.19.23</h2><br>
      <h4 >Модуль звітності </h4><br>
      <p >Інформаційний веб модуль </p>

      <br><a class="btn" href="?whotsey=raportshift"><i class="fa fa-area-chart" aria-hidden="true"></i> звіт</a>
    </div>

  </div>
</div>

<?php } else  if (htmlspecialchars($_GET["whotsey"]) == 'inventary')  {  /* INVENTARI IN  */ ?> 

  <?php include('mod/inventary.php'); /* LOAD INVENTARY  */ ?> 

<?php } else {  /* LOGIN IN  */ ?> 

  <!-- Container (About Section) -->
  <div id="about" class="container-fluid logo-main">
    <div class="row">
      <div class="col-sm-4">
        <p><img src="mirorH.png"  width="219" height="219"><br></p>
      </div>
      <div class="col-sm-8 text-center">
        <h2>MIROR v0.3.19.23</h2><br>
        <h4 >Модуль звітності </h4><br>
        <p >Інформаційний веб модуль </p>

        <br><a class="btn" href="?whotsey=raportshift"><i class="fa fa-area-chart" aria-hidden="true"></i> звіт</a>
      </div>

    </div>
  </div>

<?php } ?>
<?php } ?>
</div>

<?php $dblite->close(); ?>


<!-- Container (Foter) -->
<footer class="foter-bar-mysa text-center">
    <a class="fix-foter" href="#mysasha" title="На початок">
      <h3 class="text-right"><i class="fa fa-chevron-up fa-2x" aria-hidden="true"></i></h3>
    </a>
    <p><a href="http://miror.biz" title="">www.miror.biz</a>  v0.3.19.23 2017г.</p>
</footer>

<!--  jquery
============================================================================================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--  bootstrap.JS
============================================================================================== -->
<script src="js/bootstrap.min.js"></script>
<!--  AngularJS
============================================================================================== -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script> -->
<!--  w3
============================================================================================== -->

<script>
$(document).ready(function(){
  // подсказка
  //$('[data-toggle="popover"]').popover();  
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#mysasha']").on('click', function(event) {
    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
  
  $(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop + 600) {
          $(this).addClass("slide");
        }
    });
  });


    $("#logfilter").click(function(){
        $("#mysafilter").modal();
    });

    $("#shiftfilter").click(function(){
        $("#mysafiltershift").modal();
    });
  
})
</script>

</body>
</html>
