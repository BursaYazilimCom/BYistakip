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

    public function yenilemeFaturasiOlustur(){




    }


    // "value" => 10 yazan kısmı herkese farklı id vermek için kullanabilirsiniz.
    //bu durumda  OneSignalNotfiy.html dosyasında "user_id",10, yazan yeri değiştirmk gerekiyor.

    public function s404(){}
}