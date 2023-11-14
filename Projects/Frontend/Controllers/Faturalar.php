<?php namespace Project\Controllers;

use Method, Post,User, Redirect,Date,URL,Validation,Upload,Email,Json,Session;
use InternalFaturaModel as FaturaModel,AyarModel,SiparisModel;
use InternalCariModel as CariModel;

class Faturalar extends Controller
{

    public function __construct()
    {



    }

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){



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

    public function main()
    {

        $user = User::data();

        $faturalar = FaturaModel::liste();

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

    public function detay($id=""){

        if ($id==""){
            AyarModel::basarisiz('Hatalı İşlem','Tanımsız bir faturaya ulaşmaya çalışıyorsunuz.',URL::site());
            exit();
        }

        $faturaDetay = FaturaModel::detay($id);

        if (Session::select('cariId')==""){

            Session::insert('cariId', $faturaDetay->musteri);

        }else{

            if(Session::select('cariId')!=$faturaDetay->musteri){

                AyarModel::basarisiz('Hatalı İşlem','İlgili Fatura tarafınıza Ait Değildir.',URL::site());

            }

        }


        $odemeYontemi = AyarModel::odemeYontemleri();


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
            'gecen_gun' =>Date::diffDayUp($faturaDetay->vade_tarihi, date('d-m-Y')),
            'odeme_yontemi'    => $faturaDetay->odeme_yontemi,
            'cariDetay' => $cariDetay
        ];

        View::odemeYontemleri($odemeYontemi);
        View::detay($detay);
        View::faturaUrunleri($faturaUrunleri);

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

        $faturaDetay = FaturaModel::detay($id);
        $cariDetay = CariModel::detay($faturaDetay->musteri);
        $ekNot = Post::ekNot();

        $mailgonder = Email::subject('Fatura Hatırlatma')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

            'konu' => 'Fatura Hatırlatma',
            'mesaj' => $faturaDetay->id.' Numaralı faturanızın ödemesini hatırlatmak için bu maili aldınız. <br>'.AyarModel::tarihGoster($faturaDetay->vade_tarihi).' ödeme tarihli '.number_format($faturaDetay->genel_toplam,2).'TL Tutarında ki faturanızı ödemek için aşağıdaki sayfayı ziyaret edebilirsiniz.<br><br><br>Ek Not:<br>'.$ekNot.' <hr>',
            'link' => AyarModel::defaultAyarlar('siteUrl')."/fatura/detay/".$id,
            'link_baslik' => 'Fatura\'yı Ödemek İçin Tıklayınız',
            'firma' => AyarModel::defaultAyarlar('firmaAdi'),
            'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
            'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
            'telefon' => AyarModel::defaultAyarlar('firmaTel'),
        ])->send();

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