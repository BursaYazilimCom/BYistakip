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
        if ($sef=="") {
            Redirect::action('home');
            exit();
        }

        $projeDetay = ProjeModel::detay($sef);
        if (empty($projeDetay->id)) {
            Redirect::action('s404');
            exit();
        }
        $yolharitasi = ProjeModel::yolHaritasi($projeDetay->id);

        View::detay($projeDetay);
        View::yolHaritasi($yolharitasi);


    }
    
    public function s404()
    {

    }
}