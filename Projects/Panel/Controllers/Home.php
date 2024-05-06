<?php namespace Project\Controllers;

Use User,URL;
use PersonelModel,AyarModel,KasaModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel,SiparisModel,InternalFaturaModel as FaturaModel,InternalPlanlamaModel as PlanlamaModel;

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
        $user = User::data();
        $kasaToplami = KasaModel::gelirGiderToplami();-+
        $gelir = $kasaToplami->gelir;
        $gider = $kasaToplami->gider;
        $kasa = [
            'gelir'     => number_format($gelir,2),
            'gider'     => number_format($gider,2),
            'kazanc'    => number_format($gelir - $gider,2)
        ];

        $cariHesaplar = CariModel::liste();
        $projeler = ProjeModel::liste();
        $siparisurunleri = SiparisModel::siparisUrunleriAdet();
        $odenmeyenFaturalar = FaturaModel::faturaListele('odenmeyen');
        $kasaHesaplari = KasaModel::kasaHesaplari();


        foreach($kasaHesaplari as $kkht){

            $kkhtKasaToplami = KasaModel::kasaGelirGiderToplami($kkht->id);
            $kToplam[$kkht->id] = $kkhtKasaToplami->gelir-$kkhtKasaToplami->gider;
        }


        $siparisler     = SiparisModel::liste();
        $hatirlatmalar  = PlanlamaModel::hatirlatmalar(1,$user->id);

        View::listele($siparisler);
        View::hatirlatmalar($hatirlatmalar);
        View::musteriSayisi($cariHesaplar['adet']);
        View::odenmeyenFaturaSayisi($odenmeyenFaturalar['adet']);
        View::projeSayisi($projeler['adet']);
        View::siparisurunleri($siparisurunleri['adet']);
        View::kasaHesaplari($kasaHesaplari);
        View::kToplam($kToplam);

        Masterpage::title(AyarModel::defaultAyarlar('siteAdi'));

        View::kasaToplami($kasa);
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