<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation;
use InternalPersonelModel as PersonelModel,InternalAyarModel as AyarModel, KasaModel;

class Ayarlar extends Controller
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

        $ayarlar = AyarModel::defaultAyarListe();

        View::ayarlar($ayarlar);


    }

    public function guncelle()
    {

        $ayarlar = AyarModel::defaultAyarListe();


        for ($a=0;$a<count($_POST['anahtar']);$a++){

            if($_POST['tur'][$a]=="file"){

                if(Upload::isFile($_POST['deger'][$_POST['anahtar'][$a]])){

                    Upload::convertName()
                        ->source($_POST['deger'][$_POST['anahtar'][$a]])
                        ->target(REAL_BASE_DIR . 'Uploads/site-img/')
                        ->start();

                    $dosyaBilgi = Upload::info();

                    $dosya = $dosyaBilgi->encodeName;

                }else{
                    $dosya = AyarModel::defaultAyarlar($_POST['anahtar'][$a]);
                }

                $data = [
                    'anahtar' => $_POST['anahtar'][$a],
                    'deger' => $dosya,
                    'aciklama' => $_POST['aciklama'][$_POST['anahtar'][$a]]
                ];


            }else{

                $data = [
                    'anahtar' => $_POST['anahtar'][$a],
                    'deger' => $_POST['deger'][$_POST['anahtar'][$a]],
                    'aciklama' => $_POST['aciklama'][$_POST['anahtar'][$a]]
                ];

            }

            $guncelle = AyarModel::defaultAyarGuncelle($data);

        }

        if ($guncelle) {

            Redirect::insert(['bilgi' => '<div class="callout callout-success">Bilgiler başarı ile güncellendi!</div>'])->action('ayarlar');

        } else {

            Redirect::insert(['bilgi' => '<div class="callout callout-danger">Bilgi güncelleme işlemi yapılamadı!</div>'])->action('ayarlar');

        }

    }

    public function yetkiAlanlari(){


        $yetkiAlanlari = AyarModel::yetkiAlanlari();

        View::yetkiAlanlari($yetkiAlanlari);


    }
    
    public function yetkiAlaniGuncelle(){



        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

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
                'telefon'       =>Post::telefon(),
                'notlar'        =>Post::notlar(),
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

        print_r($data);
        
    }

    public function form($id=""){

        $yetkiAlanlari = AyarModel::yetkiAlanlari();

        if($id){
            $data = PersonelModel::detay($id);
            $yetkiler = Json::decode($data->yetkiler);


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

            $ekleData = [
                'username'      =>Post::username(),
                'password'      =>Encode::super(Post::password()),
                'email'         =>Post::email(),
                'isim'          =>Post::isim(),
                'telefon'       =>Post::telefon(),
                'notlar'        =>Post::notlar(),
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


    public function odemeYontemleri(){

        $kasaHesaplar = KasaModel::kasaHesaplari();
        $veri = AyarModel::odemeYontemleri();

        View::odemeYontemleri($veri);
        View::kasaHesaplari($kasaHesaplar);

    }

    public function odemeYontemleriEkle(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{
            $keys = Post::key();
            $values = Post::value();

            if (!empty($keys[0])){

                for ($key = 0; $key < count($keys); $key++) {
                    $entegrasyon_bilgileri[$key]['key'] = $keys[$key];
                    $entegrasyon_bilgileri[$key]['value'] = $values[$key];
                }
                $entegrasyon = Json::encode($entegrasyon_bilgileri);
            }else{
                $entegrasyon = "";
            }


            $ekleData = [
                'baslik'             =>Post::baslik(),
                'kasa_hesabi'               =>Post::kasa_hesabi(),
                'entegrasyon_bilgileri'               =>$entegrasyon,
                'durum'             =>Post::durum()
            ];

            $ekle = AyarModel::odemeYontemiEkle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('ayarlar/odemeYontemleri');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }


    }

    public function odemeYontemleriGuncelle($id){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{
            $keys = Post::key();
            $values = Post::value();

            if (!empty($keys[0])){

                for ($key = 0; $key < count($keys); $key++) {
                    $entegrasyon_bilgileri[$key]['key'] = $keys[$key];
                    $entegrasyon_bilgileri[$key]['value'] = $values[$key];
                }
                $entegrasyon = Json::encode($entegrasyon_bilgileri);
            }else{
                $entegrasyon = "";
            }


            $ekleData = [
                'id'             =>$id,
                'baslik'             =>Post::baslik(),
                'kasa_hesabi'               =>Post::kasa_hesabi(),
                'entegrasyon_bilgileri'               =>$entegrasyon,
                'durum'             =>Post::durum()
            ];

            $ekle = AyarModel::odemeYontemiGuncelle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('ayarlar/odemeYontemleri');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }


    }

    /*********************************/

    public function siparisDurumlari(){

        $veri = AyarModel::siparisDurumlari();

        View::siparisDurumlari($veri);

    }

    public function siparisDurumEkle(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $ekleData = [
                'adi'             =>Post::adi(),
                'uyari'               =>Post::uyari(),
                'sira'             =>Post::sira()
            ];

            $ekle = AyarModel::siparisDurumEkle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('ayarlar/siparisDurumlari');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }


    }

    public function siparisDurumGuncelle($id){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $ekleData = [
                'id'             =>$id,
                'adi'             =>Post::adi(),
                'uyari'               =>Post::uyari(),
                'sira'             =>Post::sira()
            ];

            $ekle = AyarModel::siparisDurumGuncelle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('ayarlar/siparisDurumlari');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }


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

            case "yetkiAlaniSil":

                $sil = AyarModel::yetkiAlaniSil($dataId);

                $data['title'] = "Yetki Alanı Silme İşlemi";

                if($sil){

                    $data['success'] = 'Yetki Alanı silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/Ayarlar/yetkiAlanlari';

                }else{

                    $data['error'] = "Yetki Alanı silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "yetkiAlaniEkle":

                $data['title'] = "Yetki Alanı Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'alan'      =>Post::alan(),
                        'baslik'    =>Post::baslik(),
                        'aciklama'  =>Post::aciklama()
                    ];

                    $ekle = AyarModel::yetkiAlaniEkle($ekleData);

                    if($ekle){

                        $data['success'] = 'Yetki Alanı ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                            <td>'.$ekle.'</td>
                                            <td>'.Post::baslik().'</td>
                                            <td>'.Post::alan().'</td>
                                            <td>'.Post::aciklama().'</td>
                                            <td>
                                                <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('Ayarlar/ajax').'\',\'yetkiAlaniSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                            </td>
                                        </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Yetki Alanı ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "odemeYontemiSil":

                $sil = AyarModel::odemeYontemiSil($dataId);

                $data['title'] = "Silme İşlemi";

                if($sil){

                    $data['success'] = 'Silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/Ayarlar/odemeYontemleri';

                }else{

                    $data['error'] = "Silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "siparisDurumSil":

                $sil = AyarModel::siparisDurumSil($dataId);

                $data['title'] = "Silme İşlemi";

                if($sil){

                    $data['success'] = 'Silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/Ayarlar/siparisDurumlari';

                }else{

                    $data['error'] = "Silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

        }

    }

    
    public function s404()
    {

    }
}