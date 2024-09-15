<?php namespace Project\Controllers;

use User,Method,Post,Redirect,Upload,Pagination,Date,Time,File,Email,URL,Security,Cart,Validation,Json;
use AyarModel,UyeModel,CariModel,SiparisModel,UrunModel,KasaModel,FaturaModel,InternalTedarikciModel as TedarikciModel;

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
        $tedarikciler   = TedarikciModel::tumListe();

        View::listele($siparisler);
        View::siparisDurumlari($durumlar);
        View::tedarikciler($tedarikciler);

        View::durumum($durum);
        View::sayfa($sayfa);
        View::filtre($filtre);

    }

    public function urunler(){


        $user = User::data();

        $siparisUrunleri     = SiparisModel::siparisUrunleriListe();

        View::listele($siparisUrunleri);

    }

    public function gruplar($id,$sayfa=0){

        $grupDetay = UrunModel::urunGrupDetay($id,$sayfa);

        $user = User::data();

        $siparisUrunleri     = SiparisModel::siparisUrunleriListe($id,$sayfa);

        Pagination::url('siparisler/gruplar/'.$id.'/'.$sayfa)->create();

        View::listele($siparisUrunleri);
        View::grupDetay($grupDetay);

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
        $urunGruplari = UrunModel::urunGrupListe();
        $tedarikciler   = TedarikciModel::tumListe();


        View::tedarikciler($tedarikciler);
        View::kasaHesaplari($kasaHesaplari);
        View::uyeBilgi($uyeBilgi);
        View::musteriler($musteriler);
        View::siparisDurumlari($siparisDurumlari);
        View::odemeYontemleri($odemeYontemleri);
        View::urunler($urunler);
        View::urunGruplari($urunGruplari);

        //AyarModel::nelerOluyor($user->isim,'siparisler','Yeni sipariş oluşturuluyor');

    }

    public function kaydet(){

        $user = User::data();

        $musteri            = Post::cari();
        $odeme_yontemi      = Post::odeme_yontemi();
        $odeme_durumu       = "0";
        $durum              = Post::durum();
        $kayit_sekli        = Post::kayit_sekli()=="1"?"0":"1";
        $siparis_notu       = Post::siparis_notu();
        $kasa_hesabı          = Post::kasa_hesabı();
        $fatura_no          = "0";

        $musteriBilgi       = CariModel::detay($musteri);

        $siparisData = [
            'cari'                  => $musteri,
            'odeme_yontemi'         => $odeme_yontemi,
            'odeme_durumu'          => $odeme_durumu,
            'olusturan'             => $user->id,
            'kayit_sekli'           => $kayit_sekli,
            'siparis_notu'          => $siparis_notu,
            'durum'                 => $durum
        ];

        $siparisOlustur = SiparisModel::ekle($siparisData);

        if($siparisOlustur){

            $siparisUrunleri = Cart::selectAll();

            $urunSAyi = 1;

            $toplamTutar = 0;
            $kdvToplami = 0;
            $siparis_tarihi     = Date::set('{year}-{monthInYear}-{dayInMonth}');


            foreach ($siparisUrunleri as $su) {

                $urunBilgi          = UrunModel::detay($su["urun"]);

                if($urunBilgi->stoklu_urun=='1'){

                    $guncelStok = $urunBilgi->guncel_stok-$su["adet"];

                    $stokAzalt = UrunModel::stokluUrunStokGuncelle($urunBilgi->id,$guncelStok);

                }

                $paraBirimDetay         = AyarModel::paraBirimDetay($urunBilgi->fiyat_birim);

                $birimFiyat             = AyarModel::tlCevir($su["fiyat"],$urunBilgi->fiyat_birim);
                $tarihKaydet            = $su["baslangic_tarihi"]!=""?AyarModel::tarihDuzelt($su["baslangic_tarihi"]):Date::set('{year}-{monthInYear}-{dayInMonth}');

                $kdvTutari              = (($su["adet"]*$birimFiyat)/100)*$su["urunKdv"];
                $toplamFiyat            = $kdvTutari+($birimFiyat*$su["adet"]);

                $bitis_tarihi           = Date::calculate($tarihKaydet, AyarModel::odemePeriyoduEklenecekGun($su["odemePeriyodu"]).' day');

                if(AyarModel::defaultAyarlar('baslangicTarihiOdemedenSonra')=='1'){

                    $tarihKaydet = $siparis_tarihi;
                    $bitis_tarihi = $siparis_tarihi;

                }else{

                    $tarihKaydet = $tarihKaydet;
                    $bitis_tarihi = $bitis_tarihi;

                }

                $urunData =[
                    'siparis'           => $siparisOlustur,
                    'urun'              => $su["urun"],
                    'urun_adi'          => $su["urun_adi"],
                    'tedarikci'         => $su["tedarikci"],
                    'cari'              => $musteri,
                    'adet'              => $su["adet"],
                    'notu'              => $su["siparis_notu"],
                    'odeme_periyodu'    => $su["odemePeriyodu"],
                    'para_birimi'       => $urunBilgi->fiyat_birim,
                    'gecerli_kur'       => $paraBirimDetay->guncel_kur,
                    'fiyat_sabitle'     => $su["fiyat_sabitle"],
                    'birim_fiyat'       => $birimFiyat,
                    'kdv'               => $su["urunKdv"],
                    'kdv_tutari'        => $kdvTutari,
                    'toplam_fiyat'      => $toplamFiyat,
                    'siparis_tarihi'    => $siparis_tarihi,
                    'baslangic_tarihi'  => $tarihKaydet,
                    'bitis_tarihi'      => $bitis_tarihi,
                    'durum'             => $durum
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

            /**FATURA OLUŞTUR**/

            $faturaData = [
                'tur'               =>"2",
                'satis_turu'        =>"1",
                'belge_no'          =>$fatura_no,
                'fatura_adi'        =>$musteriBilgi->firma_adi,
                'fatura_adresi'     =>$musteriBilgi->fatura_adresi,
                'vergi_dairesi'     =>$musteriBilgi->vergi_dairesi,
                'vergi_no'          =>$musteriBilgi->vergi_no,
                'tedarikci'         =>"0",
                'musteri'           =>$musteriBilgi->id,
                'siparis_id'        =>$siparisOlustur,
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

            /**FATURA OLUŞTUR**/

            /* Ödeme durumuna göre müşteri cari işlemleri ve kasa defteri işlemleri yapılacak*/
            /* İlgili siparişi carinin hesabına kaydet*/
            /* İlgili siparişi carinin hesabına kaydet*/
            /*Ödeme alınmışsa kasa defterine kaydet*/

            if($odeme_durumu=="1"){

                $defterData = [
                    'kasa'          =>$kasa_hesabı,
                    'islem'         =>"t",
                    'hesap'         =>"Müşteri Tahsilat: ".$musteriBilgi->adi,
                    'islem_turu'    =>"siparis",
                    'islem_tur_id'  =>$siparisOlustur,
                    'aciklama'      =>$siparisOlustur." Numaralı Siparişin Ödemesi alındı",
                    'gelir'         =>$kdvToplami+$toplamTutar,
                    'gider'         =>"",
                    'mevcut_kasa_toplami'=>KasaModel::kasaToplami()+$kdvToplami+$toplamTutar,
                    'yil'           =>Date::set('{year}'),
                    'tarih'         =>Date::set('{year}-{monthInYear}-{dayInMonth}'),
                    'islem_yapan'   =>$user->id
                ];

                $kasayaKaydet = KasaModel::deftereKaydet($defterData);

                $kasaHesapBilgi = KasaModel::hesapBilgi($kasa_hesabı);

                if ($kasayaKaydet) {

                    $kasaHesapTutarGuncelle = KasaModel::kasaHesabiTutarGuncelle($kdvToplami+$toplamTutar+$kasaHesapBilgi->tutar,$kasa_hesabı);

                }

            }

            /*Ödeme alınmışsa kasa defterine kaydet*/

            Cart::deleteAll();

            AyarModel::basarili('Başarılı İşlem','Sipariş Oluşlturuldu !',URL::site('siparisler'));

        }else{

            AyarModel::basarili('Başarısız İşlem','Sipariş Oluşlturma hatası lütfen tekrar deneyin hata devam ederse sistem yöneticinize bildirin !',URL::site('siparisler/form'));

        }

    }

    public function duzenle($id){

        $urunler = UrunModel::tumListe();
        $kasaHesaplari = KasaModel::kasaHesaplari();
        $detay      = SiparisModel::detay($id);

        if($detay->id==""){
            Redirect::action(URL::prev());
        }
        $urunleri   = SiparisModel::siparisUrunleri($id);
        $cariBilgi   = CariModel::detay($detay->cari);
        $cariler   = CariModel::tumListe();
        $siparisDurumlari = AyarModel::siparisDurumlari();
        $odemeYontemleri = AyarModel::odemeYontemleri();
        $siparisGecmisi = SiparisModel::siparisGecmisi($id);

        View::siparisGecmisi($siparisGecmisi);
        View::kasaHesaplari($kasaHesaplari);
        View::musteriler($cariler);
        View::odemeYontemleri($odemeYontemleri);
        View::detay($detay);
        View::cariBilgi($cariBilgi);
        View::siparisDurumlari($siparisDurumlari);
        View::urunler($urunleri);

    }

    public function guncelle($siparis){

        $siparisDetay = SiparisModel::detay($siparis);

        $user = User::data();

        $musteriBilgi       = CariModel::detay($siparisDetay->cari);
        $yeniSiparisDurumu  = Post::durum();
        $odeme_durumu       = Post::odeme_durumu();
        $siparis_notu       = Post::siparis_notu();

        $siparisData = [
            'id'                    => $siparis,
            'siparis_notu'          => $siparis_notu,
            'odeme_durumu'          => $odeme_durumu,
            'durum'                 => $yeniSiparisDurumu
        ];

        $siparisGuncelle = SiparisModel::guncelle($siparisData);
        $ekUyari ="";

        if($siparisDetay->durum!=$yeniSiparisDurumu){

            $gecmisData = [
                'cari'              =>$siparisDetay->cari,
                'siparis'           =>$siparis,
                'aciklama'          =>'Sipariş güncellenirken aynı zamanda durumuda değiştirildi.'.$ekUyari.'<br> Yeni Sipariş Durumu:  '.AyarModel::durum($yeniSiparisDurumu).'<br><small>Güncelleyen: '.$user->isim.'</small>',
                'guncelleyen'       =>$user->id
            ];

            $siparisGecmisEkle = SiparisModel::siparisGesmisEkle($gecmisData);

        }



        if($siparisGuncelle){

            Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><div class="alert-body">Sipariş Güncelleme işlemi gerçekleştirildi !</div></div>'])->action('siparisler/duzenle/'.$siparis);

        }else{

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><div class="alert-body">Sipariş Güncelleme hatası lütfen tekrar deneyin hata devam ederse sistem yöneticinize bildirin !</div></div>'])->action('siparisler/duzenle/'.$siparis);

        }

    }

    public function sil($id){
        $siparisDetay               = SiparisModel::detay($id);

        $siparisUrunleri            = SiparisModel::siparisUrunleri($id);

        $faturalar                  = FaturaModel::siparisFaturalari($id);

        $tumFaturalar               = (int) Post::tumFaturalar();
        $odenmemisFaturalar         = (int) Post::odenmemisFaturalar();
        $kasaDefterindenKaldir      = (int) Post::kasaDefterindenKaldir();

        if($siparisDetay->id){

            //Kasa Defteri kayıtları siliniyor
            if($kasaDefterindenKaldir=="1"){

                foreach($faturalar['liste'] as $fatura){

                    $kasaKayitlariSil = KasaModel::kasaDefteriKayitSil('fatura',$fatura->id);

                    if($kasaKayitlariSil){
                        $silinmeUyarisi = $silinmeUyarisi.$fatura->id." Numaralı fatura kasa defteri kayıtları silindi<br>";
                    }else{

                        $silinmeUyarisi = $silinmeUyarisi."HATA ! -> ".$fatura->id." Numaralı fatura kasa defteri kayıtları silineMEdi<br>"; 
                    }

                }
                
            }

            //Eüer tüm faturalar silinsiz olarak seçilmişse
            if($tumFaturalar=="1"){

                foreach($faturalar['liste'] as $fatura){

                    // Fatura ile birlikte ürünleride silindi,
                    $faturaSil = FaturaModel::sil($fatura->id);

                    if($faturaSil){
                        $silinmeUyarisi = $silinmeUyarisi.$siparisDetay->id." numaralı siparişe ait ".$fatura->id." Numaralı fatura kayıtları silindi<br>";
                    }else{

                        $silinmeUyarisi = $silinmeUyarisi."HATA ! -> ".$siparisDetay->id." numaralı siparişe ait ".$fatura->id." Numaralı fatura kayıtları silineMEdi<br>";
                    }

                }
                
            }
            //sadece ödenmemiş faturalar silinsiz seçilmişse
            if($odenmemisFaturalar=="1"){

                foreach($faturalar['liste'] as $fatura){

                    if($fatura->odeme=="0"){

                        // Fatura ile birlikte ürünleride silindi,
                        $faturaSil = FaturaModel::sil($fatura->id);

                        if($faturaSil){
                            $silinmeUyarisi = $silinmeUyarisi.$siparisDetay->id." numaralı siparişe ait ".$fatura->id." Numaralı fatura kayıtları silindi<br>";
                        }else{
    
                            $silinmeUyarisi = $silinmeUyarisi."HATA ! -> ".$siparisDetay->id." numaralı siparişe ait ".$fatura->id." Numaralı fatura kayıtları silineMEdi<br>";
                        }

                    }
                    
                }
                
            }

            // sipariş ürünleri siliniyor
            foreach($siparisUrunleri as $su){
                $siparisurunSil = SiparisModel::siparisUrunSil($su->id);
            }

            //sipariş siliniyor
            $siparisSil = SiparisModel::sil($id);

            if($siparisSil){
                $silinmeUyarisi = $silinmeUyarisi.$siparisDetay->id." numaralı sipariş verileri silindi<br>";
            }else{

                $silinmeUyarisi = $silinmeUyarisi."HATA ! -> ".$siparisDetay->id." numaralı sipariş verileri silineMEdi<br>";
            }

        }else{

            $silinmeUyarisi = "HATA ! -> Talep ettiğiniz sipariş sistemde bulunmuyor<br>";

        }

        AyarModel::bilgilendir("Sipariş Silme İşlemi Hakkında",$silinmeUyarisi,URL::prev());
       
    
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

    public function urunGuncelle($urun){

        $user = User::data();

        $degisim = "";

        $siparisUrunDetay   = SiparisModel::siparisUrunBilgi($urun);

        $degisim = $siparisUrunDetay->urun_adi." ürününde değişiklik yapıldı<br>";


        $baslangic_tarihi       = AyarModel::tarihDuzelt(Post::baslangic_tarihi());
        if($siparisUrunDetay->baslangic_tarihi!=$baslangic_tarihi){
            $degisim = $degisim."<br>Başlangıç Tarihi : ".Ayarmodel::tarihGoster($siparisUrunDetay->baslangic_tarihi)." yerine ".Post::baslangic_tarihi()." olarak değiştirildi<br>";
        }
        $bitis_tarihi           = AyarModel::tarihDuzelt(Post::bitis_tarihi());
        if($siparisUrunDetay->bitis_tarihi!=$bitis_tarihi){
            $degisim = $degisim."<br>Bitiş Tarihi : ".Ayarmodel::tarihGoster($siparisUrunDetay->bitis_tarihi)." yerine ".Post::bitis_tarihi()." olarak değiştirildi<br>";
        }
        $durum                  = Post::durum();
        if($siparisUrunDetay->durum!=$durum){
            $degisim = $degisim."<br>Ürün Durumu : ".Ayarmodel::siparisDurumAdi($durum)." olarak değiştirildi ";
            $durum = $durum;
        }else{
            $durum = $siparisUrunDetay->durum;
        }
        $siparis_notu           = Post::siparis_notu();
        $fiyat_sabitle           = Post::fiyat_sabitle()==""?'0':Post::fiyat_sabitle();

        if($siparisUrunDetay->fiyat_sabitle!=$fiyat_sabitle){
            $degisim = $degisim."<br>Ürün'ün fiyat Sabitleme özelliği değiştirildi";
        }

        $tedarikci           = Post::tedarikci();

        if($siparisUrunDetay->tedarikci!=$tedarikci){
            $degisim = $degisim."<br>Ürün'ün tedarikcisi ".TedarikciModel::tedarikciAdi($tedarikci)." olarak değiştirildi";
        }

        $odeme_periyodu = Post::odeme_periyodu();

        if($siparisUrunDetay->odeme_periyodu!=$odeme_periyodu){

            $degisim = $degisim."<br>Ürün'ün ödeme Periyodu : ".Ayarmodel::odemePeriyodu($odeme_periyodu)." olarak değiştirildi";
            $eklencekGunSayisi = AyarModel::odemePeriyoduEklenecekGun($odeme_periyodu);

            $bitis_tarihi = Date::addDay($baslangic_tarihi,$eklencekGunSayisi);

            /***
             * yeni ödeme periyoduna göre fiyat güncellemesi
             */
            $urunDetay = UrunModel::detay($siparisUrunDetay->urun);

            if($odeme_periyodu=="0"){
                $birimFiyat = 0;
            }elseif($odeme_periyodu=="T"){
                $birimFiyat = $urunDetay->fiyat;
            }elseif($odeme_periyodu== "A"){
                $birimFiyat = $urunDetay->aylik_fiyat;
            }elseif($odeme_periyodu== "3A"){
                $birimFiyat = $urunDetay->uc_aylik_fiyat;
            }elseif($odeme_periyodu== "6A"){
                $birimFiyat = $urunDetay->alti_aylik_fiyat;
            }else{
                $birimFiyat = $urunDetay->yillik_fiyat;
            }
        
            $siparisBirimFiyat  = AyarModel::tlCevir($birimFiyat,$urunDetay->fiyat_birim);
            $kdvTutari          = (($siparisBirimFiyat*$urunDetay->kdv)/100)*$siparisUrunDetay->adet;
            $toplamFiyat        = ($siparisBirimFiyat*$siparisUrunDetay->adet)+$kdvTutari;

        }else{

            $siparisBirimFiyat  = $siparisUrunDetay->birim_fiyat;
            $kdvTutari          = $siparisUrunDetay->kdv_tutari;
            $toplamFiyat        = $siparisUrunDetay->toplam_fiyat;

        }

        $siparisUrunData = [
            'id'                =>$urun,
            'odeme_periyodu'    =>$odeme_periyodu,
            'baslangic_tarihi'  =>$baslangic_tarihi,
            'bitis_tarihi'      =>$bitis_tarihi,
            'fiyat_sabitle'     =>$fiyat_sabitle,
            'birim_fiyat'       =>$siparisBirimFiyat,
            'kdv_tutari'        =>$kdvTutari,
            'toplam_fiyat'      =>$toplamFiyat,
            'tedarikci'         =>$tedarikci,
            'durum'             =>$durum,
            'siparis_notu'      =>$siparis_notu
        ];

        $guncelle = SiparisModel::siparisUrunGuncelle($siparisUrunData);

        if ($guncelle){

            

            //Siparis ürününde fiyat değişikliği oluysa sipariş ürünlerine göre sipariş toplam fiyatları güncelleniyor
            $siparisUrunleri = SiparisModel::siparisUrunleri($siparisUrunDetay->siparis);

            foreach($siparisUrunleri as $su){

                $siparisToplamTutar = $siparisToplamTutar+$su->birim_fiyat;
                $siparisKdvToplami = $siparisKdvToplami+$su->kdv_tutari;
                $siparisGenelToplam = $siparisGenelToplam+$su->toplam_fiyat;

            }

            $siparisToplamData = [
                'id'                    =>$siparisUrunDetay->siparis,
                'toplam_tutar'          =>$siparisToplamTutar,
                'kdv_tutari'            =>$siparisKdvToplami,
                'genel_toplam_tutari'   =>$siparisGenelToplam
            ];

            $siparisToplamTutarGuncelle = SiparisModel::siparisToplamTutarGuncelle($siparisToplamData);


            //Ürünün faturasında da güncelliyoruz

            $faturaDetay = FaturaModel::siparisFaturaDetay($siparisUrunDetay->siparis);

            $faturaUrunData = [
                'siparis_urun_id'   => $siparisUrunDetay->id,
                'fatura_id'         => $faturaDetay->id,
                'fiyat'             => $siparisBirimFiyat,
                'kdv_tutari'        => $kdvTutari,
                'tutar'             => $toplamFiyat
            ];

            $faturaUrunGuncelle = FaturaModel::siparisFaturaUrunFiyatGuncelle($faturaUrunData);

            $faturaurunleri = FaturaModel::faturaUrunleri($faturaDetay->id);

            foreach($faturaurunleri as $fu){

                $faturaToplamTutar = $faturaToplamTutar+$fu->fiyat;
                $faturaKdvToplami = $faturaKdvToplami+$fu->kdv_tutari;
                $faturaGenelToplam = $faturaGenelToplam+$fu->tutar;

            }

            $siparisToplamData = [
                'id'                    =>$faturaDetay->id,
                'toplam_tutar'          =>$faturaToplamTutar,
                'kdv_toplami'           =>$faturaKdvToplami,
                'genel_toplam'          =>$faturaGenelToplam
            ];

            $faturaToplamTutarGuncelle = FaturaModel::urunDuzenlemeSonrasiGuncelleme($siparisToplamData);
            

            $gecmisData = [
                'cari'          =>$siparisUrunDetay->cari,
                'siparis'       =>$siparisUrunDetay->siparis,
                'aciklama'      =>$degisim,
                'guncelleyen'   =>$user->id
            ];

            $siparisGecmisEkle = SiparisModel::siparisGesmisEkle($gecmisData);

            AyarModel::basarili('Başarılı işlem','Ürün Güncelleme işlemi başarı ile gerçekleştirildi','siparisler/urunDuzenle/'.$urun);


        }else{

            AyarModel::basarisiz('Başarısiz işlem','Ürün Güncelleme işlemi yapılamadı lütfen tekrar deneyin !','siparisler/urunDuzenle/'.$urun);
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

            Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert">Ürün Ekleme işlemi yapıldı yeni ürün eklebilrisinizi</div>'])->action('siparisler/duzenle/'.$id);
        }else{
            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert">Ürün Ekleme işlemi yapılamadı lütfen tekrar deneyin !</div>'])->action('siparisler/duzenle/'.$id);
        }

    }

    public function urunDuzenle($id){

        $siparisUrunDetay   = SiparisModel::siparisUrunBilgi($id);
        $urunDetay          = UrunModel::detay($siparisUrunDetay->urun);
        $siparisDetay       = SiparisModel::detay($siparisUrunDetay->siparis);
        $cariBilgi          = CariModel::detay($siparisDetay->cari);
        $siparisDurumlari   = AyarModel::siparisDurumlari();
        $tedarikciler       = TedarikciModel::tumListe();


        View::anaUrunDetay($urunDetay);
        View::tedarikciler($tedarikciler);
        View::urunDetay($siparisUrunDetay);
        View::siparisDurumlari($siparisDurumlari);
        View::siparisDetay($siparisDetay);
        View::cariBilgi($cariBilgi);

    }

    public function urunKontrolEdildi($id){

        $siparisUrunData = [
            'id'                =>$id,
            'islem_gerekiyor'   =>'0',
            'yapilacak_islem'   =>'-'
        ];

        $guncelle = SiparisModel::siparisUrunKontrolEdildi($siparisUrunData);

        if ($guncelle){

            AyarModel::basarili('Başarılı işlem','Ürün Güncelleme işlemi başarı ile gerçekleştirildi','siparisler/urunDuzenle/'.$id);

        }else{

            AyarModel::basarisiz('Başarısiz işlem','Ürün Güncelleme işlemi yapılamadı lütfen tekrar deneyin !','siparisler/urunDuzenle/'.$id);
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

    public function topluIslemler($yer){
        if($yer=="urunler"){

            if(Post::islem()=="islemYapildi"){

                $sec = Post::sec();

                for($s=0;$s<=count($sec);$s++){

                    $siparisUrunData = [
                        'id'                =>$sec[$s],
                        'islem_gerekiyor'   =>'0',
                        'yapilacak_islem'   =>'-'
                    ];
            
                    $guncelle = SiparisModel::siparisUrunKontrolEdildi($siparisUrunData);

                }

                if($guncelle){

                    AyarModel::basarili('İşlem Yapıldı','Seçili ürünler İşlem Yapıldı olarak işaretlendir',URL::site('siparisler/urunler'));

                }else{
                    AyarModel::basarili('İşlem YapılaMAdı','Seçili ürünler İşlem Yapıldı olarak işaretlendir',URL::site('siparisler/urunler'));
                }

            }elseif(Post::islem()=="teslimEdildi"){


                $sec = Post::sec();

                for($s=0;$s<=count($sec);$s++){

                    $guncelle = SiparisModel::urunDurumDegistir($sec[$s],AyarModel::defaultAyarlar('teslimEdildiDurumu'));

                }

                if($guncelle){

                    AyarModel::basarili('İşlem Yapıldı','Seçili ürünler Teslim Edildi olarak işaretlendir',URL::site('siparisler/urunler'));

                }else{
                    AyarModel::basarili('İşlem YapılaMAdı','Seçili ürünler Teslim Edildi olarak işaretlendir',URL::site('siparisler/urunler'));
                }


            }elseif(Post::islem()=="sil"){

            }else{}


            print_r(Post::all());

        }else{

            redirect("siparisler/urunler");

        }
    }


    /*****************SİPARİŞ DURUMLARI*******************

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

    ***********SİPARİŞ DURUMLARI********************/

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
                    $data['redirect'] = URL::site('siparisler/form');

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
                    if(Post::urun_adi()==""){
                        $urunadi = $urunDetay->adi;
                    }else{
                        $urunadi = Post::urun_adi();
                    }

                    if (empty(Post::fiyat()) and Post::fiyat()!="0") {

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

                    if (Post::tedarikci()=='0'){

                        $tedarikci = $urunDetay->tedarikci;

                    }else{

                        $tedarikci = Post::tedarikci();

                    }

                    $baslangicTarihi = Post::baslangic_tarihi()==""?date('Y-m-d'):Post::baslangic_tarihi();

                    $ekleData = [
                        'serial'                    =>$serial,
                        'urun'                      =>Post::urun(),
                        'tedarikci'                 =>$tedarikci,
                        'urun_adi'                  =>$urunadi,
                        'odemePeriyodu'             =>Post::odeme_periyodu(),
                        'odemePeriyoduTanim'        =>AyarModel::odemePeriyodu(Post::odeme_periyodu()),
                        'adet'                      =>Post::adet(),
                        'urunKdv'                   =>Post::kdv(),
                        'siparis_notu'              =>Post::siparis_notu(),
                        'fiyat_sabitle'             =>Post::fiyat_sabitle()==""?"0":Post::fiyat_sabitle(),
                        'baslangic_tarihi'          =>AyarModel::tarihGoster($baslangicTarihi),
                        'fiyat'                     =>$fiyat,
                        'fiyat_birim'               =>$urunDetay->fiyat_birim
                    ];

                    $sepeteEkle = Cart::insert($ekleData);

                    if($sepeteEkle){

                        $data['success'] = 'Sipariş Ürün ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$serial.'">
                                                <td>'.$serial.'</td>
                                                <td>'.$urunadi.'</td>
                                                <td>'.TedarikciModel::tedarikciAdi($tedarikci).'</td>
                                                <td>'.AyarModel::odemePeriyodu(Post::odeme_periyodu()).'</td>
                                                <td>'.Post::adet().'</td>
                                                <td>'.Post::siparis_notu().'</td>
                                                <td>'.AyarModel::tarihGoster($baslangicTarihi).'</td>
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