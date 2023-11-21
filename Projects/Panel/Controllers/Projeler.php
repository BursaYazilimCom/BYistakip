<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter;
use InternalProjeModel as ProjeModel,AyarModel,InternalCariModel as CariModel;

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
        $musteriler = CariModel::tumListe();

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
                'proje_baslangic_tarihi'    =>date('d.m.Y'),
                'tahmini_bitis_tarihi'      =>date('d.m.Y'),
                'bitis_tarihi'              =>'',
                'sifre'                     =>'',
                'durum'                     =>'0'

            ];
            View::detay($data);

            View::action("projeler/ekle");
        }

        View::musteriler($musteriler);

    }

    public function ekle(){

        if(Post::proje_adi()==""){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            if(Post::durum()=="1"){
                if (Post::bitis_tarihi()==""){
                    $bitisTarihi = date('Y-m-d');
                }else{
                    $bitisTarihi = AyarModel::tarihDuzelt(Post::bitis_tarihi());
                }

            }else{
                $bitisTarihi = "0000-00-00";
            }

            $ekleData = [
                'musteri' => Post::musteri(),
                'proje_adi' => Post::proje_adi(),
                'sef'       => Converter::slug(Post::proje_adi()),
                'aciklama' => Post::aciklama(),
                'proje_baslangic_tarihi' => AyarModel::tarihDuzelt(Post::proje_baslangic_tarihi()),
                'tahmini_bitis_tarihi' => AyarModel::tarihDuzelt(Post::tahmini_bitis_tarihi()),
                'bitis_tarihi' => $bitisTarihi,
                'durum' => Post::durum()
            ];

            $ekle = ProjeModel::ekle($ekleData);

            if ($ekle) {
                if (Post::sifre()!="") {
                    ProjeModel::sifreGuncelle(['id'=>$ekle,'sifre'=>Encode::type(Post::sifre(), 'md5')] );
                }

                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler');
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function update($id){

        if(Post::proje_adi()==""){


            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else{

            if(Post::durum()=="1"){

                if (Post::bitis_tarihi()==""){
                    $bitisTarihi = date('Y-m-d');
                }else{
                    $bitisTarihi = AyarModel::tarihDuzelt(Post::bitis_tarihi());
                }

            }else{
                $bitisTarihi = "0000-00-00";
            }

            if (Post::sifre()!="") {
                ProjeModel::sifreGuncelle(['id'=>$id,'sifre'=>Encode::type(Post::sifre(), 'md5')] );
            }

            $ekleData = [
                'id'                        => $id,
                'musteri'                   => Post::musteri(),
                'proje_adi'                 => Post::proje_adi(),
                'sef'                       => Converter::slug(Post::proje_adi()),
                'aciklama'                  => Post::aciklama(),
                'proje_baslangic_tarihi'    => AyarModel::tarihDuzelt(Post::proje_baslangic_tarihi()),
                'tahmini_bitis_tarihi'      => AyarModel::tarihDuzelt(Post::tahmini_bitis_tarihi()),
                'bitis_tarihi'              => $bitisTarihi,
                'durum'                     => Post::durum(),
            ];

            $ekle = ProjeModel::guncelle($ekleData);

            if($ekle){

                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('projeler/form/'.$id);

            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function yolHaritasi($id)
    {
        $projeDetay = ProjeModel::detay($id);
        $listeData = ProjeModel::yolHaritasi($id);

        View::listele($listeData);
        View::detay($projeDetay);


    }

    public function yolHaritasiEkle($id){

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

            $ekle = ProjeModel::yolHaritasiEkle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/yolHaritasi/'.$id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function yolHaritasiGuncelle($id){

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

            $ekle = ProjeModel::yolHaritasiGuncelle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/yolHaritasi/'.$yHDetay->proje_id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

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

                /*********************************************************/

            case "projeSil":

                $sil = ProjeModel::sil($dataId);

                $data['title'] = "Proje Silme İşlemi";

                if($sil){

                    $data['success'] = 'Proje silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Proje silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "yolHaritasiSil":

                $yHDetay = ProjeModel::yolHaritasiDetay($dataId);

                $sil = ProjeModel::yolHaritasiSil($dataId);

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