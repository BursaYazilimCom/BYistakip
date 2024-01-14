<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter;
use InternalProjeModel as ProjeModel,AyarModel,InternalCariModel as CariModel;

class Proje extends Controller
{

    public function __construct()
    {

    }

    public function main($sef="")
    {
        $projeDetay = ProjeModel::detay($sef);
        if (empty($projeDetay->id)) {
            Redirect::action('s404');
            exit();
        }

        if (Session::select('kullanici')!=$projeDetay->musteri.'-'.$projeDetay->id) {
            Session::insert('proje',$sef);
            Redirect::action('proje/login/'.$sef);
            exit();
        }


        if ($sef=="") {
            Redirect::action('home');
            exit();
        }


        $yolharitasi = ProjeModel::yolHaritasi($projeDetay->id);

        View::detay($projeDetay);
        View::yolHaritasi($yolharitasi);


    }

    public function calismalar($sef)
    {
        $projeDetay = ProjeModel::detay($sef);

        if (empty($projeDetay->sef)) {
            Redirect::action('s404');
            exit();
        }

        $calismalar = ProjeModel::yapilanlar($projeDetay->id);

        if (Session::select('kullanici')!=$projeDetay->musteri.'-'.$projeDetay->id) {
            Session::insert('proje',$sef);
            Redirect::action('proje/login/'.$sef);
            exit();
        }

        View::detay($projeDetay);
        View::calismalar($calismalar);
    }

    public function login($sef){

        $projeDetay = ProjeModel::detay($sef);

        if (empty($projeDetay->id)) {
            Redirect::action('s404');
            exit();
        }

        if (Post::sifre()!=""){
            $sifre = Encode::type(Post::sifre(), 'md5');

            if ($sifre == $projeDetay->sifre) {
                Session::insert('kullanici',$projeDetay->musteri.'-'.$projeDetay->id);
                Redirect::action('proje/main/'.$sef);
            }else{
                Redirect::insert(['bilgi'=>'<div class="alert alert-danger">Proje Şifresi Hatalıdır. Lütfen Şifreyi Doğru Giriniz</div>'])->action('proje/login/'.$sef);
            }

        }

        View::detay($projeDetay);

    }
    
    public function s404()
    {

    }
}