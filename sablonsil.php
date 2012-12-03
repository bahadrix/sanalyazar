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
                    $getuid = $db->prepare('SELECT uid FROM sablon WHERE sid = :sid');
                    $getuid->bindValue(':sid', $kid);
                    $getuid->execute();
                    if ($getuid->rowCount() === 0) {
                        echo "Şablon bulunamadı.";
                    } else {
                        $uid = $getuid->fetch(PDO::FETCH_OBJ)->uid;
                        if ($uid === $MEMBER->uid) {
                            if (empty($_REQUEST['onay'])) {
                                echo '<a target="_blank" href="duzelt.php?id=' . $kid . '">Bu</a> şablonu silmek istediğinizden emin misiniz?<br /><a href="sablonsil.php?id=' . $kid . '&onay=1" title="geri dönüşü olmayan bir yola giriyoruz">evet</a>';
                            }
                            else if (!empty($_REQUEST['onay']) && $_REQUEST['onay']==1) {
                                $sablonidsil = $db->prepare('UPDATE kaydedilenler SET sablonid = NULL WHERE sablonid = :sablonid');
                                $sablonidsil->bindValue(':sablonid', $kid);
                                $sablonidsil->execute();
                                $sablonsil = $db->prepare('DELETE FROM sablon WHERE sid = :kid AND uid = :uid');
                                $sablonsil->bindValue(':kid', $kid);
                                $sablonsil->bindValue(':uid', $MEMBER->uid);
                                $sablonsil->execute();
                                echo "Şablon silindi.";
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

