<?php 
include_once 'sir.php' ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# delikunduz: http://ogp.me/ns/fb/delikunduz#">
	<meta property="fb:app_id"      content="330427790354624" /> 
	<meta property="og:type"        content="delikunduz:kunduzname" /> 
	<meta property="og:url"         content="http://delikunduz.com" /> 
	<meta property="og:title"       content="<?php echo $baslik ?>" /> 
	<meta property="og:description" content="post modern şiir üretici" /> 
	<meta property="og:image"       content="http://delikunduz.com/img/kunduz.jpg" /> 
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>sanalyazar - post modern şiir üretici</title>
	<link href='http://fonts.googleapis.com/css?family=Dosis&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-24091096-4']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

	</script>
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