<?php
include_once 'ust.php';
?>
<div class="maincontainer">
    <?php
    $db = getPDO();
    $gecersizid = false;
    if (empty($_REQUEST['id'])) {
        /* ORDER BY RAND() kullanmama çabaları */
        $randomid = $db->prepare('SELECT MAX(kid) AS maxid FROM kaydedilenler');
        $randomid->execute();
        if ($randomid->rowCount() === 0)
            echo "Şu ana kadar kimse şiir kaydetmemiş. Ayıp!";
        else {
            $rid = $randomid->fetch(PDO::FETCH_OBJ)->maxid;
            $randomyazi = $db->prepare('SELECT * FROM kaydedilenler WHERE kid = :id');
            do {
                /* max id ve 0 arası bir sayı üret, üretilen sayı için çalışan 
                 * sorgu 1 satırı etki ettiyse onu al, yoksa bir daha 
                 * çalıştır.
                 */
                $rnd = mt_rand(0, $rid);
                $randomyazi->bindValue(':id', $rnd);
                $randomyazi->execute();
            } while ($randomyazi->rowCount() === 0);
            $siir = $randomyazi->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        $id = $_REQUEST['id'];
        if (preg_match('/^\d+$/', $id)) {
            $randomyazi = $db->prepare('SELECT * FROM kaydedilenler WHERE kid = :id');
            $randomyazi->bindValue(':id', $id);
            $randomyazi->execute();
            if ($randomyazi->rowCount() === 0) {
                $gecersizid = true; // gösterme kısmına ilerlemesin.
                echo "Bu id ile kayıtlı şiir bulunamadı.";
            } else {
                $siir = $randomyazi->fetch(PDO::FETCH_ASSOC);
            }
        } else {
            $gecersizid = true;
            echo "Geçersiz Id.";
        }
    }
    if (!$gecersizid) {
        /* TWITTER BİLGİLERİ */
        $twitter_kullanici_adi = ""; //@bıdıbıdı
        $twitter_adres_ve_kullaniciadi = "http://www.delikunduz.com/goster.php?id=".$siir['kid']." - ".$twitter_kullanici_adi;
        $ttxtl = unistrlen($twitter_adres_ve_kullaniciadi);
        if (unistrlen($siir['baslik'])>=140-$ttxtl) {
            $bslk = substr_unicode($siir['baslik'],0,140-$ttxtl-4)."...";
        }
        else {
            $bslk = $siir['baslik'];
        }
        $twitter_text = $bslk ." ".$twitter_adres_ve_kullaniciadi; 
        /* TWITTER BİLGİLERİ BİTTİ */
        /* FACEBOOK BİLGİLERİ */
        $furl = 'http://www.delikunduz.com/goster.php?id='.$siir['kid'];
        $fbaslik = $siir['baslik'];
        $fozettext = preg_replace('/<br\s*\/?>/',' ',$siir['kayit']);
        $fozet = substr_unicode(strip_tags($fozettext),0,256);
        $fimg = "http://localhost/sanalyazar/img/kunduz.jpg";
        $fttl = "http://www.facebook.com/sharer.php?s=100&p[url]=".urlencode($furl)."&p[title]=".urlencode($fbaslik)."&p[summary]=".urlencode($fozet)."&p[images][0]=$fimg";
        /* FACEBOOK BİLGİLERİ BİTTİ */
        
        $getusername = $db -> prepare('SELECT nick FROM uye WHERE uid = :uid');
        $getusername -> bindValue(':uid',$siir['uid']);
        $getusername -> execute();
        if ($getusername -> rowCount()===0)
            $nick = "";
        else 
            $nick = $getusername->fetch(PDO::FETCH_OBJ)->nick;
        ?>
        <h1><?php echo $siir['baslik']; ?></h1>
        <p><?php echo $siir['kayit']; ?></p>
        <div class="sharebox">
            <div class="inner">
                <ul class="share">
                    <li><?php echo '<a target="_blank" rel="nofollow" href="https://twitter.com/intent/tweet?text='.urlencode($twitter_text).'" title="twitter\'da paylaş"><img src="img/twittermini.png" /></a>'; ?></li>
                    <li><?php echo '<a target="_blank" rel="nofollow" href="'.$fttl.'" title="facebook\'ta paylaş"><img src="img/facebookmini.png" /></a>'; ?></li>
                    <?php
                    if ($MEMBER_LOGGED) {
                    ?>
                    <li><?php echo '<a target="_blank" href="oy.php?id='.$siir['kid'].'&oy=1" title="olmuş bu"><img src="img/tu.png" /></a>'?></li>
                    <li><?php echo '<a target="_blank" href="oy.php?id='.$siir['kid'].'&oy=-1" title="böyle olmaz"><img src="img/td.png" style="margin-top:10px;" /></a>'?></li>
                    <?php 
                    if ($MEMBER->uid === $siir['uid']) {
                    ?>
                    <li><?php echo '<a target="_blank" href="sil.php?id='.$siir['kid'].'" title="bu olmamış ya sil bunu"><img src="img/thrashm.png" /></a>'?></li>
                    <?php
                    }
                    }
                    ?>
                    <li><?php echo '<input type="text" value="http://www.delikunduz.com/goster.php?id='.$siir['kid'].'" class="sharelinkbox" onClick="selectAll(this);" />'; ?></li>
                    <li style="float:right; margin-top:10px;font-size:9pt;font-family:Verdana,Helvetica,Arial,sans-serif;"><?php if ($nick === "" || empty($nick)) echo ""; else echo "<a href='profil.php?kid=".$siir['uid']."'>$nick</a> kaydetti."; ?></li>
                </ul>
            </div>
        </div>
    <?php
}
?>
</div>
<?php
include_once 'alt.php';
?>
