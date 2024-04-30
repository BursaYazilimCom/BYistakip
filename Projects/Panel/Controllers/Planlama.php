<?php namespace Project\Controllers;


use User,Method,Post,Date,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter,Email;
use InternalPlanlamaModel as PlanlamaModel,AyarModel,InternalCariModel as CariModel,SmsModel;

class Planlama extends Controller
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
        


    }

    public function takvim(){

        $etkinlikTurleri = PlanlamaModel::etkinlikTurleri();

        View::etkinlikTurleri($etkinlikTurleri);
        
    }


    public function etkinlikEkle(){


            $baslangic_tarihi = Date::convert(Post::startDate(),"Y-m-d");
            $baslangic_saati = Date::convert(Post::startDate(),"H:i:s");
            $bitis_tarihi = Date::convert(Post::endDate(),"Y-m-d");
            $bitis_saat = Date::convert(Post::endDate(),"H:i:s");

            $katilimciSayi = count(Post::katilimci());
            $katilimciListe = Post::katilimci();
            $katilimcilar = [];

            $etkinlikTuru = Post::etkinlikTurDetay(Post::tur());

            for ($k=0; $k <$katilimciSayi ; $k++) { 
                array_push($katilimcilar,$katilimciListe[$k]);
            }
            /*echo "<pre>";
            print_r($katilimcilar);
            echo "</pre>";*/

            $ekleData = [
                'baslik'                => Post::title(),
                'tur'                   => Post::tur(),
                'baslangic_tarihi'      => $baslangic_tarihi,
                'baslangic_saati'       => $baslangic_saati,
                'baslangic_tarih_saat'  => Post::startDate(),
                'bitis_tarihi'          => $bitis_tarihi,
                'bitis_saat'            => $bitis_saat,
                'bitis_tarih_saat'      => Post::endDate(),
                'url'                   => Post::url(),
                'katilimcilar'          => json_encode($katilimcilar, JSON_UNESCAPED_UNICODE),
                'konum'                 => Post::konum(),
                'aciklama'              => Post::aciklama(),
                'mail_bilgilendirme'    => Post::mailBilgilendirme(),
                'sms_bilgilendirme'     => Post::smsBilgilendirme()
            ];

            /*echo "<pre>";
            print_r($ekleData);
            echo "</pre>";*/

            $ekle = PlanlamaModel::etkinlikEkle($ekleData);

            if ($ekle) {

                for ($i=0; $i < count($katilimcilar) ; $i++) {
                    
                    if(is_numeric($katilimcilar[$i]) and Post::smsBilgilendirme()=="1"){

                        if(AyarModel::defaultAyarlar('smsGonderim')=="1"){

                            $smsData = [
                                'mesaj'=>'Merhaba; '.AyarModel::defaultAyarlar('firmaAdi').' tarafından '.Date::convert(Post::startDate(),"d.m.Y H:i").' tarihinde '.Post::title().' başlıklı etkinliğe davet edildiniz. Etkinlik:'.AyarModel::defaultAyarlar("siteUrl").'/etkinlik/'.$ekle.'/'.$katilimcilar[$i],
                                'numara'=>$katilimcilar[$i]
                            ];
    
                            $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);
    
                        } 


                    }
                    if(filter_var($katilimcilar[$i], FILTER_VALIDATE_EMAIL) and Post::mailBilgilendirme()=="1"){

                        $mailgonder = Email::subject($etkinlikTuru->tur." Etkinliğine Dahil Edildiniz")->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($katilimcilar[$i])->template('by', [
                            'konu' => $etkinlikTuru->tur." Etkinliğine Dahil Edildiniz",
                            'mesaj' => 'Merhaba; Bu E-Posta\'yı, '.AyarModel::defaultAyarlar('firmaAdi').' tarafından '.Post::title().' etkinliğine dahil edildiğiniz için aldınız.<br> <hr>
                            <strong>Etkinlik Detayları:</strong><br>
                            Başlık: '.Post::title().'<br>
                            Tür: '.$etkinlikTuru->tur.'<br>
                            Açıklama: '.Post::aciklama().'<br>
                            Konum: '.Post::konum().'<br>
                            URL: '.Post::url().'<br>
                            Katılımcı Sayısı: '.count($katilimcilar).'<br>
                            Başlangıç Tarih: '.Date::convert(Post::startDate(),"d.m.Y H:i").'<br>',
                            'Bunun bir hata olduğunu düşünüyorsanız, Bu E-Postayı silebilirsiniz yada bizimle iletişime geçebilirsiniz.',
                            'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                            'link' => AyarModel::defaultAyarlar('siteUrl')."/etkinlik/".$ekle."/".$katilimcilar[$i],
                            'link_baslik' => 'Etkinlik Detayları',
                            'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
                            'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                            'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                            'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                        ])->send();
                        
                    }


                }

                
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action(URL::prev());
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        

    }

    public function etkinlikGuncelle($id){

        if($id!=Post::id()){
            redirect(URL::prev());
        }

        
        $baslangic_tarihi = Date::convert(Post::startDate(),"Y-m-d");
        $baslangic_saati = Date::convert(Post::startDate(),"H:i:s");
        $bitis_tarihi = Date::convert(Post::endDate(),"Y-m-d");
        $bitis_saat = Date::convert(Post::endDate(),"H:i:s");

        $katilimciSayi = count(Post::katilimci());
        $katilimciListe = Post::katilimci();
        $katilimcilar = [];

        $etkinlikTuru = Post::etkinlikTurDetay(Post::tur());

        for ($k=0; $k <$katilimciSayi ; $k++) { 
            array_push($katilimcilar,$katilimciListe[$k]);
        }
        /*echo "<pre>";
        print_r($katilimcilar);
        echo "</pre>";*/

        $guncellemeData = [
            'id'                    => $id,
            'baslik'                => Post::title(),
            'tur'                   => Post::tur(),
            'baslangic_tarihi'      => $baslangic_tarihi,
            'baslangic_saati'       => $baslangic_saati,
            'baslangic_tarih_saat'  => Post::startDate(),
            'bitis_tarihi'          => $bitis_tarihi,
            'bitis_saat'            => $bitis_saat,
            'bitis_tarih_saat'      => Post::endDate(),
            'url'                   => Post::url(),
            'katilimcilar'          => json_encode($katilimcilar, JSON_UNESCAPED_UNICODE),
            'konum'                 => Post::konum(),
            'aciklama'              => Post::aciklama()

        ];

        $ekle = PlanlamaModel::etkinlikGuncelle($guncellemeData);

        if ($ekle) {

            for ($i=0; $i < count($katilimcilar) ; $i++) {
                
                if(is_numeric($katilimcilar[$i]) and Post::smsBilgilendirme()=="1"){

                    if(AyarModel::defaultAyarlar('smsGonderim')=="1"){

                        $smsData = [
                            'mesaj'=>'Merhaba; '.AyarModel::defaultAyarlar('firmaAdi').' tarafından '.Date::convert(Post::startDate(),"d.m.Y H:i").' tarihinde '.Post::title().' başlıklı etkinliğe davet edildiniz Etkinlik: '.AyarModel::defaultAyarlar("siteUrl").'/etkinlik/'.$id.'/'.$katilimcilar[$i],
                            'numara'=>$katilimcilar[$i]
                        ];

                        $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);

                    } 

                }
                if(filter_var($katilimcilar[$i], FILTER_VALIDATE_EMAIL) and Post::mailBilgilendirme()=="1"){

                    $mailgonder = Email::subject($etkinlikTuru->tur." Etkinliğine Dahil Edildiniz")->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($katilimcilar[$i])->template('by', [
                        'konu' => $etkinlikTuru->tur." Etkinliğine Dahil Edildiniz",
                        'mesaj' => 'Merhaba; Bu E-Posta\'yı, '.AyarModel::defaultAyarlar('firmaAdi').' tarafından '.Post::title().' etkinliğine dahil edildiğiniz için aldınız.<br> <hr>
                        <strong>Etkinlik Detayları:</strong><br>
                        Başlık: '.Post::title().'<br>
                        Tür: '.$etkinlikTuru->tur.'<br>
                        Açıklama: '.Post::aciklama().'<br>
                        Konum: '.Post::konum().'<br>
                        URL: '.Post::url().'<br>
                        Katılımcı Sayısı: '.count($katilimcilar).'<br>
                        Başlangıç Tarih: '.Date::convert(Post::startDate(),"d.m.Y H:i").'<br>',
                        'Bunun bir hata olduğunu düşünüyorsanız, Bu E-Postayı silebilirsiniz yada bizimle iletişime geçebilirsiniz.',
                        'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                        'link' => AyarModel::defaultAyarlar('siteUrl')."/etkinlik/".$id."/".$katilimcilar[$i],
                        'link_baslik' => 'Etkinlik Detayları',
                        'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
                        'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                        'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                        'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                    ])->send();
                    
                }


            }

            
            Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action(URL::prev());
        } else {

            Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

        }
        
    }

    public function hatirlatici()
    {
        
        $listeData = PlanlamaModel::hatirlatmalar();

        View::listele($listeData);


    }

    public function hatirlatmaEkle(){

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            if(Post::periyod()=="1"){
                $yil = date("Y");
                $ay = Post::ay();
                $gun = Post::gun();
                $saat = Post::saat();
            }else{
                $zaman = strtotime(Post::zaman());
                $yil = date("Y",$zaman);
                $ay = date("m",$zaman);
                $gun = date("d",$zaman);
                $saat = date("H:i:s",$zaman);

            }


            $ekleData = [
                'aciklama'      => Post::aciklama(),
                'periyod'       => Post::periyod(),
                'yil'           => $yil,
                'ay'            => $ay,
                'gun'           => $gun,
                'saat'          => $saat,
                'durum'         => Post::durum()
            ];

            $ekle = PlanlamaModel::hatirlatmaEkle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action(URL::prev());
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function hatirlatmaGuncelle($id){

        $yHDetay = PlanlamaModel::hatirlatmaDetay($id);

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            if(Post::periyod()=="1"){
                $yil = date("Y");
                $ay = Post::ay();
                $gun = Post::gun();
                $saat = Post::saat();
            }else{
                $zaman = strtotime(Post::zaman());
                $yil = date("Y",$zaman);
                $ay = date("m",$zaman);
                $gun = date("d",$zaman);
                $saat = date("H:i:s",$zaman);

            }


            $ekleData = [
                'id'            => $id,
                'aciklama'      => Post::aciklama(),
                'periyod'       => Post::periyod(),
                'yil'           => $yil,
                'ay'            => $ay,
                'gun'           => $gun,
                'saat'          => $saat,
                'durum'         => Post::durum()
            ];

            $ekle = PlanlamaModel::hatirlatmaGuncelle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action(URL::prev());
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }


    public function etkinlikTurleri()
    {
        
        $listeData = PlanlamaModel::etkinlikTurleri();

        View::listele($listeData);


    }

    public function etkinlikTurEkle(){

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'tur'      => Post::tur(),
                'renk'      => Post::renk(),
            ];

            $ekle = PlanlamaModel::etkinlikTurEkle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action(URL::prev());
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function etkinlikTurGuncelle($id){

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'id'            => $id,
                'tur'      => Post::tur(),
                'renk'      => Post::renk()
            ];

            $ekle = PlanlamaModel::etkinlikTurGuncelle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action(URL::prev());
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function etkinlikSil($id){

        $user               = User::data();

        if (!empty($id) ){

            $sil = PlanlamaModel::etkinlikSil($id);

            if($sil){

                AyarModel::basarili('Başarılı İşlem','Etkinlik Kaydı veri tabanından silindi',URL::prev());


            }else{
                AyarModel::basarisiz('Hata','Etkinlik Kaydı veri tabanından silinemedi',URL::prev());
            }

        }else{
            AyarModel::basarisiz('Hata','Etkinlik Kaydı veri tabanından silinemedi',URL::prev());
        }

    }






    public function ajax():void{

        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

                /*********************************************************/

            case "hatirlatmaSil":

                $yHDetay = PlanlamaModel::hatirlatmaDetay($dataId);

                $sil = PlanlamaModel::hatirlatmaSil($dataId);

                $data['title'] = "Planlama Hatırlatma Silme İşlemi";

                if($sil){

                    $data['success'] = 'Planlama Hatırlatma silme işlemi başarı ile yapıldı!';
                    //$data['redirect'] = '/projeler/yolHaritasi/'.$yHDetay->proje_id;
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Planlama Hatırlatma silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            /*********************************************************/

            case "etkinlikTurSil":

                $sil = PlanlamaModel::etkinlikTurSil($dataId);

                $data['title'] = "Planlama Etkinlik Türü Silme İşlemi";

                if($sil){

                    $data['success'] = 'Planlama  Etkinlik Türü silme işlemi başarı ile yapıldı!';
                    //$data['redirect'] = '/projeler/yolHaritasi/'.$yHDetay->proje_id;
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Planlama  Etkinlik Türü silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            /*********************************************************/


        }

    }

    
    public function s404()
    {

    }
}