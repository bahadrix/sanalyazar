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
                <li class="activehn"><a href="sablonlar.php" class="hqmenunav">şablonlarım</a></li>
                <li><a href="oa.php" class="hqmenunav">oy alan kayıtlarım</a></li>
                <li><a href="ov.php" class="hqmenunav">oy verdiklerim</a></li>
            </ul>        
        </div>
        <div class="hqcontainer">
            <div class="hqinner">
                <?php
            $sayfabasina = 20; 
            if (empty($_REQUEST['p']) || (!empty($_REQUEST['p']) && !is_numeric($_REQUEST['p'])))
                $page = 1;
            else
                $page = $_REQUEST['p'];
            $db = getPDO();
            $pager = false;
            $toplamkayit = $db->prepare('SELECT COUNT(*) AS tplkyt FROM sablon WHERE uid=:uid');
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
            $getkayitlar = $db->prepare("SELECT sid, baslik FROM sablon WHERE uid = :uid ORDER BY tarih DESC LIMIT $baslangic,$sayfabasina");
            $getkayitlar->bindValue(':uid', $MEMBER->uid);
            $getkayitlar->execute();
            if ($getkayitlar->rowCount() === 0) {
                echo "Kaydettiğiniz bir şablon yok.";
            } else {
                $sablonlar = $getkayitlar->fetchAll();
                echo '<span style="margin:0;padding:0;font-size:12pt;">Şablonu düzeltmek için başlığa tıklayınız.</span>';
                echo '<ol start="' . ($baslangic + 1) . '" style="margin-top:10px;">';
                for ($i = 0; $i < count($sablonlar); $i++) {
                    echo '<li><a href="duzelt.php?id=' . $sablonlar[$i]['sid'] . '">' . $sablonlar[$i]['baslik'] . '</a><br /><a href="uret.php?id=' . $sablonlar[$i]['sid'] . '"><span style="margin-left:10px;font-size:11pt;color:#000">üret</span></a></li>';
                    if ($i !== count($sablonlar) - 1)
                        echo "<hr />";
                }
                echo "</ol>";
                if ($pager) {
                    echo '<span style="font-size:8pt;font-family:Verdana,Helvetica,Arial,sans-serif;">Sayfa:';
                    for ($j = 1; $j <= $ttlpages; $j++) {
                        if ($j == $page) {
                            echo " $j ";
                        } else {
                            echo ' <a href="sablonlar.php?p=' . $j . '">' . $j . '</a> ';
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