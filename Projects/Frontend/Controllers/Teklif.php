<?php namespace Project\Controllers;

use Method, Post,User, Redirect,Date,URL,Validation,Upload,Email,Json,Session;
use InternalTeklifModel as TeklifModel,AyarModel,SiparisModel;
use InternalCariModel as CariModel;

class Teklif extends Controller
{

    public function __construct()
    {



    }

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){



            case "bildirimGonder":

              

                break;

        }

    }

    public function main()
    {

        AyarModel::basarisiz('Hatalı İşlem','Tanımsız bir teklife ulaşmaya çalışıyorsunuz.',URL::site());
        exit();

    }


    public function detay($id=""){

        if ($id==""){
            AyarModel::basarisiz('Hatalı İşlem','Tanımsız bir faturaya ulaşmaya çalışıyorsunuz.',URL::site());
            exit();
        }

        $teklifDetay = TeklifModel::detay($id);

        if (Session::select('cariId')==""){

            Session::insert('cariId', $teklifDetay->musteri);

        }else{

            if(Session::select('cariId')!=$teklifDetay->musteri){

                AyarModel::basarisiz('Hatalı İşlem','İlgili Fatura tarafınıza Ait Değildir.',URL::site());

            }

        }

        $teklifUrunleri = TeklifModel::teklifUrunleri($id);
        $cariDetay = CariModel::detay($teklifDetay->musteri);

        $detay = (object)[
            'id'            => $id,
            'musteri'       => $cariDetay->id,
            'aciklama'      => $teklifDetay->aciklama,
            'belge_no'      => $teklifDetay->belge_no,
            'durum'         => $teklifDetay->durum,
            'ekleme_tarihi' => $teklifDetay->ekleme_tarihi,
            'odeme_yontemi' => $teklifDetay->odeme_yontemi,
            'cariDetay'     => $cariDetay
        ];

        View::detay($detay);
        View::faturaUrunleri($teklifUrunleri);


    }


    public function s404()
    {
    }
}