<?php namespace Project\Controllers;

use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation;
use InternalPersonelModel as PersonelModel,AyarModel;

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

        }







    }


    
    public function s404()
    {

    }
}