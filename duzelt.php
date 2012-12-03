<?php
include_once 'ust.php';
if (!$MEMBER_LOGGED) {
    header("Location: index.php");
} else {
    ?>
    <div class="maincontainer">
        <fieldset class="savedfs">
            <legend style="width:120px;">Yazı Düzelt</legend>
            <div style="margin:0 auto;width:940px;text-align:left;">
                <?php
                if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
                    $db = getPDO();
                    $sid = $_REQUEST['id'];
                    $st = $db->prepare('SELECT * FROM sablon WHERE sid=:sid AND uid=:uid');
                    $st->bindValue(':sid', $sid);
                    $st->bindValue(':uid', $MEMBER->uid);
                    $st->execute();
                    if ($st->rowCount() === 0)
                        echo "Böyle bir kayıt bulunamadı.";
                    else {
                        $sablonbilgi = $st->fetch(PDO::FETCH_ASSOC);
                        
                        if ($_SERVER['REQUEST_METHOD'] == "POST") {
                            if (empty($_POST['baslik']) || empty($_POST['txta'])) {
                                if (!empty($_POST['baslik']))
                                    $baslik = $_POST['baslik'];
                                if (!empty($_POST['txta']))
                                    $yazi = $_POST['txta'];
                                echo '<span class="label label-important" style="margin:10px 50px;">Başlık veya Yazı kısmı boş olamaz.</span>';
                            }
                            else {
                                $baslik = $_POST['baslik'];
                                $yazi = $_POST['txta'];
                                
                                if (!preg_match('/^[a-zçöğüşıâî0-9\.\- ]+$/i', $baslik)) {
                                    echo '<span class="label label-important" style="margin:10px 50px;font-family:Verdana,Helvetica,Arial,sans-serif;">Başlık sadece a-z,A-Z,0-9,., ,- içerebilir.</span>';
                                } else {
                                    $sablonekle = $db->prepare('UPDATE sablon SET sablon = :sablon, baslik=:baslik, duzenleme = NOW() WHERE sid = :sid AND uid = :uid');
                                    $sablonekle->bindValue(':sablon', $yazi);
                                    $sablonekle->bindValue(':sid', $sablonbilgi['sid']);
                                    $sablonekle->bindValue(':uid', $MEMBER->uid);
                                    $sablonekle->bindValue(':baslik', $baslik);
                                    $sablonekle->execute();
                                    header('Location: sablonlar.php');
                                }
                            }
                        }
                        ?>
                        <form <?php echo 'action="duzelt.php?id='.$sablonbilgi['sid'].'" ';?> method="post">
                            <input type="text" name="baslik" class="yeniinput" placeholder="Başlık"<?php echo ' value="' . $sablonbilgi['baslik'] . '" '; ?>/>
                            <div id="butonlar" style="margin:0 0 5px 50px;">
                                <button type="button" id="isim" class="btn btn-info btn-mini marginbutton">isim</button>
                                <button type="button" id="fiil" class="btn btn-info btn-mini marginbutton">fiil</button>
                                <button type="button" id="sifat" class="btn btn-info btn-mini marginbutton">sıfat</button>
                                <button type="button" id="link" onclick="var a = prompt('link: (başında http:// olmalı)', 'http://');var b = prompt('yazısı:');if (isURL(a)) $('.yenitxta').tae('url', a, b);" class="btn btn-info btn-mini marginbutton">link</button>
                                <button type="button" id="italik" class="btn btn-info btn-mini marginbutton">italik</button>
                                <button type="button" id="kalin" class="btn btn-info btn-mini marginbutton">kalın</button>
                                <button type="button" id="cizili" class="btn btn-info btn-mini marginbutton">çizili</button>
                                <button type="button" id="alticizili" class="btn btn-info btn-mini">altı çizili</button>
                            </div>
                            <textarea rows="20" placeholder="Yazı" class="yenitxta" name="txta"><?php echo $sablonbilgi['sablon']; ?></textarea>
                            <input type="submit" value="Düzelt" class="btn btn-success btn-small marginsubmit" /><input type="reset" value="Temizle" class="btn btn-small" style="margin-left:9px;" />
                        </form>
                        <h5 class="yeniyazi">Kullanabilecekleriniz:</h5>
                        <p class="yeniyazi">_<em>italik</em>_<br />
                            *<strong>kalın</strong>*<br />
                            -<del>çizili</del>-<br />
                            +<ins>altı çizili</ins>+<br />
                            "linktext":link<br />
                            {kfiil} rastgele mastarsız fiil çek<br />
                            {isim} rastgele isim çek<br />
                            {sıfat} rastgele sıfat çek<br />
                            <strong>ekler</strong>: {kfiil}-meli gibi kullanılmalı<br />
                            -i -e -ne -ecek -meye -de -dahi -den -ler -leri -lerin -lerini -lerine -en -me -meli -mez -mese -in
                        </p>
                    </div>
                    <?php
                }
            }
            else {
                echo "Geçersiz Id.";
            }
            ?>

        </fieldset>
    </div>
    <?php
}
include_once 'alt.php';
?>
