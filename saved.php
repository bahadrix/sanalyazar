<?php
include_once 'ust.php';
if (!$MEMBER_LOGGED) {
    header("Location: index.php");
} else {
    ?>
    <div class="maincontainer">
        <fieldset class="savedfs">
            <legend class="savedlegend">Kaydettiklerim</legend>
            <?php
            $sayfabasina = 3;
            if (empty($_REQUEST['p']) || (!empty($_REQUEST['p']) && !is_numeric($_REQUEST['p'])))
                $page = 1;
            else
                $page = $_REQUEST['p'];
            $db = getPDO();
            $pager = false;
            $toplamkayit = $db->prepare('SELECT COUNT(*) AS tplkyt FROM kaydedilenler WHERE uid=:uid');
            $toplamkayit->bindValue(':uid', $MEMBER->uid);
            $toplamkayit->execute();
            $tploa = $toplamkayit->fetch(PDO::FETCH_OBJ)->tplkyt;
            $ttlpages = 1;
            if ($tploa > 0) {
                if ($tploa > $sayfabasina) {
                    $pager = true;
                    if ($sayfabasina !== 1)
                        $ttlpages = (int) ($tploa / $sayfabasina) + 1;
                    else
                        $ttlpages = $tploa;
                }
            }
            if ($ttlpages < $page)
                $page = $ttlpages;
            else if ($page < 1)
                $page = 1;
            $baslangic = ($page - 1) * $sayfabasina;
            $getkayitlar = $db->prepare("SELECT kid, baslik FROM kaydedilenler WHERE uid = :uid ORDER BY tarih DESC LIMIT $baslangic,$sayfabasina");
            $getkayitlar->bindValue(':uid', $MEMBER->uid);
            $getkayitlar->execute();
            if ($getkayitlar->rowCount() === 0) {
                echo "Oy verdiğiniz bir şey yok.";
            } else {
                $yazilar = $getkayitlar->fetchAll();
                echo '<ol start="' . ($baslangic + 1) . '">';
                for ($i = 0; $i < count($yazilar); $i++) {
                    echo '<li><a href="goster.php?id=' . $yazilar[$i]['kid'] . '">' . $yazilar[$i]['baslik'] . '</a></li>';
                    if ($i !== count($yazilar) - 1)
                        echo "<hr />";
                }
                echo "</ol>";
                if ($pager) {
                    echo '<span style="font-size:8pt;font-family:Verdana,Helvetica,Arial,sans-serif;">Sayfa:';
                    for ($j = 1; $j <= $ttlpages; $j++) {
                        if ($j == $page) {
                            echo " $j ";
                        } else {
                            echo ' <a href="saved.php?p=' . $j . '">' . $j . '</a> ';
                        }
                        if ($j != $ttlpages)
                            echo ' <span style="color:#ccc">|</span> ';
                    }
                    echo '</span>';
                }
            }
            ?>
        </fieldset>
    </div>
    <?php
}
include_once 'alt.php';
?>