<?php
require_once('ust.php');
if (!$MEMBER_LOGGED)
    header("Location: index.php");
else {
    ?>
    <div class="maincontainer">
        <div class="hqmenu">
            <ul>
                <li class="active"><a href="#bilgi">bilgilerim</a></li>
                <li><a href="#sablon">şablonlarım</a></li>
                <li><a href="#encokoy">en çok oy alanlarım</a></li>
                <li><a href="#oyverdigim">oy verdiklerim</a></li>
                <li>vb</li>
            </ul>        
        </div>
        <div class="hqcontainer" id="bilgi">
            <form method="post" action="bilgi.php">
                <span class="baslik">Şifre</span>
                <table class="bilgiler" cellpadding="5">
                    <tr>
                        <td>Şu anki şifre:</td>
                        <td><input type="password" name="passnow" /></td>
                    </tr>
                    <tr>
                        <td>Yeni şifre:</td>
                        <td><input type="password" name="passnew" /></td>
                    </tr>
                    <tr>
                        <td>Yeni şifre tekrar:</td>
                        <td><input type="password" name="passnewa" /></td>
                    </tr>
                </table>
                <hr />
                <span class="baslik">E-Mail</span>
                <table class="bilgiler" cellpadding="5">
                    <tr>
                        <td>Şu anki e-mail:</td>
                        <td><input type="email" name="emailnow" /></td>
                    </tr>
                    <tr>
                        <td>Yeni e-mail:</td>
                        <td><input type="email" name="emailnew" /></td>
                    </tr>
                </table>
                <hr />
                <input type="submit" class="btn btn-mini btn-success" name="degistir" value="Değiştir" />
            </form>
        </div>
    </div>
    <?php
}
include_once 'alt.php';
?>
