<?php 
include_once 'sir.php' ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
            <form action="uyeol.php" id="register" method="post">
                <fieldset>
                    <legend>Üye Ol</legend>
                <table>
                    <tr>
                        <td>Kullanıcı Adı:</td>
                        <td><input type="text" name="nick" maxlength="50" size="50" />
                    </tr>
                    <tr>
                        <td>Ad:</td>
                        <td><input type="text" name="isim" maxlength="50" size="50" />
                    </tr>
                    <tr>
                        <td>Soyad:</td>
                        <td><input type="text" name="soyisim" maxlength="50" size="50" />
                    </tr>
                    <tr>
                        <td>Şifre:</td>
                        <td><input type="password" name="sifre" maxlength="50" size="50" />
                    </tr>
                    <tr>
                        <td>Şifre Tekrar:</td>
                        <td><input type="password" name="sifret" maxlength="50" size="50" />
                    </tr>
                    <tr>
                        <td>E-Mail:</td>
                        <td><input type="text" name="email" maxlength="50" size="50" />
                    </tr>
                    <tr>
                        <td><input type="submit" value="Üye Ol" name="sbmt" /></td>
                        <td><input type="reset" value="Temizle" />
                    </tr>
                </table>
                </fieldset>
            </form>
        </div>
    </body>
</html>