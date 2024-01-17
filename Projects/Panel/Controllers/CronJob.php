<?php namespace Project\Controllers;

use User,Method,DB,Post,Get,Date,XML,CURL,Json,Time,Email;
use AyarModel,PersonelModel,SiparisModel,InternalFaturaModel as FaturaModel,InternalCariModel as CariModel;

class CronJob extends Controller
{
    public function main(){}

    /****************GÖREV ATAMALARI***************/

    public function dovizKurlari(){

        $veri =XML::parseSimpleURL('https://www.tcmb.gov.tr/kurlar/today.xml');

        /*echo "<pre>";
        print_r($veri);
        echo "</pre>";*/

        $dovizAdedi = count($veri->Currency);

        foreach ($veri->Currency as $item) {

            $attributes = $item->attributes();

            $dovizDetay = AyarModel::paraBirimDetay($attributes['CurrencyCode']);

            if (empty($dovizDetay->id)) {

               // echo $item->Isim." Döviz Veri tabanında bulunamadı<br>";

            }else{

                $dolarGuncelle = AyarModel::kurGuncelle($attributes['CurrencyCode'],number_format((float)$item->BanknoteSelling,4));

                if($dolarGuncelle){ echo $item->Isim." Güncellendi<br>"; }else{  echo $item->Isim." Güncellemede HATA OLUŞTU<br>"; }

            }

        }

    }

    public function renewProductYear(){

        $bugun = date('Y/m/d');

        $onBesGunSonra = Date::addDay($bugun,15).' 00:00:00';

        $yillikPeriyodUrunleri = UrunModel::uyeUrunleriSonOtuz('year',$onBesGunSonra);


        foreach ($yillikPeriyodUrunleri as $pu){

            $uyeBilgi = UyeModel::detay($pu->uye);

            $urunTipi = AyarModel::urunTipleri($pu->urun_tipi);

            Email::from(AyarModel::defaultAyarlar('smtpUser'), 'BURSA YAZILIM', AyarModel::defaultAyarlar('smtpUser'))
                ->to($uyeBilgi->email, 'To')
                ->bcc(AyarModel::defaultAyarlar('iletisimEposta'), 'To 1')
                ->subject($pu->urun_adi.' '.$urunTipi.' Ürününüzün son kullanım tarihi yaklaşıyor')
                ->template('general', [
                    'name'          => $uyeBilgi->adi_soyadi,
                    'subject'       => $pu->urun_adi.' '.$urunTipi.' Ürününüzün son kullanım tarihi yaklaşıyor',
                    'content'       => $pu->urun_adi.' '.$urunTipi.' Ürününüz '.Date::convert($pu->bitis_tarihi,'{dayNumber0}.{monthNumber0}.{year}').' tarihinde ödemesi yapılmadığı taktirde kullanım süresi dolacaktır.<br>Bu süre geçtikten sonra ürününüzün durumuna göre kurtarma ücreti yansıtılabilir. <br>Aşağıdaki bulunan linkten Panelinize girerek gerekli süre uzatma işlemlerini gerçekleştirebilirsiniz<br><br> Eğer ödeme yaptıysanız lütfen dikkate almayınız. ',
                    'href'          => URL::site('userPanel'),
                    'hrefButton'    => 'Paneliniz'
                ])
                ->send();

        }


        echo "<pre>";
        //print_r($yillikPeriyodUrunleri);

        echo "</pre>";


    }

    public function faturaHatirlat(){

        $bugun = date('Y-m-d');

        $onBesGunSonra = Date::addDay($bugun,15).' 00:00:00';

        $faturalar = FaturaModel::tarihSonrasiOdenmemisFaturalar($onBesGunSonra);

        foreach ($faturalar as $fatura) {

            $cariBilgi = CariModel::detay($fatura->musteri);

            $mailgonder = Email::subject('Fatura Ödeme Hatırlatma')
                ->from(AyarModel::defaultAyarlar('iletisimEposta'))
                ->to($cariBilgi->email, 'To')
                ->bcc(AyarModel::defaultAyarlar('iletisimEposta'), 'To 1')
                ->template('by', [

                'konu' => $fatura->id.' Numaralı Faturanızın ödemesi',
                'mesaj' => $fatura->id.' Numaralı faturanızın '.Date::convert($fatura->vade_tarihi,'{dayNumber0}.{monthNumber0}.{year}').' tarihinde ödemesi yapılması gerekmektedir.<br>Ürünlerinizin yada projelerinizin kesintiye uğramaması için ödeme yapmanız gerekmektedir. Bu süre geçtikten sonra ürününüzün durumuna göre kurtarma ücreti yansıtılabilir. <br> Aşağıdaki bulunan linkten faturanızı görüntüleyerek ödeme işlemini gerçekleştirebilirsiniz.<br><br> Eğer ödeme yaptıysanız lütfen dikkate almayınız. ',
                'link' => AyarModel::defaultAyarlar('siteUrl')."/faturalar/detay/".$fatura->id,
                'link_baslik' => 'Fatura Görüntüle',
                'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                'telefon' => AyarModel::defaultAyarlar('firmaTel'),
            ])->send();
            

        }


    }

