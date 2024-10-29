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
        $odemeAraclari = ReklamModel::odemeAracTumListe(1);

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
        View::odemeAraclari($odemeAraclari);
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

    public function anlasmalar()
    {
        $anlasmalar = ReklamModel::anlasmaListe();

        View::liste($anlasmalar);


    }

    public function anlasmaForm($id=""){

        $cariler        = CariModel::tumListe();

        if($id){
            $data = ReklamModel::anlasmaDetay($id);

            View::detay($data);
            View::action("reklam/anlasmaGuncelle/".$id);

        }else{
            $data = (object)[
                'id'                =>'',
                'cari'              =>'',
                'periyod'           =>'',
                'ucret'             =>'',
                'baslangic_tarihi'  =>'',
                'bitis_tarihi'      =>'',
                'detay'             =>'',
                'durum'             =>'1'

            ];

            View::detay($data);

            View::action("reklam/anlasmaEkle");
        }
        View::cariler($cariler);

    }

    public function anlasmaEkle(){

        if(empty(Post::cari())){

            AyarModel::basarisiz('Başarısız İşlem', 'Ekleme işlemi sırasında hata oluştu ! Lütfen giriş yaptığınız bilgileri kontrol edin.'.str_replace('<br>',EOL,Validation::error('string')), URL::prev());


        }else{

            $ekleData = [
                'cari'              =>Post::cari(),
                'periyod'           =>Post::periyod(),
                'ucret'             =>Post::ucret(),
                'baslangic_tarihi'  =>Post::baslangic_tarihi(),
                'bitis_tarihi'      =>Post::bitis_tarihi()==""?null:Post::bitis_tarihi(),
                'detay'             =>Security::htmlEncode(Post::detay()),
                'durum'             =>Post::durum()
            ];

            $ekle = ReklamModel::anlasmaEkle($ekleData);

            if($ekle){

                AyarModel::basarili('Başarılı İşlem', 'Anlasma Ekleme İşlemi Yapıldı !', URL::site("reklam/anlasmalar"));

            }else{

                AyarModel::basarisiz('Başarısız İşlem', 'Hesap Ekleme İşlemi Yapılamadı !', URL::prev());

            }

        }

    }

    public function anlasmaGuncelle($id){

        if(empty(Post::cari())){

            AyarModel::basarisiz('Güncelleme İşlemi','Güncelleme işlemi sırasında hata oluştu<br>'.str_replace('<br>',EOL,Validation::error('string')),URL::prev());


        }else{

            $updateData = [
                'id'                =>$id,
                'cari'              =>Post::cari(),
                'periyod'           =>Post::periyod(),
                'ucret'             =>Post::ucret(),
                'baslangic_tarihi'  =>Post::baslangic_tarihi(),
                'bitis_tarihi'      =>Post::bitis_tarihi(),
                'detay'             =>Security::htmlEncode(Post::detay()),
                'durum'             =>Post::durum()
            ];

            $update = ReklamModel::anlasmaGuncelle($updateData);

            if($update){

                AyarModel::basarili('Güncelleme İşlemi','Güncelleme İşlemi Yapıldı !', URL::site('reklam/anlasmaForm/'.$id));

            }else{

                AyarModel::basarisiz('Güncelleme İşlemi','Güncelleme İşlemi Yapılamadı !', URL::prev());

            }

        }

    }

    /****************************************/

    public function odemeAraclari()
    {
        $liste = ReklamModel::odemeAracListe();

        View::liste($liste);

    }

    public function odemeAracForm($id=""){

        if($id){
            $data = ReklamModel::odemeAracDetay($id);

            View::detay($data);
            View::action("reklam/odemeAracGuncelle/".$id);

        }else{
            $data = (object)[
                'id'                =>'',
                'tur'               =>'',
                'numara'            =>'',
                'cvv'               =>'',
                'son_kullanim'      =>'',
                'sahibi'            =>'',
                'aciklama'          =>'',
                'durum'             =>'1'

            ];

            View::detay($data);

            View::action("reklam/odemeAracEkle");
        }

    }

    public function odemeAracEkle(){

        if(empty(Post::numara()) or empty(Post::cvv()) or empty(Post::son_kullanim()) or empty(Post::sahibi())){

            AyarModel::basarisiz('Başarısız İşlem', 'Ekleme işlemi sırasında hata oluştu ! Lütfen giriş yaptığınız bilgileri kontrol edin.'.str_replace('<br>',EOL,Validation::error('string')), URL::prev());


        }else{

            $ekleData = [
                'tur'           =>Post::tur(),
                'numara'        =>Post::numara(),
                'cvv'           =>Post::cvv(),
                'son_kullanim'  =>Post::son_kullanim(),
                'sahibi'        =>Post::sahibi(),
                'aciklama'      =>Security::htmlEncode(Post::aciklama()),
                'durum'         =>Post::durum()
            ];

            $ekle = ReklamModel::odemeAracEkle($ekleData);

            if($ekle){

                AyarModel::basarili('Başarılı İşlem', 'Ödeme Aracı Ekleme İşlemi Yapıldı !', URL::site("reklam/odemeAraclari"));

            }else{

                AyarModel::basarisiz('Başarısız İşlem', 'Ödeme Aracı İşlemi Yapılamadı !', URL::prev());

            }

        }

    }

    public function odemeAracGuncelle($id){

        if(empty(Post::numara()) or empty(Post::cvv()) or empty(Post::son_kullanim()) or empty(Post::sahibi()) ){

            AyarModel::basarisiz('Güncelleme İşlemi','Güncelleme işlemi sırasında hata oluştu<br>'.str_replace('<br>',EOL,Validation::error('string')),URL::prev());


        }else{

            $updateData = [
                'id'            =>$id,
                'tur'           =>Post::tur(),
                'numara'        =>Post::numara(),
                'cvv'           =>Post::cvv(),
                'son_kullanim'  =>Post::son_kullanim(),
                'sahibi'        =>Post::sahibi(),
                'aciklama'      =>Security::htmlEncode(Post::aciklama()),
                'durum'         =>Post::durum()
            ];

            $update = ReklamModel::odemeAracGuncelle($updateData);

            if($update){

                AyarModel::basarili('Güncelleme İşlemi','Güncelleme İşlemi Yapıldı !', URL::site('reklam/odemeAracForm/'.$id));

            }else{

                AyarModel::basarisiz('Güncelleme İşlemi','Güncelleme İşlemi Yapılamadı !', URL::prev());

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
            case "hesapSil":

                $sil = ReklamModel::hesapSil($dataId);

                $data['title'] = "Hesap Silme İşlemi";

                if($sil){

                    $data['success'] = 'Hesap silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Hesap silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "anlasmaSil":

                $sil = ReklamModel::anlasmaSil($dataId);

                $data['title'] = "Anlasma Silme İşlemi";

                if($sil){

                    $data['success'] = 'Anlasma silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Anlasma silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "odemeAracSil":

                $sil = ReklamModel::odemeAracSil($dataId);

                $data['title'] = "Ödeme Aracı Silme İşlemi";

                if($sil){

                    $data['success'] = 'Ödeme Aracı silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Ödeme Aracı silme işlemi yapılamadı!";

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