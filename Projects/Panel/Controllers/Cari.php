<?php namespace Project\Controllers;

use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation;
use InternalCariModel as CariModel,AyarModel;

class Cari extends Controller
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

        $listeleme = CariModel::liste();

        View::listeleme($listeleme);

    }
    
    public function update(){

        $data['title'] = "Cari İşlemleri";

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">'.$data['error'].'</div></div>'])->action('cari/form/'.Post::id());

        }else{


            $data = [
                'id'                =>Post::id(),
                'email'             =>Post::email(),
                'adi'               =>Post::adi(),
                'gsm'               =>Post::gsm(),
                'il'                =>Post::il(),
                'adres'             =>Post::adres(),
                'tc'                =>Post::tc(),
                'firma_adi'         =>Post::firma_adi(),
                'fatura_adresi'     =>Post::fatura_adresi(),
                'vergi_dairesi'     =>Post::vergi_dairesi(),
                'vergi_no'          =>Post::vergi_no(),
                'bakiye'            =>Post::bakiye(),
                'yonetim_notu'            =>Post::yonetim_notu(),
                'durum'             =>Post::durum()
            ];

            $guncelle = CariModel::update($data);


            if($guncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('cari/form/'.Post::id());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('cari/form/'.Post::id());

            }

        }
        
    }

    public function form($id=""){

        $sehirler = AyarModel::sehirler(1000);

        if($id){
            $data = CariModel::detay($id);

           View::detay($data);
           View::action("cari/update/".$id);

        }else{
            $data = (object)[
                'id'            =>'',
                'email'         =>'',
                'adi'           =>'',
                'gsm'           =>'',
                'gsm'           =>'',
                'il'            =>'',
                'tc'            =>'',
                'firma_adi'     =>'',
                'fatura_adresi' =>'',
                'vergi_dairesi' =>'',
                'vergi_no'      =>'',
                'bakiye'        =>'',
                'yonetim_notu'        =>'',
                'durum'         =>'1',

            ];
            View::detay($data);

            View::action("cari/add");
        }

        View::sehirler($sehirler);

    }

    public function add(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $ekleData = [
                'email'             =>Post::email(),
                'adi'               =>Post::adi(),
                'gsm'               =>Post::gsm(),
                'il'                =>Post::il(),
                'tc'                =>Post::tc(),
                'firma_adi'         =>Post::firma_adi(),
                'fatura_adresi'     =>Post::fatura_adresi(),
                'vergi_dairesi'     =>Post::vergi_dairesi(),
                'vergi_no'          =>Post::vergi_no(),
                'bakiye'            =>Post::bakiye(),
                'yonetim_notu'            =>Post::yonetim_notu(),
                'durum'             =>Post::durum()
            ];

            $ekle = CariModel::add($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('cari');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function delete($id){

        $sil = CariModel::delete($id);

        if($sil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Silme işlemi başarı ile yapıldı!</div>'])->action('cari');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Silme işlemi yapılamadı!</div>'])->action('cari');

        }

    }



        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){


            case "cariSil":

                $sil = CariModel::delete($dataId);

                $data['title'] = "Cari Silme İşlemi";

                if($sil){

                    $data['success'] = 'Silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = 'cari';

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