<?php namespace Project\Controllers;

Use User,URL;
Use AyarModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel,SiparisModel;

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
        $devamEdenProjeler = ProjeModel::devamEden();
        $siparisurunleri = SiparisModel::siparisUrunleriAdet();

        View::musteriSayisi($cariHesaplar['adet']);
        View::projeSayisi($projeler['adet']);
        View::devamEdenProjeler($devamEdenProjeler['adet']);
        View::siparisurunleri($siparisurunleri['adet']);

        Masterpage::title(AyarModel::defaultAyarlar('siteAdi'));
    }

    public function login(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $status = User::login(Post::username(),Post::password());

            if( $status === true ) {
                AyarModel::basarili('Giriş işleminiz başarılı', 'Hoşgeldiniz', URL::site('home'));

            }else{
               AyarModel::basarisiz('Başarısız Giriş','Giriş Sırasında bir hata oluştu:<br>'.User::error(), URL::site('login'));
            }

        }

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