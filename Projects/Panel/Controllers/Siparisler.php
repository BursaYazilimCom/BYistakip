<?php namespace Project\Controllers;

use User,Method,Post,Redirect,Upload,Pagination,Date,File,Email,URL,Security;
use AyarModel,UyeModel,CariModel,SiparisModel,UrunModel;

class Siparisler extends Controller
{

    public function __construct(){
        $user                   = User::data();
        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array(CURRENT_CONTROLLER,$yetkiler)){

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div>'])->action('home');

        }
    }

    public function main($durum="", $filtre="", $sayfa="0"){

        if($durum=="" or $durum=="0"){

            $durum = "0";

            Pagination::url('siparisler/main//')->create();

        }

        if ($filtre==""){

            $filtre = "id-DESC";

        }

        Pagination::url('siparisler/main/'.$durum.'/'.$filtre)->create();


        $user = User::data();

        $siparisler     = SiparisModel::liste($durum,$sayfa,$filtre);
        $durumlar       = AyarModel::siparisDurumlari();

        View::siparisler($siparisler);
        View::siparisDurumlari($durumlar);

        View::durumum($durum);
        View::sayfa($sayfa);
        View::filtre($filtre);

    }

    public function teklifler(){

        $siparisler     = SiparisModel::teklifler();

        View::siparisler($siparisler);

    }

    public function detay($id){

        $user = User::data();

        $durumlar           = SiparisModel::siparisDurumlari();
        $siparisDetay       = SiparisModel::detay($id);
        $siparisGecmisi     = SiparisModel::siparisGecmisi($id);
        $siparisSecenekleri = SiparisModel::siparisSecenekleri($id);

        MesajModel::okunduYap($id,$user->id);

        $mesajKontrol   = MesajModel::kontrol($id);
        $mesajlari      = MesajModel::siparisKonusmasi($id);

        AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$id,$id.' Numaralı siparişin detayına bakıyor');

        View::siparisSecenekleri($siparisSecenekleri);
        View::siparisDurumlari($durumlar);
        View::detay($siparisDetay);
        View::siparisGecmisi($siparisGecmisi);
        View::mesajKontrol($mesajKontrol);
        View::mesajlar($mesajlari);

    }

    public function form($id=""){

        $user = User::data();

        if (!empty($id)){
            $uyeBilgi = UyeModel::detay($id);
        }else{
            $uyeBilgi = (object)[
                'id' => '',
                'adi' => '',
                'iskonto' => ''
            ];
        }

        $siparisDurumlari = AyarModel::siparisDurumlari();
        $musteriler = CariModel::liste();
        $odemeYontemleri = AyarModel::odemeYontemleri();
        $urunler = UrunModel::tumListe();


        View::uyeBilgi($uyeBilgi);
        View::musteriler($musteriler);
        View::siparisDurumlari($siparisDurumlari);
        View::odemeYontemleri($odemeYontemleri);
        View::urunler($urunler);

        //AyarModel::nelerOluyor($user->isim,'siparisler','Yeni sipariş oluşturuluyor');

    }

    public function kaydet($uye,$taslak){

        $user = User::data();

        $musteri            = $uye;
        $musteriBilgi       = UyeModel::detay($musteri);
        $belge_no           = Post::belge_no();
        $siparisDurumu      = Post::durum();
        $siparis_adi        = Post::siparis_adi();
        $siparis_notu       = Post::siparis_notu();
        $siparis_yeri       = Post::siparis_yeri();
        $grafik_hizmeti     = Post::grafik_hizmeti();
        $montaj_hizmeti     = Post::montaj_hizmeti();
        $kayit_sekli        = Post::kayit_sekli();
        $teslim_tarihi      = AyarModel::tarihDuzelt(Post::teslim_tarihi());
        $periyodik_siparis  = Post::periyodik_siparis();
        $odeme_periyodu     = Post::odeme_periyodu();
        $kargo_kodu         = Post::kargo_kodu();
        $siparis_kodu       = Post::siparis_kodu();
        $toplamFiyat        = SiparisModel::taslakToplamTutar($taslak);
        $taslakDetay        = SiparisModel::taslakDetay($taslak);

        $siparisData = [
            'belge_no'              => $belge_no,
            'uye'                   => $musteri,
            'siparis_adi'           => $siparis_adi,
            'siparis_notu'          => $siparis_notu,
            'siparis_kodu'          => $siparis_kodu,
            'dosya'                 => '',
            'durum'                 => $siparisDurumu,
            'siparis_yeri'          => $siparis_yeri,
            'grafik_hizmeti'        => $grafik_hizmeti,
            'montaj_hizmeti'        => $montaj_hizmeti,
            'toplam_fiyat'          => $taslakDetay->genel_toplam_tutari,
            'toplam_tutar'          => $taslakDetay->toplam_tutar,
            'indirim_tutar'         => $taslakDetay->indirim_tutar,
            'ara_toplam_tutar'      => $taslakDetay->ara_toplam_tutar,
            'kdv_tutari'            => $taslakDetay->kdv_tutari,
            'genel_toplam_tutari'   => $taslakDetay->genel_toplam_tutari,
            'olusturan'             => $user->id,
            'teslim_tarihi'         => $teslim_tarihi,
            'periyodik_siparis'     => $periyodik_siparis,
            'odeme_periyodu'        => $odeme_periyodu,
            'kargo_kodu'            => $kargo_kodu,
            'kayit_sekli'           => $kayit_sekli
        ];

        $siparisOlustur = SiparisModel::siparisOlustur($siparisData);

        if($siparisOlustur){

            $siparisUrunleri= SiparisModel::taslakSiparisUrunleri($taslak);

            $urunBilgi = "";
            $urunSAyi = 1;


            foreach ($siparisUrunleri as $su) {

                $urunData =[
                    'siparis'       =>$siparisOlustur,
                    'urun'          =>$su->urun,
                    'urun_adi'      =>$su->urun_adi,
                    'uye'           =>$su->uye,
                    'en'            =>$su->en,
                    'boy'           =>$su->boy,
                    'adet'          =>$su->adet,
                    'notu'          =>$su->notu,
                    'birim_fiyat'   =>$su->birim_fiyat,
                    'kdv'           =>$su->kdv,
                    'toplam_fiyat'  =>$su->toplam_fiyat,
                    'durum'         =>$siparisDurumu
                ];

                $urunBilgi = $urunBilgi.$urunSAyi.")".$su->en."x".$su->boy." ".$su->adet." adet ".$su->urun_adi." (".$su->notu.")<br>";

                SiparisModel::siparisUrunEkle($urunData);

                /**********Hammadde stok Düşümü*********/

                if($kayit_sekli=="1"){

                    $urunDetay = UrunModel::detay($su->urun);

                    if($urunDetay->stoklu_urun=='1'){

                        $urunGuncelStok = $urunDetay->guncel_stok-$su->adet;

                        $urunStokDus = UrunModel::stokluUrunStokGuncelle($su->urun,$urunGuncelStok);

                    }else{

                        $urunMalzemeleri = UrunModel::urunMalzemeleri($su->urun);

                        foreach ($urunMalzemeleri as $um) {

                            $malzemeDetay           = MalzemeModel::detay($um->malzeme);

                            if($urunDetay->birim=="adet") {

                                if($malzemeDetay->stok_birim=="m2"){

                                    if($urunDetay->max_genislik=="0" and $urunDetay->max_yukseklik=="0"){

                                        $dusulecekStokMiktari   = $su->adet*$um->miktar;

                                    }else{

                                        $dusulecekStokMiktari   = ((($urunDetay->max_genislik*$urunDetay->max_yukseklik)/10000)*$su->adet)*$um->miktar;

                                    }

                                }else{
                                    $dusulecekStokMiktari   = $su->adet*$um->miktar;
                                }

                            }else{

                                $dusulecekStokMiktari   = ((($su->en*$su->boy)/10000)*$su->adet)*$um->miktar;

                            }

                            $GuncelStok = $malzemeDetay->stok-$dusulecekStokMiktari;

                            $stokData = [
                                'id'   => $um->malzeme,
                                'stok'    => $GuncelStok
                            ];

                            $stokGuncelle = MalzemeModel::stokGuncelle($stokData);

                            $stokHareketData = [
                                'malzeme'   => $um->malzeme,
                                'miktar'    => $dusulecekStokMiktari,
                                'hareket'   => "-",
                                'sebebi'    => $siparisOlustur." Numaralı Sipariş için düşülmüştür"
                            ];

                            MalzemeModel::hareketEkle($stokHareketData);

                            $malzemeDusulenStokBilgi = $malzemeDusulenStokBilgi." ".$malzemeDetay->adi." hammaddesinden ".$dusulecekStokMiktari." ".$malzemeDetay->stok_birim." stok düşüldü<br>";

                        }

                    }



                }

                /**********Hammadde stok Düşümü*********/

                $stokNotu = $stokNotu."<br>".$su->urun_adi." Ürünü Eklenirken ".$malzemeDusulenStokBilgi;

                $urunSAyi++;

            }

            $siparisDosyalari = SiparisModel::taslakDosyalari($taslak);

            foreach ($siparisDosyalari as $sd) {

                $dosyaData =[
                    'siparis'       =>$siparisOlustur,
                    'kayit_yili'    =>$sd->kayit_yili,
                    'dosya'         =>$sd->dosya
                ];

                SiparisModel::siparisDosyaEkle($dosyaData);

            }

            $taslakSil = SiparisModel::taslakSil($taslak);

            $gecmisData = [
                'uye'           =>$musteri,
                'siparis_id'    =>$siparisOlustur,
                'aciklama'      =>$user->isim.' tarafından sipariş oluşturuldu. ',
                'guncelleyen'   =>$user->id,
                'durum'         =>$siparisDurumu
            ];

            $siparisGecmisEkle  = SiparisModel::siparisGesmisEkle($gecmisData);

            if($kayit_sekli=="1"){

                $guncelBakiye       = $musteriBilgi->bakiye+$taslakDetay->genel_toplam_tutari;

                $uyeBakiyeGuncelle  = UyeModel::bakiyeGuncelle($musteri,$guncelBakiye);



                /***************Montaj Ekleme*****************/

                if ($montaj_hizmeti=="1"){

                    // 'yapilacak_is'      =>  $urunBilgi,

                    $data = [
                        'tur'               =>  '1',
                        'baslik'            =>  $siparis_adi,
                        'aciklama'          =>  str_replace(PHP_EOL,"<br>",$siparis_notu),
                        'organizator'       =>  $musteriBilgi->adi,
                        'yer'               =>  Post::yer(),
                        'mekan'             =>  '',
                        'salon'             =>  '',
                        'etkinlik_tarihi'   =>  AyarModel::tarihDuzelt(Post::teslim_tarihi()),
                        'baslangic_tarihi'  => AyarModel::tarihDuzelt(Post::teslim_tarihi()),
                        'etkinlik_saati'    =>  '',
                        'ikon'              =>  'fa-chevron-right',
                        'durum'             =>  '1',
                        'renk'              =>  'default',
                        'ekleyen'           =>  $user->id
                    ];

                    $etkinlikId = EtkinlikModel::ekle($data);

                    $montajNotu = "Sipariş Montaj yapılacak olarak işaretlendiği için montaj planına da eklendi";


                }



                /***************Montaj Ekleme*****************/

            }

            AyarModel::nelerOluyor($user->isim,'siparisler','Yeni sipariş kaydedildi');

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Sipariş Oluşlturuldu ! <br> '.$stokNotu.'<br>'.$montajNotu.'</div>'])->action('siparisler');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Sipariş Oluşlturma hatası lütfen tekrar deneyin hata devam ederse sistem yöneticinize bildirin !</div>'])->action('siparisler/olustur/'.$musteri.'/'.$taslak);

        }

    }

    public function duzenle($id){

        $user = User::data();

        $user                   = User::data();

        AyarModel::yetkiKontrol(\Json::decode($user->yetkiler),CURRENT_CONTROLLER,CURRENT_CFUNCTION);

        $detay      = SiparisModel::detay($id);
        $urunleri   = SiparisModel::siparisUrunleri($id);
        $dosyalari  = SiparisModel::siparisDosyalari($id);
        $uyeBilgi   = UyeModel::detay($detay->uye);
        $siparisYerleri = AyarModel::siparisYerileri();

        $siparisDurumlari = SiparisModel::siparisDurumlari();

        $araToplam          = "";
        $iskontoToplam      = "";
        $toplamKdvTutari    = "";
        $genelToplam        = "";

        foreach ($urunleri as $su) {

            $birimFiyat     = $su->birim_fiyat;
            $adet           = $su->adet;
            $kdv            = $su->kdv;
            $toplamFiyat    = $birimFiyat*$adet;

            if($uyeBilgi->iskonto>0){
                $uyeIskontosu   = ($toplamFiyat/100)*$uyeBilgi->iskonto;
                $toplamFiyat    = $toplamFiyat-$uyeIskontosu;
                $iskontoToplam  = $iskontoToplam+$uyeIskontosu;
            }else{
                $iskontoToplam  = "0.0000";
            }

            $kdvTutari      = ($toplamFiyat/100)*$kdv;
            $araToplam      = $araToplam+$toplamFiyat;
            $toplamKdvTutari= $toplamKdvTutari+$kdvTutari;

        }

        $toplamlar = [
            'id'                    => $id,
            'toplam_tutar'          => $araToplam+$iskontoToplam,
            'ara_toplam_tutar'      => $araToplam,
            'indirim_tutar'         => $iskontoToplam,
            'kdv_tutari'            => $toplamKdvTutari,
            'genel_toplam_tutari'   => $araToplam+$toplamKdvTutari
        ];

        $siparisTutarGuncelle = SiparisModel::siparisToplamTutarGuncelle($toplamlar);


        View::detay($detay);
        View::dosyalari($dosyalari);
        View::siparisUrunleri($urunleri);
        View::toplamlar($toplamlar);
        View::uyeBilgi($uyeBilgi);
        View::siparisDurumlari($siparisDurumlari);
        View::siparisYerleri($siparisYerleri);

        AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$id,$id.' Numaralı Sipariş düzenleniyor');
    }

    public function guncelle($siparis){

        $siparisDetay = SiparisModel::detay($siparis);

        $user = User::data();

        $musteriBilgi   = UyeModel::detay($siparisDetay->uye);
        $siparisDurumu  = Post::durum();
        $siparis_adi    = Post::siparis_adi();
        $siparis_notu   = Post::siparis_notu();
        $siparis_kodu   = Post::siparis_kodu();
        $grafik_hizmeti = Post::grafik_hizmeti();
        $siparis_yeri   = Post::siparis_yeri();
        $montaj_hizmeti = Post::montaj_hizmeti();
        $kayit_sekli    = Post::kayit_sekli();
        $teslim_tarihi      = AyarModel::tarihDuzelt(Post::teslim_tarihi());
        $odeme_periyodu  = Post::odeme_periyodu();
        $periyodik_siparis  = Post::periyodik_siparis();
        $kargo_kodu     = Post::kargo_kodu();

        $siparisData = [
            'id'                    => $siparis,
            'siparis_adi'           => $siparis_adi,
            'siparis_notu'          => $siparis_notu,
            'siparis_kodu'          => $siparis_kodu,
            'durum'                 => $siparisDurumu,
            'siparis_yeri'          => $siparis_yeri,
            'grafik_hizmeti'        => $grafik_hizmeti,
            'montaj_hizmeti'        => $montaj_hizmeti,
            'teslim_tarihi'         => $teslim_tarihi,
            'odeme_periyodu'         => $odeme_periyodu,
            'periyodik_siparis'         => $periyodik_siparis,
            'kargo_kodu'            => $kargo_kodu
        ];

        $siparisGuncelle = SiparisModel::guncelle($siparisData);

        if($siparisDetay->durum!=$siparisDurumu){

            $gecmisData = [
                'uye'           =>$siparisDetay->uye,
                'siparis_id'    =>$siparis,
                'aciklama'      =>'Sipariş güncellenirken aynı zamanda durumuda değiştirildi.<br><small>Güncelleyen: '.$user->isim.'</small>',
                'guncelleyen'   =>$user->id,
                'durum'         =>Post::durum()
            ];

            $siparisGecmisEkle = SiparisModel::siparisGesmisEkle($gecmisData);

        }

        if($siparisGuncelle){

            AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$siparis,$siparis.' Numaralı sipariş güncellendi');

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Sipariş Güncelleme işlemi gerçekleştirildi !</div>'])->action('siparisler/duzenle/'.$siparis);

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Sipariş Güncelleme hatası lütfen tekrar deneyin hata devam ederse sistem yöneticinize bildirin !</div>'])->action('siparisler/duzenle/'.$siparis);

        }

    }

    public function siparislerimDurum($durum){

        $user           = User::data();
        $siparislerim   = SiparisModel::uyeDurumSiparisleri($user->id,$durum);
        $durumlar       = SiparisModel::uyeSiparisDurumlari($user->id);

        View::siparislerim($siparislerim);
        View::siparisDurumlari($durumlar);
        View::durum($durum);

    }

    public function siparisGecmisi($id){

        $user = User::data();

        $durumlar       = SiparisModel::siparisDurumlari();
        $siparisDetay = SiparisModel::detay($id);
        $siparisGecmisi = SiparisModel::siparisGecmisi($id);

        AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$id,$id.' Numaralı siparişin geçmişine bakıyor');

        View::siparisDurumlari($durumlar);
        View::detay($siparisDetay);
        View::siparisGecmisi($siparisGecmisi);

    }

    public function siparisUrunGuncelle(){

        $user = User::data();

        $yetkiler               = \Json::decode($user->yetkiler);

        $urun   = Post::urun();
        $siparisUrunId   = Post::id();
        $fiyat  = Post::fiyat();
        $adet   = Post::adet();
        $en     = Post::en();
        $boy    = Post::boy();
        $eknot  = Post::eknot();

        $urunDetay      = UrunModel::detay($urun);
        $siparisUrunDetay   = SiparisModel::siparisUrunDetay(Post::id());

        if($urunDetay->birim=="m2"){

            $boyut  = ($en*$boy)/10000;

            $birimfiyat = $fiyat*$boyut;

        }else{
            $birimfiyat = $fiyat;
        }

        if($urunDetay->fiyat_birim!="TL"){
            $tlTutar = AyarModel::tlCevir($birimfiyat,$urunDetay->fiyat_birim);

            $birimfiyat = $tlTutar;

        }

        $toplamFiyat    = $birimfiyat*$adet;

        $siparisUrunData = [
            'id'            =>$siparisUrunId,
            'en'            =>$en,
            'boy'           =>$boy,
            'adet'          =>$adet,
            'notu'          =>$eknot,
            'birim_fiyat'   =>$birimfiyat,
            'kdv'           =>$urunDetay->kdv,
            'toplam_fiyat'  =>$toplamFiyat
        ];

        $guncelle = SiparisModel::siparisUrunGuncelle($siparisUrunData);

        if ($guncelle){

            AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$siparisUrunDetay->siparis,$siparisUrunDetay->siparis.' Numaralı siparişin '.$siparisUrunDetay->urun_adi.' ürünü güncellendi');

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Ürün Güncelleme işlemi yapıldı yeni ürün eklebilrisinizi</div>'])->action('siparisler/duzenle/'.$siparisUrunDetay->siparis);
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Ürün Güncelleme işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/duzenle/'.$siparisUrunDetay->siparis);
        }

    }

    public function urunEkle($id){

        $user           = User::data();

        $detay          = SiparisModel::detay($id);
        $urunDetay      = UrunModel::detay(Post::urun());
        $uyeBilgi       = UyeModel::detay($detay->uye);

        $urunBirimFiyat     = $urunDetay->fiyat;
        $urunKdv            = $urunDetay->kdv;
        $siparisAdedi       = Post::adet();

        if($urunDetay->birim=="m2"){
            $en     = Post::en();
            $boy    = Post::boy();

            $boyut  = ($en*$boy)/10000;

            $birimfiyat = $urunBirimFiyat*$boyut;

        }else{
            $birimfiyat = $urunBirimFiyat;
        }

        /***************TL ÇEVİR****************/

        if($urunDetay->fiyat_birim!="TL"){
            $tlTutar = AyarModel::tlCevir($birimfiyat,$urunDetay->fiyat_birim);

            $birimfiyat = $tlTutar;

        }

        /***************TL ÇEVİR****************/
        /***************Stok hareketi****************/

        if($detay->kayit_sekli=="1"){

            $urunMalzemeleri = UrunModel::urunMalzemeleri($urunDetay->id);

            foreach ($urunMalzemeleri as $um) {

                $malzemeDetay           = MalzemeModel::detay($um->malzeme);

                if($urunDetay->birim=="adet") {

                    if($malzemeDetay->stok_birim=="m2"){

                        if($urunDetay->max_genislik=="0" and $urunDetay->max_yukseklik=="0"){

                            $dusulecekStokMiktari   = $siparisAdedi*$um->miktar;

                        }else{

                            $dusulecekStokMiktari   = ((($urunDetay->max_genislik*$urunDetay->max_yukseklik)/10000)*$siparisAdedi)*$um->miktar;

                        }

                    }else{

                        $dusulecekStokMiktari   = $siparisAdedi*$um->miktar;

                    }

                }else{

                    $dusulecekStokMiktari   = ((($en*$boy)/10000)*$siparisAdedi)*$um->miktar;

                }

                $GuncelStok = $malzemeDetay->stok-$dusulecekStokMiktari;

                $stokData = [
                    'id'   => $um->malzeme,
                    'stok'    => $GuncelStok
                ];

                $stokGuncelle = MalzemeModel::stokGuncelle($stokData);

                $stokHareketData = [
                    'malzeme'   => $um->malzeme,
                    'miktar'    => $dusulecekStokMiktari,
                    'hareket'   => "-",
                    'sebebi'    => $id." Numaralı Sipariş için düşülmüştür"
                ];

                MalzemeModel::hareketEkle($stokHareketData);

                $malzemeDusulenStokBilgi = $malzemeDusulenStokBilgi." ".$malzemeDetay->adi." hammaddesinden ".$dusulecekStokMiktari." ".$malzemeDetay->stok_birim." stok düşüldü<br>";

            }

        }

        /***************Stok hareketi****************/

        $toplamFiyat    = $birimfiyat*$siparisAdedi;

        $siparisUrunData = [
            'siparis'       =>$id,
            'urun'          =>$urunDetay->id,
            'urun_adi'      =>$urunDetay->adi,
            'uye'           =>$detay->uye,
            'en'            =>Post::en(),
            'boy'           =>Post::boy(),
            'adet'          =>Post::adet(),
            'notu'          =>Post::eknot(),
            'birim_fiyat'   =>$birimfiyat,
            'kdv'           =>$urunKdv,
            'toplam_fiyat'  =>$toplamFiyat
        ];

        $ekle = SiparisModel::siparisUrunEkle($siparisUrunData);

        if ($ekle){

            AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$id,$id.' Numaralı siparişe '.$urunDetay->adi.' ürünü eklendi<br>'.$malzemeDusulenStokBilgi);

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Ürün Ekleme işlemi yapıldı yeni ürün eklebilrisinizi</div>'])->action('siparisler/duzenle/'.$id);
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Ürün Ekleme işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/duzenle/'.$id);
        }

    }

    public function siparisUrunKaldir($id,$siparis){

        $user               = User::data();

        $siparisDetay       = SiparisModel::detay($siparis);

        $siparisUrunDetay   = SiparisModel::siparisUrunBilgi($id);

        $urunDetay          = UrunModel::detay($siparisUrunDetay->urun);

        if($siparisDetay->kayit_sekli=="1"){

            $sil = SiparisModel::siparisUrunSil($id);

            if ($sil){

                /********STOK İADE*******/

                $urunMalzemeleri = UrunModel::urunMalzemeleri($siparisUrunDetay->urun);

                foreach ($urunMalzemeleri as $um) {

                    $malzemeDetay           = MalzemeModel::detay($um->malzeme);

                    if($urunDetay->birim=="adet") {

                        if($malzemeDetay->stok_birim=="m2"){

                            if($urunDetay->max_genislik=="0" and $urunDetay->max_yukseklik=="0"){

                                $eklenecekStokMiktari   = $siparisUrunDetay->adet*$um->miktar;

                            }else{

                                $eklenecekStokMiktari   = ((($urunDetay->max_genislik*$urunDetay->max_yukseklik)/10000)*$siparisUrunDetay->adet)*$um->miktar;

                            }

                        }else{

                            $eklenecekStokMiktari   = $siparisUrunDetay->adet*$um->miktar;

                        }

                    }else{

                        $eklenecekStokMiktari   = ((($siparisUrunDetay->en*$siparisUrunDetay->boy)/10000)*$siparisUrunDetay->adet)*$um->miktar;

                    }

                    $GuncelStok = $malzemeDetay->stok+$eklenecekStokMiktari;

                    $stokData = [
                        'id'        => $um->malzeme,
                        'stok'      => $GuncelStok
                    ];

                    $stokGuncelle = MalzemeModel::stokGuncelle($stokData);

                    $stokHareketData = [
                        'malzeme'   => $um->malzeme,
                        'miktar'    => $eklenecekStokMiktari,
                        'hareket'   => "+",
                        'sebebi'    => $siparis." Numaralı Siparişte iptal edilen ürün için kulllanılacak miktar stoğa geri aktarılmıştır"
                    ];

                    MalzemeModel::hareketEkle($stokHareketData);

                }

                /********STOK İADE SON********/

                /********MÜŞTERİ BAKİYE İADE********/

                $musteriBilgi       =   UyeModel::detay($siparisDetay->uye);

                if($siparisUrunDetay->kdv=="20") { $kdv = "1.20"; }
                elseif($siparisUrunDetay->kdv=="10") { $kdv = "1.10"; }
                else { $kdv = "1"; }

                $dusulecekFiyat     =   $siparisUrunDetay->toplam_fiyat*$kdv;

                $guncelBakiye       =   $musteriBilgi->bakiye+$dusulecekFiyat;

                $uyeBakiyeGuncelle  =   UyeModel::bakiyeGuncelle($siparisDetay->uye,$guncelBakiye);

                $bakiyeData = [
                    'uye'           => $siparisUrunDetay->uye,
                    'tutar'         => $dusulecekFiyat,
                    'hareket'       => '+',
                    'odeme_sekli'   => 'Hesaba İade',
                    'aciklama'      => $id.' Numaralı siparişten '.$siparisUrunDetay->en.'x'.$siparisUrunDetay->boy.' ölçülerinde '.$siparisUrunDetay->urun_adi.' isimli ürününüz iptal edilerek kaldırıldığı için bakiyenin hesabınıza iadesi yapıldı'
                ];

                $bakiyeGecmisEkle = CariModel::bakiyeGecmisEkle($bakiyeData);

                if($bakiyeGecmisEkle){

                    $uyari1 = '<div class="callout callout-success">Üye Bakiye Geçmişi Eklendi</div>';

                }else{

                    $uyari1 = '<div class="callout callout-danger">Üye Bakiye Geçmişi Eklenemedi</div>';

                }

                /********MÜŞTERİ BAKİYE İADE SON********/

                AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$id,$id.' Numaralı siparişten '.$siparisUrunDetay->en.'x'.$siparisUrunDetay->boy.' ölçülerinde '.$siparisUrunDetay->urun_adi.' isimli ürünü kaldırdı');

                Redirect::insert(['bilgi'=>$uyari1.'<div class="callout callout-success">Ürün kaldırıldı</div>'])->action(URL::prev());

            }else{

                Redirect::insert(['bilgi'=>$uyari1.'<div class="callout callout-danger">Ürün kaldırma işleminde hata oluştur</div>'])->action(URL::prev());

            }

        }

    }

    public function siparisDosyaYukle($id){

        if(Upload::isFile('file')){

            Upload::source('file')
                //->target('http://192.168.1.101/TERMOFOM/program/')
                ->target(REAL_BASE_DIR . 'Uploads/siparisDosyalari/'.Date::set('{Y}').'/')
                ->start();

            $dosyaBilgi = Upload::info();

            $dosya = $dosyaBilgi->encodeName;

            $data = [
                'siparis'   =>$id,
                'kayit_yili'=>Date::set('{Y}'),
                'dosya'     =>$dosya,
            ];
            SiparisModel::siparisDosyaEkle($data);

        }else{

        }

    }

    public function siparisDosyaSil($dosya,$yil,$siparis){

        $user   = User::data();

        $detay = SiparisModel::detay($siparis);

        if(File::exists(REAL_BASE_DIR . 'Uploads/siparisDosyalari/'.$yil.'/'.$dosya)){

            File::delete(REAL_BASE_DIR . 'Uploads/siparisDosyalari/'.$yil.'/'.$dosya);

        }

        $DBdosyaSil = SiparisModel::siparisDosyaSil($dosya);

        if ($DBdosyaSil){

            AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$siparis,$siparis.' Numaralı siparişten '.$dosya.' isimli dosyayı sildi');

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Dosya kaldırıldı</div>'])->action('siparisler/duzenle/'.$siparis);
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Dosya kaldırma işlemi yapılamadı</div>'])->action('siparisler/duzenle/'.$siparis);
        }

    }

    public function siparisResimKaydet(){

        $urunId = Post::siparisId();
        $resim = Post::base64_file();

        $siparisUrunDetay = SiparisModel::siparisUrunBilgi($urunId);

        $data = [
            'id' => $urunId,
            'resim' => $resim
        ];
        if($resim!=""){

            $guncelle = SiparisModel::siparisUrunResimEkle($data);

        }

        if ($guncelle){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Ürün Resim Ekleme işlemi gerçekleştirildi</div>'])->action('uretim');
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Ürün Resim Ekleme işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('uretim');
        }



    }

    public function durumGuncelle($id){

        $user = User::data();

        $durumDegistir = SiparisModel::durumDegistir($id,Post::durum());

        if(Post::siparisUrunDurumu()=='1'){

            $siparisUrunleri = SiparisModel::siparisUrunleri($id);

            foreach ($siparisUrunleri as $sur) {

                $urunDurumDegistir = SiparisModel::urunDurumDegistir($sur->id,Post::durum());

            }


        }

        $uye = UyeModel::detay(Post::uye());

        $gecmisData = [
            'uye'       =>Post::uye(),
            'siparis_id'=>$id,
            'aciklama'  =>Post::aciklama().'<br><small>Güncelleyen: '.$user->isim.'</small>',
            'guncelleyen'=>$user->id,
            'durum'     =>Post::durum()
        ];

        $siparisGecmisEkle = SiparisModel::siparisGesmisEkle($gecmisData);

        if(Post::bildirim()=="1"){

            $bildirimEkle = UyeModel::bildirimEkle(Post::uye(), $id.' Numaralı siparişinizin durumu güncellendi!');

            Email::receiver($uye->email)
                ->subject('Termofom; Sipariş Durum Güncelleme')
                ->message($id.' Numaralı siparişinizin durumu güncellendi!. Panelinize girip siparişinizin gidişatını kontrol edebilirsiniz.<br> <b>Güncelleme açıklaması</b>: '.Post::aciklama())
                ->send();

        }

        /*if(Post::durum()==AyarModel::defaulAyarlar('iptalSiparisDurumu')){



        }*/

        if($siparisGecmisEkle){

            AyarModel::nelerOluyor($user->isim,'siparisler/detay/'.$id,$id.' Numaralı siparişin durumunu değiştirdi');

            Redirect::insert(['gecmisBilgi'=>'<div class="callout callout-success">Sipariş geçmişi eklendi !</div>'])->action('siparisler/detay/'.$id);
        }else{
            Redirect::insert(['gecmisBilgi'=>'<div class="callout callout-danger">Sipariş geçmişi eklenemedi !</div>'])->action('siparisler/detay/'.$id);
        }
    }

    public function konusmaBaslat($id){

        $user           = User::data();
        $siparisDetay   = SiparisModel::detay($id);
        $uye            = UyeModel::detay($siparisDetay->uye);

        $konusma = [
            'siparis'           =>$id,
            'alici'             =>$siparisDetay->uye,
            'alici_detay'       =>$uye->adi.":".$uye->logo,
            'gonderici'         =>$user->id,
            'gonderici_detay'   =>$user->isim,
            'mesaj'             =>Post::mesaj()
        ];
        $konusmaBaslat = MesajModel::konusmaBaslat($konusma);

        if($konusmaBaslat){

            if(Post::bildirim()=="1"){

                $bildirimEkle = UyeModel::bildirimEkle(Post::uye(), $id.' Numaralı siparişiniz ile alakalı temsilcimiz size mesaj gödnerdi.');

                Email::receiver($uye->email)
                    ->subject('Termofom; Sipariş Görüşmesi')
                    ->message($id.' Numaralı siparişiniz hakkında temsilcilerimiz size bir mesaj gönderdi. Mesaja cevap vermek için lütfen panelinize giriş yapın.<br> <b>Temsilci Mesajı</b>: '.Post::mesaj())
                    ->send();

            }

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Konuşma balatıldı, Mesaj üyeye gönderildi</div>'])->action('siparisler/detay/'.$id);
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Konuşma başlatılamadı!</div>'])->action('siparisler/detay/'.$id);
        }

    }

    public function mesajGonder($id){

        $user           = User::data();
        $siparisDetay   = SiparisModel::detay($id);
        $uye            = UyeModel::detay($siparisDetay->uye);

        $mesajData = [
            'siparis'           =>$id,
            'alici'             =>$siparisDetay->uye,
            'alici_detay'       =>$uye->adi.":".$uye->logo,
            'gonderici'         =>$user->id,
            'gonderici_detay'   =>$user->isim,
            'mesaj'             =>Post::mesaj()
        ];

        $mesajEkle = MesajModel::konusmaMesajEkle($mesajData);

        if($mesajEkle){
            Redirect::action('siparisler/detay/'.$id);
        }else{
            Redirect::insert(['gecmisBilgi'=>'<div class="callout callout-danger">MEsaj Gönderilemedi !</div>'])->action('siparisler/detay/'.$id);
        }

    }

    /*****************KURUMSAL SİPARİŞLER*********************/

    public function kurumsal(){

        $user = User::data();

        $yetkiler               = \Json::decode($user->yetkiler);

        $siparisler     = SiparisModel::kurumsalSiparisler();


        View::siparisler($siparisler);


    }

    public function kurumsalSiparisEkle(){

        $user   = User::data();

        $data = [
            'firma_adi'         =>Post::firma_adi(),
            'siparis_tarihi'    =>AyarModel::tarihDuzelt(Post::siparis_tarihi()),
            'siparis_no'        =>Post::siparis_no(),
            'urun_kodu'         =>Post::urun_kodu(),
            'turu'              =>Post::turu(),
            'adet'              =>Post::adet(),
            'termin_tarihi'     =>AyarModel::tarihDuzelt(Post::termin_tarihi()),
            'aciklama'          =>Post::aciklama(),
            'not'               =>Post::not(),
            'durum'             =>Post::durum(),
            'ekleyen'           =>$user->id
        ];

        $ekle = SiparisModel::kurumsalSiparisEkle($data);

        if ($ekle){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Sipariş Ekleme işlemi yapıldı yeni ürün eklebilrisinizi</div>'])->action('siparisler/kurumsal');
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Sipariş Ekleme işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/kurumsal');
        }

    }

    public function kurumsalSiparisGuncelle(){

        $user   = User::data();

        $data = [
            'id'                =>Post::id(),
            'firma_adi'         =>Post::firma_adi(),
            'siparis_tarihi'    =>AyarModel::tarihDuzelt(Post::siparis_tarihi()),
            'siparis_no'        =>Post::siparis_no(),
            'urun_kodu'         =>Post::urun_kodu(),
            'turu'              =>Post::turu(),
            'adet'              =>Post::adet(),
            'termin_tarihi'     =>AyarModel::tarihDuzelt(Post::termin_tarihi()),
            'aciklama'          =>Post::aciklama(),
            'not'               =>Post::not(),
            'durum'             =>Post::durum(),
            'duzenleyen'        =>$user->id
        ];

        $ekle = SiparisModel::kurumsalSiparisGuncelle($data);

        if ($ekle){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Sipariş Ekleme işlemi yapıldı yeni ürün eklebilrisinizi</div>'])->action('siparisler/kurumsal');
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Sipariş Ekleme işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/kurumsal');
        }

    }

    public function kurumsalSiparisSil($id){

        $sil = SiparisModel::kurumsalSiparisSil($id);

        if ($sil){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Sipariş kaldırıldı</div>'])->action('siparisler/kurumsal');
        }

    }


    /*****************KURUMSAL SİPARİŞLER*********************/
    /*****************TASLAKLAR*********************/

    public function taslakUrunEkle($id){

        $taslakDetay    = SiparisModel::taslakDetay($id);
        $urunDetay      = UrunModel::detay(Post::urun());
        $uyeBilgi       = UyeModel::detay($taslakDetay->uye);

        $urunBirimFiyat     = Post::fiyat();
        $urunKdv            = $urunDetay->kdv;
        $siparisAdedi       = Post::adet();

        if($urunDetay->birim=="m2"){
            $en     = Post::en();
            $boy    = Post::boy();

            $boyut  = ($en*$boy)/10000;

            $birimfiyat = $urunBirimFiyat*$boyut;

        }else{
            $birimfiyat = $urunBirimFiyat;
        }

        /***************TL ÇEVİR****************/

        if($urunDetay->fiyat_birim!="TL"){
            $tlTutar = AyarModel::tlCevir($birimfiyat,$urunDetay->fiyat_birim);

            $birimfiyat = $tlTutar;

        }

        /***************TL ÇEVİR****************/

        $toplamFiyat    = $birimfiyat*$siparisAdedi;

        $taslakurunData = [
            'siparis'       =>$id,
            'urun'          =>$urunDetay->id,
            'urun_adi'      =>$urunDetay->adi,
            'uye'           =>$taslakDetay->uye,
            'en'            =>Post::en(),
            'boy'           =>Post::boy(),
            'adet'          =>Post::adet(),
            'notu'          =>Post::eknot(),
            'birim_fiyat'   =>$birimfiyat,
            'kdv'           =>$urunKdv,
            'toplam_fiyat'  =>$toplamFiyat
        ];

        $ekle = SiparisModel::taslakUrunEkle($taslakurunData);

        if ($ekle){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Ürün Ekleme işlemi yapıldı yeni ürün eklebilrisinizi</div>'])->action('siparisler/olustur/'.$taslakDetay->uye.'/'.$id);
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Ürün Ekleme işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/olustur/'.$taslakDetay->uye.'/'.$id);
        }

    }

    public function taslakUrunKaldir($id,$taslak){

        $taslakDetay = SiparisModel::taslakDetay($taslak);

        $sil = SiparisModel::taslakSiparisUrunSil($id);

        if ($sil){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Ürün kaldırıldı</div>'])->action('siparisler/olustur/'.$taslakDetay->uye.'/'.$taslak);
        }

    }

    public function taslakDosyaYukle($id){

        if(Upload::isFile('file')){

            Upload::source('file')
                ->target(REAL_BASE_DIR . 'Uploads/siparisDosyalari/'.Date::set('{Y}').'/')
                ->start();

            $dosyaBilgi = Upload::info();

            $dosya = $dosyaBilgi->encodeName;

            $data = [
                    'siparis'   =>$id,
                    'kayit_yili'=>Date::set('{Y}'),
                    'dosya'     =>$dosya,
                    ];
            SiparisModel::taslakDosyaEkle($data);

        }else{

        }

    }

    public function taslakDosyaSil($dosya,$yil,$taslak){

        $taslakDetay = SiparisModel::taslakDetay($taslak);

        File::delete(REAL_BASE_DIR . 'Uploads/siparisDosyalari/'.$yil.'/'.$dosya);

        $DBdosyaSil = SiparisModel::taslakDosyaSil($dosya);

        if ($DBdosyaSil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Dosya kaldırıldı</div>'])->action('siparisler/olustur/'.$taslakDetay->uye.'/'.$taslak);

        }

    }

    /*****************TASLAKLAR********************/

    /*****************FİYAT TEKLİFLERİ********************/
    public function fiyatTeklifleri(){

        $user = User::data();

        AyarModel::yetkiKontrol(\Json::decode($user->yetkiler),CURRENT_CONTROLLER,CURRENT_CFUNCTION);

        $fiyatTeklifleri     = SiparisModel::fiyatTeklifleri();

        View::fiyatTeklifleri($fiyatTeklifleri);

    }

    public function fiyatTeklifForm($id=""){

        $user = User::data();

        if($id==""){

            $detay      = [];
            $urunler    = [];
            $action     = "siparisler/fiyatTeklifKaydet";

        }else{

            $detay      = SiparisModel::fiyatTeklifDetay($id);
            $urunleri   = SiparisModel::fiyatTeklifUrunleri($id);
            $action     = "siparisler/fiyatTeklifGuncelle";

        }

        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array('Siparisler/fiyatTeklifFiyatlandirma',$yetkiler)){

            $fiyatlandirmaYetkisi = "0";

        }else{

            $fiyatlandirmaYetkisi = "1";

        }

        if(!in_array('Siparisler/fiyatTeklifOnay',$yetkiler)){

            $onayYetkisi = "0";

        }else{

            $onayYetkisi = "1";

        }

        View::fiyatlandirmaYetkisi($fiyatlandirmaYetkisi);
        View::onayYetkisi($onayYetkisi);
        View::detay($detay);
        View::urunleri($urunleri);
        View::action($action);

    }

    public function fiyatTeklifGuncelle(){

        $user = User::data();

        for ($i=0;$i<count($_POST["id"]);$i++){

            $urundata = [
                'id'     =>$_POST["id"][$i],
                'fiyat'  =>$_POST['fiyat'][$i]
            ];

            $urunGuncelle = SiparisModel::fiyatTeklifUrunFiyatGuncelle($urundata);

        }

        $teklifDetay = SiparisModel::fiyatTeklifDetay(Post::teklifid());

        if (Post::durum()==""){
            $durum = $teklifDetay->durum;
        }else{
            $durum = Post::durum();
        }

        $teklifdata = [
            'id'            =>Post::teklifid(),
            'durum'         =>$durum,
            'onaylayan'     =>$user->id
        ];

        $fiyatTeklifVerildi = SiparisModel::fiyatTeklifVerildiYap($teklifdata);

        if($fiyatTeklifVerildi){

            AyarModel::nelerOluyor($user->isim,'siparisler/fiyatTeklifleri',Post::teklifid().' Numaralı teklif güncellendi');


        Redirect::insert(['bilgi'=>'<div class="callout callout-success">Teklif isteğiniz kaydedildi</div>'])->action('siparisler/fiyatTeklifleri');

            }else{

        Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Teklif isteği kayıt işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/fiyatTeklifleri');

        }

    }

    public function fiyatTeklifKaydet(){

        $user   = User::data();

        $data = [
            'personel'      =>$user->id,
            'firma_adi'     =>Post::firma_adi(),
            'musteri_adi'   =>Post::musteri_adi(),
            'tel'           =>Post::tel(),
            'mail_adresi'   =>Post::mail_adresi(),
            'ek_not'        =>Post::ek_not()
        ];

        $ekle = SiparisModel::fiyatTeklifEkle($data);

        if($ekle){

            $eklenen = 0;

            for ($i=0;$i<count($_POST["istenen_is"]);$i++){

                $urundata = [
                    'teklif_id'     =>$ekle,
                    'istenen_is'    =>$_POST['istenen_is'][$i],
                    'adet'          =>$_POST['adet'][$i],
                    'olcu'          =>$_POST['olcu'][$i],
                    'aciklama'      =>$_POST['aciklama'][$i],
                    'fiyat'         =>$_POST['fiyat'][$i]
                ];

                $urunEkle = SiparisModel::fiyatTeklifUrunEkle($urundata);

                if($urunEkle){
                    $eklenen = $eklenen+1;
                }

            }

            AyarModel::nelerOluyor($user->isim,'siparisler/fiyatTeklifleri','Yeni bir fiyat teklifi kaydetti');

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Teklif isteğiniz kaydedildi</div>'])->action('siparisler/fiyatTeklifleri');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Teklif isteği kayıt işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/fiyatTeklifleri');

        }

    }

    public function fiyatTeklifYazdir($id){

        $detay = SiparisModel::fiyatTeklifDetay($id);
        $urunler = SiparisModel::fiyatTeklifUrunleri($id);

        View::detay($detay);
        View::urunler($urunler);

    }

    public function fiyatTeklifSil($id){

        $user = User::data();

        $sil = SiparisModel::fiyatTeklifSil($id);

        if($sil){

            AyarModel::nelerOluyor($user->isim,'siparisler/fiyatTeklifleri','Bir fiyat teklifni sildi');

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Teklif Silindi</div>'])->action('siparisler/fiyatTeklifleri');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Teklif silme isteğiniz gerçekleştirilemedi lütfen tekrar deneyin !</div>'])->action('siparisler/fiyatTeklifleri');

        }

    }

    public function fiyatTeklifUrunSil($id,$urun){

        $sil = SiparisModel::fiyatTeklifUrunSil($urun);

        if($sil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Teklif Silindi</div>'])->action('siparisler/fiyatTeklifForm/'.$id);

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Teklif silme isteğiniz gerçekleştirilemedi lütfen tekrar deneyin !</div>'])->action('siparisler/fiyatTeklifForm/'.$id);

        }

    }

    /*****************FİYAT TEKLİFLERİ********************/

    /*****************SİPARİŞ DURUMLARI********************/

    public function siparisDurumlari($id=""){
        $user = User::data();

        if ($id) {
            $data = SiparisModel::siparisDurumDetay($id);
            View::detay($data);
            View::action("siparisler/siparisDurumBilgiGuncelle/" . $id);

        } else {
            $data = (object)[
                'id' => '',
                'adi' => '',
                'uyari' => ''
            ];
            View::action("siparisler/siparisDurumEkle");
        }

        $siparisDurumlari = SiparisModel::siparisDurumlari();

        View::detay($data);
        View::siparisDurumlari($siparisDurumlari);

    }

    public function siparisDurumBilgiGuncelle($id){

        $id     = Post::id();
        $adi    = Post::adi();
        $uyari  = Post::uyari();
        $sira   = Post::sira();

        $data   = [
            'id'    => $id,
            'adi'   => $adi,
            'uyari' => $uyari,
            'sira'  => $sira
        ];

        $guncelle = SiparisModel::siparisDurumBilgiGuncelle($data);

        if($guncelle){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Siparis Durum Bilgileri Güncellendi</div>'])->action('siparisler/siparisDurumlari/'.$id);
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Sipariş Durum güncelleme işllemi yapılamadı !</div>'])->action('siparisler/siparisDurumlari/'.$id);
        }


    }

    /*****************SİPARİŞ DURUMLARI********************/

    public function s404(){}
}