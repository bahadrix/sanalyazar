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
        <?php
            $uyeoldu = false; //formu tekrar gösterelim mi? false = evet.
            $nick = "";$ad="";$soyad="";$email="";
            if (!empty($_POST['nick']) && !empty($_POST['sifre']) && !empty($_POST['sifret']) && !empty($_POST['email']) && !empty($_POST['isim']) && !empty($_POST['soyisim'])) {
                $nick = $_POST['nick'];
                $sifre = $_POST['sifre'];
                $sifret = $_POST['sifret'];
                $email = $_POST['email'];
                $ad = $_POST['isim'];
                $soyad = $_POST['soyisim'];
                
                if (strlen($nick)<3 || strlen($nick)>25)
                    $nickErr = "Kullanıcı Adı 3-25 karakter arası olmalı.";
                else if (!preg_match("/^([a-zşçüıöğ0-9]+\s?)*$/i", $nick))
                    $nickErr = 'Kullanıcı Adı harf ve rakamlardan oluşmalı.';
                
                if (!filter_var($email,FILTER_VALIDATE_EMAIL))
                    $emailErr = 'Geçersiz email adresi';
                
                if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/',$ad))
                    $adErr = 'İsim sadece harf ve boşluk içerebilir';
                
                if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+)$/',$soyad))
                    $soyadErr = 'Soyisim sadece harf içerebilir';
                
                if ($sifret != $sifre) 
                    $sifretErr = "Şifreler aynı olmalı";
                
                if (strlen($sifre) < 3 || strlen($sifre) > 30)
                    $sifreErr = 'Şifre 3-30 karakter olmalı';
                
                if (empty($nickErr) && empty($emailErr) && empty($adErr) && empty($soyadErr) && empty($sifretErr) && empty($sifreErr)) {
                    $dblink = getPDO();
                    $nickkontrol = $dblink->prepare('SELECT nick FROM uye WHERE nick=:nick');
                    $nickkontrol -> bindValue(':nick',$nick,PDO::PARAM_STR);
                    $nickkontrol -> execute();
                    if ($nickkontrol->rowCount() > 0) 
                        $nickErr = $nick.' kullanıcı adı daha önceden alınmış.';
                    else {
                        $emailkontrol = $dblink->prepare('SELECT email FROM uye WHERE email=:email');
                        $emailkontrol -> bindValue(':email',$email,PDO::PARAM_STR);
                        $emailkontrol -> execute();
                        if ($emailkontrol->rowCount()>0) 
                            $emailErr = 'E-Mail adresi daha önceden alınmış.';
                        else {
                            $salt = generateSalt($nick . $ad . $soyad);
                            $sifreh = hash('sha256',$sifre . $salt);
                            $uyeet = $dblink -> prepare('INSERT INTO uye (sifre,nick,ad,soyad,email,tarih) VALUES (:sifreh,:nick,:ad,:soyad,:email,NOW())');
                            $uyeet -> bindValue(':sifreh',$sifreh);
                            $uyeet -> bindValue(':nick',$nick);
                            $uyeet -> bindValue(':ad',$ad);
                            $uyeet -> bindValue(':soyad',$soyad);
                            $uyeet -> bindValue(':email',$email);
                            if ($uyeet -> execute()) {
                                $uyeoldu = true;
                                echo 'Üyeliğiniz açıldı. Lütfen email adresinize gönderdiğimiz aktivasyon mailini onaylayıp giriş yapınız.<br />10 saniye içinde anasayfaya yönlendiriliceksiniz.';
                                echo "<meta http-equiv='refresh' content='10;url=index.php'>";
                            }
                            else {
                                if (DEBUG_MODE) {
                                    print_r($uyeet->errorInfo());
                                    echo "<br />";
                                }
                                echo 'Hata oluştu. Lütfen tekrar deneyin';
                            }
                        }
                    }
                }
            }
            else if ($_SERVER["REQUEST_METHOD"] == "POST") { //form gönderildiyse ve ilk if'e girmediyse -- bütün alanlar doldurulmadıysa: 
                $nick = $_POST['nick'];
                $email = $_POST['email'];
                $ad = $_POST['isim'];
                $soyad = $_POST['soyisim'];
                
                if (strlen($nick)<3 || strlen($nick)>25)
                    $nickErr = "Kullanıcı Adı 3-25 karakter arası olmalı.";
                else if (!preg_match("/^([a-zşçüıöğ0-9]+\s?)*$/i", $nick))
                    $nickErr = 'Kullanıcı Adı harf ve rakamlardan oluşmalı.';
                
                if (!filter_var($email,FILTER_VALIDATE_EMAIL))
                    $emailErr = 'Geçersiz email adresi';
                
                if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/',$ad))
                    $adErr = 'İsim sadece harf ve boşluk içerebilir';
                
                if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+)$/',$soyad))
                    $soyadErr = 'Soyisim sadece harf içerebilir';
                
                $butunAlanlar = "Bütün alanların doldurulması zorunludur.";
            }
            if (!$uyeoldu) {
        ?>
            <form action="uyeol.php" id="register" method="post">
                <fieldset>
                    <legend>Üye Ol</legend>
                    <?php if (!empty($butunAlanlar)) {
                      echo '<span class="label label-important">'.$butunAlanlar.'</span>';
                    }
                    ?>
                <table>
                    <tr>
                        <td>Kullanıcı Adı:</td>
                        <td><input type="text" name="nick" maxlength="25" size="50" value="<?php echo $nick; ?>" /><?php if (!empty($nickErr)) { ?><span class="label label-important"><?php echo $nickErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td>Şifre:</td>
                        <td><input type="password" name="sifre" maxlength="30" size="50" /><?php if (!empty($sifreErr)) { ?><span class="label label-important"><?php echo $sifreErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td>Şifre Tekrar:</td>
                        <td><input type="password" name="sifret" maxlength="30" size="50" /><?php if (!empty($sifretErr)) { ?><span class="label label-important"><?php echo $sifretErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td>E-Mail:</td>
                        <td><input type="text" name="email" maxlength="100" size="50" value="<?php echo $email; ?>" /><?php if (!empty($emailErr)) { ?><span class="label label-important"><?php echo $emailErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td>Ad:</td>
                        <td><input type="text" name="isim" maxlength="30" size="50" value="<?php echo $ad; ?>" /><?php if (!empty($adErr)) { ?><span class="label label-important"><?php echo $adErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td>Soyad:</td>
                        <td><input type="text" name="soyisim" maxlength="30" size="50" value="<?php echo $soyad; ?>" /><?php if (!empty($soyadErr)) { ?><span class="label label-important"><?php echo $soyadErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Üye Ol" name="sbmt" /></td>
                        <td><input type="reset" value="Temizle" />
                    </tr>
                </table>
                </fieldset>
            </form>
            <?php
            } // if !üyeol
            ?>
        </div>
    </body>
</html>