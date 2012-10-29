<?php

/**
 * Mother Model!
 * Bir PDO Statement'Ä±nÄ± doÄŸrudan object almaya yarar.
 * Çok lezzetli oldu, bundan da bir proje olur ;)
 * 
 * @author Bahadir
 * @version 0.5
 *
 */
class motherModel {

    /**
     * 
     * @param PDOStatement $statement
     */
    function __construct($statement) {
        $statement->setFetchMode(PDO::FETCH_INTO, $this);
        return $statement->fetch(PDO::FETCH_INTO);
    }

}

class wordModel extends motherModel {
    
}

class memberModel extends motherModel {

    /**
     * User ID, primary key
     * @var integer
     */
    public $uid;

    /**
     * Kullanıcı adı
     * @var string
     */
    public $nick;

    /**
     * Kullanıcı adı, ad ve soyad ile gerçek şifrenin sha256 ile haşlanmış hali
     * @var string
     */
    public $sifre;

    /**
     * Ad
     * @var string
     */
    public $ad;

    /**
     * Soyad
     * @var string
     */
    public $soyad;

    /**
     * E-Posta adresi
     * @var string
     */
    public $email;

    /**
     * Üyelik tarihi
     * @var date
     */
    public $tarih;

    /**
     * Üyenin son giriş yaptığı tarih
     * @var datetime
     */
    public $son_online;

    /**
     * Üye aktif mi?
     * @var boolean
     */
    public $aktif;

}

?>