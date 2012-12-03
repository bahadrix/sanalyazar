<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'core/db.php';
    include_once 'uyekontrol.php';
    include_once 'core/lingustian.php';
    include_once 'wordengine.php';
    if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
        $sid = $_REQUEST['id'];
        $db = getPDO();
        $sablonal = $db->prepare('SELECT sid,baslik,sablon FROM sablon WHERE sid = :sid AND uid = :uid');
        $sablonal -> bindValue(':sid', $sid);
        $sablonal -> bindValue(':uid',$MEMBER->uid);
        $sablonal -> execute();
        if ($sablonal->rowCount()===0) {
            echo "Böyle bir kayıt bulunamadı.";
        }
        else {
            $sbln = $sablonal->fetch(PDO::FETCH_ASSOC);
            $we = new wordEngine();
            $yazi = $we->sacmala($sbln['sablon']); //Böyle ürettiğimiz için kelimeleri veritabanından çekiyorsak baş harfler büyük olmayacak ana sayfadaki gibi. Bir çare sonra düşünürüz. WordEngine'den halletmek lazım.
            $yazikontrol = $db->prepare('SELECT kid,kayit FROM kaydedilenler WHERE kayit=:kayit');
            $yazikontrol -> bindValue(':kayit',$yazi);
            $yazikontrol -> execute();
            if ($yazikontrol->rowCount()>0) { //Şu an çalıştıramadım niyeyse.
                $varid = $yazikontrol->fetch(PDO::FETCH_OBJ)->kid;
                echo 'Bu yazı daha önce eklenmiş. <a href="goster.php?id='.$varid.'">Buradan buyrun</a>';
            }
            else {
                $yaziekle = $db->prepare('INSERT INTO kaydedilenler (kayit,tarih,uid,baslik,sablonid) VALUES (:kayit,NOW(),:uid,:baslik,:sablonid)');
                $yaziekle -> bindValue(':kayit',$yazi);
                $yaziekle -> bindValue(':uid',$MEMBER->uid);
                $yaziekle -> bindValue(':baslik',$sbln['baslik']);
                $yaziekle -> bindValue(':sablonid',$sbln['sid']);
                $yaziekle -> execute();
                $getsid = $db->prepare('SELECT kid FROM kaydedilenler WHERE uid = :uid AND sablonid = :sablonid ORDER BY tarih DESC');
                $getsid->bindValue(':uid', $_SESSION['uye']->uid);
                $getsid->bindValue(':sablonid',$sbln['sid']);
                $getsid->execute();
                $sid = $getsid->fetch(PDO::FETCH_OBJ)->kid;
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo "goster.php?id=$sid";
                }
                else {
                    header("Location: goster.php?id=$sid");
                }
            }
        }
    }
    else {
        echo "Geçersiz Id";
    }
}
else {
    echo "ı ıh";
}
?>

