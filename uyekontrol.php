<?php

session_start();
if (!empty($_SESSION['logged']) && $_SESSION['logged']) {
    if (empty($_SESSION["signature"])) {
        session_unset();
        session_destroy();
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
        $browser = $_SERVER["HTTP_USER_AGENT"];
        $sig = $_SESSION["signature"];
        $salt = substr($sig, 0, 15);
        $sessionhash = substr($sig, 15, 64);
        $yenisessionhash = hash("sha256", $ip . $salt . $browser);
        if ($yenisessionhash !== $sessionhash) {
            session_unset();
            session_destroy();
        }
        if (!empty($_SESSION["son_islem"]) && (time() - $_SESSION["son_islem"]) > 1800) { //30 dakikadan fazla olduysa.
            session_unset();
            session_destroy();
        }
        $_SESSION["son_islem"] = time();
        /* Buraya Daha Fazla Session Sanity Check eklenmeli */
    }
}
$MEMBER_LOGGED = !empty($_SESSION['logged']) && $_SESSION['logged']; //yukardaki işlemlerde session_destroy() olduysa diye en sona eklendi.
/**
 * Member object
 * @var memberModel
 */
$MEMBER = $MEMBER_LOGGED ? $_SESSION['uye'] : null;
?>
