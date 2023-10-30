<?php namespace Project\Controllers;

Use User,KasaModel;

class Initialize extends Controller
{

    const exclude = ["By","Login"];
    
    
    public function main()
    {
        if(!User::isLogin()){
            redirect("Login");
        }else{

            $user = User::data();

            View::user($user);

        }

        $kasaHesaplari      = KasaModel::turHesaplari(1);
        $bankaHesaplari     = KasaModel::turHesaplari(2);
        $posHesaplari       = KasaModel::turHesaplari(3);
        $kkartiHesaplari    = KasaModel::turHesaplari(4);
        $veresiyeHesaplari  = KasaModel::turHesaplari(5);
        $digerHesaplar      = KasaModel::turHesaplari(6);

        //AyarModel::nelerOluyor($user->isim,'masraf', 'Masraf Yönetimini inceliyor');


        View::kasaHesaplari($kasaHesaplari);
        View::bankaHesaplari($bankaHesaplari);
        View::posHesaplari($posHesaplari);
        View::kkartiHesaplari($kkartiHesaplari);
        View::veresiyeHesaplari($veresiyeHesaplari);
        View::digerHesaplar($digerHesaplar);

        Theme::active('Default');
        
        Masterpage::headPage('head')
                  ->bodyPage('body')
                  ->browserIcon(FILES_DIR . 'favicon.ico');
    }
}