<?php ob_start(); include 'sir.php' ;

$sacmalik = migirDuzelt(nl2br($we->sacmala($migir)));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>sanalyazar - post modern şiir üretici</title>
	<link href='http://fonts.googleapis.com/css?family=Dosis&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class="nav">
                        <span class="active"><a href="index.php" class="brand">sanalyazar</a></span>
                    </div>
                    <ul class="nav pull-right"><li><a href="uyeol.php">üye ol</a></li><li><a href="javascript:void(0);">giriş yap</a></li></ul>
                </div>
            </div>
        </div>
        <div class="maincontainer">
            <h1><?php echo $baslik?></h1>
            <?php echo $sacmalik; ?>
        </div>
    </body>
</html>
