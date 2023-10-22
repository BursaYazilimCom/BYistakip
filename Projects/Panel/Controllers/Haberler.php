<?php namespace Project\Controllers;

use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Security,File;
use InternalHaberModel as HaberModel,InternalFirmaModel as FirmaModel,AyarModel,InternalSektorModel as SektorModel;

class Haberler extends Controller
{

    public function __construct()
    {
        $user                   = User::data();
        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array(CURRENT_CONTROLLER,$yetkiler)){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger"><div class="alert-body">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div></div>'])->action('home');

        }
    }

    public function main()
    {

        $listele = HaberModel::liste();

        View::listele($listele);

    }
    
    public function update($id){

        $data['title'] = "Haber Güncelleme";

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            if($id!=Post::id()){

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger"><div class="alert-body">Beklenmeyen bir hata ile karşılaşıldı, Lütfen herşeyi doğru yaptığınıza emin olun!</div></div>'])->action('haberler/form/'.$id);

            }

            $detay = HaberModel::detay($id);

            if(Upload::isFile('resim')){

                Upload::mimes('image/jpeg', 'image/png')
                    ->convertName()
                    ->source('resim')
                    ->target(REAL_BASE_DIR . 'Uploads/haber-resimleri/')
                    ->start();
                $dosyaBilgi = Upload::info();

                $resim = $dosyaBilgi->encodeName;

            }else{
                $resim = $detay->resim;
            }

            $guncelData = [

                'id'        => $id,
                'kategori'  => Post::kategori(),
                'firma_id'  => Post::firma_id(),
                'sef'       => Post::sef(),
                'baslik'    => Post::baslik(),
                'aciklama'  => Post::aciklama(),
                'durum'     => Post::durum(),
                'yazar'     => Post::yazar(),
                'resim'     => $resim

            ];

            $guncelle = HaberModel::update($guncelData);

            if($guncelle){

                if(Upload::isFile('resimler')){

                    Upload::mimes('image/jpeg', 'image/png')
                        ->convertName()
                        ->source('resimler')
                        ->target(REAL_BASE_DIR . 'Uploads/haber-resimleri/')
                        ->start();
                    $resimlerDosyaBilgi = Upload::info();

                    $resimler = $resimlerDosyaBilgi->encodeName;
                    $resimSay = count($resimler);

                    for ($r=0;$r<=$resimSay;$r++){

                        $resimData = [
                            'firma_id'             =>$id,
                            'resim'            =>$resimler[$r],
                        ];

                        $resimKaydet = HaberModel::haberResimEkle($resimData);

                        $resimData="";

                    }

                }

            }

            if($guncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('haberler/form/'.Post::id());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('haberler/form/'.Post::id());

            }

        }

        print_r($data);
        
    }

    public function form($id=""){

        $firmalar       = FirmaModel::tumListe();
        $kategoriler    = HaberModel::kategoriListe();


        View::firmalar($firmalar);
        View::kategoriler($kategoriler);


        if($id){

            $data = HaberModel::detay($id);

           $haberResimleri = HaberModel::resimleri($id);

           View::haberResimleri($haberResimleri);
           View::detay($data);
           View::action("haberler/update/".$id);

        }else{

            $haberResimleri = [];
            $data = (object)[
                'id'        =>'',
                'kategori'  =>'',
                'firma_id'  =>'',
                'sef'       =>'',
                'resim'     =>'',
                'baslik'    =>'',
                'aciklama'  =>'',
                'detay'     =>'',
                'durum'     =>''
            ];
            View::detay($data);
            View::action("haberler/add");

            View::haberResimleri($haberResimleri);
        }



    }

    public function add(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{


            if(Upload::isFile('resim')){

                Upload::mimes('image/jpeg', 'image/png')
                    ->convertName()
                    ->source('resim')
                    ->target(REAL_BASE_DIR . 'Uploads/haber-resimleri/')
                    ->start();
                $dosyaBilgi = Upload::info();

                $resim = $dosyaBilgi->encodeName;

            }else{
                $resim = "";
            }


            $ekleData = [
                'kategori'  => Post::kategori(),
                'firma_id'  => Post::firma_id(),
                'sef'       => Post::sef(),
                'baslik'    => Post::baslik(),
                'aciklama'  => Post::aciklama(),
                'durum'     => Post::durum(),
                'yazar'     => Post::yazar(),
                'resim'     => $resim

            ];

            $ekle = FirmaModel::ekle($ekleData);

            if ($ekle){

                if(Upload::isFile('resimler')){

                    Upload::mimes('image/jpeg', 'image/png')
                        ->convertName()
                        ->source('resimler')
                        ->target(REAL_BASE_DIR . 'Uploads/haber-resimleri/')
                        ->start();
                    $resimlerDosyaBilgi = Upload::info();

                    echo "<pre>";
                    print_r($resimlerDosyaBilgi);
                    echo "</pre>";

                    $resimler = $resimlerDosyaBilgi->encodeName;
                    $resimSay = count($resimler);

                    for ($r=0;$r<=$resimSay;$r++){

                        $resimData = [
                            'haber_id'             =>$ekle,
                            'resim'            =>$resimler[$r],
                        ];

                        $resimKaydet = HaberModel::haberResimEkle($resimData);

                        $resimData="";

                    }

                }else{
                    $resimler = "";
                }

            }

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('haberler');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function delete($id){

        $sil = HaberModel::delete($id);

        if($sil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Personel silme işlemi başarı ile yapıldı!</div>'])->action('haberler');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Personel silme işlemi yapılamadı!</div>'])->action('haberler');

        }

    }

    public function resimSil($haber,$id){

        $resimData = HaberModel::haberResimData($id);


        if( File::exists('../Uploads/haber-resimleri/'.$resimData->resim) )
        {
            $resimSunucudanSil = File::delete('../Uploads/haber-resimleri/'.$resimData->resim);
        }

            $resimDataSil = HaberModel::haberResimSil($id);

        if($resimDataSil){

            Redirect::insert(['bilgi'=>'<div  class="alert alert-success" role="alert"><div class="alert-body">Firma resmi başarıyla silimndi!</div></div>'])->action('haberler/form/'.$haber);
        }else{
            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><div class="alert-body">Firma resmi SİLİNEMEDİ!</div></div>'])->action('haberler/form/'.$haber);
        }

    }




        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){



            case "haberSil":

                $sil = HaberModel::delete($dataId);

                $data['title'] = "Haber Silme İşlemi";

                if($sil){

                    $data['success'] = 'Haber silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = 'haberler';

                }else{

                    $data['error'] = "Haber silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

        }







    }


    
    public function s404()
    {

    }
}