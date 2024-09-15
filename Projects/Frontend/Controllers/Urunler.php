<?php namespace Project\Controllers;


use User,Method,Post,Redirect,Json,URL,Validation,Converter,Security;
use InternalUrunModel as UrunModel;

class Urunler extends Controller
{

    public function __construct()
    {

    }

    public function main()
    {
        $listeData = UrunModel::liste();

        View::listele($listeData);
    }

    public function grup($id,$sayfa=0)
    {
        if(!is_numeric($id)){
            redirect('urun');
        }

        $grupDetay = UrunModel::urunGrupDetay($id);
        $listeData = UrunModel::grupListe($id,$sayfa=0);

        View::listele($listeData);
        View::grupDetay($grupDetay);

        Masterpage::headPage('head')
            ->bodyPage('body');

    }

    public function gruplar()
    {
        $listeData = UrunModel::urunGrupListe();
        $siralamaYeri = "urunGuruplari";

        View::siralamaYeri($siralamaYeri);
        View::listele($listeData);


    }

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

            case "urunGrupOzellikSil":

                $ozellikDetay = UrunModel::urunGrupOzellikDetay($dataId);

                $sil = UrunModel::urunGrupOzellikSil($dataId);


                $data['title'] = "Grup Özellik Silme İşlemi";

                if($sil){

                    $data['success'] = 'Grup Özellik silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Grup Özellik silme işlemi yapılamadı!";

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