<?php namespace Project\Controllers;

use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation;
use InternalPersonelModel as PersonelModel,AyarModel ;
use InternalIlanModel as IlanModel,InternalFirmaModel as FirmaModel,InternalUyeModel as UyeModel,InternalSektorModel as SektorModel,InternalKategoriModel as KategoriModel;

class Ilanlar extends Controller
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

        $ilanlar = IlanModel::elemanIlanListe();

        View::ilanlar($ilanlar);

    }

    public function eleman()
    {

        $ilanlar = IlanModel::elemanIlanListe();

        View::ilanlar($ilanlar);

    }
    
    public function elemanIlanDuzenle(){

        $data['title'] = "Panel Girişi";

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $data = [
                'id'                =>Post::id(),
                'firma_id'          =>Post::firma_id(),
                'kategori'          =>Post::kategori(),
                'sektor'            =>Post::sektor(),
                'baslik'            =>Post::baslik(),
                'aciklama'          =>Post::aciklama(),
                'calisma_sekli'     =>Post::calisma_sekli(),
                'cinsiyet'          =>Post::cinsiyet(),
                'egitim_seviyesi'   =>Post::egitim_seviyesi(),
                'il'                =>Post::il(),
                'ilce'              =>Post::ilce(),
                'personel_sayisi'   =>Post::personel_sayisi(),
                'aylik_ucret'       =>Post::aylik_ucret()
            ];

            $guncelle = IlanModel::elemanIlanGuncelle($data);


            if($guncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('Ilanlar/form/'.Post::id());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('Ilanlar/form/'.Post::id());

            }

        }

        print_r($data);
        
    }

    public function elemanIlanform($id=""){

        if($id){

            $data = IlanModel::elemanIlanDetay($id);

           View::detay($data);
           View::action("Ilanlar/update/".$id);

        }else{
            $data = (object)[
                'id'                =>'',
                'firma_id'          =>'',
                'kategori'          =>'',
                'sektor'            =>'',
                'baslik'            =>'',
                'aciklama'          =>'',
                'calisma_sekli'     =>'',
                'cinsiyet'          =>'',
                'egitim_seviyesi'   =>'',
                'il'                =>'',
                'ilce'              =>'',
                'personel_sayisi'   =>'',
                'aylik_ucret'       =>''
            ];
            View::detay($data);
            View::action("Ilanlar/add");
        }


    }

    public function elemanIlanEkle(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $data = [
                'firma_id'          =>Post::firma_id(),
                'kategori'          =>Post::kategori(),
                'sektor'            =>Post::sektor(),
                'baslik'            =>Post::baslik(),
                'aciklama'          =>Post::aciklama(),
                'calisma_sekli'     =>Post::calisma_sekli(),
                'cinsiyet'          =>Post::cinsiyet(),
                'egitim_seviyesi'   =>Post::egitim_seviyesi(),
                'il'                =>Post::il(),
                'ilce'              =>Post::ilce(),
                'personel_sayisi'   =>Post::personel_sayisi(),
                'aylik_ucret'       =>Post::aylik_ucret()
            ];

            $ekle = IlanModel::elemanIlanEkle($data);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('Ilanlar/eleman');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function elemanIlanSil($id){

        $sil = IlanModel::elemanIlanSil($id);

        if($sil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Eleman İlan silme işlemi başarı ile yapıldı!</div>'])->action('Ilanlar/eleman');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Eleman İlan silme işlemi yapılamadı!</div>'])->action('Ilanlar/eleman');

        }

    }



        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

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