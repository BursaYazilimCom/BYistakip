<?php namespace Project\Controllers;


use User,Method,Post,Redirect,Json,URL,Validation,Converter,Security,Upload;
use InternalReklamModel as ReklamModel,AyarModel,InternalTedarikciModel as TedarikciModel,InternalCariModel as CariModel;

class Reklam extends Controller
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

    public function hesaplar()
    {
        $hesaplar = ReklamModel::hesapListe();

        View::liste($hesaplar);


    }


    public function hesapForm($id=""){

        $platformlar    = ReklamModel::platformlar();
        $hesap_durumlari= ReklamModel::hesap_durumlari();
        $cariler        = CariModel::tumListe();

        if($id){
            $data = ReklamModel::hesapDetay($id);

            View::detay($data);
            View::action("reklam/hesapGuncelle/".$id);

        }else{
            $data = (object)[
                'id'            =>'',
                'cari'          =>'',
                'ads_id'        =>'',
                'mail_adresi'   =>'',
                'mail_adresi2'  =>'',
                'sifre'         =>'',
                'rvd'           =>'',
                'dogrulama_tel' =>'',
                'odeme_yontemi' =>'',
                'odeme_araci'   =>'',
                'proxy'         =>'',
                'aciklama'      =>'',
                'ek_not'        =>'',
                'platform'      =>'',
                'acilis_tarihi' =>'',
                'durum'         =>'1'

            ];

            View::detay($data);
            View::platformlar($platformlar);

            View::action("reklam/hesapEkle");
        }
        View::cariler($cariler);
        View::platformlar($platformlar);
        View::hesap_durumlari($hesap_durumlari);

    }

    public function hesapEkle(){

        if(empty(Post::mail_adresi()) or empty(Post::sifre())){

                AyarModel::basarisiz('Başarısız İşlem', 'Ekleme işlemi sırasında hata oluştu ! Lütfen giriş yaptığınız bilgileri kontrol edin.'.str_replace('<br>',EOL,Validation::error('string')), URL::prev());


        }else{

            $ekleData = [
                'ads_id'            =>Post::ads_id(),
                'cari'              =>Post::cari(),
                'mail_adresi'       =>Post::mail_adresi(),
                'mail_adresi2'      =>Post::mail_adresi2(),
                'sifre'             =>Post::sifre(),
                'reklam_url'        =>Post::reklam_url(),
                'rvd'               =>Post::rvd(),
                'dogrulama_tel'     =>Post::dogrulama_tel(),
                'odeme_yontemi'     =>Post::odeme_yontemi(),
                'odeme_araci'       =>Post::odeme_araci(),
                'proxy'             =>Post::proxy(),
                'aciklama'          =>Security::htmlEncode(Post::aciklama()),
                'ek_not'            =>Security::htmlEncode(Post::ek_not()),
                'platform'          =>Post::platform(),
                'acilis_tarihi'     =>Post::acilis_tarihi(),
                'durum'             =>Post::durum()
            ];

            $ekle = ReklamModel::hesapEkle($ekleData);

            if($ekle){

                AyarModel::basarili('Başarılı İşlem', 'Hesap Ekleme İşlemi Yapıldı !', URL::site("reklam/hesaplar"));

            }else{

                AyarModel::basarisiz('Başarısız İşlem', 'Hesap Ekleme İşlemi Yapılamadı !', URL::prev());

            }

        }

    }

    public function hesapGuncelle($id){

        if(empty(Post::mail_adresi()) or empty(Post::sifre())){

            AyarModel::basarisiz('Güncelleme İşlemi','Güncelleme işlemi sırasında hata oluştu<br>'.str_replace('<br>',EOL,Validation::error('string')),URL::prev());


        }else{

            $updateData = [
                'id'                =>$id,
                'cari'              =>Post::cari(),
                'ads_id'            =>Post::ads_id(),
                'mail_adresi'       =>Post::mail_adresi(),
                'mail_adresi2'      =>Post::mail_adresi2(),
                'sifre'             =>Post::sifre(),
                'reklam_url'        =>Post::reklam_url(),
                'rvd'               =>Post::rvd(),
                'dogrulama_tel'     =>Post::dogrulama_tel(),
                'odeme_yontemi'     =>Post::odeme_yontemi(),
                'odeme_araci'       =>Post::odeme_araci(),
                'proxy'             =>Post::proxy(),
                'aciklama'          =>Security::htmlEncode(Post::aciklama()),
                'ek_not'            =>Security::htmlEncode(Post::ek_not()),
                'platform'          =>Post::platform(),
                'acilis_tarihi'     =>Post::acilis_tarihi(),
                'durum'             =>Post::durum()
            ];

            $update = ReklamModel::hesapGuncelle($updateData);

            if($update){

                AyarModel::basarili('Güncelleme İşlemi','Güncelleme İşlemi Yapıldı !', URL::site('reklam/hesapForm/'.$id));

            }else{

                AyarModel::basarisiz('Güncelleme İşlemi','Güncelleme İşlemi Yapılamadı !', URL::prev());

            }

        }

    }

    /****************************************/

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

                /*********************************************************/
            case "hesapSil":

                $sil = ReklamModel::hesapSil($dataId);

                $data['title'] = "Hesap Silme İşlemi";

                if($sil){

                    $data['success'] = 'Ürün silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Ürün silme işlemi yapılamadı!";

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