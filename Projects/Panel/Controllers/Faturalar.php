<?php namespace Project\Controllers;

use Method, Post,User, Redirect,Date,FPDF,URL;
use InternalFaturaModel as FaturaModel,AyarModel,KasaModel,SiparisModel,UyeModel,InternalMalzemeModel as MalzemeModel, TedarikciModel;

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

        if ($id!=""){

            $faturaDetay = FaturaModel::detay($id);
            $faturaUrunleri = FaturaModel::faturaUrunleri(($id));
            $tedarikciDetay = TedarikciModel::detay($faturaDetay->tedarikci);

            View::faturaDetay($faturaDetay);
            View::faturaUrunleri($faturaUrunleri);
            View::tedarikciDetay($tedarikciDetay);

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Fatura detayı bulunamadı!</div>'])->action(URL::prev());

        }

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