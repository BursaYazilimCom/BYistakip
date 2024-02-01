<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter;
use InternalPlanlamaModel as PlanlamaModel,AyarModel,InternalCariModel as CariModel;

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

    public function hatirlatici()
    {
        
        $listeData = PlanlamaModel::hatirlatmalar();

        View::listele($listeData);


    }

    public function hatirlatmaEkle($id){

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'proje_id'  => $id,
                'baslik'    => Post::baslik(),
                'aciklama'  => Post::aciklama(),
                'sira'      => Post::sira(),
                'durum'     => Post::durum()
            ];

            $ekle = ProjeModel::hatirlatmaEkle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/yolHaritasi/'.$id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function hatirlatmaGuncelle($id){

        $yHDetay = ProjeModel::yolHaritasiDetay($id);

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'id'        => $id,
                'baslik'    => Post::baslik(),
                'aciklama'  => Post::aciklama(),
                'sira'      => Post::sira(),
                'durum'     => Post::durum()
            ];

            $ekle = ProjeModel::hatirlatmaGuncelle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/yolHaritasi/'.$yHDetay->proje_id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
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

                $yHDetay = ProjeModel::hatirlatmaDetay($dataId);

                $sil = ProjeModel::hatirlatmaSil($dataId);

                $data['title'] = "Proje Yol Haritasi Silme İşlemi";

                if($sil){

                    $data['success'] = 'Proje Yol Haritasi silme işlemi başarı ile yapıldı!';
                    //$data['redirect'] = '/projeler/yolHaritasi/'.$yHDetay->proje_id;
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Proje Yol Haritasi silme işlemi yapılamadı!";

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