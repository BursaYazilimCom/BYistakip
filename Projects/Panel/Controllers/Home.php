<?php namespace Project\Controllers;

Use User,URL;
use PersonelModel,AyarModel,KasaModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel,SiparisModel,InternalFaturaModel as FaturaModel;

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
        $kasaToplami = KasaModel::kasaToplami();

        $cariHesaplar = CariModel::liste();
        $projeler = ProjeModel::liste();
        $siparisurunleri = SiparisModel::siparisUrunleriAdet();
        $odenmeyenFaturalar = FaturaModel::faturaListele('odenmeyen');
        $kasaHesaplari = KasaModel::kasaHesaplari();
        $siparisler     = SiparisModel::liste();

        View::listele($siparisler);
        View::musteriSayisi($cariHesaplar['adet']);
        View::odenmeyenFaturaSayisi($odenmeyenFaturalar['adet']);
        View::projeSayisi($projeler['adet']);
        View::siparisurunleri($siparisurunleri['adet']);
        View::kasaHesaplari($kasaHesaplari);

        Masterpage::title(AyarModel::defaultAyarlar('siteAdi'));

        View::kasaToplami(number_format($kasaToplami,2));
    }

    public function logout(){

        User::logout(URL::prev());

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