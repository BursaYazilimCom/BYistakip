<?php namespace Project\Controllers;

Use User,URL,Validation,Post,Json,Encode,Upload,Email,Masterpage,View;
Use AyarModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel,SiparisModel,InternalFaturaModel as FaturaModel;

class Profil extends Controller
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
        

        View::cariDetay($cariDetay);
        View::user($user);

        Masterpage::title(AyarModel::defaultAyarlar('siteAdi'));
    }

    public function guncelle() {

        $user = User::data();
        
        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

            AyarModel::basarisiz('Başarısız ',$data['error'].' - Güncelleme Sırasında bir hata oluştu lütfen tekrar deneyiniz', URL::site('profil'));

        }else{


            $adi            = Post::adi();
            $tc             = Post::tc();
            $gsm            = Post::gsm();
            $firma_adi      = Post::firma_adi();
            $fatura_adresi  = Post::fatura_adresi();
            $il             = Post::il();
            $vergi_no       = Post::vergi_no();
            $vergi_dairesi  = Post::vergi_dairesi();

            $cariData = [
                'id'            =>$user->id,
                'adi'           =>$adi,
                'gsm'           =>$gsm,
                'il'            =>$il,
                'tc'            =>$tc,
                'firma_adi'     =>$firma_adi,
                'fatura_adresi' =>$fatura_adresi,
                'vergi_dairesi' =>$vergi_dairesi,
                'vergi_no'      =>$vergi_no
            ];

            $guncelle = CariModel::update($cariData);

            if($guncelle){

                AyarModel::basarili('Güncelleme','Güncelleme isleminiz başarılı', URL::site('profil'));

            }else{

                AyarModel::basarisiz('Başarısız Güncelleme','Güncelleme Sırasında bir hata oluştu', URL::site('profil'));
            }




        }

    }

    public function sifre() {

        $user = User::data();
        $cariDetay = CariModel::detay($user->id);
        

        View::cariDetay($cariDetay);
        View::user($user);

        
    }

    public function sifreDegistir()
    {
        $user = User::data();

        $newpass        = Encode::super(Post::newpass());
        $pass           = Encode::super(Post::pass());

        if(Post::newpass() != '' and $pass == $user->pass){

                $passData = [
                    'id'        =>$user->id,
                    'pass'      =>$newpass,
                ];
                $sifreGuncelle = CariModel::updatePassword($passData);

                if($sifreGuncelle){

                    AyarModel::basarili('Sifre','Şifre değiştirme isleminiz başarılı. Lütfen değiştirmiş olduğunuz şifre ile tekrar giriniz', URL::site('home'));
                }else{

                    AyarModel::basarisiz('Sifre','Sifre değiştirme Sırasında bir hata oluştu', URL::site('profil/sifre'));
                }

            
        }else{

            AyarModel::basarisiz('Sifre','Mevcut şifrenizi yanlış girdiniz', URL::site('profil/sifre'));
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