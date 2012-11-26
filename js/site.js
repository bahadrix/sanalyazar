/*
 * jQuery.ajaxQueue - A queue for ajax requests
 * 
 * (c) 2011 Corey Frang
 * Dual licensed under the MIT and GPL licenses.
 *
 * http://stackoverflow.com/questions/3034874/sequencing-ajax-requests/3035268#3035268
 * http://gnarf.net/2011/06/21/jquery-ajaxqueue/
 * 
 * Requires jQuery 1.5+
 */
(function(a){var b=a({});a.ajaxQueue=function(c){function g(b){d=a.ajax(c).done(e.resolve).fail(e.reject).then(b,b)}var d,e=a.Deferred(),f=e.promise();b.queue(g),f.abort=function(h){if(d)return d.abort(h);var i=b.queue(),j=a.inArray(g,i);j>-1&&i.splice(j,1),e.rejectWith(c.context||c,[f,h,""]);return f};return f}})(jQuery)
/* ajaxQueue bitti */

/*
 * sanalyazar kısmı
 * @version 0.01
 */ 
function submitDuzelt() {
    $('input[type="submit"]').removeAttr('disabled');
    $('input[type="submit"]').removeClass('disabled');
};

/* üye ol validation kısmı */
$(document).on('reset','#register',function() {
    $('#uyetablo').find('span').remove();
});

$(document).on('submit', '#register', function(e) {
    $('input[name="sbmt"]').attr("disabled","disabled");
    $('input[name="sbmt"]').addClass('disabled');
    setTimeout('submitDuzelt()', 5000);
    
    $('#uyetablo').find('span').remove();
    
    var $nick = $('input[name="nick"]'),
        $sifre = $('input[name="sifre"]'),
        $sifret = $('input[name="sifret"]'),
        $email = $('input[name="email"]'),
        $ad = $('input[name="isim"]'),
        $soyad = $('input[name="soyisim"]'),
        nickErr,
        sifreErr,
        sifreTErr,
        emailErr,
        adErr,
        soyadErr,
        err = false,
        nickregExp = /^(?:[a-zşçüıöğ0-9_-]+\s?)*$/i,
        // email regexp http://projects.scottsplayground.com/email_address_validation/ adresinden alındı.
        emailregExp = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i,
        adregExp = /^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/,
        soyadregExp = /^(?:[A-ZIÇÖŞÜ][a-zıçöğşü]+\s*)+$/;
    
    /* validation kontrolleri */
    if (!$nick.val() || !$sifre.val() || !$sifret.val() || !$email.val() || !$ad.val() || !$soyad.val()) {
        err = true;
    }
        
    if ($nick.val().length < 3 || $nick.val().length > 25) {
        err = true;
        nickErr = '<span class="label label-important">Kullanıcı Adı 3-25 karakter arası olmalı.</span>';
    }
    else if (!nickregExp.test($nick.val())) {
        err = true;
        nickErr = '<span class="label label-important">Kullanıcı Adı sadece harf, rakam, boşluk ve _- içerebilir.</span>';
    }
    
    if (!emailregExp.test($email.val())) {
        err = true;
        emailErr = '<span class="label label-important">Geçersiz email adresi</span>';
    }
    
    if (!adregExp.test($ad.val())) {
        err = true;
        adErr = '<span class="label label-important">İsim sadece harf ve boşluk içerebilir.<br />Baş harfler büyük olmalıdır.</span>';
    }

    if (!soyadregExp.test($soyad.val())) {
        err = true;
        soyadErr = '<span class="label label-important">Soyisim sadece harf içerebilir.<br />Baş harfler büyük olmalıdır.</span>';
    }

    if ($sifret.val() !== $sifre.val()) {
        err = true;
        sifreTErr = '<span class="label label-important">Şifreler aynı olmalı</span>';
    }

    if ($sifre.val().length < 3 || $sifre.val().length > 30) {
        err = true;
        sifreErr = '<span class="label label-important">Şifre 3-30 karakter olmalı</span>';
    }
    /* validation kontrolleri bitti */
    
    /* hataları ekle */
    if ($nick.parent().find('span').length === 0 && typeof nickErr !== 'undefined') {
        $nick.parent().append(nickErr);
        $nick.focus();
    }
    
    if ($sifre.parent().find('span').length === 0 && typeof sifreErr !== 'undefined') {
        $sifre.parent().append(sifreErr);
        if (!$('input').is(":focus"))
            $sifre.focus();
    }
    
    if ($sifret.parent().find('span').length === 0 && typeof sifreTErr !== 'undefined') {
        $sifret.parent().append(sifreTErr);
        if (!$('input').is(":focus"))
            $sifret.focus();
    }
    
    if ($email.parent().find('span').length === 0 && typeof emailErr !== 'undefined') {
        $email.parent().append(emailErr);
        if (!$('input').is(":focus"))
            $email.focus();
    }
    
    if ($ad.parent().find('span').length === 0 && typeof adErr !== 'undefined') {
        $ad.parent().append(adErr);
        if (!$('input').is(":focus"))
            $ad.focus();
    }
    
    if ($soyad.parent().find('span').length === 0 && typeof soyadErr !== 'undefined') {
        $soyad.parent().append(soyadErr);
        if (!$('input').is(":focus"))
            $soyad.focus();
    }
    /* hataları ekle bitti */
    if (err) //hata yoksa gönder
        e.preventDefault();
});
/* üye ol validation kısmı bitti */

/* kullanıcı girişi validation */
$(document).on('submit','form[name="loginform"]',function(e) {
    e.stopPropagation();
    e.preventDefault();
    $('input[name="loginsubmit"]').attr("disabled","disabled");
    $('input[name="loginsubmit"]').addClass('disabled');
    setTimeout('submitDuzelt()', 5000);
    $(this).find('span[class="label label-warning"]').remove();
    var $k = $('input[name="kuladi"]'),
        $s = $('input[name="parola"]');
    if (!$k.val() || !$s.val()) {
        $(this).append('<span class="label label-warning">Bütün alanlar doldurulmalı.</span>');
    }
    else {
        $.ajaxQueue({
            url: 'login.php',
            type: 'POST',
            dataType: 'html',
            data: $(this).serialize(),
            success: function(data) {
                if ((/^ok\d+$/).test(data)) {
                    var id = data.substring(2);
                    document.location = "goster.php?id="+id;
                }
                else if (data === 'OK') {
                    location.reload(true);
                } 
                else {
                    $('form[name="loginform"]').append(data);
                }
            }
        });
    }
});
/* kullanıcı girişi validation bitti */

$(document).on('click','.girisyap', function() {
    var $l = $('.upperbox');
    if (!$l.is(':visible')) {
        $l.fadeIn(100).focus();
    }
    else {
       $l.fadeOut(100); 
    }
});

$(document).on("mouseenter","label[class='checkbox']",function() {
   $(this).find('.tipbox').show(); 
});
$(document).on("mouseleave","label[class='checkbox']",function() {
   $(this).find('.tipbox').hide(); 
});