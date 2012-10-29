<?php
session_start();
$MEMBER_LOGGED = !empty($_SESSION['logged']) && $_SESSION['logged'];
/**
 * Member object
 * @var memberModel
 */
$MEMBER = $MEMBER_LOGGED ? $_SESSION['uye'] : null;
?>
