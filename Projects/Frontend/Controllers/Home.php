<?php namespace Project\Controllers;

Use User,URL,AyarModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel;

class Home extends Controller
{
    /**
     * Home::main
     * 
     * Loads opening page.
     * Location: Views/Home/main.wizard.php
     */
    public function main(string ...$parameters)
    {
        $cariHesaplar = CariModel::liste();
        $projeler = ProjeModel::liste();
        View::musteriSayisi($cariHesaplar['adet']);
        View::projeSayisi($projeler['adet']);
        Masterpage::title(AyarModel::defaultAyarlar('siteAdi'));
    }


    /**
     * Home::s404
     * 
     * Loads show 404 page.
     * Location: Views/Home/s404.wizard.php
     */
    public function s404()
    {
        # Sets masterpage title.
        Masterpage::title('404! File Not Found');
    }
}