<?php
require_once('ust.php');
if (!$MEMBER_LOGGED)
    header("Location: index.php");
else {
    ?>
    <div class="maincontainer">
        <div class="hqmenu">
            <ul>
                <li><a href="hq.php" class="hqmenunav">bilgilerim</a></li>
                <li><a href="sablonlar.php" class="hqmenunav">şablonlarım</a></li>
                <li class="activehn"><a href="oa.php" class="hqmenunav">oy alan kayıtlarım</a></li>
                <li><a href="ov.php" class="hqmenunav">oy verdiklerim</a></li>
            </ul>        
        </div>
        <div class="hqcontainer">
            <div class="hqinner">
                    <?php
                    $sayfabasina = 20; //bir sayfada gösterilecek yazı sayısı
                    if (empty($_REQUEST['p']) || (!empty($_REQUEST['p']) && !is_numeric($_REQUEST['p'])))
                        $page = 1;
                    else
                        $page = $_REQUEST['p'];
                    $db = getPDO();
                    $pager = false;
                    $toplamoyalanlar = $db->prepare('SELECT COUNT(DISTINCT kid) AS oyalankid FROM oylar INNER JOIN kaydedilenler ON oylar.yid = kaydedilenler.kid WHERE kaydedilenler.uid = :uid');
                    $toplamoyalanlar->bindValue(':uid', $MEMBER->uid);
                    $toplamoyalanlar->execute();
                    $tploa = $toplamoyalanlar->fetch(PDO::FETCH_OBJ)->oyalankid;
                    $ttlpages=1;
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
                    $getoylar = $db->prepare("SELECT SUM(oylar.oy) as oytoplam, kid, baslik FROM oylar INNER JOIN kaydedilenler ON oylar.yid = kaydedilenler.kid WHERE kaydedilenler.uid = :uid GROUP BY kid ORDER BY oytoplam DESC LIMIT $baslangic,$sayfabasina");
                    $getoylar->bindValue(':uid', $MEMBER->uid);
                    $getoylar->execute();
                    if ($getoylar->rowCount() === 0) {
                        echo "Oy almış şiiriniz yok.";
                    } else {
                        $yazilar = $getoylar->fetchAll();
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
                                    echo ' <a href="oa.php?p=' . $j . '">' . $j . '</a> ';
                                }
                                if ($j != $ttlpages)
                                    echo ' <span style="color:#ccc">|</span> ';
                            }
                            echo '</span>';
                        }
                    }
                    ?>
            </div>
        </div>
    </div>
<?php
}
include_once 'alt.php';
?>
