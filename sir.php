<?php

include_once 'core/db.php'; // ust.php'siz sayfalara include edilirse diye.
include_once 'core/lingustian.php';
include_once 'wordengine.php';

$we = new WordEngine();

$libBelkide = array(
    'belkide',
    'aslında',
    'gerçektende',
    'hiç değilse',
    'en azından',
    'hiç yoktan'
);


$libSokulan = array(
    'giren',
    'sokulan',
    'soktuğum',
    'koyduğum',
    'verdiğim',
    'yaptığım',
    'yapılan',
    'kerttiğim'
);

$gunGelecek = array(
    'Gün gelecek',
    'Devran dönecek',
    'İşte o zaman',
    'Bir gün',
    'O geldiğinde',
    'Yarın',
    'Elbet bir gün',
    'O gelecek'
);

$libBirBir = array(
    'bir bir',
    'teker teker',
    'birer birer',
    'peyder pey',
    'paşa paşa'
);

$hepsi = array(
    'hepsi',
    'bir kısmı',
    'az birazı',
    'azıcığı',
    'paşa paşa'
);

shuffle($libBelkide);
$belkide = $libBelkide[0];

shuffle($libSokulan);
$giren = $libSokulan[0];

shuffle($gunGelecek);
$gun_gelecek = $gunGelecek[0];

shuffle($gunGelecek);
$birgun = $gunGelecek[0];

shuffle($libBirBir);
$birbir = $libBirBir[0];

$yapmali = $we->sacmala('{kfiil}-meli');

$baslik = $yapmali;

$migir = <<<MGR
{sıfat} {isim}-lerin hepsi {kfiil}-ecek
$gun_gelecek {isim}-ler $birbir {kfiil}-ecek
{isim}-e $giren {isim} olmasa da,
Bu {isim}-ler $birgun {kfiil}-ecek.

Belki {kfiil}-mese-de {isim}-ler, {kfiil}-mese-de {isim}-ler; {isim}-ler
$birgun, bu {isim}-ler {kfiil}-ecek, {kfiil}-ecek.

{kfiil}-meli $belkide bu {isim}-leri, {kfiil}-meli
{kfiil}-meli {sıfat} {isim}-lerine, {sıfat} {isim}-lerine
$belkide {sıfat} {isim}-lerini, {sıfat} {isim}-lerini {kfiil}-meli
$yapmali {isim}-lerini, $yapmali {isim}-lerini.

MGR;

$migir2 = <<<MGR


MGR;
$migir = trim($migir);


// test


$db = getPDO();
?>