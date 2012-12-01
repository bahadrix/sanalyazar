<?php
require_once('ust.php');
if (!$MEMBER_LOGGED)
    header("Location: index.php");
else {
    ?>
    <div class="maincontainer">
        <div class="hqmenu">
            <ul>
                <li class="activehn"><a href="hq.php" class="hqmenunav">bilgilerim</a></li>
                <li><a href="#sablon" class="hqmenunav">şablonlarım</a></li>
                <li><a href="oa.php" class="hqmenunav">en çok oy alanlarım</a></li>
                <li><a href="ov.php" class="hqmenunav">oy verdiklerim</a></li>
            </ul>        
        </div>
        <div class="hqcontainer">
            <div class="hqinner">
                    <form method="post" action="hq.php">
                        <span class="baslik">Şifre</span>
                        <table class="bilgiler" cellpadding="5">
                            <tr>
                                <td><div class="tipbox sakla" style="top:-10px;">İki alan için de şu anki şifrenin doldurulması zorunludur.<div class="tail1"></div><div class="tail2"></div></div><span class="tiphandle">Şu anki şifre:*</span></td>
                                <td><input type="password" name="passnow" /></td>
                            </tr>
                            <tr>
                                <td>Yeni şifre:</td>
                                <td><input type="password" name="passnew" /></td>
                            </tr>
                            <tr>
                                <td>Yeni şifre tekrar:</td>
                                <td><input type="password" name="passnewa" /></td>
                            </tr>
                        </table>
                        <hr />
                        <span class="baslik">E-Mail</span>
                        <table class="bilgiler" cellpadding="5">
                            <tr>
                                <td>Şu anki e-mail:</td>
                                <td><input type="email" name="emailnow" /></td>
                            </tr>
                            <tr>
                                <td>Yeni e-mail:</td>
                                <td><input type="email" name="emailnew" /></td>
                            </tr>
                        </table>
                        <hr />
                        <input type="submit" class="btn btn-mini btn-success" name="degistir" value="Değiştir" />
                    </form>
                </div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $db = getPDO();
                echo '<div class="bilgidegistir">';
                if (!empty($_POST['passnow'])) {
                    $sifre = $_POST['passnow'];
                    $getsifrebilgi = $db->prepare('SELECT nick,ad,soyad,sifre,email FROM uye WHERE uid = :uid');
                    $getsifrebilgi->bindValue(':uid', $MEMBER->uid);
                    $getsifrebilgi->execute();
                    if ($getsifrebilgi->rowCount() === 0) { //ne olur ne olmaz :)
                        session_unset();
                        session_destroy();
                        header('Location: index.php?act=logged_out');
                    } else {
                        $sifrebilgi = $getsifrebilgi->fetch(PDO::FETCH_ASSOC);
                        $salt = generateSalt($sifrebilgi['nick'] . $sifrebilgi['ad'] . $sifrebilgi['soyad']);
                        $sifreh = hash('sha256', $sifre . $salt);
                        if ($sifreh !== $sifrebilgi['sifre']) {
                            echo "Şu anki şifrenizi yanlış girdiniz.";
                        } else {
                            if (!empty($_POST['passnew'])) { //şifre değiştir
                                $sifrey = $_POST['passnew'];
                                if (empty($_POST['passnewa'])) {
                                    echo "Yeni şifre tekrar alanı boş olmamalı.";
                                } else if ($sifrey === $_POST['passnow']) {
                                    echo "Eski ve yeni şifre aynı olmamalı.";
                                } else {
                                    $sifret = $_POST['passnewa'];
                                    if ($sifrey !== $sifret) {
                                        echo "Yeni girilen şifreler aynı değil.";
                                    } else if (unistrlen($sifrey) < 3 || unistrlen($sifrey) > 30) {
                                        echo "Şifre 3-30 karakter olmalı.";
                                    } else {
                                        $sifreyh = hash('sha256', $sifrey . $salt);
                                        $sifreupdate = $db->prepare('UPDATE uye SET sifre = :sifre WHERE uid = :uid');
                                        $sifreupdate->bindValue(':uid', $MEMBER->uid);
                                        $sifreupdate->bindValue(':sifre', $sifreyh);
                                        $sifreupdate->execute();
                                        echo "Şifreniz başarıyla değişti.";
                                    }
                                }
                            } //şifre değiştir bitti
                            if (!empty($_POST['emailnow'])) { //email değiştir
                                $emailnow = $_POST['emailnow'];
                                if ($emailnow !== $sifrebilgi['email'])
                                    echo "Şu anki email'inizi yanlış girdiniz.";
                                else {
                                    if (empty($_POST['emailnew'])) {
                                        echo "Yeni email adresi girmemişsiniz.";
                                    } else {
                                        $emailnew = $_POST['emailnew'];
                                        if (!filter_var($emailnew, FILTER_VALIDATE_EMAIL))
                                            echo 'Yeni girilen email adresi geçersiz.';
                                        else {
                                            $emailupdate = $db->prepare('UPDATE uye SET email = :email WHERE uid = :uid');
                                            $emailupdate->bindValue(':uid', $MEMBER->uid);
                                            $emailupdate->bindValue(':sifre', $sifreyh);
                                            $emailupdate->execute();
                                            echo "Email adresiniz başarıyla değişti.";
                                        } //geçerli email else'i
                                    } //emailnew boş değil else'i
                                } // şu anki email doğru else'i
                            } //email değiştir bitti
                            if (empty($_POST['emailnow']) && empty($_POST['passnew'])) {
                                echo "Bir işlem gerçekleşmedi.";
                            }
                        } //şu anki şifre doğru girildi else'i
                    } //kullanıcı var else'i
                } //şu anki şifre girildi else'i
                else {
                    echo "Şu anki şifre boş olmamalı.";
                }
                echo '</div>';
            } //request method if'i
            ?>
        </div>
    </div>
    <?php
}
include_once 'alt.php';
?>
