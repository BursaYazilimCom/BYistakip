<?php namespace Project\Controllers;

use User,Method,Post,Redirect,AyarModel,CariModel,InternalFaturaModel as FaturaModel,URL,Date;

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


         $faturaId = $post['merchant_oid'];
         $faturaDetay = FaturaModel::detay($faturaId);
         $cariDetay = CariModel::detay($faturaDetay->musteri);

         if($faturaDetay->odeme=="1" ){
             echo "OK";
             exit;
             }

         if( $post['status'] == 'success' ) { ## Ödeme Onaylandı

             $odendiYap = FaturaModel::odemeDurumDegistir($faturaId,1,date('Y-m-d'));

             /*** Kasa Defterine İşle ***/


             $defterData = [
                 'kasa'          =>$kasa_hesabı,
                 'islem'         =>"t",
                 'hesap'         =>"Fatura Tahsilatı: ".$cariDetay->adi,
                 'islem_turu'    =>"fatura",
                 'islem_tur_id'  =>$id,
                 'aciklama'      =>$id." Numaralı Fatura Ödemesi",
                 'gelir'         =>$tutar,
                 'gider'         =>"",
                 'mevcut_kasa_toplami'=>KasaModel::kasaToplami()+$tutar,
                 'yil'           =>Date::set('{year}'),
                 'tarih'         =>$odeme_tarihi,
                 'islem_yapan'   =>$user->id
             ];

             $kasayaKaydet = KasaModel::deftereKaydet($defterData);

             $kasaHesapBilgi = KasaModel::hesapBilgi($kasa_hesabı);

             if ($kasayaKaydet) {
                 $odemData = [
                     'id'  =>$id,
                     'alinan_odeme'        =>$tutar+$faturaDetay->alinan_odeme,
                 ];

                 $faturaOdemeEkle = FaturaModel::odemeEkle($odemData);

                 $kasaHesapTutarGuncelle = KasaModel::kasaHesabiTutarGuncelle($tutar+$kasaHesapBilgi->tutar,$kasa_hesabı);

                 $toplamFaturaOdemesi = $tutar+$faturaDetay->alinan_odeme;

                 if ($toplamFaturaOdemesi>=$faturaDetay->genel_toplam){

                     DB::siparislerUpdateId(['odeme_durumu'=>'1'],$faturaDetay->siparis_id);

                 }

                 if(Post::odendi()=="1"){

                     $faturaOdemeDurumDegistir = FaturaModel::odemeDurumDegistir($faturaDetay->id,"1");

                 }


                 /*BİLDİRİM*/
                 $ekMailBilgi = "";

                 if($bildirim=="1"){

                     $mailgonder = Email::subject('Ödeme Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                         'konu' => 'Ödeme Bildirimi',
                         'mesaj' => $faturaDetay->id.' numaralı faturanız için '.Date::convert($odeme_tarihi, '{dayInMonth}.{monthInYear-}.{year}').' tarihinde '.$tutar.' TL Tutarında ödemeniz alınmış ve kayıtlarımıza işlenmiştir.<br> Ödemeniz için teşekkür ederiz.<br><hr>'.$aciklama,
                         //'link' => URL::site(),
                         //'link_baslik' => 'Tıklayınız',
                         'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                         'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                         'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                         'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                     ])->send();

                     if ($mailgonder) {
                         $ekMailBilgi = "Müşteriye Bilgilendirme Maili Gönderildi !";
                     }else{
                         $ekMailBilgi = "Müşteriye Bilgilendirme Maili Gönderilemedi !".Email::error();
                     }

                 }

                 /*BİLDİRİM*/

                 Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Ödeme Başarı İle Eklendi !.<br>'.$ekMailBilgi.'</div></div>'])->action('siparisler/duzenle/'.$id);

             }else{
                 Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">İşlem sırasında hata oluştu !.</div></div>'])->action(URL::prev());
             }



             /*** Kasa Defterine İşle ***/


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