<?php namespace Project\Controllers;

Use User,URL,Validation,Post,Encode,Email;
Use AyarModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel,SiparisModel;

class Login extends Controller
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

    public function enter(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

            AyarModel::basarisiz('Başarısız Giriş',$data['error'].' - Giriş Sırasında bir hata oluştu:<br>'.User::error(), URL::site('login'));

        }else{

            $status = User::login(Post::email(),Post::password());

            if( $status === true ) {

                $user = User::data();

                $girisEkle = AyarModel::girisEkle($user->id,User::ip());

                AyarModel::basarili('Giriş işleminiz başarılı', 'Hoşgeldiniz', URL::site('home'));

            }else{

               AyarModel::basarisiz('Başarısız Giriş','Giriş Sırasında bir hata oluştu:<br>'.User::error(), URL::site('login'));

            }

        }

    }

    public function password(){
        



    }

    public function out(){

        User::logout(URL::site('login'));

    }

    public function renevPassword(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

            AyarModel::basarisiz('Başarısız Sıfırlama',$data['error'].' - Şifre sıfırlama Sırasında bir hata oluştu:<br>'.User::error(), URL::site('login'));

        }else{

            $mailAdresi = Post::email();

            if (filter_var($mailAdresi, FILTER_VALIDATE_EMAIL)) {

                $cariDetay = CariModel::mailDetay($mailAdresi);

                if($cariDetay->id!=""){

                    $yeniSifre = rand(11111111,99999999);

                    $sifreData = [
                        'id' => $cariDetay->id,
                        'pass' => Encode::super($yeniSifre)
                    ];

                    $sifreGuncelle = CariModel::updatePassword($sifreData);

                    if($sifreGuncelle){

                        $bilgilendirmData = [
                            'tur'               =>'0', // 0: mail, 1: sms, 2: tel araması
                            'cari'              =>$cariDetay->id,
                            'ilgili_tur'        =>'SifreSifirlama',
                            'gonderilen_adres'  =>$cariDetay->email,
                            'gonderilen_icerik' =>'Talebiniz doğrultusunda şifreniz sıfılanmıştır. Şifreniz aşağıdaki gibidir.<br> Giriş yaptıktan sonra şifrenizi tekrar değiştirebilirsiniz.<br><br> Sifreniz: <b>'.$yeniSifre.'</b><br><br><hr>'
                        ];

                        AyarModel::bilgilendirmeEkle($bilgilendirmData);

                        Email::subject('Şifre Sıfırmala Bildirimi')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [
                            'konu'          => 'Şifre Sıfırmala Bildirimi',
                            'mesaj'         => 'Talebiniz doğrultusunda şifreniz sıfılanmıştır. Şifreniz aşağıdaki gibidir.<br> Giriş yaptıktan sonra şifrenizi tekrar değiştirebilirsiniz.<br><br> Sifreniz: <b>'.$yeniSifre.'</b><br><br><hr>',
                            'link'          => URL::site('login'),
                            'link_baslik'   => 'Giriş Yapmak İçin Tıklayınız',
                            'firma'         => AyarModel::defaultAyarlar('firmaAdi'),
                            'firma_link'    => AyarModel::defaultAyarlar('siteUrl'),
                            'hakkimizda'    => AyarModel::defaultAyarlar('siteKisaAciklama'),
                            'adres'         => AyarModel::defaultAyarlar('firmaAdresi'),
                            'telefon'       => AyarModel::defaultAyarlar('firmaTel')
                        ])->send();

                        AyarModel::basarili('İşleminiz gerçekleştirildi', 'Girmiş olduğunuz e-posta adresi sistemlerimizde kayıtlı ise yeni şifre bilgisini içeren bir e-posta gönderilecek. Lütfen E-posta adresinizi kontrol ediniz...'.Email::error(), URL::site('login'));

                    }else{

                        AyarModel::basarisiz('HATA !','Şifre güncelleme işlemi sırasında hata oluştu, Lütfen tekrar deneyiniz. Eğer hata devam ederse lütfen bizimle iletişime geçiniz', URL::site('login/password'));
                    }


                }else{

                    AyarModel::basarili('İşleminiz gerçekleştirildi', 'Girmiş olduğunuz e-posta adresi sistemlerimizde kayıtlı ise yeni şifre bilgisini içeren bir e-posta gönderilecek. Lütfen E-posta adresinizi kontrol ediniz.', URL::site('login'));

                }       

            } else {
                AyarModel::basarisiz('Başarısız Giriş','Girmiş olduğunuz veri bir E-posta adresi değildir! Lütfen geçerli bir mail adresi giriniz.<br>', URL::site('login/password'));
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