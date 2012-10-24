<?php ob_start(); include 'sir.php' ;

$sacmalik = migirDuzelt(nl2br($we->sacmala($migir)));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/tr_TR/all.js#xfbml=1&appId=330427790354624";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>


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
        <br/><br/>
        <div class="fb-like" data-href="http://delikunduz.com" data-send="true" data-width="450" data-show-faces="true" data-font="tahoma"></div>
        <br/>
        <div class="fb-comments" data-href="http://delikunduz.com" data-num-posts="20" data-width="470"></div>
    </body>
</html>
