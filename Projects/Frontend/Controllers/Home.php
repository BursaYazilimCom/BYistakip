<?php namespace Project\Controllers;

Use User,URL;
Use AyarModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel,SiparisModel,InternalFaturaModel as FaturaModel;

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

        $cariDetay = CariModel::detay($user->id);
        $uyeUrunleri = SiparisModel::uyeSiparisUrunleri($user->id,'0');
        $cariProjeleri = ProjeModel::CariProjeleri($user->id,0);
        $faturalar = FaturaModel::cariFaturalari($user->id,"0");
        $odenenler = FaturaModel::cariFaturaToplamlari($user->id,'1');
        $odenmeyenler = FaturaModel::cariFaturaToplamlari($user->id,'0');

        $toplamlar = [
            'uyeUrunAdet' => SiparisModel::uyeUrunAdet($user->id),
            'talepler' => 5,
            'odenmemisFaturaToplami' => number_format(FaturaModel::cariFaturaToplamlari($user->id,'0')->toplam,2),
            'odenenFaturaToplami' => number_format(FaturaModel::cariFaturaToplamlari($user->id,'1')->toplam,2)
        ];


        View::cariFaturaToplamlari(['odenen'=>number_format($odenenler->toplam,2),'odenmeyen'=>number_format($odenmeyenler->toplam,2)]);
        View::cariDetay($cariDetay);
        View::faturalar($faturalar);
        View::uyeUrunleri($uyeUrunleri);
        View::cariProjeleri($cariProjeleri);
        View::toplamlar($toplamlar);


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