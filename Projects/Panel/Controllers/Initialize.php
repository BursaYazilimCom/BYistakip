<?php namespace Project\Controllers;

Use User,KasaModel,AyarModel,InternalUrunModel as UrunModel,SiparisModel,InternalDestekModel as DestekModel;

class Initialize extends Controller
{

    const exclude = ["By","Login","Paytr","CronJob"];
    
    
    public function main()
    {
        if(!User::isLogin()){
            redirect("Login");
        }else{

            $user = User::data();

            View::user($user);

        }
        $bekleyenTalepler  = DestekModel::bekleyenTalepler();
        $urunGruplari       = UrunModel::urunGrupListe();

        $kasaHesaplari      = KasaModel::turHesaplari(1);
        $bankaHesaplari     = KasaModel::turHesaplari(2);
        $posHesaplari       = KasaModel::turHesaplari(3);
        $kkartiHesaplari    = KasaModel::turHesaplari(4);
        $veresiyeHesaplari  = KasaModel::turHesaplari(5);
        $digerHesaplar      = KasaModel::turHesaplari(6);
        $paraBirimleri      = AyarModel::paraBirimleri();
        $odemeperiyodlari   = AyarModel::odemePeriyodlari();

        $islemGerekenSiparisler = SiparisModel::islemGerekenSiparisler();

        //AyarModel::nelerOluyor($user->isim,'masraf', 'Masraf Yönetimini inceliyor');


        View::bekleyenTalepler($bekleyenTalepler);
        View::odemeperiyodlari($odemeperiyodlari);
        View::islemGerekenSiparisler($islemGerekenSiparisler);
        View::urunGruplari($urunGruplari);
        View::kasaHesaplari($kasaHesaplari);
        View::bankaHesaplari($bankaHesaplari);
        View::posHesaplari($posHesaplari);
        View::kkartiHesaplari($kkartiHesaplari);
        View::veresiyeHesaplari($veresiyeHesaplari);
        View::digerHesaplar($digerHesaplar);
        View::paraBirimleri($paraBirimleri);

        Theme::active('Default');
        
        Masterpage::headPage('head')
                  ->bodyPage('body');
    }
}