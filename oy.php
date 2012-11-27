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
                if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id']) && !empty($_REQUEST['oy']) && is_numeric($_REQUEST['oy'])) {

                    $kid = $_REQUEST['id'];
                    $gerial = false;
                    if (!empty($_REQUEST['op']) && $_REQUEST['op'] === "g") {
                        $gerial = true;
                    }
                    $db = getPDO();
                    $st = $db->prepare('SELECT kid FROM kaydedilenler WHERE kid=:kid');
                    $st->bindValue(':kid', $kid);
                    $st->execute();
                    if ($st->rowCount() === 0)
                        echo "Böyle bir kayıt bulunamadı.";
                    else {
                        $oy = $_REQUEST['oy'];
                        if ($oy > 0)
                            $oy = 1;
                        else if ($oy < 0)
                            $oy = -1;
                        else
                            $oy = 0;
                        if ($oy === 1 || $oy === -1) {
                            // daha sonra zaman kısıtlı oy verme eklenebilir (günde 1 gibi)
                            // geri alma opsiyonu o zaman kaldırılabilir.
                            $getoy = $db->prepare('SELECT uid FROM oylar WHERE yid = :yid AND uid = :uid');
                            $getoy->bindValue(':yid', $kid);
                            $getoy->bindValue(':uid', $MEMBER->uid);
                            $getoy->execute();
                            if ($getoy->rowCount() > 0) {
                                if ($gerial) {
                                    $siloy = $db->prepare('DELETE FROM oylar WHERE yid = :yid AND uid = :uid');
                                    $siloy->bindValue(':yid', $kid);
                                    $siloy->bindValue(':uid', $MEMBER->uid);
                                    $siloy->execute();
                                    echo "Oy geri alındı.";
                                    //javascript kullanmayan biri back yaparsa tekrar oy verir.
                                    //bi fix bulamadım bu duruma. Ya da kalsın mı böyle?
                                } else {
                                    echo "Daha önce oy vermişsiniz.<br />";
                                    echo '<a href="oy.php?id=' . $kid . '&oy=' . $oy . '&op=g">geri al?</a>'; //geri al için &oy=$oy'a gerek yok. en baştaki IF kontrolünden dolayı koydum.
                                }
                            } else {
                                if (basename($_SERVER['HTTP_REFERER']) === "goster.php" || substr_unicode(basename($_SERVER['HTTP_REFERER']),0,14)==="goster.php?id=") { //ne olur ne olmaz.
                                    $setoy = $db->prepare('INSERT INTO oylar (uid,yid,oy,tarih) VALUES (:uid,:yid,:oy,NOW())');
                                    $setoy->bindValue(':uid', $MEMBER->uid);
                                    $setoy->bindValue(':yid', $kid);
                                    $setoy->bindValue(':oy', $oy);
                                    $setoy->execute();
                                    echo "Oyunuz kaydedildi.<br />";
                                    echo '<a href="oy.php?id=' . $kid . '&oy=' . $oy . '&op=g">geri al?</a>'; //geri al için &oy=$oy'a gerek yok. en baştaki IF kontrolünden dolayı koydum.
                                } else {
                                    echo "Geçersiz oy.";
                                }
                            }
                        } else {
                            echo "Geçersiz oy.";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    include_once 'alt.php';
}
?>
