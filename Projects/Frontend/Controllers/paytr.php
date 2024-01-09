<?php namespace Project\Controllers;

use User,Method,Post,Request,Email,Redirect,URL,Date,DB;
use AyarModel,CariModel,KasaModel,InternalFaturaModel as FaturaModel,SiparisModel;

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
         if( $hash != Request::hash() ) {
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

         $odemeTutari = Request::total_amount();
         $faturaBilgi = explode('80108',Request::merchant_oid());
         $faturaId = $faturaBilgi[0];
         $faturaDetay = FaturaModel::detay($faturaId);
         $cariDetay = CariModel::detay($faturaDetay->musteri);
         $faturaUrunleri = FaturaModel::faturaUrunleri($faturaId);
         $siparisUrunleri = SiparisModel::siparisUrunleri($faturaDetay->siparis_id);

         if($faturaDetay->odeme=="1" ){
             echo "OK";
             exit;
         }

         if( Request::status() == 'success' ) { ## Ödeme Onaylandı

             AyarModel::nelerOluyor($faturaId.' Numaralı fatura ödemesi yapıldı','3',$faturaId);

             $odendiYap = FaturaModel::odemeDurumDegistir($faturaId,'1',date('Y-m-d'));

             $siparisOdenmemisFaturaToplami = FaturaModel::siparisOdenmemisFaturaToplami($faturaDetay->siparis_id);

             if ($odemeTutari>=$siparisOdenmemisFaturaToplami->toplam) {

                $siparisOdendiYap = SiparisModel::odemeDurumDegistir($faturaDetay->siparis_id,'1');

             }

                 if(AyarModel::defaultAyarlar('baslangicTarihiOdemedenSonra')=="1"){

                     foreach ($siparisUrunleri as $sur){

                         $baslangic_tarihi       = Date::set('{year}-{monthInYear}-{dayInMonth}');
                         $bitis_tarihi           = Date::calculate($baslangic_tarihi, AyarModel::odemePeriyoduEklenecekGun($sur->odeme_periyodu).' day');

                         $siparisUrunleriTarihGuncelle = SiparisModel::siparisUrunTarihGuncelle($sur->id,$baslangic_tarihi,$bitis_tarihi);

                         if ($siparisUrunleriTarihGuncelle) {

                             AyarModel::nelerOluyor($sur->siparis.' Numaralı siparişin '.$sur->urun_adi.' ürünü başlangıç bitiş tarihi güncellendi','1',$sur->siparis);

                             $sipariGecmisi = [
                                 'cari'          =>$sur->cari,
                                 'siparis'       =>$sur->siparis,
                                 'aciklama'      =>" Fatura Ödemesi sonrası sistem tarafından ".$sur->urun_adi." ".$sur->notu." ürününün başlangıç tarihi: ".$baslangic_tarihi." - bitis tarihi: ".$bitis_tarihi." olarak değiştirildi",
                                 'guncelleyen'   => $sur->cari
                             ];

                             $urunDurumDegistir = SiparisModel::urunDurumDegistir($sur->id,AyarModel::defaultAyarlar('odemeSonrasiUrunDurumu'));

                             $gecmisEkle = SiparisModel::siparisGesmisEkle($sipariGecmisi);

                             SiparisModel::siparisurunIslemGerekiyor($sur->id,'1','Başlangıç bitiş tarihi güncellendi kontrol gerekiyor. Ürüne ait yapılması gereken bir işlem varsa gerçekleştiriniz.');

                         }

                     }

                 }else{

                     foreach ($siparisUrunleri as $sur) {

                         AyarModel::nelerOluyor($sur->siparis . ' Numaralı siparişin ' . $sur->urun_adi . ' ürünü başlangıç bitiş tarihi güncellendi', '1', $sur->siparis);

                         // Ürün durumunu işlem bekliyor olarak değiştir
                         $urunDurumDegistir = SiparisModel::urunDurumDegistir($sur->id, AyarModel::defaultAyarlar('odemeSonrasiUrunDurumu'));

                         //sipariş geçmişi ekleniyor
                         $sipariGecmisi = [
                             'cari' => $sur->cari,
                             'siparis' => $sur->siparis,
                             'aciklama' => $faturaId." Numaralı Fatura Paytr ile Ödemesi yapıldı.",
                             'guncelleyen' => $sur->cari
                         ];

                         $gecmisEkle = SiparisModel::siparisGesmisEkle($sipariGecmisi);

                         //ürüne işlem yapılması için uyarı eklendi
                         SiparisModel::siparisurunIslemGerekiyor($sur->id, '1', 'Faturanın ödemesi yapıldı, Faturaya ait Ürünlerde yapılması gereken bir işlem varsa gerçekleştiriniz.');
                     }
                 }
                 /*
                  * başlangıç ve bitiş tarihleri ayarlanacak
                  * */

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