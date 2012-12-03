<?php
include_once 'ust.php';
if (!$MEMBER_LOGGED) {
    header("Location: index.php");
} else {
?>
    <div class="maincontainer">
        <fieldset class="savedfs">
            <legend style="width:120px;">Yeni Yazı</legend>
            <div style="margin:0 auto;width:940px;text-align:left;">
                <?php
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
                        }
                        else {
                            $db = getPDO();
                            $sablonekle = $db->prepare('INSERT INTO sablon (sablon,baslik,tarih,uid) VALUES (:sablon,:baslik,NOW(),:uid)');
                            $sablonekle->bindValue(':sablon', $yazi);
                            $sablonekle->bindValue(':uid', $_SESSION['uye']->uid);
                            $sablonekle->bindValue(':baslik', $baslik);
                            $sablonekle->execute();
                            header('Location: sablonlar.php');
                        }
                    }
                }
                ?>
                <form action="yeni.php" method="post">
                    <input type="text" name="baslik" class="yeniinput" placeholder="Başlık"<?php if (!empty($baslik)) echo ' value="'.$baslik.'" '; ?>/>
                    <div id="butonlar" style="margin:0 0 5px 50px;">
                        <button type="button" id="isim" class="btn btn-info btn-mini marginbutton">isim</button>
                        <button type="button" id="fiil" class="btn btn-info btn-mini marginbutton">fiil</button>
                        <button type="button" id="sifat" class="btn btn-info btn-mini marginbutton">sıfat</button>
                        <button type="button" id="link" onclick="var a=prompt('link: (başında http:// olmalı)', 'http://');var b=prompt('yazısı:');if(isURL(a))$('.yenitxta').tae('url',a,b);" class="btn btn-info btn-mini marginbutton">link</button>
                        <button type="button" id="italik" class="btn btn-info btn-mini marginbutton">italik</button>
                        <button type="button" id="kalin" class="btn btn-info btn-mini marginbutton">kalın</button>
                        <button type="button" id="cizili" class="btn btn-info btn-mini marginbutton">çizili</button>
                        <button type="button" id="alticizili" class="btn btn-info btn-mini">altı çizili</button>
                    </div>
                    <textarea rows="20" placeholder="Yazı" class="yenitxta" name="txta"><?php if (!empty($yazi)) echo $yazi; ?></textarea>
                    <input type="submit" value="Ekle" class="btn btn-success btn-small marginsubmit" /><input type="reset" value="Temizle" class="btn btn-small" style="margin-left:9px;" />
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
        </fieldset>
    </div>
<?php
}
include_once 'alt.php';
?>
