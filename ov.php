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
                <li><a href="oa.php" class="hqmenunav">oy alan kayıtlarım</a></li>
                <li class="activehn"><a href="ov.php" class="hqmenunav">oy verdiklerim</a></li>
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
                    $toplamoyalanlar = $db->prepare('SELECT COUNT(oy) AS oyverdigim FROM oylar WHERE oylar.uid = :uid');
                    $toplamoyalanlar->bindValue(':uid', $MEMBER->uid);
                    $toplamoyalanlar->execute();
                    $tploa = $toplamoyalanlar->fetch(PDO::FETCH_OBJ)->oyverdigim;
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
                    $getoyverdigim = $db->prepare("SELECT kid, baslik FROM oylar INNER JOIN kaydedilenler ON oylar.yid = kaydedilenler.kid WHERE oylar.uid = :uid ORDER BY oylar.tarih DESC LIMIT $baslangic,$sayfabasina");
                    $getoyverdigim->bindValue(':uid', $MEMBER->uid);
                    $getoyverdigim->execute();
                    if ($getoyverdigim->rowCount() === 0) {
                        echo "Oy verdiğiniz bir şey yok.";
                    } else {
                        $yazilar = $getoyverdigim->fetchAll();
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
                                    echo ' <a href="ov.php?p=' . $j . '">' . $j . '</a> ';
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