<?php
/*
 * En son SQL Dump'ı: https://dl.dropbox.com/u/34922847/sequeldump/sanalyazar.sql
 */
ob_start();
include_once 'core/db.php';
include_once 'funct.php';
include_once 'uyekontrol.php';
include_once 'sir.php';
$sacmalik = migirDuzelt(nl2br($we->sacmala($migir)));
?>
?>
<!DOCTYPE html>
<html lang="tr">
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
                                    <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input name="kuladi" type="text" maxlength="25" placeholder="kullanıcı adı" style="height:30px;" /></div>
                                    <div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input name="parola" type="password" maxlength="30" placeholder="şifre" style="height:30px;" /></div>
                                    <?php
                                    /* $currentFile = $_SERVER["PHP_SELF"];
                                      $parts = Explode('/', $currentFile);
                                      $page = $parts[count($parts) - 1];
                                      if ($page == "index.php") */
                                    echo '<label class="checkbox" style="text-align:left;"><div class="tipbox">Şu an görünen şiiri kaydetmek için bu kutuyu işaretleyin.<div class="tail1"></div><div class="tail2"></div></div><input type="checkbox" name="skaydet"><span style="margin-left:-50px;padding-top:-20px;">Kaydet?</span></label>';
                                    echo '<input type="hidden" value="' . $sacmalik . '" name="ksiir" /><input type="hidden" value="' . $baslik . '" name="baslik" />';
                                    ?>
                                    <input type="submit" name="loginsubmit" value="Gir" class="btn btn-mini" /><br /><br />
                                </form>
                                <hr />
                                <a href="sifre.php">Şifremi Unuttum</a>
                            </div>
                            <li>
                                <a href="uyeol.php">üye ol</a>
                            </li>
                            <li>
                                <a class="dropdown girisyap" href="giris.php">giriş yap<b class="caret"></b></a>
                            </li>
                        <?php } else { ?>
                            <div class="upperbox userbox">
                                <p style="color:#333;">
                                    <i class="icon-user"></i>
                                    <?php echo $MEMBER->nick; ?>
                                </p>
                                <hr />
                                <p style="text-align:left; padding:0; padding-left:25px; margin:0;">
                                    <a href="hq.php">Kullanıcı Bilgileri</a><br />
                                    <a href="yeni.php">Şablon Yarat</a><br />
                                    <a href="login.php?logout">Çıkış</a>
                                </p>
                            </div>

                            <li>
                                <a href="saved.php">kaydettiklerim</a> <?php //placeholder, başka şeyler eklenebilir.      ?>
                            </li> 
                            <li>
                                <a class="dropdown girisyap" href="hq.php">ben <b class="caret"></b></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div><div class="maincontainer">
            <h1><?php echo $baslik ?></h1>
            <p><?php echo $sacmalik; ?></p>
            <?php
            if ($MEMBER_LOGGED) {
            ?>
            <div class="sharebox">
                <div class="inner">
                    <form action="kaydet.php" method="post">
                        <?php
                        echo '<input type="hidden" name="kaydetbaslik" value="'.$baslik.'" /><input type="hidden" value="' . $sacmalik . '" name="kaydetsiir" />';
                        ?>
                        <input type="submit" name="kaydetbunu" value="kaydet" class="btn btn-info btn-small" style="margin:15px 0 0 0;" />
                    </form>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
        <?php
        include_once 'alt.php';
        ?>
