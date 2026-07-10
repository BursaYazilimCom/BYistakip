<?php namespace Project\Controllers;

use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation;
use InternalPersonelModel as PersonelModel,AyarModel,RaporModel;

class Personel extends Controller
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

        $personeller = PersonelModel::liste();

        View::personeller($personeller);

    }
    
    public function update(){

        $data['title'] = "Panel Girişi";

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $personelDetay = PersonelModel::detay(Post::id());

            if(Upload::isFile('resim')){

                Upload::mimes('image/jpeg', 'image/png')
                    ->extensions('jpg', 'png')
                    ->convertName()
                    ->source('resim')
                    ->target(REAL_BASE_DIR . 'Uploads/personel_resimleri')
                    ->start();
                $dosyaBilgi = Upload::info();

                $dosya = $dosyaBilgi->encodeName;

            }else{
                $dosya = $personelDetay->resim;
            }

            if(!empty(Post::pass())){

                $passData = [
                    'id'      =>Post::id(),
                    'password'=>Encode::super(Post::password()),
                ];
                $sifreGuncelle = PersonelModel::updatePassword($passData);

            }

            $data = [
                'id'            =>Post::id(),
                'username'      =>Post::username(),
                'email'         =>Post::email(),
                'isim'          =>Post::isim(),
                'resim'         =>$dosya,
                'telefon'       =>Post::telefon(),
                'notlar'        =>Post::notlar(),
                'unvan'         =>Post::unvan(),
                'ban'           =>Post::ban()==''?'0':Post::ban(),
                'aktivasyon'    =>Post::aktivasyon()==''?'0':Post::aktivasyon()
            ];

            $guncelle = PersonelModel::update($data);


            if($guncelle or $sifreGuncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('personel/form/'.Post::id());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('personel/form/'.Post::id());

            }

        }
        
    }

    public function form($id=""){

        $yetkiAlanlari = AyarModel::yetkiAlanlari();
  

        if($id){
            $data       = PersonelModel::detay($id);
            $yetkiler   = Json::decode($data->yetkiler);


           View::yetkiler($yetkiler);
           View::detay($data);
           View::action("personel/update/".$id);

        }else{
      
            $data = (object)[
                'id'  =>'',
                'username'  =>'',
                'email'     =>'',
                'isim'      =>'',
                'telefon'   =>'',
                'cinsiyet'  =>'',
                'notlar'    =>'',
                'unvan'     =>'',
                'ban'       =>'',
                'aktivasyon' =>''
            ];
            View::detay($data);
            View::action("personel/register");
      
        }

        View::yetkiAlanlari($yetkiAlanlari);
      


    }

    public function register(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            if(Upload::isFile('resim')){

                Upload::mimes('image/jpeg', 'image/png')
                    ->extensions('jpg', 'png')
                    ->convertName()
                    ->convertName()
                    ->source('resim')
                    ->target(REAL_BASE_DIR . 'Uploads/personel_resimleri/')
                    ->start();
                $dosyaBilgi = Upload::info();

                $dosya = $dosyaBilgi->encodeName;

            }else{
                $dosya = "";
            }

            $ekleData = [
                'username'      =>Post::username(),
                'password'      =>Encode::super(Post::password()),
                'email'         =>Post::email(),
                'isim'          =>Post::isim(),
                'resim'         =>$dosya,
                'telefon'       =>Post::telefon(),
                'notlar'        =>Post::notlar(),
                'unvan'         =>Post::unvan(),
                'ban'           =>Post::ban()==''?'0':Post::ban(),
                'aktivasyon'    =>Post::aktivasyon()==''?'0':Post::aktivasyon(),
                'aktiflik'      =>'1',
                'panel_rengi'   =>'light'
            ];

            $ekle = PersonelModel::ekle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('personel');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function delete($id){

        $sil = PersonelModel::delete($id);

        if($sil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Personel silme işlemi başarı ile yapıldı!</div>'])->action('personel');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Personel silme işlemi yapılamadı!</div>'])->action('personel');

        }

    }

    public function izinler($id){

        $yetkiAlanlari = AyarModel::yetkiAlanlari();

        $data = PersonelModel::detay($id);

        $yetkiler = Json::decode($data->yetkiler);


        View::yetkiler($yetkiler);
        View::yetkiAlanlari($yetkiAlanlari);
        View::detay($data);

    }

    public function yetkiDuzenle($id){

        if ($id!=Post::id()){
            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action('personel/izinler/'.Post::id());
        }



            $yetkiler = Json::encode($_POST['yetki']);

            $data = [
                'id'            =>Post::id(),
                'yetkiler'      =>$yetkiler
            ];

            $guncelle = PersonelModel::yetkiGuncelle($data);

            if($guncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('personel/izinler/'.Post::id());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action('personel/izinler/'.Post::id());

            }



    }

    public function mesaiSaatleri($id,$sayfa=""){

        $personelDetay          = PersonelModel::detay($id);
        $mesaiSaatleri          = PersonelModel::mesaiSaatleri($id,$sayfa="");
        $personelListe          = PersonelModel::calisanlar();


        View::mesaiSaatleri($mesaiSaatleri);
        View::personelDetay($personelDetay);
        View::personelListe($personelListe);

    }

    public function mesaiEkle() {

        $user = User::data();

        $gunlukCalismaBaslangicSaati            = AyarModel::defaultAyarlar("gunlukCalismaBaslangicSaati");
        $gunlukCalismaSuresiDakika              = AyarModel::defaultAyarlar("gunlukCalismaSuresiDakika");
        $gunlukCalismaBitisSaati                = AyarModel::defaultAyarlar("gunlukCalismaBitisSaati");
        $gunlukCalismadaDusulecekMolaDakikasi   = AyarModel::defaultAyarlar("gunlukCalismadaDusulecekMolaDakikasi");
        $fazlaMesaiMolaliSaat                   = AyarModel::defaultAyarlar("fazlaMesaiMolaliSaat");
        $fazlaMesaiDusulecekMolaDakikasi        = AyarModel::defaultAyarlar("fazlaMesaiDusulecekMolaDakikasi");
        $fazlaMesaiToleransSaati                = AyarModel::defaultAyarlar("fazlaMesaiToleransSaati");
        $gunlukCalismaBaslangicToleransSaati    = AyarModel::defaultAyarlar("gunlukCalismaBaslangicToleransSaati");
        $gunlukYemekMolasiBaslangicSaati        = AyarModel::defaultAyarlar("gunlukYemekMolasiBaslangicSaati");
        $haftalikToplamCalismaDakikasi          = AyarModel::defaultAyarlar("haftalikToplamCalismaDakikasi");
        $fazlaMEsaiSaatUcretiCarpimi            = AyarModel::defaultAyarlar("fazlaMEsaiSaatUcretiCarpimi");
        $fazlaMesaiYemekHakedisSuresi           = (strtotime($fazlaMesaiMolaliSaat)-strtotime($gunlukCalismaBitisSaati))/60;
       

        $personel                       = Post::personel();
        $giris_tarihi                   = Post::giris_tarihi();
        $giris_saati                    = Post::giris_saati().":00";
        $cikis_tarihi                   = Post::cikis_tarihi();
        $cikis_saati                    = Post::cikis_saati().":00";
        $fazla_mesai_sebebi             = Post::fazla_mesai_sebebi();
        $izin_durumu                    = Post::izin_durumu();
        $kayit_turu                     = Post::kayit_turu();
        $gunluk_not                     = Post::gunluk_not();
        $ise_gelis_yol_ucreti           = Post::ise_gelis_yol_ucreti();
        $isten_cikis_yol_ucreti         = Post::isten_cikis_yol_ucreti();
        $yemek_hakedisi                 = Post::yemek_hakedis();

        if(strtotime($giris_saati)>=strtotime($gunlukCalismaBaslangicToleransSaati) and strtotime($giris_saati)<strtotime($gunlukCalismaBaslangicSaati)){
            $giris_saati = $gunlukCalismaBaslangicSaati.":00";
        }

        if(strtotime($cikis_saati)<strtotime($fazlaMesaiToleransSaati) and strtotime($cikis_saati)>strtotime($gunlukCalismaBitisSaati)){
            $cikis_saati = $gunlukCalismaBitisSaati.":00";
        }

        $girisStr = strtotime($giris_tarihi." ".$giris_saati);
        $cikisStr = strtotime($cikis_tarihi." ".$cikis_saati);
        $gunlukToplamCalismaSuresikDakika = ($cikisStr-$girisStr)/60;
        $yemekHakEdis = 0;
        

        //günlük toplam çalışma süresi büyükse günlük çalışma sürüresi -(eksi) günlük hak edilen mola dakikası ise yani 540'dan büyükse
        if($gunlukToplamCalismaSuresikDakika>$gunlukCalismaSuresiDakika-$gunlukCalismadaDusulecekMolaDakikasi){

            $yemekHakEdis = 1;

            //60 dk mola düşüldüğünü belirtiyoruz
            $sureDususNotu = "Günlük Toplam Mola Süresi:".$gunlukCalismadaDusulecekMolaDakikasi."dk. düşüldü.";

            //Ne kadar fazla mesai yaptığını buluyoruz -> mesait toplamından günlük çalışma süresini ve mola süresini düşüyoruz
            $fazlaMesaiSuresi = $gunlukToplamCalismaSuresikDakika-$gunlukCalismaSuresiDakika-$gunlukCalismadaDusulecekMolaDakikasi;

            //eğer fazla mesai süresi yemek hakediş süresinden büyük yada eşitse
            if($fazlaMesaiSuresi>=$fazlaMesaiYemekHakedisSuresi){
                $sureDususNotu .="Fazla Mesaide ki yemek molası Süresi:".$fazlaMesaiDusulecekMolaDakikasi."dk. düşüldü.";
                $fazlaMesaiSuresi = $fazlaMesaiSuresi-$fazlaMesaiDusulecekMolaDakikasi;
                $yemekHakEdis++;
            }


            $gunlukCalismaSuresi = AyarModel::defaultAyarlar("gunlukCalismaSuresiDakika");

            $gunlukToplamCalismaSuresikDakika = $gunlukCalismaSuresi+$fazlaMesaiSuresi;

        }else{
            //Eğer günlük çalışma süresi normal mesainin altındaysa

            $fazlaMesaiSuresi = 0;
         
            //eğer 4 saatten fazla çalışması var ise
            if($gunlukToplamCalismaSuresikDakika<240){

                $sureDususNotu = "";
                $gunlukCalismaSuresi = $gunlukToplamCalismaSuresikDakika;
            //eğer çalışma saatleri 4 ila 7,5 saat arasındaysa
            }elseif($gunlukToplamCalismaSuresikDakika>240 and $gunlukToplamCalismaSuresikDakika<450){
                $sureDususNotu = "Mola Süresi:45dk. olarak düşüldü.";
                $gunlukCalismaSuresi = $gunlukToplamCalismaSuresikDakika-45;
            }elseif($gunlukToplamCalismaSuresikDakika>450 and $gunlukToplamCalismaSuresikDakika<=540){

                $sureDususNotu = "Mola Süresi:60dk. olarak düşüldü.";
                $gunlukCalismaSuresi = $gunlukToplamCalismaSuresikDakika-60;
            }
   
        }

        // Eğer işe giriş saati mesai başlangıcından sonra ise geç geldi olarak işaretle
        if(strtotime($giris_saati)>strtotime($gunlukCalismaBaslangicSaati)){ $gec_gelme_durumu = 1;
        }else{ $gec_gelme_durumu=0; }
        

        $data = [
            'personel'                  =>$personel,
            'yil'                       =>date("Y",strtotime($giris_tarihi)),
            'ay'                        =>date("m",strtotime($giris_tarihi)),
            'hafta'                     =>date("W",strtotime($giris_tarihi)),
            'gun'                       =>date("d",strtotime($giris_tarihi)),
            'giris_tarihi'              =>$giris_tarihi,
            'giris_saati'               =>$giris_saati,
            'giris_tarih_saat'          =>$giris_tarihi." ".$giris_saati,
            'cikis_tarihi'              =>$cikis_tarihi,
            'cikis_saati'               =>$cikis_saati,
            'cikis_tarih_saat'          =>$cikis_tarihi." ".$cikis_saati,
            'fazla_mesai_dakikasi'      =>$fazlaMesaiSuresi,
            'fazla_mesai_sebebi'        =>$fazla_mesai_sebebi,
            'gunlukCalismaSuresi'       =>$gunlukCalismaSuresi,
            'gunlukToplamCalismaSuresi' =>$gunlukToplamCalismaSuresikDakika,
            'gunluk_not'                =>$gunluk_not,
            'dakika_dusus_notlari'      =>$sureDususNotu,
            'yemek_hakedis'             =>$yemek_hakedisi,
            'ise_gelis_yol_ucreti'      =>$ise_gelis_yol_ucreti,
            'isten_cikis_yol_ucreti'    =>$isten_cikis_yol_ucreti,
            'izin_durumu'               =>$izin_durumu,
            'kayit_turu'                =>$kayit_turu,
            'kayit_yapan_personel'      =>$user->id,
            'gec_gelme_durumu'          =>$gec_gelme_durumu
        ];

        $mesaiEkle = PersonelModel::mesaiEkle($data);

        //echo DB::stringQuery();
        if($mesaiEkle){
            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Personel mesai saati ekleme işlemi başarı ile yapıldı!</div>'])->action('personel/mesaiSaatleri/'.$personel);
        }else{
           Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Personel mesai saati ekleme işlemi yapılamadı!</div>'])->action('personel/mesaiSaatleri/'.$personel);
        }
        

    }

    public function maasBordrosu($id){

        $ay     = Post::ay();
        $yil    = Post::yil();

        $personelDetay          = PersonelModel::detay($id);
        $personelSaatUcreti     = $personelDetay->maas/225;
        $personelGunlukUcreti   = $personelDetay->maas/30;
        $mesaiSaatleri          = PersonelModel::mesaiSaatleri($id,$sayfa="");
        $personelListe          = PersonelModel::tumListe();

        

        if($ay==""){
            $mevcutAy   = date('m');
        }else{
            $mevcutAy   = $ay;
        }

        if($yil==""){
            $mevcutYil   = date('Y');
        }else{
            $mevcutYil   = $yil;
        }

        $aylikYolYemekHakedisi = PersonelModel::aylikYolYemek($id,$mevcutAy,$mevcutYil); 


        /*if($mevcutAy<10){
            $mevcutAy = "0".$mevcutAy;
        }*/

        //$ayKacGunCekiyor = 31;
        $ayKacGunCekiyor = cal_days_in_month(CAL_GREGORIAN, $mevcutAy, $mevcutYil);

        $ayinIlkGunu = date('Y-'.$mevcutAy.'-01');
        $ayinSonGunu = date('Y-'.$mevcutAy.'-'.$ayKacGunCekiyor);

        $aylikMesaiSaatleri = PersonelModel::aylikCalismaSaatiToplamlari($id,$mevcutAy,$mevcutYil);

        $aylikYapilanMesaiToplami = $aylikMesaiSaatleri->acs/60;

        $ilkHafta = date('W',mktime(0,0,0,$mevcutAy,1,$mevcutYil));
        $sonHafta = date('W',mktime(0,0,0,$mevcutAy,$ayKacGunCekiyor,$mevcutYil));

        //echo $ilkHafta;


        $ayinHaftaİciGunleri = RaporModel::ayinHaftaIciGunleri($ayinIlkGunu,$ayinSonGunu);

 
        $aylikCalismaMaasHesapSaati ="";
        for($h=(int)$ilkHafta;$h<=$sonHafta;$h++){
            $tatilGunuEkSuresi =0;

            $haftalikSure = PersonelModel::haftalikCalismaSaatleri($id,$h,$mevcutAy,$mevcutYil);

            $haftalikMesaiSaatleri[$h]['hafta']             = $h;
            $haftalikMesaiSaatleri[$h]['toplamSureDK']      = $haftalikSure->haftalikToplamCalismaSaati;
            $haftalikMesaiSaatleri[$h]['toplamSureSAAT']    = $haftalikSure->haftalikToplamCalismaSaati/60;
            $haftalikMesaiSaatleri[$h]['normalSureDK']      = $haftalikSure->haftalikNormalCalismaSaati;
            $haftalikMesaiSaatleri[$h]['normalSureSAAT']    = $haftalikSure->haftalikNormalCalismaSaati/60;
            $haftalikMesaiSaatleri[$h]['fazlaMesai']        = $haftalikSure->haftalikFazlaMesaiSaati;
            
            // Haftalık mesai saatleri toplamı ayarlardaki haftalık tomlam calisma dakikasına esit yada büyükse 
            // yada ayın ilk günü pazartesi değilse haftasonu hakedişini veriyoruz
            if($haftalikMesaiSaatleri[$h]['toplamSureDK']>=AyarModel::defaultAyarlar("haftalikToplamCalismaDakikasi")){

                
                $haftalikMesaiSaatleri[$h]['haftaSonuHakedisi'] = "1";
                $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati'] = 7.5;
                $haftalikMesaiSaatleri[$h]['haftaSonuUcreti'] = $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati']*$personelSaatUcreti;
                $haftaSonuicinEklenecekSure = $haftaSonuicinEklenecekSure+7.5;

            }else{
                //Eğer eksik zaman ayın son haftası yada ilk haftası ise bu haftaların kaç gün olduğunu bulup buna göre mesai hesaplaması yapılacak
                if($h==$sonHafta or $h==(int)$ilkHafta){

                    if($h==$sonHafta){
                        $haftaBaslangic = $sonHafta;
                    }elseif($h==$ilkHafta){
                        $haftaBaslangic = (int)$ilkHafta;
                    }else{
                        $haftaBaslangic = $h;
                    }

               
                    $haftaninGunleri = AyarModel::haftaninGunleri($mevcutYil,$haftaBaslangic,$mevcutAy);

                    $ilgiliHaftaNormalCalismaSüresi = AyarModel::defaultAyarlar('gunlukCalismaSuresiDakika')*count($haftaninGunleri);
                    
                    //Bu haftanin olması gereken çalışma süresi, kişinin çalışma süresinden büyük ise yani kişide eksik çalışma varsa 
                    //ilgili eksikliği kişinin mesailerinden düşülmesi için hesapa katılıyor
                    if($ilgiliHaftaNormalCalismaSüresi>$haftalikMesaiSaatleri[$h]['toplamSureDK']){

                        $haftalikMesaiSaatleri[$h]["eksikCalismaSuresi"] = $haftalikMesaiSaatleri["eksikCalismaSuresi"]+($ilgiliHaftaNormalCalismaSüresi-$haftalikMesaiSaatleri[$h]['toplamSureDK']);
                        $haftalikMesaiSaatleri[$h]['haftaSonuHakedisi'] = "0";
                        $haftalikMesaiSaatleri[$h]['haftaSonuUcreti'] = $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati']*$personelSaatUcreti;
                        $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati'] = 0;

                    }else{

                        if($h==$sonHafta){

                            $haftalikMesaiSaatleri[$h]['haftaSonuHakedisi'] = "0";
                            $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati'] = 0;
                            $haftalikMesaiSaatleri[$h]['haftaSonuUcreti'] = $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati']*$personelSaatUcreti;
                            $haftaSonuicinEklenecekSure = $haftaSonuicinEklenecekSure+0;

                        }else{

                            $haftalikMesaiSaatleri[$h]['haftaSonuHakedisi'] = "1";
                            $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati'] = 7.5;
                            $haftalikMesaiSaatleri[$h]['haftaSonuUcreti'] = $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati']*$personelSaatUcreti;
                            $haftaSonuicinEklenecekSure = $haftaSonuicinEklenecekSure+7.5;

                        }

                        

                    }
                    
                    
                }else{
                   

                    $ilgiliHaftaNormalCalismaSüresi = AyarModel::defaultAyarlar('gunlukCalismaSuresiDakika')*5;

                    $haftalikMesaiSaatleri[$h]["eksikCalismaSuresi"] = $haftalikMesaiSaatleri["eksikCalismaSuresi"]+($ilgiliHaftaNormalCalismaSüresi-$haftalikMesaiSaatleri[$h]['toplamSureDK']);

                    $haftalikMesaiSaatleri[$h]['haftaSonuHakedisi'] = "0";
                    $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati'] = 0;
                    $haftalikMesaiSaatleri[$h]['haftaSonuUcreti'] = $haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati']*$personelSaatUcreti;

                }

                
   

            }
         
            $haftaninGunleri = AyarModel::haftaninGunleri($mevcutYil,$h,$mevcutAy);

            $aylikCalismaMaasHesapSaati = $aylikCalismaMaasHesapSaati+$haftalikMesaiSaatleri[$h]['toplamSureDK']+($haftalikMesaiSaatleri[$h]['haftaSonuHakedisSaati']*60);

                $haftalikMesaiSaatleri[$h]['haftalikUcret'] = $personelSaatUcreti*$haftalikMesaiSaatleri[$h]['normalSureSAAT'];
                $haftalikMesaiSaatleri[$h]['haftalikMesaiUcreti'] = ($personelSaatUcreti*($haftalikMesaiSaatleri[$h]['fazlaMesai']/60))*AyarModel::defaultAyarlar('fazlaMEsaiSaatUcretiCarpimi');


        }

        ////Maaş ve saat toplamları

        for($hm=(int)$ilkHafta;$hm<=$sonHafta;$hm++){

            $normalCalisma          = $normalCalisma+$haftalikMesaiSaatleri[$hm]['normalSureSAAT'];
            $fazlaMesai             = $fazlaMesai+($haftalikMesaiSaatleri[$hm]['fazlaMesai']/60);
            $haftaSonuHakedis       = $haftaSonuHakedis+($haftalikMesaiSaatleri[$hm]['haftaSonuHakedisSaati']);
            $eksikCalismaSuresi     = $eksikCalismaSuresi+($haftalikMesaiSaatleri[$hm]['eksikCalismaSuresi']/60);

            $normalCalismaUcreti    = $normalCalismaUcreti+$haftalikMesaiSaatleri[$hm]['haftalikUcret'];
            $fazlaMesaiUcreti       = $fazlaMesaiUcreti+$haftalikMesaiSaatleri[$hm]['haftalikMesaiUcreti'];
            $haftaSonuUcreti        = $haftaSonuUcreti+$haftalikMesaiSaatleri[$hm]['haftaSonuUcreti'];

        }

        $toplamcalismaSaati = $normalCalisma+$haftaSonuHakedis;

        if($toplamcalismaSaati<225){

            
                $toplamMaas = $normalCalismaUcreti+$haftaSonuUcreti;
                $toplamcalisma = $normalCalisma+$haftaSonuHakedis;
            
           
            

        }elseif($toplamcalismaSaati>=225 and $fazlaMesai==0){

         

            $toplamMaas = 225*$personelSaatUcreti;
            $toplamcalisma = 225;

        }elseif($toplamcalismaSaati>=225 and $fazlaMesai>0){
           

            $toplamMaas     = $normalCalismaUcreti+ $fazlaMesaiUcreti+$haftaSonuUcreti;
            $toplamcalisma  = $normalCalisma+$fazlaMesai+$haftaSonuHakedis;

        }



        /********Boordro Hesaplama */

            $ayinHaftaİciGunleri = RaporModel::ayinHaftaIciGunleri($ayinIlkGunu,$ayinSonGunu);

            $gunler = [];
            $kayitTuru = [];
            for($gun=1;$gun<=$ayKacGunCekiyor;$gun++){

                if($gun<10){
                    $gun = "0".$gun;
                }

                $bugun = $mevcutYil."-".$mevcutAy."-".$gun;

                $calismaSaatleriSQL = PersonelModel::gunlukMesaiSaatleriTarih($id,$bugun);

                if($calismaSaatleriSQL->gunlukCalismaSuresi!=""){

                    $gunler[$gun]["normalCalismaSaati"] = $calismaSaatleriSQL->gunlukCalismaSuresi/60;
                    $gunler[$gun]["fazlaMesaiSaati"] = $calismaSaatleriSQL->fazla_mesai_dakikasi/60;
                    $gunler[$gun]["kayitTuru"] = $calismaSaatleriSQL->kayit_turu;
               
                    array_push($kayitTuru,$calismaSaatleriSQL->kayit_turu);

                }else{

                    $gunler[$gun]["normalCalismaSaati"] = 0;
                    $gunler[$gun]["fazlaMesaiSaati"] = 0;
                    
                    //eğer gun hafta içi günlerinden değilse
                    if(!in_array($bugun,$ayinHaftaİciGunleri)){

                        $gunler[$gun]["kayitTuru"] = 'HT';
                        array_push($kayitTuru,"HT");

                    }else{

                        $gunler[$gun]["kayitTuru"] = "UI";
                        array_push($kayitTuru,"UI");

                    }

                }

            }

            /*
                echo "<pre>";
                print_r($gunler);
                print_r($kayitTuru);
                echo "</pre>";
            */

        /********Boordro Hesaplama */
        
        /*
        $toplamMaas = $normalCalismaUcreti+ $fazlaMesaiUcreti+$haftaSonuUcreti;
        $toplamcalisma = $normalCalisma+$fazlaMesai+$haftaSonuHakedis;
        */
        
      /*     echo '<pre style="padding: 50px">';
            echo "Ocak Ayında Toplam ".($toplamcalisma)." saat çalışma ve hakediş için ".number_format($toplamMaas,2)." TL maaş hesaplanmıştır<br>";
            echo "Hafta Bazında Aylık<br>";
            
            echo $aylikCalismaMaasHesapSaati/60;
            echo "<br>";
                print_r($haftalikMesaiSaatleri);
            echo '</pre>';*/
/*
            echo "<pre style='padding: 80px'>";

                echo "Olması gereken çalışma saati:".(count($ayinHaftaİciGunleri)*9)."<br>";
                echo "Normal çalışma saati:".$normalCalisma."<br>";
                echo "Fazla Mesai çalışma saati:".$fazlaMesai."<br>";
                echo "Eksik çalışma süresi:".$eksikCalismaSuresi."<br>";
                echo "Hafta sonu hakediş saati:".$haftaSonuHakedis."<br>";
                echo "toplam calisma: ".$toplamcalisma."<br>";
                echo "toplam maas: ".$toplamMaas."<br>";
                echo "aylık mesai saatleri toplami:".$aylikYapilanMesaiToplami."<br>";
                echo "hafta sonu için eklenecek sure:".$haftaSonuicinEklenecekSure."<br>";
                //echo "mesai saatleri:".$mesaiSaatleri."<br>";

            echo "</pre>";*/
        
        // Ay ve yıl bilgilerini belirleyin Sat

        View::aylikYolYemekHakedisi($aylikYolYemekHakedisi);
        View::kayitTuru($kayitTuru);
        View::kayitTuruSayi(array_count_values($kayitTuru));
        View::gunler($gunler);
        View::ayKacGunCekiyor($ayKacGunCekiyor);
        View::aylikOlmasiGerekenCalismaSaati(count($ayinHaftaİciGunleri)*9);
        View::normalCalisma($normalCalisma);
        View::fazlaMesai($fazlaMesai);
        View::eksikCalismaSuresi($eksikCalismaSuresi);
        View::haftaSonuHakedis($haftaSonuHakedis);
        View::toplamcalisma($toplamcalisma);
        View::toplamMaas(number_format($toplamMaas,2));
        View::buYil($mevcutYil);
        View::buAy($mevcutAy);
        View::aylikMesaiSaatleri($aylikYapilanMesaiToplami);
        View::haftaSonuHakEdisSuresi($haftaSonuicinEklenecekSure);
        View::mesaiSaatleri($mesaiSaatleri);
        View::personelDetay($personelDetay);
        View::personelListe($personelListe);

    }

        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

            case "panelColor":

                if($user->panel_rengi=="light"){
                    $panel_rengi = "dark";
                }else{
                    $panel_rengi = "light";
                }

                $veri = [
                    'id'            => $user->id,
                    'panel_rengi'   =>$panel_rengi
                ];

                $degistir = PersonelModel::temaDegistir($veri);

                break;

            case "personelSil":

                $sil = PersonelModel::delete($dataId);

                $data['title'] = "Kullanıcı Silme İşlemi";

                if($sil){

                    $data['success'] = 'Personel silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = 'personel';

                }else{

                    $data['error'] = "Personel silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;
            
            case "personelMesaiSil":

                $sil = PersonelModel::mesaiSaatiSil($dataId);

                $data['title'] = "Mesai Silme İşlemi";

                if($sil){

                    $data['success'] = 'Mesai silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Mesai silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

        }







    }


    
    public function s404()
    {

    }
}