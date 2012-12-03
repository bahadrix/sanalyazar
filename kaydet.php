<?php

/*
 * Şu an anasayfada çıkan şiirleri kullanıcının, giriş yaptıktan sonra kaydedebilmesi için oluşturulmuş sayfa.
 * Daha sonra şablon yarat için de kullanılabilir. Veya başka sayfa yaparım bilemedim şimdi :)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'core/db.php';
    include_once 'uyekontrol.php';
    include_once 'classTextile.php';
    if (!$MEMBER_LOGGED)
        header('Location: index.php');
    else {
        $kaydet = false;
        if (!empty($_POST['kaydetsiir']) && !empty($_POST['kaydetbaslik'])) {
            $yazi = $_POST['kaydetsiir'];
            $baslik = $_POST['kaydetbaslik'];
            /* Textile öncesi kod:
             * Yazı kısmında Textile kullanacağımız için şimdilik gerek duymuyorum.
             * Textile ile çok deneme yapamadım, yapınca gerek duyarsak tekrar ekleriz.
              $kaydet = true;
              veritabanına kod girişini önleme çabaları:
              if (!preg_match('/^[a-zçöğüşıâî\- ]+$/i', $baslik) || !preg_match('/^(?:[a-zİÇÖĞÜŞıçöğüşâî\-,\.; ]+(?:\<br \/\>)*)+$/i', $yazi)) {
              $kaydet = false;
              }
              if ($kaydet) { */
            $kaydet = true;
            if (!preg_match('/^[a-zçöğüşıâî\- ]+$/i', $baslik)) //başlık kontrolü yeniden eklendi.
                $kaydet = false;
            if ($kaydet) {
                $link = getPDO();
                $siirekle = $link->prepare('INSERT INTO kaydedilenler (kayit,tarih,uid,baslik) VALUES (:kayit,NOW(),:uid,:baslik)');
                $siirekle->bindValue(':kayit', $yazi);
                $siirekle->bindValue(':uid', $_SESSION['uye']->uid);
                $siirekle->bindValue(':baslik', $baslik);
                $siirekle->execute();
                $getsid = $link->prepare('SELECT kid FROM kaydedilenler WHERE uid = :uid ORDER BY tarih DESC');
                $getsid->bindValue(':uid', $_SESSION['uye']->uid);
                $getsid->execute();
                $sid = $getsid->fetch(PDO::FETCH_OBJ)->kid;
            }
            if ($kaydet)
                header("Location: goster.php?id=" . $sid);
            else {
                header("Location: index.php");
            }
        }
    }
} else {
    header("Location: index.php");
}
?>
