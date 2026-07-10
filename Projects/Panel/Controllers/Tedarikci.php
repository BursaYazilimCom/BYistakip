<?php namespace Project\Controllers;

use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation;
use InternalTedarikciModel as TedarikciModel,AyarModel,SiparisModel,InternalProjeModel as ProjeModel,InternalFaturaModel as FaturaModel;
use InternalUrunModel as UrunModel,MasrafModel,KasaModel;

class Tedarikci extends Controller
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

        $listeleme = TedarikciModel::liste();

        View::listeleme($listeleme);

    }

    public function alimYap($id){
        $tedarikciDetay     = TedarikciModel::detay($id);
        $tedarikciler       = TedarikciModel::tumListe();
        $tumUrunler         = UrunModel::tumListe();

        $masrafKalemleri    = MasrafModel::masrafKalemleri();

        $kasaHesaplari      = KasaModel::turHesaplari(1);
        $bankaHesaplari     = KasaModel::turHesaplari(2);
        $posHesaplari       = KasaModel::turHesaplari(3);
        $kkartiHesaplari    = KasaModel::turHesaplari(4);
        $veresiyeHesaplari  = KasaModel::turHesaplari(5);
        $digerHesaplar      = KasaModel::turHesaplari(6);

        View::digerHesaplar($digerHesaplar);
        View::veresiyeHesaplari($veresiyeHesaplari);
        View::kkartiHesaplari($kkartiHesaplari);
        View::posHesaplari($posHesaplari);
        View::kasaHesaplari($kasaHesaplari);
        View::bankaHesaplari($bankaHesaplari);
        View::masrafKalemleri($masrafKalemleri);
        View::tedarikciDetay($tedarikciDetay);
        View::tumUrunler($tumUrunler);
        View::tedarikciler($tedarikciler);


    }

    public function detay($id){
        $tedarikciDetay = TedarikciModel::detay($id);

        View::tedarikciDetay($tedarikciDetay);


    }
    
    public function update(){

        $data['title'] = "Tedarikci İşlemleri";

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">'.$data['error'].'</div></div>'])->action('tedarikci/form/'.Post::id());

        }else{


            $data = [
                'id'                =>Post::id(),
                'adi'               =>Post::adi(),
                'firma_adi'         =>Post::firma_adi(),
                'ilgili_kisi'           =>Post::ilgili_kisi(),
                'vergi_dairesi'     =>Post::vergi_dairesi(),
                'vergi_no'          =>Post::vergi_no(),
                'adres'             =>Post::adres(),
                'telefon'           =>Post::telefon(),
                'banka_hesaplari'   =>Post::banka_hesaplari(),
                'ek_bilgiler'       =>Post::ek_bilgiler()
            ];

            $guncelle = TedarikciModel::update($data);


            if($guncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('tedarikci/form/'.Post::id());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('tedarikci/form/'.Post::id());

            }

        }
        
    }

    public function form($id=""){

        if($id){
            $data = TedarikciModel::detay($id);

           View::detay($data);
           View::action("tedarikci/update/".$id);

        }else{
            $data = (object)[
                'id'                =>'',
                'adi'               =>'',
                'firma_adi'         =>'',
                'ilgili_kisi'           =>'',
                'vergi_dairesi'     =>'',
                'vergi_no'          =>'',
                'adres'             =>'',
                'telefon'           =>'',
                'banka_hesaplari'   =>'',
                'ek_bilgiler'       =>'',

            ];
            View::detay($data);

            View::action("tedarikci/add");
        }

    }

    public function add(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $ekleData = [
                'adi'               =>Post::adi(),
                'firma_adi'         =>Post::firma_adi(),
                'ilgili_kisi'           =>Post::ilgili_kisi(),
                'vergi_dairesi'     =>Post::vergi_dairesi(),
                'vergi_no'          =>Post::vergi_no(),
                'adres'             =>Post::adres(),
                'telefon'           =>Post::telefon(),
                'banka_hesaplari'   =>Post::banka_hesaplari(),
                'ek_bilgiler'       =>Post::ek_bilgiler()
            ];

            $ekle = TedarikciModel::add($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('tedarikci');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function delete($id){

        $sil = TedarikciModel::delete($id);

        if($sil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Silme işlemi başarı ile yapıldı!</div>'])->action('tedarikci');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Silme işlemi yapılamadı!</div>'])->action('tedarikci');

        }

    }



        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){


            case "tedarikciSil":

                $sil = TedarikciModel::delete($dataId);

                $data['title'] = "Tedarikci Silme İşlemi";

                if($sil){

                    $data['success'] = 'Silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = 'tedarikci';

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