    public function aylikYenilemeFaturasiOlustur(){
        $tur = "A";

        $bugun = date('Y-m-d');

        $tarih = Date::addDay($bugun,AyarModel::defaultAyarlar('yillikUrunFaturaGunu')).' 00:00:00';

        echo $bugun;
        echo "<hr>";
        echo $tarih;

        echo "<pre>";

        $tumSiparisler = SiparisModel::yenilenecekSiparisler($tarih,$tur);
        //print_r($tumSiparisler);
        foreach ($tumSiparisler as $siparis) {

            $cariBilgi = CariModel::detay($siparis->cari);

            $siparisurunleri = SiparisModel::yenilenecekSiparisUrunleri($tarih,$siparis->id,$tur);
            $yenilenecekurunsayisi = count($siparisurunleri);

            $faturaUrunSayi = 1;
            foreach ($siparisurunleri as $su) {

                if($faturaUrunSayi==1){
                    $toplamFiyat = "";
                    //eğer siparişteki ürün adeti 1 den fazla ile fatura oluşturmak için fiyat toplamlarını alıyoruz
                    foreach ($siparisurunleri as $sut) {
                        $toplamFiyat = $toplamFiyat+$sut->birim_fiyat * $sut->adet;
                        $kdvToplami = $kdvToplami+($toplamFiyat*$sut->kdv/100);
                        //fiyat birimine egöre güncel kur alınacak
                    }

                    $faturaData = [
                        'tur'               =>"2",
                        'satis_turu'        =>"1",
                        'belge_no'          =>rand(0,999999),
                        'fatura_adi'        =>$cariBilgi->firma_adi,
                        'fatura_adresi'     =>$cariBilgi->fatura_adresi,
                        'vergi_dairesi'     =>$cariBilgi->vergi_dairesi,
                        'vergi_no'          =>$cariBilgi->vergi_no,
                        'tedarikci'         =>"0",
                        'musteri'           =>$cariBilgi->id,
                        'siparis_id'        =>$siparis->id,
                        'toplam_tutar'      =>$toplamTutar,
                        'kdv_toplami'       =>$kdvToplami,
                        'genel_toplam'      =>$kdvToplami+$toplamTutar,
                        'belge_tarihi'      =>$siparis_tarihi,
                        'vade_tarihi'       =>Date::addDay($siparis_tarihi, 5),
                        'durum'             =>"1",
                        'odeme'             =>$odeme_durumu,
                        'odeme_yontemi'     =>$odeme_yontemi,
                        'aciklama'          =>""
                    ];

                    $faturaOlustur = FaturaModel::ekle($faturaData);

                }



                if ($faturaOlustur){

                    $siparisUrunleriDB = SiparisModel::siparisUrunleri($siparisOlustur);

                    foreach ($siparisUrunleriDB as $suDb){

                        $fUrun = [
                            'fatura'                =>$faturaOlustur,
                            'urun'                  =>$suDb->urun,
                            'siparis_urun_id'       =>$suDb->id,
                            'eklenecek_gun_sayisi'  =>AyarModel::odemePeriyoduEklenecekGun($suDb->odeme_periyodu),
                            'urun_adi'              =>$suDb->urun_adi,
                            'aciklama'              =>$suDb->notu,
                            'miktar'                =>$suDb->adet,
                            'fiyat'                 =>$suDb->birim_fiyat,
                            'kdv'                   =>$suDb->kdv,
                            'kdv_tutari'            =>$suDb->kdv_tutari,
                            'tutar'                 =>$suDb->toplam_fiyat,
                        ];

                        $faturaUrunEkle = FaturaModel::urunEkle($fUrun);

                    }

                }

                $faturaUrunSayi++;
            }


        }


        //$yillikPeriyodUrunleri = UrunModel::uyeUrunleriSonOtuz('year',$onBesGunSonra);


        /*foreach ($yillikPeriyodUrunleri as $pu){

            $uyeBilgi = UyeModel::detay($pu->uye);

            $urunTipi = AyarModel::urunTipleri($pu->urun_tipi);

            Email::from(AyarModel::defaultAyarlar('smtpUser'), 'BURSA YAZILIM', AyarModel::defaultAyarlar('smtpUser'))
                ->to($uyeBilgi->email, 'To')
                ->bcc(AyarModel::defaultAyarlar('iletisimEposta'), 'To 1')
                ->subject($pu->urun_adi.' '.$urunTipi.' Ürününüzün son kullanım tarihi yaklaşıyor')
                ->template('general', [
                    'name'          => $uyeBilgi->adi_soyadi,
                    'subject'       => $pu->urun_adi.' '.$urunTipi.' Ürününüzün son kullanım tarihi yaklaşıyor',
                    'content'       => $pu->urun_adi.' '.$urunTipi.' Ürününüz '.Date::convert($pu->bitis_tarihi,'{dayNumber0}.{monthNumber0}.{year}').' tarihinde ödemesi yapılmadığı taktirde kullanım süresi dolacaktır.<br>Bu süre geçtikten sonra ürününüzün durumuna göre kurtarma ücreti yansıtılabilir. <br>Aşağıdaki bulunan linkten Panelinize girerek gerekli süre uzatma işlemlerini gerçekleştirebilirsiniz<br><br> Eğer ödeme yaptıysanız lütfen dikkate almayınız. ',
                    'href'          => URL::site('userPanel'),
                    'hrefButton'    => 'Paneliniz'
                ])
                ->send();

        }*/


        echo "<pre>";
        //print_r($yillikPeriyodUrunleri);

        echo "</pre>";




    }


    // "value" => 10 yazan kısmı herkese farklı id vermek için kullanabilirsiniz.
    //bu durumda  OneSignalNotfiy.html dosyasında "user_id",10, yazan yeri değiştirmk gerekiyor.

    public function s404(){}
}