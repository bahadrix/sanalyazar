<?php
include_once 'ust.php';
if ($MEMBER_LOGGED) {
    header("Location: index.php");
}
else {
?>

<div class="maincontainer">
    <?php
    $uyeoldu = false; //formu tekrar gösterelim mi? false = evet.
    if (!empty($_POST['nick']) && !empty($_POST['sifre']) && !empty($_POST['sifret']) && !empty($_POST['email']) && !empty($_POST['isim']) && !empty($_POST['soyisim'])) {
        $nick = $_POST['nick'];
        $sifre = $_POST['sifre'];
        $sifret = $_POST['sifret'];
        $email = $_POST['email'];
        $ad = $_POST['isim'];
        $soyad = $_POST['soyisim'];

        if (strlen($nick) < 3 || strlen($nick) > 25)
            $nickErr = "Kullanıcı Adı 3-25 karakter arası olmalı.";
        else if (!preg_match("/^(?:[a-zşçüıöğ0-9_-]+\s?)*$/i", $nick))
            $nickErr = 'Kullanıcı Adı sadece harf, rakam, boşluk ve _- içerebilir.';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $emailErr = 'Geçersiz email adresi';

        if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/', $ad))
            $adErr = 'İsim sadece harf ve boşluk içerebilir<br />Baş harfler büyük olmalıdır.';

        if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/', $soyad))
            $soyadErr = 'Soyisim sadece harf içerebilir<br />Baş harfler büyük olmalıdır.';

        if ($sifret != $sifre)
            $sifretErr = "Şifreler aynı olmalı";

        if (strlen($sifre) < 3 || strlen($sifre) > 30)
            $sifreErr = 'Şifre 3-30 karakter olmalı';

        if (empty($nickErr) && empty($emailErr) && empty($adErr) && empty($soyadErr) && empty($sifretErr) && empty($sifreErr)) {
            $dblink = getPDO();
            $nickkontrol = $dblink->prepare('SELECT nick FROM uye WHERE nick=:nick');
            $nickkontrol->bindValue(':nick', $nick, PDO::PARAM_STR);
            $nickkontrol->execute();
            if ($nickkontrol->rowCount() > 0)
                $nickErr = $nick . ' kullanıcı adı daha önceden alınmış.';
            else {
                $emailkontrol = $dblink->prepare('SELECT email FROM uye WHERE email=:email');
                $emailkontrol->bindValue(':email', $email, PDO::PARAM_STR);
                $emailkontrol->execute();
                if ($emailkontrol->rowCount() > 0)
                    $emailErr = 'E-Mail adresi daha önceden alınmış.';
                else {
                    $salt = generateSalt($nick . $ad . $soyad);
                    $sifreh = hash('sha256', $sifre . $salt);
                    $uyeet = $dblink->prepare('INSERT INTO uye (sifre,nick,ad,soyad,email,tarih,aktif) VALUES (:sifreh,:nick,:ad,:soyad,:email,NOW(),1)'); //aktif şimdilik 1
                    $uyeet->bindValue(':sifreh', $sifreh);
                    $uyeet->bindValue(':nick', $nick);
                    $uyeet->bindValue(':ad', $ad);
                    $uyeet->bindValue(':soyad', $soyad);
                    $uyeet->bindValue(':email', $email);
                    if ($uyeet->execute()) {
                        $uyeoldu = true;
                        echo 'Üyeliğiniz açıldı. Lütfen email adresinize gönderdiğimiz aktivasyon mailini onaylayıp giriş yapınız.<br />10 saniye içinde anasayfaya yönlendiriliceksiniz.';
                        echo "<meta http-equiv='refresh' content='10;url=index.php'>";
                    } else {
                        if (DEBUG_MODE) {
                            print_r($uyeet->errorInfo());
                            echo "<br />";
                        }
                        echo 'Hata oluştu. Lütfen tekrar deneyin.';
                    }
                }
            }
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") { //form gönderildiyse ve ilk if'e girmediyse -- bütün alanlar doldurulmadıysa: 
        $nick = $_POST['nick'];
        $email = $_POST['email'];
        $ad = $_POST['isim'];
        $soyad = $_POST['soyisim'];

        if (strlen($nick) < 3 || strlen($nick) > 25)
            $nickErr = "Kullanıcı Adı 3-25 karakter arası olmalı.";
        else if (!preg_match("/^(?:[a-zşçüıöğ0-9_-]+\s?)*$/i", $nick))
            $nickErr = 'Kullanıcı Adı sadece harf, rakam, boşluk ve _- içerebilir.';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $emailErr = 'Geçersiz email adresi';

        if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/', $ad))
            $adErr = 'İsim sadece harf ve boşluk içerebilir<br />Baş harfler büyük olmalıdır.';

        if (!preg_match('/^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/', $soyad))
            $soyadErr = 'Soyisim sadece harf içerebilir<br />Baş harfler büyük olmalıdır.';
    }
    if (!$uyeoldu) {
        ?>
        <form action="uyeol.php" id="register" method="post">
            <fieldset>
                <legend>Üye Ol</legend>
                <span class="alert alert-info alertfont"><strong>Uyarı!</strong> Bütün alanların doldurulması zorunludur.</span>
                <table id="uyetablo">
                    <tr>
                        <td>Kullanıcı Adı:</td>
                        <td><input type="text" name="nick" maxlength="25" size="50" <?php if (!empty($nick)) { ?>value="<?php echo $nick; ?>" <?php } ?> /><?php if (!empty($nickErr)) { ?><span class="label label-important"><?php echo $nickErr ?></span><?php } ?>
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
                        <td><input type="text" name="email" maxlength="100" size="50" <?php if (!empty($email)) { ?>value="<?php echo $email; ?>" <?php } ?> /><?php if (!empty($emailErr)) { ?><span class="label label-important"><?php echo $emailErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td>Ad:</td>
                        <td><input type="text" name="isim" maxlength="30" size="50" <?php if (!empty($ad)) { ?>value="<?php echo $ad; ?>" <?php } ?> /><?php if (!empty($adErr)) { ?><span class="label label-important"><?php echo $adErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td>Soyad:</td>
                        <td><input type="text" name="soyisim" maxlength="30" size="50" <?php if (!empty($soyad)) { ?>value="<?php echo $soyad; ?>" <?php } ?> /><?php if (!empty($soyadErr)) { ?><span class="label label-important"><?php echo $soyadErr ?></span><?php } ?>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Üye Ol" name="sbmt" class="btn btn-info btn-small" /></td>
                        <td><input type="reset" value="Temizle" class="btn btn-small" />
                    </tr>
                </table>
            </fieldset>
        </form>
        <?php
    } // if !üyeol
    ?>
</div>
<?php
}
include_once 'alt.php';
?>