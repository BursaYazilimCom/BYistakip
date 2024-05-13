<?php namespace Project\Controllers;

use Method, Post,User, Redirect,Date,FPDF,URL,Validation,Upload,Email,Json;
use InternalFaturaModel as FaturaModel,AyarModel,KasaModel,SiparisModel,InternalUrunModel as UrunModel,UyeModel,InternalSmsModel as SmsModel;
use InternalMalzemeModel as MalzemeModel,InternalTedarikciModel as  TedarikciModel,InternalCariModel as CariModel,MasrafModel;



class Faturalar extends Controller
{

    public function __construct()
    {

        $user                   = User::data();
        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array(CURRENT_CONTROLLER,$yetkiler)){

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div>'])->action('home');

        }

    }

    public function alisFaturasiKaydet()
    {
        $user = User::data();
        if(Post::tedarikci()!="") {
            $tedarikci = Post::tedarikci();
            $tedarikciDetay = TedarikciModel::detay($tedarikci);
        }else{
            $tedarikci = 0;
        }

        $cariDetay = CariModel::detay(Post::musteri());

        $faturaData = [
            'tur'               =>"1",
            'satis_turu'        =>"0",
            'belge_no'          =>Post::belge_no(),
            'fatura_adi'        =>"",
            'fatura_adresi'     =>"",
            'vergi_dairesi'     =>"",
            'vergi_no'          =>"",
            'tedarikci'         =>$tedarikci,
            'musteri'           =>"0",
            'siparis_id'        =>"0",
            'toplam_tutar'      =>0,
            'kdv_toplami'       =>0,
            'genel_toplam'      =>0,
            'belge_tarihi'      =>Post::belge_tarihi(),
            'vade_tarihi'       =>Post::vade_tarihi(),
            'durum'             =>Post::durum(),
            'odeme'             =>Post::odeme(),
            'odeme_yontemi'     =>"0",
            'aciklama'          =>Post::notu()
        ];

        $faturaOlustur = FaturaModel::ekle($faturaData);

        if ($faturaOlustur){
            $urun       = Post::urun();
            $miktar     = Post::miktar();
            $ilgili_urun= Post::ilgili_urun();
            $fiyat      = Post::fiyat();
            $kdv        = Post::kdv();
            $tutar      = Post::tutar();
            $aciklama   = Post::aciklama();

            $stokGuncellemeNotu = "";

            for($fu=0;$fu<count(Post::urun());$fu++){

                $urunToplam = $urunToplam+($fiyat[$fu]*$miktar[$fu]);

                $kdvTutari = $kdvTutari+((($fiyat[$fu]*$kdv[$fu])/100)*$miktar[$fu]);

                $urunKdvTutari = (($fiyat[$fu]*$kdv[$fu])/100)*$miktar[$fu];

                $fUrun = [
                    'fatura'                =>$faturaOlustur,
                    'urun'                  =>"0",
                    'siparis_urun_id'       =>"0",
                    'eklenecek_gun_sayisi'  =>"0",
                    'urun_adi'              =>$urun[$fu],
                    'aciklama'              =>$aciklama[$fu],
                    'miktar'                =>$miktar[$fu],
                    'fiyat'                 =>$fiyat[$fu],
                    'kdv'                   =>$kdv[$fu],
                    'kdv_tutari'            =>$urunKdvTutari,
                    'tutar'                 =>$urunKdvTutari+($fiyat[$fu]*$miktar[$fu]),
                ];

                $faturaUrunEkle = FaturaModel::urunEkle($fUrun);

                $urunDetay = UrunModel::detay($ilgili_urun[$fu]);

                if ($faturaUrunEkle and $urun[$fu]!="" and Post::stok_kaydi()=="1" and $urunDetay->stoklu_urun=="1") {

                    $stokGuncelle = UrunModel::stokluUrunStokGuncelle($urunDetay->id,$miktar[$fu]);

                    $stokGuncellemeNotu = $stokGuncellemeNotu."<br>".$urunDetay->adi." Ürünününe ".$miktar[$fu]." adet stok eklendi";

                }

            }

            $fData = [
                'id'  => $faturaOlustur,
                'toplam_tutar'  => $urunToplam,
                'kdv_toplami'   => $kdvTutari,
                'genel_toplam'  => $urunToplam+$kdvTutari
            ];
            $faturaToplami = $urunToplam+$kdvTutari;
            $faturaTutarGuncelle = FaturaModel::urunDuzenlemeSonrasiGuncelleme($fData);

            //Eğer ödeme yapılmışsa marsaf kalemi ekleniyor
            if(Post::odeme()=="1"){

                if(Upload::isFile('belge_dosya')) {

                    Upload::convertName()
                        ->source('belge_dosya')
                        ->target(REAL_BASE_DIR . 'Uploads/masraf_belgeleri/')
                        ->start();
                    $dosyaBilgi = Upload::info();

                    $dosya = $dosyaBilgi->encodeName;
                }else{
                    $dosya = "";
                }

                $data = [
                    'kalem'         =>Post::kalem(),
                    'kasa'          =>Post::kasa(),
                    'belge_no'      =>Post::belge_no(),
                    'belge_dosya'   =>$dosya,
                    'aciklama'      =>Post::aciklama()==""?$faturaOlustur." ID fatura odemesi.":$faturaOlustur." ID fatura odemesi. - ".Post::aciklama(),
                    'odeme_durumu'  =>"1",
                    'tutar'         =>$faturaToplami,
                    'odeme_tarihi'  =>Post::odeme_tarihi()
                ];

                $ekle = MasrafModel::masrafEkle($data);

                if($ekle){

                    if(!empty(Post::kasa())){

                        $masrafKalemi = MasrafModel::bilgi(Post::kalem());

                        $kasaDetay = KasaModel::hesapBilgi(Post::kasa());
                        $kasaToplami = KasaModel::kasaToplami();

                        $defterData = [
                            'kasa'          =>Post::kasa(),
                            'islem'         =>'o',
                            'hesap'         =>'Tedarikci Fatura Odemesi:'.$masrafKalemi->adi,
                            'islem_turu'    =>'fatura',
                            'islem_tur_id'  =>$faturaOlustur,
                            'aciklama'      =>Post::aciklama(),
                            'gelir'         =>'0.0000',
                            'gider'         =>$faturaToplami,
                            'mevcut_kasa_tutari' =>$kasaDetay->tutar,
                            'mevcut_kasa_toplami'=>$kasaToplami,
                            'belge'         =>$dosya,
                            'yil'           =>Date::set('{year}'),
                            'tarih'         =>Date::set('{year}-{monthNumber0}-{dayNumber0}'),
                            'islem_yapan'   =>$user->id
                        ];

                        $deftereKaydet = KasaModel::deftereKaydet($defterData);

                        $kasadaTutari = $kasaDetay->tutar-$faturaToplami;

                        $kasaGuncelle = KasaModel::kasaHesabiTutarGuncelle($kasadaTutari,Post::kasa());

                    }

                    $giderNotu = "Fatura Ödemesi Giderlere ve Kasa Defterine işlendi";
                }else{
                    $giderNotu = "HATA ! Fatura Ödemesi Giderlere ve Kasa Defterine İŞLENEMEDİ";

                }


            }
            //Eğer ödeme yapılmışsa marsaf kalemi eklendi

        }

        if($faturaOlustur){

            AyarModel::basarili("Fatura Oluşturuldu","Fatura oluşturmak işlemi başarı ile gerçekleştirildi".$stokGuncellemeNotu."<br>".$giderNotu,URL::site('faturalar/duzenle/'.$faturaOlustur));
        }else{
            AyarModel::basarisiz("Fatura Oluşturuma Hatası","Fatura oluşturmak işlemi GERÇEKLEŞTİRİLEMEDİ".$stokGuncellemeNotu."<br>".$giderNotu,URL::site('faturalar'));
        }




    }

    public function faturaKaydet(){
        
        $cariDetay = CariModel::detay(Post::musteri());

        $faturaData = [
            'tur'               =>"2",
            'satis_turu'        =>"1",
            'belge_no'          =>Post::belge_no(),
            'fatura_adi'        =>$cariDetay->firma_adi,
            'fatura_adresi'     =>$cariDetay->fatura_adresi,
            'vergi_dairesi'     =>$cariDetay->vergi_dairesi,
            'vergi_no'          =>$cariDetay->vergi_no,
            'tedarikci'         =>"0",
            'musteri'           =>$cariDetay->id,
            'siparis_id'        =>"0",
            'toplam_tutar'      =>0,
            'kdv_toplami'       =>0,
            'genel_toplam'      =>0,
            'belge_tarihi'      =>Post::belge_tarihi(),
            'vade_tarihi'       =>Post::vade_tarihi(),
            'durum'             =>Post::durum(),
            'odeme'             =>Post::odeme(),
            'odeme_yontemi'     =>Post::odeme_yontemi(),
            'aciklama'          =>Post::notu()
        ];

        $faturaOlustur = FaturaModel::ekle($faturaData);

        if ($faturaOlustur){
            $urun       = Post::urun();
            $miktar     = Post::miktar();
            $fiyat      = Post::fiyat();
            $kdv        = Post::kdv();
            $tutar      = Post::tutar();
            $aciklama      = Post::aciklama();

            for($fu=0;$fu<count(Post::urun());$fu++){

                $urunToplam = $urunToplam+($fiyat[$fu]*$miktar[$fu]);

                $kdvTutari = $kdvTutari+((($fiyat[$fu]*$kdv[$fu])/100)*$miktar[$fu]);


                $urunKdvTutari = (($fiyat[$fu]*$kdv[$fu])/100)*$miktar[$fu];

                $fUrun = [
                    'fatura'                =>$faturaOlustur,
                    'urun'                  =>"0",
                    'siparis_urun_id'       =>"0",
                    'eklenecek_gun_sayisi'  =>"0",
                    'urun_adi'              =>$urun[$fu],
                    'aciklama'              =>$aciklama[$fu],
                    'miktar'                =>$miktar[$fu],
                    'fiyat'                 =>$fiyat[$fu],
                    'kdv'                   =>$kdv[$fu],
                    'kdv_tutari'            =>$urunKdvTutari,
                    'tutar'                 =>$urunKdvTutari+($fiyat[$fu]*$miktar[$fu]),
                ];

                $faturaUrunEkle = FaturaModel::urunEkle($fUrun);

            }

            $fData = [
                'id'  => $faturaOlustur,
                'toplam_tutar'  => $urunToplam,
                'kdv_toplami'   => $kdvTutari,
                'genel_toplam'  => $urunToplam+$kdvTutari
            ];
            $faturaTutarGuncelle = FaturaModel::urunDuzenlemeSonrasiGuncelleme($fData);

        } 

        if($faturaOlustur){
            AyarModel::basarili("Fatura Oluşturuldu","Fatura oluşturmak işlemi başarı ile gerçekleştirildi",URL::site('faturalar/duzenle/'.$faturaOlustur));
        }else{
            AyarModel::basarisiz("Fatura Oluşturuma Hatası","Fatura oluşturmak işlemi GERÇEKLEŞTİRİLEMEDİ",URL::site('faturalar'));
        }

        

        /**FATURA OLUŞTUR**/

    }

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

            case "faturaUrunSil":

                $data['title'] = "Ürün Silme İşlemi";

                $faturaUrunDetay = FaturaModel::faturaUrunDetay($dataId);

                $sil = FaturaModel::faturaUrunSil($faturaUrunDetay->fatura,$dataId);

                $faturaUrunleri = FaturaModel::faturaUrunleri($faturaUrunDetay->fatura);

                if($sil){

                    $toplamTutar = 0;
                    $geneltoplamTutar = 0;
                    $kdvToplami = 0;

                    foreach ($faturaUrunleri as $furun) {


                        $toplamTutar        = $toplamTutar+($furun->fiyat*$furun->miktar);
                        $kdvToplami         = $kdvToplami+$furun->kdv_tutari;
                        $geneltoplamTutar   = $geneltoplamTutar+$furun->tutar;

                    }
                    $data = [
                        'id'    => $faturaUrunDetay->fatura,
                        'toplam_tutar' => $toplamTutar,
                        'kdv_toplami'  => $kdvToplami,
                        'genel_toplam' => $geneltoplamTutar
                    ];

                    $faturaTutarGuncelle = FaturaModel::faturaTutarGuncelle($data);

                    $data['success'] = 'Ürün işlemi başarı ile yapıldı!';
                    $data['redirect'] = URL::site().'faturalar/duzenle/'.$faturaUrunDetay->fatura;

                }else{

                    $data['error'] = "Ürün silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "bildirimGonder":

                $data['title'] = "Bildirim Gönderme İşlemi";

                $faturaUrunDetay = FaturaModel::faturaUrunDetay($dataId);

                $bildirimGOnder = "0";

                if($bildirimGOnder){

                    $data['success'] = 'Bildirim başarı ile gönderildi!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Bildirim Gödnerme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

        }

    }

    public function main($filtre="")
    {
        if($filtre!=""){
            $bol = explode("-",$filtre);
            $filtre = [
                'filtre' => $bol[0],
                'deger'  => $bol[1]
            ];
        }else{
            $filtre = "";
        }

        $user = User::data();

        $faturalar = FaturaModel::liste($filtre);

        View::faturalar($faturalar);

    }

    public function siparis($id)
    {

        $user = User::data();

        $faturalar = FaturaModel::siparisFaturalari($id);

        View::faturalar($faturalar);

    }

    public function form($id=""){

        $user = User::data();

        if ($id!=""){

            $faturaDetay = FaturaModel::detay($id);
            $faturaUrunleri = FaturaModel::faturaUrunleri(($id));

            View::faturaDetay($faturaDetay);
            View::faturaUrunleri($faturaUrunleri);

        }

    }

    public function guncelle($id){

        $faturaDetay    = FaturaModel::detay($id);
        $cariDetay      = CariModel::detay($faturaDetay->musteri);
        $belge_no       = Post::belge_no();
        $belge_tarihi   = AyarModel::tarihDuzelt(Post::belge_tarihi());
        $vade_tarihi    = AyarModel::tarihDuzelt(Post::vade_tarihi());
        $odeme_yontemi  = Post::odeme_yontemi()=="" ? "0" : Post::odeme_yontemi();
        $siparis_notu   = Post::siparis_notu();

        if($faturaDetay->tur=="1") {

            $firmaAdi       = "";
            $fatura_adresi  = "";
            $vergi_dairesi  = "";
            $vergi_no       = "";

        }else{
            $firmaAdi       = $cariDetay->firma_adi;
            $fatura_adresi  = $cariDetay->fatura_adresi;
            $vergi_dairesi  = $cariDetay->vergi_dairesi;
            $vergi_no       = $cariDetay->vergi_no;
        }

        $data = [
            'id'                => $id,
            'belge_no'          => $belge_no,
            'fatura_adi'        => $firmaAdi,
            'fatura_adresi'     => $fatura_adresi,
            'vergi_dairesi'     => $vergi_dairesi,
            'vergi_no'          => $vergi_no,
            'tedarikci'         => $faturaDetay->tedarikci,
            'musteri'           => "0",
            'belge_tarihi'      => $belge_tarihi,
            'vade_tarihi'       => $vade_tarihi,
            'durum'             => $vade_tarihi,
            'odeme_yontemi'     => $odeme_yontemi,
            'aciklama'          => $siparis_notu
        ];

        $faturaGuncelle = FaturaModel::guncelle($data);


        if ($faturaGuncelle){

            $say = count(Post::id());

            $urunId     = Post::id();
            $urun_adi   = Post::urun_adi();
            $miktar     = Post::miktar();
            $aciklama   = Post::aciklama();
            $fiyat      = Post::fiyat();
            $kdv        = Post::kdv();

            for ($i=0; $i<$say; $i++) {

                $fiyati = (float) $fiyat[$i];
                $adet = (int) $miktar[$i];

                $urunData = [
                    'id'            => $urunId[$i],
                    'urun_adi'      => $urun_adi[$i],
                    'aciklama'      => $aciklama[$i],
                    'miktar'        => $miktar[$i],
                    'fiyat'         => $fiyati,
                    'kdv'           => $kdv[$i],
                    'kdv_tutari'    => (($fiyati*$adet)/100)*$kdv[$i],
                    'tutar'         => ((($fiyati*$adet)/100)*$kdv[$i])+($fiyati*$adet)
                ];

                $faturaUrunGuncelle = FaturaModel::faturaUrunGuncelle($urunData);



                if ($faturaUrunGuncelle){

                    if($faturaDetay->satis_turu=="1"){
                        //Eğer fatura ürünli ilk sipariş ise faturada ki ürün fiyatları ile siparişteki ürün fiyatları eşitleniyor
                        $faturaUrunDetay = FaturaModel::faturaUrunDetay($urunId[$i]);

                        $urunFiyatData = [

                            'id'            => $faturaUrunDetay->siparis_urun_id,
                            'adet'          => $miktar[$i],
                            'birim_fiyat'   => $fiyati,
                            'kdv'           => $kdv[$i],
                            'kdv_tutari'    => (($fiyati*$adet)/100)*$kdv[$i],
                            'toplam_fiyat'  => ((($fiyati*$adet)/100)*$kdv[$i])+($fiyati*$adet)

                        ];
                        $siparisUrunFiyatGuncelle = SiparisModel::siparisUrunFiyatGuncelle($urunFiyatData);

                    }

                    $urunGuncellemeUyariNotu = "<br>".$urun_adi[$i]." Ürünü güncellendi";
                }else{
                    $urunGuncellemeUyariNotu = "<br>".$urun_adi[$i]." Ürünü Malesef güncelleneMEdi";
                }

            }

            $faturaUrunleri = FaturaModel::faturaUrunleri($id);


            $toplamTutar = 0;
            $geneltoplamTutar = 0;
            $kdvToplami = 0;

            foreach ($faturaUrunleri as $furun) {

                $toplamTutar        = $toplamTutar+($furun->fiyat*$furun->miktar);
                $kdvToplami         = $kdvToplami+$furun->kdv_tutari;
                $geneltoplamTutar   = $geneltoplamTutar+$furun->tutar;

            }

            $data = [
                'id'    => $id,
                'toplam_tutar' => $toplamTutar,
                'kdv_toplami'  => $kdvToplami,
                'genel_toplam' => $geneltoplamTutar
            ];

            $faturaTutarGuncelle = FaturaModel::faturaTutarGuncelle($data);

            if ($faturaTutarGuncelle){
                $urunTutarGuncellemeUyariNotu = "<br>Fatura Tutarı güncellendi";
            }else{
                $urunTutarGuncellemeUyariNotu = "<br><span class='text-danger'>Fatura Tutarı Malesef güncelleneMEdi</span>";
            }

            //Eğer güncellenen fatura yenileme faturası değilde SATIŞ FATURASI İSE sipariş tutarınıda güncelliyoruz
            if($faturaDetay->satis_turu=="1"){

                $siparisToplamData =[
                    'id'                    =>$faturaDetay->siparis_id,
                    'toplam_tutar'          =>$toplamTutar,
                    'kdv_tutari'            =>$kdvToplami,
                    'genel_toplam_tutari'   =>$geneltoplamTutar
                ];

                SiparisModel::siparisToplamTutarGuncelle($siparisToplamData);

            }

            AyarModel::basarili('Başarıli İşlem','Fatura Güncelleme İşlemi gerçekleştirildi'.$urunGuncellemeUyariNotu.$urunTutarGuncellemeUyariNotu,URL::site('faturalar/duzenle/'.$id));

        }else{

            AyarModel::basarisiz('Başarısız İşlem','Fatura Güncelleme İşlemi Başarısız Oldu',URL::site('faturalar/duzenle/'.$id));

        }


    }

    public function duzenle($id=""){

        $user = User::data();
        $musteriler = CariModel::tumListe();
        $odemeYontemi = AyarModel::odemeYontemleri();
        $urunler = UrunModel::tumListe();

        if ($id!=""){

            $maliIslemler = KasaModel::maliSorgu('fatura',$id);

            $faturaDetay    = FaturaModel::detay($id);
            $faturaUrunleri = FaturaModel::faturaUrunleri($id);
            $cariDetay      = CariModel::detay($faturaDetay->musteri);

            if($faturaDetay->tur=="1" and $faturaDetay->tedarikci!="") {

                $tedarikci = TedarikciModel::detay($faturaDetay->tedarikci);

            }

            $detay = (object)[
                'id'                    => $id,
                'musteri'               => $cariDetay->id,
                'tur'                   => $faturaDetay->tur,
                'satis_turu'            => $faturaDetay->satis_turu,
                'aciklama'              => $faturaDetay->aciklama,
                'belge_no'              => $faturaDetay->belge_no,
                'odeme'                 => $faturaDetay->odeme,
                'durum'                 => $faturaDetay->durum,
                'resmi_fatura_dosyasi'  => $faturaDetay->resmi_fatura_dosyasi,
                'belge_tarihi'          => AyarModel::tarihGoster($faturaDetay->belge_tarihi),
                'vade_tarihi'           => $faturaDetay->vade_tarihi=="0000-00-00" ? '': AyarModel::tarihGoster($faturaDetay->vade_tarihi),
                'odeme_yontemi'         => $faturaDetay->odeme_yontemi,
                'cariDetay'             => $cariDetay
            ];

            $gelir = 0;
            $gider = 0;
            View::detay($detay);
            View::tedarikci($tedarikci);
            View::gelir($gelir);
            View::gider($gider);
            View::maliIslemler($maliIslemler);
            View::faturaUrunleri($faturaUrunleri);

        }else{

            $faturaDetay = [
               'belge_no'       => '',
               'belge_tarihi'   => '',
               'vade_tarihi'    => '',
            ];

        }

        View::urunler($urunler);
        View::musteriler($musteriler);
        View::odemeYontemleri($odemeYontemi);

    }

    public function olustur(){

        $user = User::data();
        $musteriler = CariModel::tumListe();
        $odemeYontemi = AyarModel::odemeYontemleri();
        $urunler = UrunModel::tumListe();

        View::urunler($urunler);
        View::musteriler($musteriler);
        View::odemeYontemleri($odemeYontemi);

    }

    public function urunGuncelle($urun){

        $faturaUrunDetay = FaturaModel::faturaUrunDetay($urun);

        $malzemeDetay = MalzemeModel::detay($faturaUrunDetay->urun);

        $faturaDetay = FaturaModel::detay($faturaUrunDetay->fatura);


        $urunAdi    = Post::urunAdi();
        $miktar     = Post::miktar();
        $fiyat      = Post::fiyat();
        $kdv        = Post::kdv();

        $data = [
            'id'        => $urun,
            'fatura'    => $faturaUrunDetay->fatura,
            'urun'      => $faturaUrunDetay->urun,
            'urun_adi'  => $urunAdi,
            'miktar'    => $miktar,
            'fiyat'     => $fiyat,
            'kdv'       => $kdv,
            'tutar'     => $fiyat*$miktar
        ];

        $urunGuncelle = FaturaModel::faturaUrunGuncelle($data);

        if($urunGuncelle) {

            $araToplam = 0;
            $kdvToplam = 0;
            $toplam    = 0;

            $faturaUrunleri = FaturaModel::faturaUrunleri($faturaUrunDetay->fatura);

            foreach ($faturaUrunleri as $fu) {

                $araToplam  = $araToplam+($fu->fiyat*$fu->miktar);
                $malzemeKdvTutari = FaturaModel::kdvHesapla(($fu->fiyat*$fu->miktar),$fu->kdv);
                $kdvToplam = $kdvToplam+$malzemeKdvTutari;
                $toplam     = $araToplam+$kdvToplam;

            }

            $faturaData = [
                'id'            =>$faturaDetay->id,
                'tur'           =>$faturaDetay->tur,
                'toplam_tutar'  =>$araToplam,
                'ara_toplam'    =>$araToplam,
                'kdv'           =>$kdvToplam,
                'indirim'       =>'0',
                'toplam'        =>$toplam,
            ];

            $faturaGuncelle = FaturaModel::urunDuzenlemeSonrasiGuncelleme($faturaData);

            if ($faturaGuncelle) {

                if($faturaDetay->tedarikci!=""){

                    $eskiFaturaTutari = $faturaDetay->toplam;

                    $guncelFatura = FaturaModel::detay($faturaDetay->id);

                    $yeniFaturaTutari = $toplam;

                    $tedarikciDetay = TedarikciModel::detay($faturaDetay->tedarikci);


                    if ($eskiFaturaTutari > $toplam){

                        $faturaFarki = $eskiFaturaTutari-$toplam;

                        $guncelBakiye = $tedarikciDetay->guncel_bakiye+$faturaFarki;

                        $bakiyeGuncelle = TedarikciModel::bakiyeGuncelle($tedarikciDetay->id,$guncelBakiye);

                    }else{
                        $faturaFarki = $toplam-$eskiFaturaTutari;

                        $guncelBakiye = $tedarikciDetay->guncel_bakiye-$faturaFarki;

                        $bakiyeGuncelle = TedarikciModel::bakiyeGuncelle($tedarikciDetay->id,$guncelBakiye);

                    }

                    //Redirect::insert(['bilgi'=>'<div class="callout callout-success">Fatura Urun Bilgileri Güncellendi!</div>'])->action(URL::site("fatura/duzenle/".$faturaDetay->id));

                }else{}

                /////////////////////// STOK İŞLEMLERİ //////////////////////

                if($miktar<$faturaUrunDetay->miktar){

                    $fark = $faturaUrunDetay->miktar-$miktar;

                    $guncelStok = $malzemeDetay->stok-$fark;

                    $stokGuncelle = MalzemeModel::stokGuncelle(['id'=>$malzemeDetay->id,'stok'=>$guncelStok]);

                    $data = [
                        'malzeme'   => $faturaUrunDetay->urun,
                        'miktar'    => $fark,
                        'hareket'   => "-",
                        'sebebi'    => $faturaDetay->belge_no." Numaralı faturada stok düzenlemesi yapıldığı için aradaki fark stokdan düşülmüştür",
                        'fatura_no' => $faturaDetay->belge_no==""?"-":$faturaDetay->belge_no
                    ];

                    $stokHareketEkle = MalzemeModel::hareketEkle($data);

                }else{
                    $fark = $miktar-$faturaUrunDetay->miktar;

                    $guncelStok = $malzemeDetay->stok+$fark;

                    $stokGuncelle = MalzemeModel::stokGuncelle(['id'=>$malzemeDetay->id,'stok'=>$guncelStok]);

                    $data = [
                        'malzeme'   => $faturaUrunDetay->urun,
                        'miktar'    => $fark,
                        'hareket'   => "+",
                        'sebebi'    => $faturaDetay->belge_no." Numaralı faturada stok düzenlemesi yapıldığı için aradaki fark stoka eklenmiştir",
                        'fatura_no' => $faturaDetay->belge_no==""?"-":$faturaDetay->belge_no
                    ];

                    $stokHareketEkle = MalzemeModel::hareketEkle($data);

                }

                /////////////////////// STOK İŞLEMLERİ //////////////////////


                Redirect::insert(['bilgi'=>'<div class="callout callout-success">Fatura Urun Bilgileri Güncellendi!</div>'])->action(URL::site("fatura/duzenle/".$faturaDetay->id));

            }else{

                Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Fatura Urun Bilgileri Güncellenemedi!</div>'])->action(URL::site("fatura/duzenle/".$faturaDetay->id));

            }

        }



    }

    public function resmilestir($id){

        $faturaDetay = FaturaModel::detay($id);
        $cariDetay   = CariModel::detay($faturaDetay->musteri);

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">'.$data['error'].'</div></div>'])->action('faturalar');

        }else{

            $faturaNo = Post::fatura_no();
            $bildirim = Post::bildirim();

            if(Upload::isFile('fatura_dosya')){

                Upload::mimes('application/pdf')
                    ->convertName($faturaNo.'-'.date('Y-m-d-H-i-s'))
                    ->source('fatura_dosya')
                    ->target(REAL_BASE_DIR . 'Uploads/faturalar/')
                    ->start();
                $dosyaBilgi = Upload::info();
                $dosyaAdi   = $dosyaBilgi->encodeName;
            }else{
                $dosyaAdi   = "";
            }

            $resmiData = [
                'id' => $id,
                'belge_no' => $faturaNo,
                'fatura_dosya'     => $dosyaAdi,
                'durum'     => "2"
            ];

            $faturaResmilestir = FaturaModel::faturaResmilestir($resmiData);

            $ekMailBilgi = "";

            if ($faturaResmilestir) {

                if($bildirim=="1"){

                    $mailgonder = Email::subject('Resmi Fatura Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                        'konu' => 'Resmi Fatura Bildirimi',
                        'mesaj' => $faturaDetay->id.' faturanız resmileştirilmiştir. '.$faturaDetay->genel_toplam.' TL Tutarında ki faturanızın; Resmi Fatura Numarası:'.$faturaNo.' Resmi faturanızı görüntülemek için aşağıdaki linke tıklayınız..<br> <hr>',
                        'link' => AyarModel::defaultAyarlar('siteUrl')."/Uploads/faturalar/".$dosyaAdi,
                        'link_baslik' => 'Resmi Faturanız',
                        'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                        'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
                        'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                        'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                        'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                    ])->send();

                    //SMS GÖNDERİMİ
                    if(AyarModel::defaultAyarlar('smsGonderim')=="1"){

                        $smsData = [
                            'mesaj'=>$faturaDetay->id.' faturanız resmileştirilmiştir. '.number_format($faturaDetay->genel_toplam,2).' TL Tutarında ki faturanızın; Resmi faturanız E-Posta olarak iletilmiştir.',
                            'numara'=>$cariDetay->gsm
                        ];

                        $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);

                    }
                    //SMS GÖNDERİMİ

                    if ($mailgonder) {
                        $ekMailBilgi = "<br>Müşteriye Bilgilendirme Maili Gönderildi !";
                    }else{
                        $ekMailBilgi = "<br><span class='text-danger'>Müşteriye Bilgilendirme Maili Gönderilemedi !</span>".Email::error();

                    }

                }

                AyarModel::basarili("Başarılı işlem","Resmişleştirme işlemi başarı ile yapıldı!".$ekMailBilgi.$smsGonder,URL::site("faturalar"));

            }else{
                AyarModel::basarili("Başarısız işlem","Resmişleştirme işlemi YAPILAMADI!".$ekMailBilgi ,URL::site("faturalar"));
            }

        }

    }

    public function kalemEkle($id){

            $urunadi    = Post::urun_adi();
            $aciklama   = Post::aciklama();
            $miktar     = Post::miktar();
            $kdv        = Post::kdv();
            $fiyat      = Post::fiyat();
            $kdvTutari  = ($miktar*$fiyat)*$kdv/100;
            $toplam     = ($miktar*$fiyat)+$kdvTutari;

            $data = [
                'fatura'    => $id,
                'urun'  => '0',
                'urun_adi'  => $urunadi,
                'aciklama'  => $aciklama,
                'miktar'    => $miktar,
                'kdv'       => $kdv,
                'fiyat'     => $fiyat,
                'kdv_tutari'=> $kdvTutari,
                'tutar'     => $toplam
            ];

            $urunEkle = FaturaModel::urunEkle($data);

            if ($urunEkle) {

                $faturaUrunleri = FaturaModel::faturaUrunleri($id);

                $toplamTutar = 0;
                $geneltoplamTutar = 0;
                $kdvToplami = 0;

                foreach ($faturaUrunleri as $furun) {


                        $toplamTutar        = $toplamTutar+($furun->fiyat*$furun->miktar);
                        $kdvToplami         = $kdvToplami+$furun->kdv_tutari;
                        $geneltoplamTutar   = $geneltoplamTutar+$furun->tutar;

                }
                $data = [
                    'id'    => $id,
                    'toplam_tutar' => $toplamTutar,
                    'kdv_toplami'  => $kdvToplami,
                    'genel_toplam' => $geneltoplamTutar
                ];

                $faturaTutarGuncelle = FaturaModel::faturaTutarGuncelle($data);

                AyarModel::basarili("Başarılı işlem","Ürün Ekleme işlemi başarı ile yapıldı!",URL::site("faturalar/duzenle/").$id);
            }else{
                AyarModel::basarisiz("Başarısız işlem","Ürün Ekleme işlemi YAPILAMADI!",URL::site("faturalar/duzenle/").$id);
            }


    }

    public function odendiYap($id)
    {

        $user = User::data();
        $faturaDetay = FaturaModel::detay($id);
        $faturaUrunleri = FaturaModel::faturaUrunleri($faturaDetay->id);

        $cariDetay = CariModel::detay($faturaDetay->musteri);



        //Fatura ödendi yapılyıor
        if(Post::siparisOdendi()=="1"){

            $siparisOdemeDurumDegistir = SiparisModel::odemeDurumDegistir($faturaDetay->siparis_id,"1");

            if($siparisOdemeDurumDegistir){
                $ekBilgi = "Siparişin Ödeme durumu değiştirildi !";
            }

        }

        //Fatura ödendi yapılyıor
        if(Post::odendi()=="1"){

            $faturaOdemeDurumDegistir = FaturaModel::odemeDurumDegistir($faturaDetay->id,"1");

        }

        if ($faturaOdemeDurumDegistir) {

            $sipariGecmisi = [
                'cari' => $faturaDetay->musteri,
                'siparis' => $faturaDetay->siparis_id,
                'aciklama' => $faturaDetay->id." Numaralı Fatura ödendi olarak işaretlendi kasa defterine herhangi bir veri işlenmedi.",
                'guncelleyen' => $user->id
            ];

            $gecmisEkle = SiparisModel::siparisGesmisEkle($sipariGecmisi);

            Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Fatura Ödendi olarak işaretlendi !.<br>'.$ekBilgi.'</div></div>'])->action(URL::prev());

        }else{
            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">İşlem sırasında hata oluştu !.</div></div>'])->action(URL::prev());
        }




    }

    public function sil($id)
    {

        $faturaDetay = FaturaModel::detay($id);

        $sil = FaturaModel::sil($id);

        if ($sil) {
            AyarModel::basarili("Silme islemi","Silme islemi başarı ile tamamlandı!",URL::site("faturalar"));
        } else {
            AyarModel::basarisiz("Silme islemi","Silme islemi YAPILAMADI!",'faturalar/siparis/'.$faturaDetay->siparis_id);
        }
//test
    }

    public function yazdir($id){

        function tr($t){ return iconv("UTF-8","iso-8859-9",$t); }

        /*

            $pdf->Ln(20);   // 20px dikey boşluk yaptı
            $pdf->Cell(20); // 20px solda yatay boşluk yaptı

        */


        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->AddFont('arial_tr','','arial_tr.php',true);
        $pdf->SetFont('arial_tr','',10);

        $pdf->Ln(20);

        $pdf->Cell(70,10,tr('OYAK ENAULT OTO FAB. A.Ş.'),1,0,'L');
        $pdf->Ln();
        $pdf->MultiCell(70,7,tr('BURSA ORGANİZE SANAYİ BÖLGESİ MAVİ CAD: NO:5 PK:255 NİLÜFER / BURSA '),1);
        //$pdf->Ln();
        $pdf->Cell(70,10,tr('ERTUĞRULGAZİ'),1,0,'L');
        $pdf->Cell(70,10,tr('6490039840'),1,0,'L');
        $pdf->Output('I','TF-1905-FATURA');



        /*
        $user = User::data();

        $user                   = User::data();

        AyarModel::yetkiKontrol(\Json::decode($user->yetkiler),CURRENT_CONTROLLER,CURRENT_CFUNCTION);

        $detay      = SiparisModel::detay($id);
        $urunleri   = SiparisModel::siparisUrunleri($id);
        $uyeBilgi   = UyeModel::detay($detay->uye);
        $faturaDetay= FaturaModel::bengeNoDetay($detay->belge_no);


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

        View::detay($detay);
        View::faturaDetay($faturaDetay);
        View::siparisUrunleri($urunleri);
        View::toplamlar($toplamlar);
        View::uyeBilgi($uyeBilgi);

        exit();
        */
    }

    public function bildirimGonder($id){

        //$mail = new PHPMailer(true);



        $faturaDetay = FaturaModel::detay($id);
        $cariDetay = CariModel::detay($faturaDetay->musteri);
        $ekNot = Post::ekNot();

        $mailgonder = Email::subject('Fatura Hatırlatma')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

            'konu' => 'Fatura Hatırlatma',
            'mesaj' => $faturaDetay->id.' Numaralı faturanızın ödemesini hatırlatmak için bu maili aldınız. <br>'.AyarModel::tarihGoster($faturaDetay->vade_tarihi).' ödeme tarihli '.number_format($faturaDetay->genel_toplam,2).'TL Tutarında ki faturanızı ödemek için aşağıdaki sayfayı ziyaret edebilirsiniz.<br><br><br>Ek Not:<br>'.$ekNot.' <hr>',
            'link' => AyarModel::defaultAyarlar('siteUrl')."/faturalar/detay/".$id,
            'link_baslik' => 'Fatura\'yı Ödemek İçin Tıklayınız',
            'firma' => AyarModel::defaultAyarlar('firmaAdi'),
            'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
            'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
            'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
            'telefon' => AyarModel::defaultAyarlar('firmaTel'),
        ])->send();

        //SMS GÖNDERİMİ
        if(AyarModel::defaultAyarlar('smsGonderim')=="1"){

            $smsData = [
                'mesaj'=>'Bu sms Fatura ödemenizi hatırlatmak için gönderilmiştir.'.AyarModel::tarihGoster($faturaDetay->vade_tarihi).' ödeme tarihli '.number_format($faturaDetay->genel_toplam,2).'TL faturanız bulunmaktadır. Hizmetinizin kesitiye uğramaması için ödeme yapmanız gerekmektedir. ',
                'numara'=>$cariDetay->gsm
            ];

            $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);

        }
        //SMS GÖNDERİMİ

        if ($mailgonder) {
            AyarModel::basarili('Başarılı İşlem','Fatura hatırlatma işlemi gerçekleştirildi.',URL::site('faturalar'));
        }else{
            AyarModel::basarisiz('Başarısız İşlem','Fatura hatırlatma işlemi gerçekleştirilemedi.',URL::site('faturalar'));

        }

        


    }


    public function s404()
    {
    }
}