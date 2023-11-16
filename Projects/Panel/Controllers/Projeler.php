<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter;
use InternalProjeModel as ProjeModel,AyarModel;

class Projeler extends Controller
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
        $listeData = ProjeModel::liste();

        View::listele($listeData);


    }


    public function form($id=""){
        $gruplar = ProjeModel::urunGrupListe();

        if($id){
            $data = ProjeModel::detay($id);

            View::detay($data);
            View::action("projeler/update/".$id);

        }else{
            $data = (object)[
                'id'                        =>'',
                'musteri'                   =>'',
                'proje_adi'                 =>'',
                'aciklama'                  =>'',
                'proje_baslangic_tarihi'    =>'',
                'tahmini_bitis_tarihi'      =>'0.0000',
                'bitis_tarihi'              =>'0.0000',
                'durum'                     =>'1'

            ];
            View::detay($data);

            View::action("projeler/ekle");
        }

        View::gruplar($gruplar);

    }

    public function ekle(){

        if(!Validation::check()){


            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else{

            $ekleData = [
                'musteri'                   =>Post::musteri(),
                'proje_adi'                 =>Post::proje_adi(),
                'aciklama'                  =>Post::aciklama(),
                'proje_baslangic_tarihi'    =>Post::proje_baslangic_tarihi(),
                'tahmini_bitis_tarihi'      =>Post::tahmini_bitis_tarihi(),
                'bitis_tarihi'              =>Post::bitis_tarihi(),
                'durum'                     =>Post::durum(),
            ];

            $ekle = ProjeModel::ekle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('projeler');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function update($id){

        if(!Validation::check()){


            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else{

            $ekleData = [
                'id'                        =>$id,
                'musteri'                   =>Post::musteri(),
                'proje_adi'                 =>Post::proje_adi(),
                'aciklama'                  =>Post::aciklama(),
                'proje_baslangic_tarihi'    =>Post::proje_baslangic_tarihi(),
                'tahmini_bitis_tarihi'      =>Post::tahmini_bitis_tarihi(),
                'bitis_tarihi'              =>Post::bitis_tarihi(),
                'durum'                     =>Post::durum(),
            ];

            $ekle = ProjeModel::guncelle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('projeler/form/'.$id);
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function gruplar()
    {
        $listeData = UrunModel::urunGrupListe();

        View::listele($listeData);


    }

        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

                /*********************************************************/

            case "projeSil":

                $sil = ProjeModel::sil($dataId);

                $data['title'] = "Proje Silme İşlemi";

                if($sil){

                    $data['success'] = 'Proje silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/projeler';

                }else{

                    $data['error'] = "Proje silme işlemi yapılamadı!";

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