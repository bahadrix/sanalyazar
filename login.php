<?php

/**
 * Login ve logout işlemlerini gerçekleştirir.
 * Login de hata olursa (yanlış şifre vb) hata mesajlarını döndürür.
 * Giriş başarılı olursa "OK" döndürür. Bu durumda mevcut sayfanın refresh edilmesi yeterlidir.
 * 
 * Bi ara brute force önlemi alınmalı
 * 
 * O an görünen şiiri kaydetme özelliği eklenecek.
 * 
 * @version 0.1 
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['kuladi']) || empty($_POST['parola'])) {
        echo '<span class="label label-warning">Bütün alanlar doldurulmalı.</span>';
    } else {
        include_once 'core/db.php';
        include_once 'funct.php';

        $kuladi = $_POST['kuladi'];
        $parola = $_POST['parola'];

        $link = getPDO();

        $st = $link->prepare('SELECT * FROM uye WHERE nick=:nick');
        $st->bindValue(':nick', $kuladi);
        $st->execute();

        if (!$st->rowCount()) {
            echo '<span class="label label-warning">Böyle bir kullanıcı adı bulunamadı.</span>';
        } else {
            $uye = new memberModel($st);
            $salt = generateSalt($uye->nick . $uye->ad . $uye->soyad);
            $hash = hash('sha256', $parola . $salt);
            if ($uye->sifre != $hash) {
                echo '<span class="label label-warning">Yanlış şifre.</span>';
            } else {
                $upd = $link->prepare('UPDATE uye SET son_online = NOW() WHERE uid = :uid');
                $upd->bindValue(':uid', $uye->uid);
                $upd->execute();

                session_cache_expire(30);
                session_start();
                $_SESSION['logged'] = true;
                $_SESSION['uye'] = $uye;
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo "OK"; //sayfayı javascriptten yenile.
                }
                else
                    header("Location: index.php");
            }
        }
    }
}
else if (isset($_REQUEST['logout'])) {
    session_start();
    session_destroy();
    header("Location: index.php?act=logged_out");
}
?>
