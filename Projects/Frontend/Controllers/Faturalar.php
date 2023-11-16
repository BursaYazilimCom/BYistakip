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

        AyarModel::basarisiz('Hatalı İşlem','Tanımsız bir faturaya ulaşmaya çalışıyorsunuz.',URL::site());
        exit();

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

        View::detay($detay);
        View::faturaUrunleri($faturaUrunleri);

    }

    public function odemeIslemi($ok){

        if($ok=="ok"){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Kredi kartı ödeme işlemi gerçekleşti, BAKİYENİZ ARTIRILDI! </div>'])->action('cari/bakiyeYukle');

            //Eğer artırıldığınız görümüyor; <br>
            //Arka planda banka ile entegre durumdayız, Banka onayı kimi zaman uzayabildiği için bankadan onay gelir gelmez bakiye artırım işleminiz gerçekleşecektir.
        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Kredi kartı ödeme işleminde HATA OLUŞTU.</div>'])->action('cari/bakiyeYukle');
        }

    }

    public function odemeYap($id){

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

        $faturaUrunleri = FaturaModel::faturaUrunleri($id);
        $cariDetay = CariModel::detay($faturaDetay->musteri);

        $detay = (object)[
            'id'            => $id,
            'musteri'       => $cariDetay->id,
            'tur'           => $faturaDetay->tur,
            'aciklama'      => $faturaDetay->aciklama,
            'belge_no'      => $faturaDetay->belge_no,
            'odeme'         => $faturaDetay->odeme,
            'durum'         => $faturaDetay->durum,
            'resmi_fatura_dosyasi'    => $faturaDetay->resmi_fatura_dosyasi,
            'belge_tarihi'  => AyarModel::tarihGoster($faturaDetay->belge_tarihi),
            'vade_tarihi'   => $faturaDetay->vade_tarihi=="0000-00-00" ? '': AyarModel::tarihGoster($faturaDetay->vade_tarihi),
            'gecen_gun'     => Date::diffDayUp($faturaDetay->vade_tarihi, date('d-m-Y')),
            'odeme_yontemi' => $faturaDetay->odeme_yontemi,
            'cariDetay'     => $cariDetay
        ];

        View::detay($detay);
        View::faturaUrunleri($faturaUrunleri);


        $merchant_id 	= AyarModel::defaultAyarlar('paytrMerchantId'); // kurumsal@paytr.com
        $merchant_key 	= AyarModel::defaultAyarlar('paytrMerchantKey');
        $merchant_salt	= AyarModel::defaultAyarlar('paytrMerchantSalt');

        $email = $cariDetay->email;

        $payment_amount	= number_format($faturaDetay->genel_toplam,2,'.','')*100; //9.99 için 9.99 * 100 = 999 gönderilmelidir.

        $merchant_oid = $id.'80108'.rand(111111,999999);

        $user_name = $cariDetay->adi;

        $user_address = $cariDetay->fatura_adresi;

        $user_phone = $cariDetay->gsm;

        $merchant_ok_url = URL::site('faturalar/odemeIslemi/ok');

        $merchant_fail_url = URL::site('faturalar/odemeIslemi/nok');

        $urunler = [];

        foreach ($faturaUrunleri as $fu) {
            $urunler = array_merge($urunler, array($fu->urun_adi, $fu->fiyat, $fu->miktar));
        }

        $user_basket =base64_encode(json_encode(array( $urunler )));

        if( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $user_ip=$ip;

        $timeout_limit = "30";

        $debug_on = 1;

        $test_mode = 1;

        $no_installment	= 0; // Taksit yapılmasını istemiyorsanız, sadece tek çekim sunacaksanız 1 yapın

        $max_installment = 0;

        $currency = "TL";

        $hash_str = $merchant_id .$user_ip .$merchant_oid .$email .$payment_amount .$user_basket.$no_installment.$max_installment.$currency.$test_mode;
        $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));

        $odemeData = [
            'fatura_id'     =>$id,
            'tutar'         =>$faturaDetay->genel_toplam,
            'aciklama'      =>'Paytr KK Ödeme İşlemi Başlatıldı.'
        ];
        $faturaOdemeIslemiEkle = FaturaModel::odemeIslemiEkle($odemeData);


        $post_vals=array(
            'merchant_id'       =>$merchant_id,
            'user_ip'           =>$user_ip,
            'merchant_oid'      =>$merchant_oid,
            'email'             =>$email,
            'payment_amount'    =>(int)$payment_amount,
            'paytr_token'       =>$paytr_token,
            'user_basket'       =>$user_basket,
            'debug_on'          =>$debug_on,
            'no_installment'    =>$no_installment,
            'max_installment'   =>$max_installment,
            'user_name'         =>$user_name,
            'user_address'      =>$user_address,
            'user_phone'        =>$user_phone,
            'merchant_ok_url'   =>$merchant_ok_url,
            'merchant_fail_url' =>$merchant_fail_url,
            'timeout_limit'     =>$timeout_limit,
            'currency'          =>$currency,
            'test_mode'         =>$test_mode
        );

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = @curl_exec($ch);

        if(curl_errno($ch))
            die("PAYTR IFRAME connection error. err:".curl_error($ch));

        curl_close($ch);

        $result=json_decode($result,1);

        if($result['status']=='success'){
            $token=$result['token']; }
        else{
            die("PAYTR IFRAME failed. reason:".$result['reason']);
        }

        $veri = Post::all();

        View::token($token);
        View::veri($veri);

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