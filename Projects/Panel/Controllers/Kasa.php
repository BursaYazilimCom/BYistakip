<?php namespace Project\Controllers;

use Method, Post,URL, Redirect,Date,Pagination,User,Email,DB;
use TedarikciModel, FaturaModel,AyarModel,MalzemeModel,KasaModel,SiparisModel,CariModel;

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
        $bankaHesaplari     = KasaModel::turHesaplari(2);
        $posHesaplari       = KasaModel::turHesaplari(3);
        $kkartiHesaplari    = KasaModel::turHesaplari(4);
        $veresiyeHesaplari  = KasaModel::turHesaplari(5);
        $digerHesaplar      = KasaModel::turHesaplari(6);


        View::kasaHesaplari($kasaHesaplari);
        View::bankaHesaplari($bankaHesaplari);
        View::posHesaplari($posHesaplari);
        View::kkartiHesaplari($kkartiHesaplari);
        View::veresiyeHesaplari($veresiyeHesaplari);
        View::digerHesaplar($digerHesaplar);

       // AyarModel::nelerOluyor($user->isim,'kasa','Kasa yönetimine bakıyor');

    }

    public function odemeEkle($yer,$id){
        $user = User::data();

        $user           = User::data();
        $kasa_hesabi    = Post::kasa();
        $aciklama       = Post::aciklama();
        $tutar          = Post::tutar();
        $odeme_tarihi   = Post::odeme_tarihi();
        $bildirim       = Post::bildirim();

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

                    $mailgonder = Email::subject('Ödeme Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                        'konu' => 'Ödeme Bildirimi',
                        'mesaj' => $siparisDetay->id.' numaralı sipariniz için '.Date::convert($odeme_tarihi, '{dayInMonth}.{monthInYear-}.{year}').' tarihinde '.$tutar.' TL Tutarında ödemeniz alınmış ve kayıtlarımıza işlenmiştir.<br> Ödemeniz için teşekkür ederiz.<br><hr>'.$aciklama,
                        'link' => URL::site(),
                        'link_baslik' => 'Tıklayınız',
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

            $kasayaKaydet = KasaModel::deftereKaydet($defterData);

            $kasaHesapBilgi = KasaModel::hesapBilgi($kasa_hesabi);

            if ($kasayaKaydet) {
                //Sipariş geçmişine yapılan ödemeyi ekle

                //sipariş geçmişi ekleniyor
                $sipariGecmisi = [
                    'cari' => $faturaDetay->musteri,
                    'siparis' => $faturaDetay->siparis_id,
                    'aciklama' => $faturaDetay->id." Numaralı Fatura ödemesi ".$kasaHesapBilgi->adi." kasa hesabına kaydedildi.",
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

                //Fatura ödendi yapılyıor
                if(Post::siparisOdendi()=="1"){

                    $faturaOdemeDurumDegistir = SiparisModel::odemeDurumDegistir($faturaDetay->siparis_id,"1");

                }
                //Fatura ödendi yapılyıor
                if(Post::odendi()=="1"){

                    $faturaOdemeDurumDegistir = FaturaModel::odemeDurumDegistir($faturaDetay->id,"1");

                    SiparisModel::siparisurunIslemGerekiyor($faturaDetay->siparis_id, '1', 'Ürünün Faturası ödendi olarak işaretlendi. Üründe yapılması gereken bir işlem varsa gerçekleştiriniz.');

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

                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Ödeme Başarı İle Eklendi !.<br>'.$ekMailBilgi.'</div></div>'])->action(URL::prev());

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

        $kayitlar = KasaModel::kayitlar($id,$sayfa);

        //AyarModel::nelerOluyor($user->isim,'kasa/kayitlar/'.$id,$kasa->adi.'İsimli kasa hesabını inceliyor.');


        View::detay($kasa);
        View::kayitlar($kayitlar);

    }

    public function tumKayitlar(){

        $user   = User::data();

        $kayitlar = KasaModel::tumKayitlar();
        $kasaToplami = KasaModel::kasaToplami();

        //AyarModel::nelerOluyor($user->isim,'kasa/tumKayitlar','Kasa defterini inceliyor.');
        
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

        }


    }


    public function s404()
    {
    }
}