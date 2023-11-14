<?php namespace Project\Controllers;

use User,Method,Post,Redirect,AyarModel,CariModel,URL,Date;

class paytr extends Controller
{

     public function main(){


         $post = $_POST;

         ####################### DÜZENLEMESİ ZORUNLU ALANLAR #######################
         #
         ## API Entegrasyon Bilgileri - Mağaza paneline giriş yaparak BİLGİ sayfasından alabilirsiniz.
         $merchant_key 	= AyarModel::defaultAyarlar('paytrMerchantKey');
         $merchant_salt	= AyarModel::defaultAyarlar('paytrMerchantSalt');
         ###########################################################################

         ####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
         #
         ## POST değerleri ile hash oluştur.
         $hash = base64_encode( hash_hmac('sha256', $post['merchant_oid'].$merchant_salt.$post['status'].$post['total_amount'], $merchant_key, true) );
         //$hash = "123456789";
         #
         ## Oluşturulan hash'i, paytr'dan gelen post içindeki hash ile karşılaştır (isteğin paytr'dan geldiğine ve değişmediğine emin olmak için)
         ## Bu işlemi yapmazsanız maddi zarara uğramanız olasıdır.
         if( $hash != $post['hash'] ) {
             die('PAYTR notification failed: bad hash');
         }
         ###########################################################################
         ## BURADA YAPILMASI GEREKENLER
         ## 1) Siparişin durumunu $post['merchant_oid'] değerini kullanarak veri tabanınızdan sorgulayın.
         ## 2) Eğer sipariş zaten daha önceden onaylandıysa veya iptal edildiyse  echo "OK"; exit; yaparak sonlandırın.

         /* Sipariş durum sorgulama örnek
             $durum = SQL
            if($durum == "onay" || $durum == "iptal"){
                 echo "OK";
                 exit;
             }
          */

         $kontrol = CariModel::krediKartiOdemeKontrol($post['merchant_oid']);

         if($kontrol->durum=="1" || $kontrol->durum=="2" ){
             echo "OK";
             exit;
             }

         if( $post['status'] == 'success' ) { ## Ödeme Onaylandı

             $data = [
                 'uye'           => $kontrol->uye,
                 'anahtar'       => $post['merchant_oid'],
                 'durum'         => '1',
                 'hata_kodu'     => 'ok',
                 'hata_mesaji'   => 'Başarılı ödeme',
                 'odeme_tutari'  => $post['total_amount']/100
             ];

             $guncelle = CariModel::kartOdemeGuncelle($data);

             UyeModel::IpEkle($user->id,User::ip(),"KARTODEMESIBASARILI");

             $bakiyeData = [
                 'uye'           =>$kontrol->uye,
                 'tutar'         =>$kontrol->tutar,
                 'hareket'       =>'+',
                 'odeme_sekli'   =>'Kredi kartı ödemesi',
                 'aciklama'      => Date::convert($kontrol->tarih, '{dayNumber0}.{monthNumber0}.{year}').' tarihinde Kredi kartınız ile '.$kontrol->tutar.' TL tutarında ödeme yapılmıştır. '
             ];

             $bakiyeGecmisEkle = CariModel::bakiyeGecmisEkle($bakiyeData);

             $bakiyeArtir = CariModel::bakiyeArtir($kontrol->uye,$kontrol->tutar);

             if($bakiyeArtir){   $uyari2 = '<div class="callout callout-success">Bakiyeniz Artırıldı </div>';
             }else{              $uyari2 = '<div class="callout callout-danger">Bakiye artırımı yapılamadı!</div>'; }

             $bildirimEkle = UyeModel::bildirimEkle($kontrol->uye,' Kredi kartı ile bakiye yüklemesi yapıldı. Ödeme Bakiyenize Eklendi !');

             $hediyeBakiye = AyarModel::defaultAyarlar('bakiyeYuklemeHediyesi');

             if($hediyeBakiye!="" and $hediyeBakiye>"0"){

                 if(UyeModel::kuponKullanim('ilkBakiye',$kontrol->uye)=="0"){

                     if($hediyeBakiye[0]=="%"){

                         $yuzde = $hediyeBakiye[1].$hediyeBakiye[2];
                         $hediyeTutar = number_format(($kontrol->tutar/100)*$yuzde,2);

                         $hediyeBakiyeData = [
                             'uye'           =>$kontrol->uye,
                             'tutar'         =>$hediyeTutar,
                             'hareket'       =>'+',
                             'odeme_sekli'   =>'Bakiye Yükleme Hediyesi',
                             'aciklama'      => Date::convert($kontrol->tarih, '{dayNumber0}.{monthNumber0}.{year}').' tarihinde yaptığınız ilk ödemeye istinaden hediye edilen '.$hediyeTutar.' TL hesabınıza eklenmiştir. '
                         ];

                         $hediyeBakiyeGecmisEkle = CariModel::bakiyeGecmisEkle($hediyeBakiyeData);

                         $hediyeBakiyeArtir = CariModel::bakiyeArtir($kontrol->uye,$hediyeTutar);

                         UyeModel::kuponKullandir('ilkBakiye',$kontrol->uye);

                     }else{

                         $hediyeBakiyeData = [
                             'uye'           =>$kontrol->uye,
                             'tutar'         =>$hediyeBakiye,
                             'hareket'       =>'+',
                             'odeme_sekli'   =>'Bakiye Yükleme Hediyesi',
                             'aciklama'      => Date::convert($kontrol->tarih, '{dayNumber0}.{monthNumber0}.{year}').' tarihinde yaptığınız ilk ödemeye istinaden hediye edilen '.$hediyeBakiye.' TL hesabınıza eklenmiştir. '
                         ];

                         $hediyeBakiyeGecmisEkle = CariModel::bakiyeGecmisEkle($hediyeBakiyeData);

                         $hediyeBakiyeArtir = CariModel::bakiyeArtir($kontrol->uye,$hediyeBakiye);

                         UyeModel::kuponKullandir('ilkBakiye',$kontrol->uye);

                     }

                     $bildirimEkle = UyeModel::bildirimEkle($kontrol->uye,' İlk Bakiye yüklemenize istinaden hesabınıza hediyeniz yüklendi!');
                 }
             }


             /*if($bakiyeArtir){
                 Redirect::wait(1)->insert(['bilgi'=>$uyari2.'<div class="callout callout-success">Kredi kartı ödeme işlemi gerçekleşti ve BAKİYENİZ ARTIRILDI!</div>'])->action('cari/bakiyeYukle');
             }else{

                 Redirect::wait(1)->insert(['bilgi'=>$uyari2.'<div class="callout callout-warning">Kredi kartı ödeme işlemi gerçekleşti ve BAKİYENİZ ARTIRMA YAPILAMADI!. Lütfen bizimle iletişime geçiniz</div>'])->action('cari/bakiyeYukle');

             }*/

             ## BURADA YAPILMASI GEREKENLER
             ## 1) Siparişi onaylayın.
             ## 2) Eğer müşterinize mesaj / SMS / e-posta gibi bilgilendirme yapacaksanız bu aşamada yapmalısınız.
             ## 3) 1. ADIM'da gönderilen payment_amount sipariş tutarı taksitli alışveriş yapılması durumunda
             ## değişebilir. Güncel tutarı $post['total_amount'] değerinden alarak muhasebe işlemlerinizde kullanabilirsiniz.

         } else { ## Ödemeye Onay Verilmedi

             UyeModel::IpEkle($user->id,User::ip(),"KARTODEMESIBASARISIZ");

             $data = [
                 'uye'           => $kontrol->uye,
                 'anahtar'       => $post['merchant_oid'],
                 'durum'         => '2',
                 'hata_kodu'     => $post['failed_reason_code'],
                 'hata_mesaji'   => $post['failed_reason_msg'],
                 'odeme_tutari'  => $post['total_amount']
             ];

             $guncelle = CariModel::kartOdemeGuncelle($data);

             ## BURADA YAPILMASI GEREKENLER
             ## 1) Siparişi iptal edin.
             ## 2) Eğer ödemenin onaylanmama sebebini kayıt edecekseniz aşağıdaki değerleri kullanabilirsiniz.
             ## $post['failed_reason_code'] - başarısız hata kodu
             ## $post['failed_reason_msg'] - başarısız hata mesajı

             /*Redirect::wait(1)->insert(['bilgi'=>'<div class="callout callout-danger">Kredi kartı ile ödeme işlemi yapılamadı!<br>
                     <div class="alert alert-info">Hata Kodu: '.$failed_reason_code.' Hata Mesajı: '.$failed_reason_msg.'</div>
                     </div>'])->action('cari/bakiyeYukle');*/

         }

         ## Bildirimin alındığını PayTR sistemine bildir.
         echo "OK";
         exit;
     }

    public function hata(){

        $user = User::data();

        if (!empty($user->id)){
            Redirect::action('cari/bakiyeYukle');
        }

        $kontrol = CariModel::krediKartiOdemeKontrol($post['merchant_oid']);

        $data = [
            'uye'           => $kontrol->uye,
            'anahtar'       => $post['merchant_oid'],
            'durum'         => '2',
            'hata_kodu'     => $post['failed_reason_code'],
            'hata_mesaji'   => $post['failed_reason_msg'],
            'odeme_tutari'  => $post['total_amount']
        ];

        $guncelle = CariModel::kartOdemeGuncelle($data);

        echo "OK";
    }

    public function s404()
    {

    }
}