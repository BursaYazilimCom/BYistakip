<?php namespace Project\Controllers;

use User,Method,Post,Redirect,Upload,Pagination,Date,Time,File,Email,URL,Security,Cart,Validation,Json;
use AyarModel,UyeModel,CariModel,SiparisModel,UrunModel,KasaModel;

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

        View::listele($siparisler);
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

        //Cart::deleteAll();

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

        $kasaHesaplari = KasaModel::kasaHesaplari();
        $siparisDurumlari = AyarModel::siparisDurumlari();
        $musteriler = CariModel::liste();
        $odemeYontemleri = AyarModel::odemeYontemleri();
        $urunler = UrunModel::tumListe();


        View::kasaHesaplari($kasaHesaplari);
        View::uyeBilgi($uyeBilgi);
        View::musteriler($musteriler);
        View::siparisDurumlari($siparisDurumlari);
        View::odemeYontemleri($odemeYontemleri);
        View::urunler($urunler);

        //AyarModel::nelerOluyor($user->isim,'siparisler','Yeni sipariş oluşturuluyor');

    }

    public function kaydet(){

        $user = User::data();

        $musteri            = Post::cari();
        $odeme_yontemi      = Post::odeme_yontemi();
        $odeme_durumu       = Post::odeme_durumu()=="1"?"1":"0";
        $durum              = Post::durum();
        $kayit_sekli        = Post::kayit_sekli()=="1"?"0":"1";
        $fatura             = Post::fatura();
        $siparis_notu       = Post::siparis_notu();

        $musteriBilgi       = CariModel::detay($musteri);

        $siparisData = [
            'cari'                  => $musteri,
            'odeme_yontemi'         => $odeme_yontemi,
            'odeme_durumu'          => $odeme_durumu,
            'olusturan'             => $user->id,
            'kayit_sekli'           => $kayit_sekli,
            'fatura'                => $fatura,
            'siparis_notu'          => $siparis_notu,
            'durum'                 => $durum
        ];

        $siparisOlustur = SiparisModel::ekle($siparisData);

        if($siparisOlustur){

            $siparisUrunleri = Cart::selectAll();

            echo "<pre>";
            print_r($siparisUrunleri);
            echo "</pre>";

            $urunSAyi = 1;

            $toplamTutar = 0;
            $kdvToplami = 0;


            foreach ($siparisUrunleri as $su) {

                $urunBilgi          = UrunModel::detay($su["urun"]);
                $paraBirimDetay     = AyarModel::paraBirimDetay($urunBilgi->fiyat_birim);

                $birimFiyat         = AyarModel::tlCevir($su["fiyat"],$urunBilgi->fiyat_birim);
                $tarihKaydet        = AyarModel::tarihDuzelt($su["baslangic_tarihi"]);

                $kdvTutari          = (($su["adet"]*$birimFiyat)/100)*$su["urunKdv"];
                $toplamFiyat        = $kdvTutari+($birimFiyat*$su["adet"]);

                $siparis_tarihi     = Date::set('{year}-{monthInYear}-{dayInMonth}');
                $bitis_tarihi       = Date::calculate($tarihKaydet, AyarModel::odemePeriyoduEklenecekGun($su["odemePeriyodu"]).' day');

                $urunData =[
                    'siparis'       =>$siparisOlustur,
                    'urun'          =>$su["urun"],
                    'urun_adi'      =>$su["urun_adi"],
                    'cari'          =>$musteri,
                    'adet'          =>$su["adet"],
                    'notu'          =>$su["siparis_notu"],
                    'odeme_periyodu'=>$su["odemePeriyodu"],
                    'para_birimi'   =>$urunBilgi->fiyat_birim,
                    'gecerli_kur'   =>$paraBirimDetay->guncel_kur,
                    'fiyat_sabitle' =>$su["fiyat_sabitle"],
                    'birim_fiyat'   =>$birimFiyat,
                    'kdv'           =>$su["urunKdv"],
                    'kdv_tutari'    =>$kdvTutari,
                    'toplam_fiyat'  =>$toplamFiyat,
                    'siparis_tarihi'  =>$siparis_tarihi,
                    'baslangic_tarihi'=>$tarihKaydet,
                    'bitis_tarihi'    =>$bitis_tarihi,
                    'durum'         =>$durum
                ];

                SiparisModel::siparisUrunEkle($urunData);

                $toplamTutar = $toplamTutar+($birimFiyat*$su["adet"]);
                $kdvToplami = $kdvToplami+$kdvTutari;

                $urunSAyi++;

            }

            $gecmisData = [
                'cari'          =>$musteri,
                'siparis'       =>$siparisOlustur,
                'aciklama'      =>$user->isim.' tarafından sipariş oluşturuldu. ',
                'guncelleyen'   =>$user->id,
            ];

            $siparisGecmisEkle  = SiparisModel::siparisGesmisEkle($gecmisData);

            $tutarData = [
                'id'                    =>$siparisOlustur,
                'toplam_tutar'          =>$toplamTutar,
                'kdv_tutari'            =>$kdvToplami,
                'genel_toplam_tutari'   =>$kdvToplami+$toplamTutar
            ];

            $siparisToplamTutarGuncelle = SiparisModel::siparisToplamTutarGuncelle($tutarData);

            /* Ödeme durumuna göre müşteri cari işlemleri ve kasa defteri işlemleri yapılacak*/

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Sipariş Oluşlturuldu !</div>'])->action('siparisler');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Sipariş Oluşlturma hatası lütfen tekrar deneyin hata devam ederse sistem yöneticinize bildirin !</div>'])->action('siparisler/form');

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

    /*****************SİPARİŞ DURUMLARI********************/

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

            case "sepetUrunSil":

                $sil = Cart::delete($dataId);

                $data['title'] = "Ürün Silme İşlemi";

                if($sil){

                    $data['success'] = 'Ürün işlemi başarı ile yapıldı!';
                    $data['redirect'] = 'siparisler/form';

                }else{

                    $data['error'] = "Ürün silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "sepeteUrunEkle":

                $data['title'] = "Siparişe Ürün Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $urunDetay = UrunModel::detay(Post::urun());

                    if (empty(Post::fiyat()) or Post::fiyat() == 0) {

                        if (Post::odeme_periyodu() == "0") {
                            $fiyat = "0.00";
                        } elseif (Post::odeme_periyodu() == "T") {
                            $fiyat = $urunDetay->fiyat;
                        }elseif (Post::odeme_periyodu() == "A") {
                            $fiyat = $urunDetay->aylik_fiyat;
                        }elseif (Post::odeme_periyodu() == "3A") {
                            $fiyat = $urunDetay->uc_aylik_fiyat;
                        }elseif (Post::odeme_periyodu() == "6A") {
                            $fiyat = $urunDetay->alti_aylik_fiyat;
                        }elseif (Post::odeme_periyodu() == "Y") {
                            $fiyat = $urunDetay->yillik_fiyat;
                        }else{
                            $fiyat = $urunDetay->fiyat;
                        }

                    }else{
                        $fiyat = Post::fiyat();
                    }

                    $serial = Date::set('{year}{monthInYear}{dayInMonth}{hour}{minute}{second}');

                    $ekleData = [
                        'serial'                    =>$serial,
                        'urun'                      =>Post::urun(),
                        'urun_adi'                  =>$urunDetay->adi,
                        'odemePeriyodu'             =>Post::odeme_periyodu(),
                        'odemePeriyoduTanim'        =>AyarModel::odemePeriyodu(Post::odeme_periyodu()),
                        'adet'                      =>Post::adet(),
                        'urunKdv'                   =>Post::kdv(),
                        'siparis_notu'              =>Post::siparis_notu(),
                        'fiyat_sabitle'             =>Post::fiyat_sabitle()==""?"0":Post::fiyat_sabitle(),
                        'baslangic_tarihi'          =>Post::baslangic_tarihi()==""?Date::current():Post::baslangic_tarihi(),
                        'fiyat'                     =>$fiyat,
                        'fiyat_birim'               =>$urunDetay->fiyat_birim
                    ];

                    $sepeteEkle = Cart::insert($ekleData);

                    if($sepeteEkle){

                        $data['success'] = 'Sipariş Ürün ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$serial.'">
                                                <td>'.$serial.'</td>
                                                <td>'.$urunDetay->adi.'</td>
                                                <td>'.AyarModel::odemePeriyodu(Post::odeme_periyodu()).'</td>
                                                <td>'.Post::adet().'</td>
                                                <td>'.Post::siparis_notu().'</td>
                                                <td>'.$fiyat." ".$urunDetay->fiyat_birim.'</td>
                                                <td>'.Post::kdv().'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$serial.'\',\''.URL::site('Siparisler/ajax').'\',\'sepetUrunSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Sipariş Ürün işlemi yapılamadı!";

                    }

                }

                echo Json::encode($data);

                break;

        }

    }

    public function s404(){}
}