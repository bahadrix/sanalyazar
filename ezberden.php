<?php ob_start(); 

include 'core/db.php';
include 'core/lingustian.php';
include 'wordengine.php';




$we = new WordEngine();

$baslik = "asd";

$migir = <<<MGR
{sıfat} {isim}-lerin hepsi {kfiil}-ecek
[Gün gelecek|Devran dönecek|İşte o zaman|Bir gün|O geldiğinde|Yarın|Elbet bir gün] {isim}-ler [bir bir|teker teker|birer birer|peyder pey|paşa paşa] {kfiil}-ecek
{isim}-e [giren|sokulan|soktuğum|koyduğum|verdiğim|kerttiğim] {isim} olmasa da,
Bu {isim}-ler [Gün gelecek|Devran dönecek|İşte o zaman|Bir gün|O geldiğinde|Yarın|Elbet bir gün] {kfiil}-ecek

Belki {kfiil}-mese-de {isim}-ler, {kfiil}-mese-de {isim}-ler; {isim}-ler
[Gün gelecek|Devran dönecek|İşte o zaman|Bir gün|O geldiğinde|Yarın|Elbet bir gün], bu {isim}-ler {kfiil}-ecek, {kfiil}-ecek

{kfiil}-meli [belkide|aslında|gerçektende|hiç değilse|en azından|hiç yoktan] bu {isim}-leri, {kfiil}-meli
{kfiil}-meli {sıfat} {isim}-lerine, {sıfat} {isim}-lerine
[belkide|aslında|gerçektende|hiç değilse|en azından|hiç yoktan] {sıfat} {isim}-lerini, {sıfat} {isim}-lerini {kfiil}-meli
inilemeli {isim}-lerini, inilemeli {isim}-lerini
MGR;

$migir2 = <<<MGR


MGR;
$migir = trim($migir);

// test


$params['k'] = isset($_REQUEST['k']) ? $_REQUEST['k'] : array() ;
$params['s'] = isset($_REQUEST['s']) ? $_REQUEST['s'] : array() ;
$params['i'] = isset($_REQUEST['i']) ? $_REQUEST['i'] : array() ;
$params['z'] = isset($_REQUEST['z']) ? $_REQUEST['z'] : array() ;
$params['e'] = isset($_REQUEST['e']) ? $_REQUEST['e'] : array() ;
$params['p'] = isset($_REQUEST['p']) ? $_REQUEST['p'] : array() ;



$sacmalik = nl2br($we->ezbereSacmala($migir, $params));

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# delikunduz: http://ogp.me/ns/fb/delikunduz#">
  <meta property="fb:app_id"      content="330427790354624" /> 
  <meta property="og:type"        content="delikunduz:kunduzname" /> 
  <meta property="og:url"         content="http://delikunduz.com" /> 
  <meta property="og:title"       content="<?php echo $baslik ?>" /> 
  <meta property="og:description" content="<?php echo $sacmalik ?>" /> 
  <meta property="og:image"       content="http://delikunduz.com/img/kunduz.jpg" /> 
  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>delikunduz</title>
<link href='http://fonts.googleapis.com/css?family=Dosis&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <style>
      body {
        font-family: 'Dosis', sans-serif;
        font-size: 1.2em;
      	text-align: center;
      	text-shadow: 4px 4px 4px #eee;
      }
    </style>
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

<h1><?php echo $baslik?></h1>
<?php echo $sacmalik; ?>
<br/><br/>
<div class="fb-like" data-href="http://delikunduz.com" data-send="true" data-width="450" data-show-faces="true" data-font="tahoma"></div>
<br/>
<div class="fb-comments" data-href="http://delikunduz.com" data-num-posts="20" data-width="470"></div>
</body>
</html>
