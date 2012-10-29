<?php
ob_start();
include_once 'core/db.php';
include_once 'uyekontrol.php';
include_once 'funct.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>sanalyazar - post modern şiir üretici</title>
        <link href='http://fonts.googleapis.com/css?family=Dosis&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/site.js"></script>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class="nav">
                        <span class="active"><a href="index.php" class="brand">sanalyazar</a></span>
                    </div>
                    <ul class="nav pull-right">
                        <?php if (!$MEMBER_LOGGED) { ?>
                        <div class="upperbox loginbox">
                            <p style="color:#333;">Giriş</p>
                            <form action="login.php" method="post" name="loginform">
                                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input name="kuladi" type="text" maxlength="25" placeholder="kullanıcı adı" /></div>
                                <div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input name="parola" type="password" maxlength="30" placeholder="şifre" /></div>
                                <input type="submit" name="loginsubmit" value="Gir" class="btn btn-mini" /><br /><br />
                            </form>
                            <hr />
                            <a href="sifre.php">Şifremi Unuttum</a>
                        </div>
                            <?php } else { ?>
                        <div class="upperbox userbox">
                            <p style="color:#333;"><?php echo $MEMBER->nick; ?></p><hr /><p style="text-align:left; padding:0; padding-left:25px; margin:0;"><a href="hq.php">Kullanıcı Bilgileri</a><br /><a href="yeni.php">Mıgır Yarat</a><br /><a href="login.php?logout">Çıkış</a></p>
                        </div>
                            <?php } ?>
                        <?php
                        if ($MEMBER_LOGGED) {
                        ?>
                        <li>
                            <a href="uyeol.php">kaydettiklerim</a> <?php //placeholder, başka şeyler eklenebilir. ?>
                        </li> 
                        <li>
                            <a class="dropdown girisyap" href="javascript:void(0);">ben<b class="caret"></b></a>
                        </li>                                               
                        <?php 
                        }
                        else {
                        ?>                        
                        <li>
                            <a href="uyeol.php">üye ol</a>
                        </li>
                        <li>
                            <a class="dropdown girisyap" href="javascript:void(0);">giriş yap<b class="caret"></b></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>