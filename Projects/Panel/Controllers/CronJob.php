<?php namespace Project\Controllers;

use User,Method,DB,Post,Get,Date,XML,CURL,Json,Time,Email,URL,Masterpage;
use AyarModel,PersonelModel,SiparisModel,InternalFaturaModel as FaturaModel,InternalCariModel as CariModel,UrunModel;

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

    public function yenilemeFaturasiOlustur($tur=""){

        if ($tur=="") {
            echo "<h3>Yenileme Faturası oluşturabilmek için oluşturulacak fatura döngüsünü belirtiniz.</h3><ul style='line-height: 30px'>";
            echo "<li><strong>Aylık döngüler için: </strong>".URL::site('cronJob/yenilemeFaturasiOlustur/A')."</li>";
            echo "<li><strong>3 Aylık döngüler için: </strong>".URL::site('cronJob/yenilemeFaturasiOlustur/3A')."</li>";
            echo "<li><strong>6Aylık döngüler için: </strong>".URL::site('cronJob/yenilemeFaturasiOlustur/6A')."</li>";
            echo "<li><strong>Yıllık döngüler için: </strong>".URL::site('cronJob/yenilemeFaturasiOlustur/Y')."</li>";
            echo "<li><strong>Faturaların otomatik oluşturulması için belirleyeceğiniz aralıklarda üstteki adresleri CronJob çalıştırılması gerekmektedir.</li></ul>";
            echo "Aşağıdaki kodu cronTab'a eklerseniz 30dk aralıklarla Aylık ödeme periyodlu siparişleri kontrol ederek süresi gelen ürünlerin faturalarını oluşturur<br>";
            echo "<pre>";
            echo "*/30 * * * * curl -L -s ".URL::site('cronJob/yenilemeFaturasiOlustur/')."<strong>A</strong> >/dev/null 2>&1";
            echo "</pre>";
            exit();
        }
        if ($tur=="A") {
            $faturaGunu = AyarModel::defaultAyarlar('aylikurunFaturaGunu');
        }elseif ($tur=="3A") {
            $faturaGunu = AyarModel::defaultAyarlar('3aylikurunFaturaGunu');
        }elseif ($tur=="6A") {
            $faturaGunu = AyarModel::defaultAyarlar('6aylikurunFaturaGunu');
        }elseif ($tur=="Y") {
            $faturaGunu = AyarModel::defaultAyarlar('yillikUrunFaturaGunu');
        }

        $bugun = date('Y-m-d');

        $tarih = Date::addDay($bugun,$faturaGunu);

        echo $bugun;
        echo "<hr>";
        echo $tarih;

        echo "<pre>";

        $tumSiparisler = SiparisModel::yenilenecekSiparisler($tarih,$tur);

        //print_r($tumSiparisler);
        foreach ($tumSiparisler as $siparis) {

            $cariBilgi = CariModel::detay($siparis->cari);
            $faturaID= "";

            $siparisurunleri = SiparisModel::yenilenecekSiparisUrunleri($tarih,$siparis->id,$tur);
            $yenilenecekurunsayisi = count($siparisurunleri);

            //sipariş ürünleri döndür
            $faturaUrunSayi = 1;
            foreach ($siparisurunleri as $su) {

                $donemBaslamaTarihi = $su->bitis_tarihi;

                //siparis ürünleri bütüş tarihlerine uzatılmış bitiş tarihini ekle

                if ($su->odeme_periyodu == "A") {
                    $donemBitisTarihi = date("Y-m-d", strtotime("+1 month", strtotime($su->bitis_tarihi)));
                }elseif ($su->odeme_periyodu == "3A"){
                    $donemBitisTarihi = date("Y-m-d", strtotime("+3 month", strtotime($su->bitis_tarihi)));
                }elseif ($su->odeme_periyodu == "6A"){
                    $donemBitisTarihi = date("Y-m-d", strtotime("+6 month", strtotime($su->bitis_tarihi)));
                } elseif ($su->odeme_periyodu == "Y") {
                    $donemBitisTarihi = date("Y-m-d", strtotime("+12 month", strtotime($su->bitis_tarihi)));
                }else{
                    echo "odeme periyodu bulunamadı";
                    exit();
                }

                //ilgili sipariş ürününün uzatma faturası daha önceden oluşturulmuşmu kontrol ediliyor
                $faturaTekrarKontrolu = FaturaModel::faturaTekrarKonrolu($su->bitis_tarihi,$donemBitisTarihi,$su->id);


                //Eğer ilgili fatura daha önce oluşturulmuşşa
                if ($faturaTekrarKontrolu['adet']>0) {

                    //daha önce oluşturulan son faturanın ürün bilgileri
                    $ilgiliFaturaUrunBilgileri = $faturaTekrarKontrolu['data'];
                    // daha önce oluşturulan fatura bilgileri
                    $faturaDetay = FaturaModel::detay($ilgiliFaturaUrunBilgileri->fatura);

                    //daha önce oluşturulan faturaya göre bir sonraki fatura kesilecek tarihi buluyoruz
                    $sonrakiFaturaKesimTarihi = Date::removeDay($ilgiliFaturaUrunBilgileri->donem_bitis_tarihi,$faturaGunu);

                    //$sonrakiFaturaKesimTarihi = Date::removeDay($bugun,$faturaGunu);

                    $bugun = date('Y-m-d');

                    //eğer faha önce oluşturulan faturanın ödemesi yapılmamış ve bugün sonraki fatura kesim tarihi yada sonraki kesim tarihi geçmiş ise ilgili fautarya göre tekrar fatura oluşturulması için güncelliyoruz
                    if ($faturaDetay->odeme=="0" and $sonrakiFaturaKesimTarihi <= $bugun) {

                        $donemBaslamaTarihi = $ilgiliFaturaUrunBilgileri->donem_bitis_tarihi;
                        //echo "DBT1".$donemBaslamaTarihi."<br>";

                        if ($su->odeme_periyodu == "A") {
                            $donemBitisTarihi = date("Y-m-d", strtotime("+1 month", strtotime($donemBaslamaTarihi)));
                        }elseif ($su->odeme_periyodu == "3A"){
                            $donemBitisTarihi = date("Y-m-d", strtotime("+3 month", strtotime($donemBaslamaTarihi)));
                        }elseif ($su->odeme_periyodu == "6A"){
                            $donemBitisTarihi = date("Y-m-d", strtotime("+6 month", strtotime($donemBaslamaTarihi)));
                        } elseif ($su->odeme_periyodu == "Y") {
                            $donemBitisTarihi = date("Y-m-d", strtotime("+12 month", strtotime($donemBaslamaTarihi)));
                        }else{
                            echo "odeme periyodu bulunamadı";
                            exit();
                        }

                    }else{

                    }


                }

                $faturaTekrarKontrolu2 = FaturaModel::faturaTekrarKonrolu($donemBaslamaTarihi,$donemBitisTarihi,$su->id);

                if($faturaTekrarKontrolu2['adet']==0){

                    if($faturaID==""){
                        $toplamFiyat = 0;
                        $kdvToplami = 0;
                        //eğer siparişteki ürün adeti 1 den fazla ile fatura oluşturmak için fiyat toplamlarını alıyoruz
                        foreach ($siparisurunleri as $sut) {
                            //fiyat birimine egöre güncel kur alınacak
                            if($sut->fiyat_sabitle=="0"){
                                //Ürünün güncel fiyatını öğreniyoruz
                                $urunBilgi = UrunModel::detay($sut->urun);
                                //ödeme periyoduna göre fiyatı hesaplıyoruz
                                if ($sut->odeme_periyodu == "A") {
                                    $urunFiyati = $urunBilgi->aylik_fiyat;
                                }elseif ($sut->odeme_periyodu == "3A"){
                                    $urunFiyati = $urunBilgi->uc_aylik_fiyat;
                                }elseif ($sut->odeme_periyodu == "6A"){
                                    $urunFiyati = $urunBilgi->alti_aylik_fiyat;
                                } elseif ($sut->odeme_periyodu == "Y") {
                                    $urunFiyati = $urunBilgi->yillik_fiyat;
                                }

                                $toplamFiyat = $toplamFiyat+$urunFiyati * $sut->adet;
                                $kdvToplami = $kdvToplami+($toplamFiyat*$sut->kdv/100);

                            }else{
                                $toplamFiyat = $toplamFiyat+$sut->birim_fiyat * $sut->adet;
                                $kdvToplami = $kdvToplami+($toplamFiyat*$sut->kdv/100);
                                $urunFiyati = $sut->birim_fiyat;
                            }
                            $toplamTutar = $toplamFiyat+$kdvToplami;

                        }

                        $faturaData = [
                            'tur'               =>"2",
                            'satis_turu'        =>"2",
                            'belge_no'          =>rand(0,999999),
                            'fatura_adi'        =>$cariBilgi->firma_adi,
                            'fatura_adresi'     =>$cariBilgi->fatura_adresi,
                            'vergi_dairesi'     =>$cariBilgi->vergi_dairesi,
                            'vergi_no'          =>$cariBilgi->vergi_no,
                            'tedarikci'         =>"0",
                            'musteri'           =>$cariBilgi->id,
                            'siparis_id'        =>$siparis->id,
                            'toplam_tutar'      =>AyarModel::tlCevir($toplamFiyat,$su->para_birimi),
                            'kdv_toplami'       =>AyarModel::tlCevir($kdvToplami,$su->para_birimi),
                            'genel_toplam'      =>AyarModel::tlCevir($toplamTutar,$su->para_birimi),
                            'belge_tarihi'      =>date("Y-m-d"),
                            'vade_tarihi'       =>$donemBitisTarihi,
                            'durum'             =>"1",
                            'odeme'             =>"0",
                            'odeme_yontemi'     =>$siparis->odeme_yontemi,
                            'aciklama'          =>$siparis->id." Numaralı sipariş yenileme faturası"
                        ];

                        $faturaID = FaturaModel::ekle($faturaData);

                        if ($faturaID) {
                            echo $su->id." Numaralı sipariş ürünü için  ".$faturaID." Numaralı yenileme faturası Oluşturuldu<br>";
                        }else{
                            echo $su->id." Numaralı sipariş ürünü için yenileme faturası oluşturulamadı<br>";
                        }

                    }
                    $urunTLFiyat = AyarModel::tlCevir($urunFiyati,$su->para_birimi);
                    $urunKdvTutari = $su->kdv*$urunTLFiyat/100;

                    $urunToplamFiyat = ($urunTLFiyat*$su->adet)+$urunKdvTutari;


                    $fUrun = [
                        'fatura'                =>$faturaID,
                        'urun'                  =>$su->urun,
                        'siparis_urun_id'       =>$su->id,
                        'eklenecek_gun_sayisi'  =>AyarModel::odemePeriyoduEklenecekGun($su->odeme_periyodu),
                        'donem_baslangic_tarihi'=>$donemBaslamaTarihi,
                        'donem_bitis_tarihi'    =>$donemBitisTarihi,
                        'urun_adi'              =>$su->urun_adi,
                        'aciklama'              =>$su->notu,
                        'miktar'                =>$su->adet,
                        'fiyat'                 =>$urunTLFiyat,
                        'kdv'                   =>$su->kdv,
                        'kdv_tutari'            =>$urunKdvTutari,
                        'tutar'                 =>$urunToplamFiyat,
                    ];

                    //print_r($fUrun);

                    $faturaUrunEkle = FaturaModel::urunEkle($fUrun);

                    if ($faturaUrunEkle) {
                        echo $su->id." Numaralı sipariş ürünü için oluşturulan  ".$faturaID." Numaralı yenileme faturasına fatura ürünü eklendi<br>";
                    }else{
                        echo $su->id." Numaralı sipariş ürünü için yenileme faturası oluşturulamadı<br>";
                    }


                }else{

                    echo $su->id." Numaralı sipariş ürünü için yenileme faturası daha önce oluşturulmuş<br>";
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