<?php namespace Project\Controllers;

use Method, Post, Redirect,Date,Pagination,User, TedarikciModel, FaturaModel,AyarModel,MalzemeModel,KasaModel;

class Kasa extends Controller
{

    public function __construct()
    {
        $user                   = User::data();
        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array(CURRENT_CONTROLLER,$yetkiler)){

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div>'])->action('home');

        }
    }

    public function main()
    {

        $user               = User::data();

        $kasaHesaplari      = KasaModel::turHesaplari(1);
        $bankaHesaplari     = KasaModel::turHesaplari(2);
        $posHesaplari       = KasaModel::turHesaplari(3);
        $kkartiHesaplari    = KasaModel::turHesaplari(4);
        $veresiyeHesaplari  = KasaModel::turHesaplari(5);
        $digerHesaplar      = KasaModel::turHesaplari(6);


        View::kasaHesaplari($kasaHesaplari);
        View::bankaHesaplari($bankaHesaplari);
        View::posHesaplari($posHesaplari);
        View::kkartiHesaplari($kkartiHesaplari);
        View::veresiyeHesaplari($veresiyeHesaplari);
        View::digerHesaplar($digerHesaplar);

       // AyarModel::nelerOluyor($user->isim,'kasa','Kasa yönetimine bakıyor');

    }

    public function hesapEkle(){

        $user               = User::data();

        if (!empty(Post::adi()) or !empty(Post::tur())){

            $data = [
                'adi'       => Post::adi(),
                'hesap_no'  => Post::hesap_no(),
                'aciklama'  => Post::aciklama(),
                'tur'       => Post::tur(),
                'durum'     => Post::durum()
            ];

            $hesapEkle = KasaModel::hesapEkle($data);

            if($hesapEkle){

                AyarModel::nelerOluyor($user->isim,'kasa','Yeni bir kasa hesabı ekledi');

                Redirect::insert(['bilgi'=>'<div class="callout callout-success">Kasa Hesabı başarı ile eklendi!</div>'])->action('kasa');
            }else{
                Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Kasa Hesabı ekleme işlemi yapılamadı!</div>'])->action('kasa');
            }

        }else{
            Redirect::insert(['bilgi'=>'<div class="callout callout-warning">Gerekli alanları doldurunuz!</div>'])->action('kasa');
        }

    }

    public function kayitlar($id,$sayfa=""){

        $user   = User::data();

        Pagination::url('kasa/kayitlar/'.$id.'/')->create();

        $kasa = KasaModel::bilgi($id);
        $kayitlar = KasaModel::kayitlar($id,$sayfa);

        //AyarModel::nelerOluyor($user->isim,'kasa/kayitlar/'.$id,$kasa->adi.'İsimli kasa hesabını inceliyor.');


        View::detay($kasa);
        View::kayitlar($kayitlar);

    }

    public function tumKayitlar(){

        $user   = User::data();

        $kayitlar = KasaModel::tumKayitlar();
        $kasaToplami = KasaModel::kasaToplami();

        //AyarModel::nelerOluyor($user->isim,'kasa/tumKayitlar','Kasa defterini inceliyor.');
        
        View::kasaToplami($kasaToplami);
        View::kayitlar($kayitlar);

    }

    public function kasaHesapGuncelle($id){


        $guncelData = [

            'id'        => $id,
            'adi'  => Post::adi(),
            'hesap_no'  => Post::hesap_no(),
            'aciklama'  => Post::aciklama(),
            'tur'       => Post::tur(),
            'durum'     => Post::durum(),
            'tutar'     => Post::tutar()

        ];

        $guncelle = KasaModel::hesapGuncelle($guncelData);

        if($guncelle){
            Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('kasa');
        }else{

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('kasa');

        }



    }

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){


            case "kasaHesapDuzenle":

                $data['title'] = "Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::id();

                    $updateData = [
                        'id'             =>$id,
                        'adi'            =>Post::adi(),
                        'sef'            =>Converter::urlWord(Post::adi()),
                        'title'          =>Post::title(),
                        'icon'           =>Post::icon(),
                        'aciklama'       =>Post::aciklama(),
                        'sira'           =>Post::sira(),
                        'anasayfa'       =>Post::anasayfa(),
                        'durum'          =>Post::durum()
                    ];

                    $guncelle = KategoriModel::update($updateData);

                    if($guncelle){

                        $data['success'] = 'Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('kategoriler');
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Guncelleme işlemi yapılamadı!";

                    }

                }

                echo Json::encode($data);


                break;

        }


    }


    public function s404()
    {
    }
}