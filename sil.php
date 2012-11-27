<?php
include_once 'ust.php';
if (!$MEMBER_LOGGED) {
    header("Location: index.php");
    exit;
} else {
?>
    <div class="maincontainer">
        <div class="oysonuc">
            <div class="oyreturn">
                <?php
                if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
                    $kid = $_REQUEST['id'];
                    $db = getPDO();
                    $getuid = $db->prepare('SELECT uid FROM kaydedilenler WHERE kid = :kid');
                    $getuid->bindValue(':kid', $kid);
                    $getuid->execute();
                    if ($getuid->rowCount() === 0) {
                        echo "Yazı bulunamadı.";
                    } else {
                        $uid = $getuid->fetch(PDO::FETCH_OBJ)->uid;
                        //phpmyadmin'den FK olayını beceremediğim için buraya uid var mı kontrolü eklenebilir.
                        if ($uid === $MEMBER->uid) {
                            if (empty($_REQUEST['onay'])) {
                                echo '<a target="_blank" href="goster.php?id=' . $kid . '">Bu</a> şiiri silmek istediğinizden emin misiniz?<br /><a href="sil.php?id=' . $kid . '&onay=1" title="geri dönüşü olmayan bir yola giriyoruz">evet</a>';
                            }
                            else if (!empty($_REQUEST['onay']) && $_REQUEST['onay']==1) {
                                $siirsil = $db->prepare('DELETE FROM kaydedilenler WHERE kid = :kid AND uid = :uid');
                                $siirsil->bindValue(':kid', $kid);
                                $siirsil->bindValue(':uid', $MEMBER->uid);
                                $siirsil->execute();
                                $oysil = $db->prepare('DELETE FROM oylar WHERE yid = :yid');
                                $oysil->bindValue(':yid', $kid);
                                $oysil->execute();
                                echo "Yazı silindi.";
                            }
                            else {
                                echo "Geçersiz işlem.";
                            }
                        }
                        else {
                            echo "Yazı size ait değil.";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>

