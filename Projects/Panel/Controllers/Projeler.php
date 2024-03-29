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
        $siralamaYeri = "yolHaritasi";

        View::siralamaYeri($siralamaYeri);
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

    public function yapilanlar($id)
    {
        $projeDetay = ProjeModel::detay($id);
        $listeData = ProjeModel::yapilanlar($id);

        View::listele($listeData);
        View::detay($projeDetay);


    }

    public function yapilanIslemEkle($id){
        $user = User::data();

        if(Post::islem()==""){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !. Lütfen gerekli alanları doldurunuz.</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'proje_id'  => $id,
                'tur'       => Post::tur(),
                'islem'     => Post::islem(),
                'link'      => Post::link(),
                'ekleyen'   => $user->id
            ];

            $ekle = ProjeModel::yapilanIslemEkle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/yapilanlar/'.$id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }

        }
        

    }

    public function yapilanIslemGuncelle($id){
        $user = User::data();

        $yIDetay = ProjeModel::yapilanIslemDetay($id);

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'id'        => $id,
                'tur'       => Post::tur(),
                'islem'     => Post::islem(),
                'ekleyen'     => $user->id
            ];

            $ekle = ProjeModel::yapilanIslemGuncelle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/yapilanlar/'.$yIDetay->proje_id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function personeller($id)
    {
        $projeDetay = ProjeModel::detay($id);
        $listeData = ProjeModel::personeller($id);

        View::listele($listeData);
        View::detay($projeDetay);


    }

    public function personelEkle($id){

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'proje_id'      => $id,
                'personel_id'   => Post::personel_id(),
                'gorevi'        => Post::gorevi(),
                'islem'         => Post::islem()
            ];

            $ekle = ProjeModel::personelEkle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/personeller/'.$id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function personelGuncelle($id){

        $yIDetay = ProjeModel::personelDetay($id);

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'id'            => $id,
                'gorevi'        => Post::gorevi()
            ];

            $ekle = ProjeModel::personelGuncelle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/personeller/'.$yIDetay->proje_id);
            } else {

                Redirect::insert(['bilgi' => '<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.'.$ekle.'</div></div>'])->action(URL::prev());

            }
        }

    }

    public function geriBildirimler($id){
        $projeDetay = ProjeModel::detay($id);
        $bildirimler = ProjeModel::geriBildirimler($id);

        View::listele($bildirimler);
        View::detay($projeDetay);
    }

    public function geriBildirimGuncelle($id){

        $gbDetay = ProjeModel::geriBildirimDetay($id);

        if(!Validation::check()){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else {

            $ekleData = [
                'id'        => $id,
                'cevap'     => Post::cevap(),
                'durum'     => Post::durum()
            ];

            $ekle = ProjeModel::geriBildirimGuncelle($ekleData);

            if ($ekle) {
                Redirect::insert(['bilgi' => '<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.'.$ekle.'</div></div>'])->action('projeler/geriBildirimler/'.$gbDetay->proje_id);
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

            case "yapilanIslemSil":

                $yHDetay = ProjeModel::yapilanIslemDetay($dataId);

                $sil = ProjeModel::yapilanIslemSil($dataId);

                $data['title'] = "Proje yapilan işlem Silme İşlemi";

                if($sil){

                    $data['success'] = 'Proje yapilan işlem Silme İşlem başarı ile yapıldı!';
                    //$data['redirect'] = '/projeler/yolHaritasi/'.$yHDetay->proje_id;
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Proje yapilan işlem Silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

                case "personelSil":
    
                    $sil = ProjeModel::personelSil($dataId);
    
                    $data['title'] = "Projede çalışan personel Silme İşlemi";
    
                    if($sil){
    
                        $data['success'] = 'Projede çalışan personel Silme İşlemi başarı ile yapıldı!';
                        //$data['redirect'] = '/projeler/yolHaritasi/'.$yHDetay->proje_id;
                        $data['redirect'] = '';
    
                    }else{
    
                        $data['error'] = "Projede çalışan personel Silme işlemi yapılamadı!";
    
                    }
    
                    echo Json::encode($data);
    
                    break;
                case "geriBildirimSil":
    
                    $sil = ProjeModel::geriBildirimSil($dataId);
    
                    $data['title'] = "Projede geri bildirim Silme İşlemi";
    
                    if($sil){
    
                        $data['success'] = 'Projede geri bildirim Silme İşlemi başarı ile yapıldı!';
                        //$data['redirect'] = '/projeler/yolHaritasi/'.$yHDetay->proje_id;
                        $data['redirect'] = '';
    
                    }else{
    
                        $data['error'] = "Projede geri bildirim Silme işlemi yapılamadı!";
    
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