<?php

/**
 * Login ve logout işlemlerini gerçekleştirir.
 * Login de hata olursa (yanlış şifre vb) hata mesajlarını döndürür.
 * Giriş başarılı olursa "OK" döndürür. Bu durumda mevcut sayfanın refresh edilmesi yeterlidir.
 * Kaydet kutusu tiklendiyse "ok{id}" döndürür. 
 * 
 * Bi ara brute force önlemi alınmalı
 * 
 * 
 * @version 0.21
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
                echo '<span class="label label-warning">Yanlış kullanıcı adı veya şifre.</span>';
            } else {
                $upd = $link->prepare('UPDATE uye SET son_online = NOW() WHERE uid = :uid');
                $upd->bindValue(':uid', $uye->uid);
                $upd->execute();

                session_regenerate_id();
                session_cache_expire(30);
                session_start();
                $_SESSION['logged'] = true;
                $_SESSION['uye'] = $uye;

                /*
                 * Kullanıcı için unique, IP adresi ve tarayıcıdan oluşan bir
                 * imza yarat. Kullanıcı girişi yapmış kullanıcıların 
                 * authentication'ında kullanılıcak. Başka IP adresi veya
                 * tarayıcıyla girmeye çalışan bir session'ın invalid olduğunu
                 * anlamamızı sağlayacak.
                 */
                $str = generateRandomString();
                $ref = $_SERVER["REMOTE_ADDR"];
                $agent = $_SERVER["HTTP_USER_AGENT"];
                $hashed = hash("sha256", $ref . $str . $agent);
                $saltedhash = $str . $hashed;
                $_SESSION['signature'] = $saltedhash;
                /* -- SIGNATURE END -- */
                
                $_SESSION['son_islem'] = time();
                $kaydet = false;
                if (!empty($_POST['skaydet']) && $_POST['skaydet'] === "on") {
                    $yazi = $_POST['ksiir'];
                    $baslik = $_POST['baslik'];
                    $kaydet = true;
                    //veritabanına kod girişini önleme çabaları:
                    if (!preg_match('/^[a-zçöğüşıâî\-]+$/i', $baslik) || !preg_match('/^(?:[a-zİÇÖĞÜŞıçöğüşâî\-,\.; ]+(?:\<br \/\>)*)+$/i',$yazi)) {
                        $kaydet = false;
                    }
                    if ($kaydet) {
                        $siirekle = $link->prepare('INSERT INTO kaydedilenler (kayit,tarih,uid,baslik) VALUES (:kayit,NOW(),:uid,:baslik)');
                        $siirekle -> bindValue(':kayit',$yazi);
                        $siirekle -> bindValue(':uid',$_SESSION['uye']->uid);
                        $siirekle -> bindValue(':baslik',$baslik);
                        $siirekle -> execute();
                        $getsid = $link -> prepare('SELECT kid FROM kaydedilenler WHERE uid = :uid ORDER BY tarih DESC');
                        $getsid -> bindValue(':uid',$_SESSION['uye']->uid);
                        $getsid -> execute();
                        $sid = $getsid->fetch(PDO::FETCH_OBJ)->kid;
                    }
                }

                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    //sayfayı javascriptten yenile.
                    if ($kaydet) {
                        echo "ok".$sid;
                    }
                    else
                        echo "OK"; 
                }
                else {
                    if ($kaydet)
                        header("Location: goster.php?id=".$sid);
                    else
                        header("Location: index.php");
                }
            }
        }
    }
}
else if (isset($_REQUEST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php?act=logged_out");
}
?>
