<?php namespace Project\Controllers;

use Method, Post,User, Redirect,Date,FPDF,URL,Validation,Upload,Email,Json;
use InternalTeklifModel as TeklifModel,AyarModel,KasaModel,SiparisModel,InternalUrunModel as UrunModel,UyeModel;
use InternalMalzemeModel as MalzemeModel, TedarikciModel,InternalCariModel as CariModel;



class Teklifler extends Controller
{

    public function __construct()
    {

        $user                   = User::data();
        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array(CURRENT_CONTROLLER,$yetkiler)){

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div>'])->action('home');

        }

    }

    public function teklifKaydet(){
        
        $cariDetay = CariModel::detay(Post::musteri());

        $teklifData = [
           
            'musteri'           =>Post::musteri(),
            'belge_no'          =>Post::belge_no(),
            'fatura_adi'        =>$cariDetay->firma_adi,
            'fatura_adresi'     =>$cariDetay->fatura_adresi,
            'durum'             =>Post::durum(),
            'odeme_yontemi'     =>Post::odeme_yontemi(),
            'aciklama'          =>Post::notu()
        ];

        $teklifOlustur = TeklifModel::ekle($teklifData);

        if ($teklifOlustur){
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
                    'teklif'                =>$teklifOlustur,
                    'urun_adi'              =>$urun[$fu],
                    'aciklama'              =>$aciklama[$fu],
                    'miktar'                =>$miktar[$fu],
                    'fiyat'                 =>$fiyat[$fu],
                    'kdv'                   =>$kdv[$fu],
                    'kdv_tutari'            =>$urunKdvTutari,
                    'tutar'                 =>$urunKdvTutari+($fiyat[$fu]*$miktar[$fu]),
                ];

                $teklifUrunEkle = TeklifModel::urunEkle($fUrun);

            }

        } 

        if($teklifOlustur){
            AyarModel::basarili("Teklif Oluşturuldu","Teklif oluşturmak işlemi başarı ile gerçekleştirildi",URL::site('teklifler/duzenle/'.$teklifOlustur));
        }else{
            AyarModel::basarisiz("Teklif Oluşturuma Hatası","Teklif oluşturmak işlemi GERÇEKLEŞTİRİLEMEDİ",URL::site('teklifler'));
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

            case "teklifUrunSil":

                $data['title'] = "Ürün Silme İşlemi";

                $teklfiUrunDetay = TeklifModel::teklifUrunDetay($dataId);

                $sil = TeklifModel::teklifUrunSil($teklfiUrunDetay->teklif,$dataId);

                if($sil){

                    $data['success'] = 'Ürün işlemi başarı ile yapıldı!';
                    $data['redirect'] = URL::site().'teklifler/duzenle/'.$teklfiUrunDetay->teklif;

                }else{

                    $data['error'] = "Ürün silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

        }

    }

    public function main()
    {

        $user = User::data();

        $teklifler = TeklifModel::liste();

        View::teklifler($teklifler);

    }

    public function form($id=""){

        $user = User::data();

        if ($id!=""){

            $teklifDetay = TeklifModel::detay($id);
            $teklifUrunleri = TeklifModel::teklifUrunleri(($id));

            View::teklifDetay($teklifDetay);
            View::teklifUrunleri($teklifUrunleri);

        }

    }

    public function guncelle($id){

        $teklifDetay    = TeklifModel::detay($id);
        $cariDetay      = CariModel::detay($teklifDetay->musteri);
        $belge_no       = Post::belge_no();
        $odeme_yontemi  = Post::odeme_yontemi();
        $aciklama       = Post::siparis_notu();
        $durum          = Post::durum();

        $data = [
            'id'                => $id,
            'belge_no'          => $belge_no,
            'fatura_adi'        => $cariDetay->firma_adi,
            'fatura_adresi'     => $cariDetay->fatura_adresi,
            'odeme_yontemi'     => $odeme_yontemi,
            'durum'             => $durum,
            'aciklama'          => $aciklama
        ];

        $teklifGuncelle = TeklifModel::guncelle($data);


        if ($teklifGuncelle){

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

                $teklifUrunGuncelle = TeklifModel::teklifUrunGuncelle($urunData);



                if ($teklifUrunGuncelle){

                    $urunGuncellemeUyariNotu = "<br>".$urun_adi[$i]." Ürünü güncellendi";
                }else{
                    $urunGuncellemeUyariNotu = "<br>".$urun_adi[$i]." Ürünü Malesef güncelleneMEdi";
                }

            }

            if ($teklifUrunGuncelle){
                $urunTutarGuncellemeUyariNotu = "<br>Teklif güncellendi";
            }else{
                $urunTutarGuncellemeUyariNotu = "<br><span class='text-danger'>Teklif Tutarı Malesef güncelleneMEdi</span>";
            }


            AyarModel::basarili('Başarıli İşlem','Teklif Güncelleme İşlemi gerçekleştirildi'.$urunGuncellemeUyariNotu.$urunTutarGuncellemeUyariNotu,URL::site('teklifler/duzenle/'.$id));

        }else{

            AyarModel::basarisiz('Başarısız İşlem','Teklif Güncelleme İşlemi Başarısız Oldu',URL::site('teklifler/duzenle/'.$id));

        }


    }

    public function duzenle($id=""){

        $user = User::data();
        $musteriler = CariModel::tumListe();
        $odemeYontemi = AyarModel::odemeYontemleri();


        if ($id!=""){

            $teklifDetay = TeklifModel::detay($id);
            $teklifUrunleri = TeklifModel::teklifUrunleri($id);
            $cariDetay = CariModel::detay($teklifDetay->musteri);

            $detay = (object)[
                'id'                => $id,
                'musteri'           => $cariDetay->id,
                'aciklama'          => $teklifDetay->aciklama,
                'belge_no'          => $teklifDetay->belge_no,
                'durum'             => $teklifDetay->durum,
                'ekleme_tarihi'     => $teklifDetay->ekleme_tarihi,
                'odeme_yontemi'     => $teklifDetay->odeme_yontemi,
                'cariDetay'         => $cariDetay
            ];

            $gelir = 0;
            $gider = 0;
            View::detay($detay);

            View::teklifUrunleri($teklifUrunleri);

        }else{

            $teklifDetay = [
               'belge_no'    => ''
            ];

        }

        View::musteriler($musteriler);
        View::odemeYontemleri($odemeYontemi);

    }

    public function olustur(){

        $user = User::data();
        $musteriler = CariModel::tumListe();
        $odemeYontemi = AyarModel::odemeYontemleri();

        View::musteriler($musteriler);
        View::odemeYontemleri($odemeYontemi);

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
                'teklif'    => $id,
                'urun'  => '0',
                'urun_adi'  => $urunadi,
                'aciklama'  => $aciklama,
                'miktar'    => $miktar,
                'kdv'       => $kdv,
                'fiyat'     => $fiyat,
                'kdv_tutari'=> $kdvTutari,
                'tutar'     => $toplam
            ];

            $urunEkle = TeklifModel::urunEkle($data);

            if ($urunEkle) {

                AyarModel::basarili("Başarılı işlem","Ürün Ekleme işlemi başarı ile yapıldı!",URL::site("teklifler/duzenle/").$id);
            }else{
                AyarModel::basarisiz("Başarısız işlem","Ürün Ekleme işlemi YAPILAMADI!",URL::site("teklifler/duzenle/").$id);
            }


    }


    public function sil($id)
    {

        $teklifDetay = TeklifModel::detay($id);

        $sil = TeklifModel::sil($id);

        if ($sil) {

            Redirect::insert(['bilgi' => '<div class="callout callout-success">Silme işlemi başarı ile yapıldı!</div>'])->action('teklifler');

        } else {

            Redirect::insert(['bilgi' => '<div class="callout callout-danger">Silme işlemi yapılamadı!</div>'])->action('teklifler');

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


    public function s404()
    {
    }
}