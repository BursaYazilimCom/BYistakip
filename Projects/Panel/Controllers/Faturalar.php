<?php namespace Project\Controllers;

use Method, Post,User, Redirect,Date,FPDF,URL,Validation,Upload,Email;
use InternalFaturaModel as FaturaModel,AyarModel,KasaModel,SiparisModel,InternalUrunModel as UrunModel,UyeModel,InternalMalzemeModel as MalzemeModel, TedarikciModel,CariModel;

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

    public function main()
    {

        $user = User::data();

        $faturalar = FaturaModel::liste();

        View::faturalar($faturalar);

    }

    public function siparis($id)
    {

        $user = User::data();

        $faturalar = FaturaModel::liste($id);

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

    public function duzenle($id=""){

        $user = User::data();
        $musteriler = CariModel::tumListe();
        $odemeYontemi = AyarModel::odemeYontemleri();
        $urunler = UrunModel::tumListe();

        if ($id!=""){

            $faturaDetay = FaturaModel::detay($id);
            $faturaUrunleri = FaturaModel::faturaUrunleri($id);
            $cariDetay = CariModel::detay($faturaDetay->musteri);

            $detay = (object)[
                'id'    => $id,
                'musteri'    => $cariDetay->id,
                'tur'    => $faturaDetay->tur,
                'aciklama'    => $faturaDetay->aciklama,
                'belge_no'    => $faturaDetay->belge_no,
                'odeme'    => $faturaDetay->odeme,
                'durum'    => $faturaDetay->durum,
                'resmi_fatura_dosyasi'    => $faturaDetay->resmi_fatura_dosyasi,
                'belge_tarihi'    => AyarModel::tarihGoster($faturaDetay->belge_tarihi),
                'vade_tarihi'    => $faturaDetay->vade_tarihi=="0000-00-00" ? '': AyarModel::tarihGoster($faturaDetay->vade_tarihi),
                'odeme_yontemi'    => $faturaDetay->odeme_yontemi,
                'cariDetay' => $cariDetay
            ];


            View::detay($detay);
            View::faturaUrunleri($faturaUrunleri);

        }else{

           $faturaDetay = [
               'belge_no'    => '',
               'belge_tarihi'    => '',
               'vade_tarihi'    => '',
           ];

        }

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
                        'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                        'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                        'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                    ])->send();

                    if ($mailgonder) {
                        $ekMailBilgi = "<br>Müşteriye Bilgilendirme Maili Gönderildi !";
                    }else{
                        $ekMailBilgi = "<br><span class='text-danger'>Müşteriye Bilgilendirme Maili Gönderilemedi !</span>".Email::error();
                    }

                }

                AyarModel::basarili("Başarılı işlem","Resmişleştirme işlemi başarı ile yapıldı!".$ekMailBilgi,URL::site("faturalar"));

            }else{
                AyarModel::basarili("Başarısız işlem","Resmişleştirme işlemi YAPILAMADI!".$ekMailBilgi,URL::site("faturalar"));
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

    public function sil($id,$stok="")
    {

        $user = User::data();

        $faturaDetay = FaturaModel::detay($id);
        $faturaUrunleri = FaturaModel::faturaUrunleri($id);

        $tedarikci = TedarikciModel::detay($faturaDetay->tedarikci);

        if($stok == ""){

            $sil = FaturaModel::sil($id);

        }else{

            foreach ($faturaUrunleri as $fu) {

                $malzemeDetay = MalzemeModel::detay($fu->urun);


                $stok = $malzemeDetay->stok-$fu->miktar;


                $stokData = [
                    'id'        => $malzemeDetay->id,
                    'stok'      => $stok
                ];

                $stokGuncelle = MalzemeModel::stokGuncelle($stokData);

                $faturaData = ["fatura_no"=>$faturaDetay->id,"malzeme"=>$fu->urun];

                $stokHareketSil = MalzemeModel::stokHareketSil($id,$faturaData);

            }

            $sil = FaturaModel::sil($id);

        }

        /*******TEDARİKÇİ BAKİYE GÜNCELLE******/

        $guncelBakiye = $tedarikci->guncel_bakiye+$faturaDetay->toplam;

        $tedarikciBakiyeGuncelle = TedarikciModel::bakiyeGuncelle($id,$guncelBakiye);

        /*******TEDARİKÇİ BAKİYE GÜNCELLE******/


        if ($sil) {

            AyarModel::nelerOluyor($user->isim,'fatura',$id.' Numaralı fatura silindi');

            Redirect::insert(['bilgi' => '<div class="callout callout-success">Silme işlemi başarı ile yapıldı!</div>'])->action('tedarikci/detay/'.$faturaDetay->tedarikci);

        } else {

            Redirect::insert(['bilgi' => '<div class="callout callout-danger">Silme işlemi yapılamadı!</div>'])->action('tedarikci/detay/'.$faturaDetay->tedarikci);

        }

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


    public function s404()
    {
    }
}