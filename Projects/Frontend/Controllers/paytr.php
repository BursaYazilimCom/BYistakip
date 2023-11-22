<?php namespace Project\Controllers;

use User,Method,Post,Request,Email,Redirect,AyarModel,CariModel,KasaModel,InternalFaturaModel as FaturaModel,URL,Date,DB;

class Paytr extends Controller
{

     public function main(){


         $kasa_hesabi = AyarModel::defaultAyarlar('paytrKasaHesabi');
         $odeme_tarihi = date('Y-m-d');

         ####################### DÜZENLEMESİ ZORUNLU ALANLAR #######################
         #
         ## API Entegrasyon Bilgileri - Mağaza paneline giriş yaparak BİLGİ sayfasından alabilirsiniz.
         $merchant_key 	= AyarModel::defaultAyarlar('paytrMerchantKey');
         $merchant_salt	= AyarModel::defaultAyarlar('paytrMerchantSalt');
         ###########################################################################

         ####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
         #
         ## POST değerleri ile hash oluştur.
         $hash = base64_encode( hash_hmac('sha256', Request::merchant_oid().$merchant_salt.Request::status().Request::total_amount(), $merchant_key, true) );
         //$hash = "123456789";
         #
         ## Oluşturulan hash'i, paytr'dan gelen post içindeki hash ile karşılaştır (isteğin paytr'dan geldiğine ve değişmediğine emin olmak için)
         ## Bu işlemi yapmazsanız maddi zarara uğramanız olasıdır.
        /* if( $hash != Request::hash() ) {
             die('PAYTR notification failed: bad hash');
         }*/
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


         $faturaBilgi = explode('80108',Request::merchant_oid());
         $faturaId = $faturaBilgi[0];
         $faturaDetay = FaturaModel::detay($faturaId);
         $cariDetay = CariModel::detay($faturaDetay->musteri);

         if($faturaDetay->odeme=="1" ){
             echo "OK";
             exit;
         }

         if( Request::status() == 'success' ) { ## Ödeme Onaylandı

             $odendiYap = FaturaModel::odemeDurumDegistir($faturaId,'1',date('Y-m-d'));

             $tutar = $faturaDetay->genel_toplam;

             $defterData = [
                 'kasa'                 => $kasa_hesabi,
                 'islem'                => "t",
                 'hesap'                => "Fatura Tahsilatı: ".$cariDetay->adi,
                 'islem_turu'           => "fatura",
                 'islem_tur_id'         => $faturaId,
                 'aciklama'             => $faturaId." Numaralı Fatura (PAYTR KK) Ödemesi",
                 'gelir'                => $tutar,
                 'gider'                => "0",
                 'mevcut_kasa_toplami'  => KasaModel::kasaToplami()+$tutar,
                 'yil'                  => Date::set('{year}'),
                 'tarih'                => $odeme_tarihi,
                 'islem_yapan'          => $cariDetay->id
             ];

             $kasayaKaydet              = KasaModel::deftereKaydet($defterData);

             $kasaHesapBilgi            = KasaModel::hesapBilgi($kasa_hesabi);

             $kasaHesapTutarGuncelle    = KasaModel::kasaHesabiTutarGuncelle($tutar+$kasaHesapBilgi->tutar,$kasa_hesabi);

             DB::siparislerUpdateId(['odeme_durumu'=>'1'],$faturaDetay->siparis_id);

             $odemeData = [
                 'fatura_id'            =>$faturaId,
                 'tutar'                =>$tutar,
                 'aciklama'             =>'Paytr Ödeme İşlemi Gerçekleştirildi'
             ];

             $faturaOdemeIslemiEkle = FaturaModel::odemeIslemiEkle($odemeData);

             /*
             $ekMailBilgi = "Ödemeniz kredi kartı ekstrenizde ".AyarModel::defaultAyarlar('firmaAdi')." yada PAYTR olarak görünecektir";

             $mailgonder = Email::subject('Ödeme Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                 'konu' => 'Ödeme Bildirimi',
                 'mesaj' => $faturaDetay->id.' numaralı faturanız için '.Date::convert($odeme_tarihi, '{dayInMonth}.{monthInYear-}.{year}').' tarihinde '.$tutar.' TL Tutarında kredi kartı ödemeniz alınmış ve kayıtlarımıza işlenmiştir.<br> Ödemeniz için teşekkür ederiz.<br><hr>'.$ekMailBilgi,
                 'link' => URL::site('faturalar/detay/'.$faturaId),
                 'link_baslik' => 'Fatura Detay',
                 'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                 'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                 'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                 'telefon' => AyarModel::defaultAyarlar('firmaTel'),
             ])->send();
            */

             echo "OK";
             exit;

             /*** Kasa Defterine İşle ***/

             ## BURADA YAPILMASI GEREKENLER
             ## 1) Siparişi onaylayın.
             ## 2) Eğer müşterinize mesaj / SMS / e-posta gibi bilgilendirme yapacaksanız bu aşamada yapmalısınız.
             ## 3) 1. ADIM'da gönderilen payment_amount sipariş tutarı taksitli alışveriş yapılması durumunda
             ## değişebilir. Güncel tutarı $post['total_amount'] değerinden alarak muhasebe işlemlerinizde kullanabilirsiniz.

         } else { ## Ödemeye Onay Verilmedi

             $odemeData = [
                 'fatura_id'     =>Post::merchant_oid(),
                 'tutar'         =>Post::total_amount(),
                 'aciklama'      =>'HATA ! Paytr Ödeme İşlemi Başarısız.['.Request::failed_reason_code().']['.Request::failed_reason_msg().']'
             ];
             $faturaOdemeIslemiEkle = FaturaModel::odemeIslemiEkle($odemeData);

             ## BURADA YAPILMASI GEREKENLER
             ## 1) Siparişi iptal edin.
             ## 2) Eğer ödemenin onaylanmama sebebini kayıt edecekseniz aşağıdaki değerleri kullanabilirsiniz.
             ## $post['failed_reason_code'] - başarısız hata kodu
             ## $post['failed_reason_msg'] - başarısız hata mesajı

             /*Redirect::wait(1)->insert(['bilgi'=>'<div class="callout callout-danger">Kredi kartı ile ödeme işlemi yapılamadı!<br>
                     <div class="alert alert-info">Hata Kodu: '.$failed_reason_code.' Hata Mesajı: '.$failed_reason_msg.'</div>
                     </div>'])->action('cari/bakiyeYukle');*/

             echo "OK";
             exit;

         }


         ## Bildirimin alındığını PayTR sistemine bildir.

     }

    public function s404(){}
}