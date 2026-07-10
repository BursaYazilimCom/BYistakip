<?php namespace Project\Controllers;

use Method, Post,URL, Redirect,Date,Pagination,User,Email,DB,Json;
use TedarikciModel, FaturaModel,AyarModel,MalzemeModel,KasaModel,SiparisModel,CariModel,InternalSmsModel as SmsModel;

class Kasa extends Controller
{

    public function __construct()
    {
        $user                   = User::data();
        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array(CURRENT_CONTROLLER,$yetkiler)){

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div>'])->action('home');

        }
    }

    public function main()
    {

        $user               = User::data();

        $kasaHesaplari      = KasaModel::turHesaplari(1);

        foreach($kasaHesaplari as $kht){

            $khtKasaToplami = KasaModel::kasaGelirGiderToplami($kht->id);
            $kToplam[$kht->id] = $khtKasaToplami->gelir-$khtKasaToplami->gider;
        }

     
        $bankaHesaplari     = KasaModel::turHesaplari(2);
        foreach($bankaHesaplari as $bht){

            $bhtKasaToplami = KasaModel::kasaGelirGiderToplami($bht->id);
            $kToplam[$bht->id] = $bhtKasaToplami->gelir-$bhtKasaToplami->gider;
        }
        $posHesaplari       = KasaModel::turHesaplari(3);

        foreach($posHesaplari as $pht){

            $phtKasaToplami = KasaModel::kasaGelirGiderToplami($pht->id);
            $kToplam[$pht->id] = $phtKasaToplami->gelir-$phtKasaToplami->gider;
        }

        $kkartiHesaplari    = KasaModel::turHesaplari(4);
        foreach($kkartiHesaplari as $kkht){

            $kkhtKasaToplami = KasaModel::kasaGelirGiderToplami($kkht->id);
            $kToplam[$kkht->id] = $kkhtKasaToplami->gelir-$kkhtKasaToplami->gider;
        }
        $veresiyeHesaplari  = KasaModel::turHesaplari(5);

        foreach($veresiyeHesaplari as $vht){

            $vhtKasaToplami = KasaModel::kasaGelirGiderToplami($vht->id);
            $kToplam[$vht->id] = $vhtKasaToplami->gelir-$vhtKasaToplami->gider;
        }

        $digerHesaplar      = KasaModel::turHesaplari(6);

        foreach($digerHesaplar as $dht){

            $dhtKasaToplami = KasaModel::kasaGelirGiderToplami($dht->id);
            $kToplam[$dht->id] = $dhtKasaToplami->gelir-$dhtKasaToplami->gider;
        }


        View::kToplam($kToplam);
        View::kasaHesaplari($kasaHesaplari);
        View::bankaHesaplari($bankaHesaplari);
        View::posHesaplari($posHesaplari);
        View::kkartiHesaplari($kkartiHesaplari);
        View::veresiyeHesaplari($veresiyeHesaplari);
        View::digerHesaplar($digerHesaplar);

       // AyarModel::nelerOluyor($user->isim,'kasa','Kasa yönetimine bakıyor');

    }

    public function odemeEkle($yer,$id=""){
        $user = User::data();
  
        $user           = User::data();
        $kasa_hesabi    = Post::kasa();
        $aciklama       = Post::aciklama();
        $tutar          = Post::tutar();
        $odeme_tarihi   = Post::odeme_tarihi();
        $bildirim       = Post::bildirim();
        $uzat           = Post::uzat();
        $cari           = Post::cari();

        if ($yer=="siparis"){

            $siparisDetay = SiparisModel::detay($id);
            $cariDetay = CariModel::detay($siparisDetay->cari);

            $defterData = [
                'kasa'          =>$kasa_hesabi,
                'islem'         =>"t",
                'hesap'         =>"Sipariş Ödemesi Tahsilatı: ".$cariDetay->adi,
                'islem_turu'    =>"siparis",
                'islem_tur_id'  =>$id,
                'aciklama'      =>$id." Numaralı Siparişin Ödemesi",
                'gelir'         =>$tutar,
                'gider'         =>"",
                'mevcut_kasa_toplami'=>KasaModel::kasaToplami()+$tutar,
                'yil'           =>Date::set('{year}'),
                'tarih'         =>$odeme_tarihi,
                'islem_yapan'   =>$user->id
            ];

            $kasayaKaydet = KasaModel::deftereKaydet($defterData);

            $kasaHesapBilgi = KasaModel::hesapBilgi($kasa_hesabi);

            if ($kasayaKaydet) {

                $kasaHesapTutarGuncelle = KasaModel::kasaHesabiTutarGuncelle($tutar+$kasaHesapBilgi->tutar,$kasa_hesabi);

                $toplamSiparisOdemesi = $tutar+$siparisDetay->alinan_odeme;

                if ($toplamSiparisOdemesi>=$siparisDetay->genel_toplam_tutari){

                    DB::siparislerUpdateId(['odeme_durumu'=>'1'],$id);

                }

                /*BİLDİRİM*/
                $ekMailBilgi = "";

                if($bildirim=="1"){

                    //SMS GÖNDERİMİ
                    if(AyarModel::defaultAyarlar('smsGonderim')=="1"){

                        $smsData = [
                            'mesaj'=>$siparisDetay->id.' numaralı sipariniz için '.number_format($tutar,2).' TL ödemeniz alınmış ve kayıtlarımıza işlenmiştir.Ödemeniz için teşekkür ederiz.',
                            'numara'=>$cariDetay->gsm
                        ];

                        $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);

                    }
                    //SMS GÖNDERİMİ

                    $mailgonder = Email::subject('Ödeme Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                        'konu' => 'Ödeme Bildirimi',
                        'mesaj' => $siparisDetay->id.' numaralı sipariniz için '.Date::convert($odeme_tarihi, '{dayInMonth}.{monthInYear-}.{year}').' tarihinde '.$tutar.' TL Tutarında ödemeniz alınmış ve kayıtlarımıza işlenmiştir.<br> Ödemeniz için teşekkür ederiz.<br><hr>'.$aciklama,
                        'link' => URL::site(),
                        'link_baslik' => 'Tıklayınız',
                        'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                        'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
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

        }
        elseif ($yer=="fatura"){

            $faturaDetay = FaturaModel::detay($id);
            $faturaUrunleri = FaturaModel::faturaUrunleri($faturaDetay->id);

            $cariDetay = CariModel::detay($faturaDetay->musteri);

            $defterData = [
                'kasa'          =>$kasa_hesabi,
                'islem'         =>"t",
                'hesap'         =>"Fatura Tahsilatı: ".$cariDetay->adi,
                'islem_turu'    =>"fatura",
                'islem_tur_id'  =>$id,
                'aciklama'      =>$id." Numaralı Fatura Ödemesi",
                'gelir'         =>$tutar,
                'gider'         =>"0",
                'mevcut_kasa_toplami'=>KasaModel::kasaToplami()+$tutar,
                'yil'           =>Date::set('{year}'),
                'tarih'         =>$odeme_tarihi,
                'islem_yapan'   =>$user->id
            ];

            $toplamAlinanOdeme = $faturaDetay->alinan_odeme+$tutar;

            $kasayaKaydet = KasaModel::deftereKaydet($defterData);

            $kasaHesapBilgi = KasaModel::hesapBilgi($kasa_hesabi);

            if ($kasayaKaydet) {

                //Fatura ödendi yapılyıor
                if(Post::odendi()=="1"){

                    $faturaOdemeDurumDegistir = FaturaModel::odemeDurumDegistir($faturaDetay->id,"1");

                }
                
                //Eğer süre uzatma seçildiyse ürünlerin süresini uzat
                $gecmisNotu = "";
                if ($uzat) {

                    foreach ($faturaUrunleri as $fu) {
                        $siparisUrunBilgi = SiparisModel::siparisUrunBilgi($fu->siparis_urun_id);
                        $eklenecekGun = AyarModel::odemePeriyoduEklenecekGun($siparisUrunBilgi->odeme_periyodu);

                        $baslangic_tarihi       = $siparisUrunBilgi->bitis_tarihi;
                        $bitis_tarihi           = Date::calculate($baslangic_tarihi, $eklenecekGun.' day');

                        $siparisUrunleriTarihGuncelle = SiparisModel::siparisUrunTarihGuncelle($siparisUrunBilgi->id,$baslangic_tarihi,$bitis_tarihi);

                        //Fatura ödendi yapıldıktan sonra ilgili ürüne tedarikçiden işlem yapılması için uyarı giriliyor
                        //
                        if(Post::odendi()=="1"){

                            SiparisModel::siparistekilUrunIslemGerekiyor($siparisUrunBilgi->id, '1', 'Ürünün Faturası ödendi olarak işaretlendi. Üründe yapılması gereken bir işlem varsa gerçekleştiriniz.');

                        }

                        if($siparisUrunleriTarihGuncelle){
                            $uzatmaUyarisi = $siparisUrunBilgi->notu." ürünü ".$bitis_tarihi." tarihine kadar süresi uzatıldı !";
                            $gecmisNotu =  "<br>".$siparisUrunBilgi->notu." ürünü ".$bitis_tarihi." tarihine kadar süresi uzatıldı !";
                        }else{
                            $uzatmaUyarisi = $siparisUrunBilgi->notu." ürünü uzatma işlemi yapılamadı !";
                        }

                    }
                    
                }

                //Sipariş geçmişine yapılan ödemeyi ekle

                //sipariş geçmişi ekleniyor
                $sipariGecmisi = [
                    'cari' => $faturaDetay->musteri,
                    'siparis' => $faturaDetay->siparis_id,
                    'aciklama' => $faturaDetay->id." Numaralı Fatura ödemesi ".$kasaHesapBilgi->adi." kasa hesabına kaydedildi.".$gecmisNotu,
                    'guncelleyen' => $user->id
                ];

                $gecmisEkle = SiparisModel::siparisGesmisEkle($sipariGecmisi);


                $odemData = [
                    'id'  =>$id,
                    'alinan_odeme'        =>$tutar+$faturaDetay->alinan_odeme,
                ];

                $faturaOdemeEkle = FaturaModel::odemeEkle($odemData);

                $kasaHesapTutarGuncelle = KasaModel::kasaHesabiTutarGuncelle($tutar+$kasaHesapBilgi->tutar,$kasa_hesabi);

                $toplamFaturaOdemesi = $tutar+$faturaDetay->alinan_odeme;

                if ($toplamFaturaOdemesi>=$faturaDetay->genel_toplam){

                    DB::siparislerUpdateId(['odeme_durumu'=>'1'],$faturaDetay->siparis_id);

                }

                //Eğer toplam odeme fatura tutarından büyük yada eşitle sipariş ödendi yaplıyor
                if($faturaDetay->genel_toplam==$toplamAlinanOdeme or $faturaDetay->genel_toplam<$toplamAlinanOdeme){
                    $siparisOdemeDurumDegistir = SiparisModel::odemeDurumDegistir($faturaDetay->siparis_id,"1");
                }elseif($toplamAlinanOdeme<$faturaDetay->genel_toplam){
                    //Eğer toplam odeme fatura tutarından küçükse sipariş kısmi ödendi yaplıyor
                    $siparisOdemeDurumDegistir = SiparisModel::odemeDurumDegistir($faturaDetay->siparis_id,"2");

                }else{

                }

                //Siparis ödendi yapılyıor
                /*if(Post::siparisOdendi()=="1"){

                    $faturaOdemeDurumDegistir = SiparisModel::odemeDurumDegistir($faturaDetay->siparis_id,"1");

                }*/


                /*BİLDİRİM*/
                $ekMailBilgi = "";

                if($bildirim=="1"){


                    //SMS GÖNDERİMİ
                    if(AyarModel::defaultAyarlar('smsGonderim')=="1"){

                        $smsData = [
                            'mesaj'=>$faturaDetay->id.' numaralı faturanız için '.$tutar.' TL Tutarında ödemeniz alınmış ve kayıtlarımıza işlenmiştir.Ödemeniz için teşekkür ederiz',
                            'numara'=>$cariDetay->gsm
                        ];

                        $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);

                    }
                    //SMS GÖNDERİMİ



                    $mailgonder = Email::subject('Ödeme Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                        'konu' => 'Ödeme Bildirimi',
                        'mesaj' => $faturaDetay->id.' numaralı faturanız için '.Date::convert($odeme_tarihi, '{dayInMonth}.{monthInYear-}.{year}').' tarihinde '.$tutar.' TL Tutarında ödemeniz alınmış ve kayıtlarımıza işlenmiştir.<br> Ödemeniz için teşekkür ederiz.<br><hr>'.$aciklama,
                        //'link' => URL::site(),
                        //'link_baslik' => 'Tıklayınız',
                        'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                        'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
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

                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Ödeme Başarı İle Eklendi !.<br>'.$ekMailBilgi.'</div></div>'])->action(URL::prev());

            }else{
                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">İşlem sırasında hata oluştu !.</div></div>'])->action(URL::prev());
            }



        }
        elseif ($yer=="tahsilat"){
            

            $cariDetay = CariModel::detay($cari);

            $defterData = [
                'kasa'          =>$kasa_hesabi,
                'islem'         =>"t",
                'hesap'         =>"Harici Tahsilat: ".$cariDetay->adi,
                'islem_turu'    =>"cari",
                'islem_tur_id'  =>$cari,
                'aciklama'      =>$cariDetay->adi." isimli Cari harici ödeme yaptı.".$aciklama,
                'gelir'         =>$tutar,
                'gider'         =>"0",
                'mevcut_kasa_toplami'=>KasaModel::kasaToplami()+$tutar,
                'yil'           =>Date::set('{year}'),
                'tarih'         =>$odeme_tarihi,
                'islem_yapan'   =>$user->id
            ];

            $kasayaKaydet = KasaModel::deftereKaydet($defterData);

            $kasaHesapBilgi = KasaModel::hesapBilgi($kasa_hesabi);

            if ($kasayaKaydet) {
                //Sipariş geçmişine yapılan ödemeyi ekle

                $ekMailBilgi = "";

                $kasaHesapTutarGuncelle = KasaModel::kasaHesabiTutarGuncelle($tutar+$kasaHesapBilgi->tutar,$kasa_hesabi);

                if($bildirim=="1"){

                    //SMS GÖNDERİMİ
                    if(AyarModel::defaultAyarlar('smsGonderim')=="1"){

                        $smsData = [
                            'mesaj'=>'Sayın. '.$cariDetay->adi.' '.number_format($tutar,2).' TL ödemeniz alınmış ve kayıtlarımıza işlenmiştir.Ödemeniz için teşekkür ederiz.',
                            'numara'=>$cariDetay->gsm
                        ];

                        $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);

                    }
                    //SMS GÖNDERİMİ

                    $mailgonder = Email::subject('Tahsilat Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                        'konu' => 'Tahsilat Bildirimi',
                        'mesaj' => 'Sayın. '.$cariDetay->adi.' '.Date::convert($odeme_tarihi, '{dayInMonth}.{monthInYear-}.{year}').' tarihinde '.$tutar.' TL Tutarında ödemeniz alınmış ve kayıtlarımıza işlenmiştir.<br> Ödemeniz için teşekkür ederiz.<br><hr>'.$aciklama,
                        //'link' => URL::site(),
                        //'link_baslik' => 'Tıklayınız',
                        'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                        'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
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

                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Tahsilat Başarı İle Eklendi !.<br>'.$ekMailBilgi.'</div></div>'])->action(URL::prev());

            }else{
                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">İşlem sırasında hata oluştu !.</div></div>'])->action(URL::prev());
            }

        }
        else{

        }



    }

    public function odemeKaldir($yer,$id){
        if ($yer=="fatura"){

            $odenmediYap            = Post::odenmediYap();
            $kasaDefterindenKaldir  = Post::kasaDefterindenKaldir();
            $siparisOdenmediYap     = Post::siparisOdenmediYap();
            $bildirim               = Post::bildirim();

            $faturaDetay = FaturaModel::detay($id);
            $cariDetay = CariModel::detay($faturaDetay->musteri);

            $ekMailBilgi = "";

            if ($odenmediYap=="1"){

                $odenmediYap = FaturaModel::odemeDurumDegistir($id,"0");

                if ($odenmediYap){

                    if ($kasaDefterindenKaldir=="1"){
                        $kasaDefterindenKaldir = KasaModel::odemeKaldir('fatura',$id);

                        /******************/
                        $faturaAlinanOdemeGuncelle = FaturaModel::odemeEkle(['id'=>$id,'alinan_odeme'=>0]);

                        /******************/
                    }

                    if($siparisOdenmediYap=="1"){

                        $siparisOdenmediYap = SiparisModel::odemeDurumDegistir($faturaDetay->siparis_id,"0");

                    }

                    if($bildirim=="1"){

                        $mailgonder = Email::subject('Fatura Ödeme İptal Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                            'konu' => 'Ödeme Bildirimi',
                            'mesaj' => $faturaDetay->id.' numaralı faturanız için daha önceden kaydedilen ödemeleriniz kayıtlarımızdan kaldırıldı. Bu işlemin yanlışlıkla olduğunu düşünüyorsanız lütfen bizimle iletişime geçiniz.<br> <hr>',
                            //'link' => URL::site(),
                            //'link_baslik' => 'Tıklayınız',
                            'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                            'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
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

                    Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Fatura Ödeme Durumu Değişikliği gerçekleştirildi !.<br>'.$ekMailBilgi.'</div></div>'])->action('faturalar');

                }else{

                    Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Fatura Ödeme Durumu Değişikliği sırasında hata oluştu !.</div></div>'])->action(URL::prev());

                }



            }else{



            }


        }
    }

    public function hesapEkle(){

        $user               = User::data();

        if (!empty(Post::adi()) or !empty(Post::tur())){

            $data = [
                'adi'       => Post::adi(),
                'hesap_no'  => Post::hesap_no(),
                'aciklama'  => Post::aciklama(),
                'tur'       => Post::tur(),
                'durum'     => Post::durum()
            ];

            $hesapEkle = KasaModel::hesapEkle($data);

            if($hesapEkle){

                //AyarModel::nelerOluyor($user->isim,'kasa','Yeni bir kasa hesabı ekledi');

                Redirect::insert(['bilgi'=>'<div class="callout callout-success">Kasa Hesabı başarı ile eklendi!</div>'])->action('kasa');
            }else{
                Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Kasa Hesabı ekleme işlemi yapılamadı!</div>'])->action('kasa');
            }

        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-warning">Gerekli alanları doldurunuz!</div>'])->action('kasa');
        }

    }

    public function kayitlar($id,$sayfa=0){

        $user   = User::data();

        Pagination::url('kasa/kayitlar/'.$id.'/')->create();

        $kasa = KasaModel::hesapBilgi($id);
        $kasaToplamlari = KasaModel::kasaGelirGiderToplami($id);

        $kayitlar = KasaModel::kayitlar($id,$sayfa);

        //AyarModel::nelerOluyor($user->isim,'kasa/kayitlar/'.$id,$kasa->adi.'İsimli kasa hesabını inceliyor.');


        View::detay($kasa);
        View::kasaToplamlari($kasaToplamlari);
        View::kayitlar($kayitlar);

    }

    public function tumKayitlar(){

        $user   = User::data();

        $kayitlar = KasaModel::tumKayitlar();
        $kasaToplami = KasaModel::kasaToplami();
        $gelirGiderToplami = KasaModel::gelirGiderToplami();

        //AyarModel::nelerOluyor($user->isim,'kasa/tumKayitlar','Kasa defterini inceliyor.');
        
        View::gelirGiderToplami($gelirGiderToplami);
        View::kasaToplami($kasaToplami);
        View::kayitlar($kayitlar);

    }

    public function kasaHesapGuncelle($id){


        $guncelData = [

            'id'        => $id,
            'adi'  => Post::adi(),
            'hesap_no'  => Post::hesap_no(),
            'aciklama'  => Post::aciklama(),
            'tur'       => Post::tur(),
            'durum'     => Post::durum(),
            'tutar'     => Post::tutar()

        ];

        $guncelle = KasaModel::hesapGuncelle($guncelData);

        if($guncelle){
            Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('kasa');
        }else{

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('kasa');

        }



    }

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){


            case "kasaHesapDuzenle":

                $data['title'] = "Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::id();

                    $updateData = [
                        'id'             =>$id,
                        'adi'            =>Post::adi(),
                        'sef'            =>Converter::urlWord(Post::adi()),
                        'title'          =>Post::title(),
                        'icon'           =>Post::icon(),
                        'aciklama'       =>Post::aciklama(),
                        'sira'           =>Post::sira(),
                        'anasayfa'       =>Post::anasayfa(),
                        'durum'          =>Post::durum()
                    ];

                    $guncelle = KategoriModel::update($updateData);

                    if($guncelle){

                        $data['success'] = 'Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('kategoriler');
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Guncelleme işlemi yapılamadı!";

                    }

                }

                echo Json::encode($data);


                break;

                case "kayitSil":
    
                    $sil = KasaModel::kayitSil($dataId);
    
                    $data['title'] = "Kasa Kayıt Silme İşlemi";
    
                    if($sil){
    
                        $data['success'] = 'Kasa Kayıt silme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::prev();
                        //$data['redirect'] = '';
    
                    }else{
    
                        $data['error'] = "Kasa Kayıt  silme işlemi yapılamadı!";
    
                    }
    
                    echo Json::encode($data);
    
                    break;

        }


    }


    public function s404()
    {
    }
}