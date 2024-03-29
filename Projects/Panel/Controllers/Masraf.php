<?php namespace Project\Controllers;

use Method, Post, Redirect,Date,User,URL,Json,Pagination,AyarModel,MasrafModel,KasaModel,Upload;

class Masraf extends Controller
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

        $masrafKalemleri    = MasrafModel::masrafKalemleri();

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

        View::masrafKalemleri($masrafKalemleri);

    }

    public function kayitlar($id,$sayfa=""){

        $user = User::data();

        Pagination::url('masraf/kayitlar/'.$id.'/')->create();

        $masraf = MasrafModel::bilgi($id);
        $kayitlar = MasrafModel::kayitlar($id,$sayfa);

        //AyarModel::nelerOluyor($user->isim,'masraf/kayitlar/'.$id, $masraf->adi.' isimli masraf kaleminin kayıtlarını inceliyor');

        View::detay($masraf);
        View::kayitlar($kayitlar);

    }

    public function tumKayitlar(){

        $user = User::data();

        $kayitlar = MasrafModel::tumKayitlar();

        //AyarModel::nelerOluyor($user->isim,'masraf/tumKayitlar', 'Tüm Masraf kayıtlarını inceliyor');

        View::kayitlar($kayitlar);

    }

    public function masrafEkle(){

        $user = User::data();

        $tutar = Post::tutar();


        if(Upload::isFile('belge_dosya')) {

            Upload:
            convertName()
                ->source('belge_dosya')
                ->target(UPLOADS_DIR . 'masraf_belgeleri/')
                ->start();
            $dosyaBilgi = Upload::info();

            $dosya = $dosyaBilgi->encodeName;
        }else{
            $dosya = "";
        }

            $data = [
                'kalem'         =>Post::kalem(),
                'kasa'          =>Post::kasa(),
                'belge_no'      =>Post::belge_no(),
                'belge_dosya'   =>$dosya,
                'aciklama'      =>Post::aciklama(),
                'odeme_durumu'  =>Post::odeme_durumu(),
                'tutar'         =>$tutar,
                'odeme_tarihi'  =>AyarModel::tarihDuzelt(Post::odeme_tarihi())
            ];

            $ekle = MasrafModel::masrafEkle($data);

        if($ekle){

            if(!empty(Post::kasa()) and Post::odeme_durumu()!="0"){

                $masrafKalemi = MasrafModel::bilgi(Post::kalem());

                $kasaDetay = KasaModel::hesapBilgi(Post::kasa());
                $kasaToplami = KasaModel::kasaToplami();

                $defterData = [
                    'kasa'          =>Post::kasa(),
                    'islem'         =>'o',
                    'hesap'         =>'Masraf:'.$masrafKalemi->adi,
                    'islem_turu'    =>'masraf',
                    'islem_tur_id'  =>$ekle,
                    'aciklama'      =>Post::aciklama(),
                    'gelir'         =>'0.0000',
                    'gider'         =>$tutar,
                    'mevcut_kasa_tutari' =>$kasaDetay->tutar,
                    'mevcut_kasa_toplami'=>$kasaToplami,
                    'belge'         =>$dosya,
                    'yil'           =>Date::set('{year}'),
                    'tarih'         =>Date::set('{year}-{monthNumber0}-{dayNumber0}'),
                    'islem_yapan'   =>$user->id
                ];

                $deftereKaydet = KasaModel::deftereKaydet($defterData);

                $kasadaTutari = $kasaDetay->tutar-$tutar;

                $kasaGuncelle = KasaModel::kasaHesabiTutarGuncelle($kasadaTutari,Post::kasa());

            }

           // AyarModel::nelerOluyor($user->isim,'masraf/kayitlar/'.Post::kalem(), $masrafKalemi->adi.' masraf kalemine '.$kasaDetay->tutar.' tutarında masraf eklemesi yaptı');



                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action(URL::prev());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

    }

    public function masrafSil($id){

        $sil = MasrafModel::giderSil($id);

        if($sil){
            Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Silme İşlemi Yapıldı !.</div></div>'])->action('masraf/tumKayitlar');
        }else{

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Silme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

        }

    }

    public function anaKalemEkle()
    {
        if (Post::dataAction()=="anaKalemGuncelle"){

            if (!empty(Post::adi())){

                $data = [
                    'id'       => Post::update_id(),
                    'adi'       => Post::adi(),
                    'renk'      => Post::renk()
                ];

                $kalemEkle = MasrafModel::anaKalemGuncelle($data);

                if($kalemEkle){
                    Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('masraf');
                }else{

                    Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

                }

            }else{
                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());
            }

        }else{
            if (!empty(Post::adi())){

                $data = [
                    'adi'       => Post::adi(),
                    'renk'      => Post::renk()
                ];

                $kalemEkle = MasrafModel::anaKalemEkle($data);

                if($kalemEkle){
                    Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('masraf');
                }else{

                    Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

                }

            }else{
                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());
            }
        }



    }

    public function anaKalemGuncelle($id){

        if (!empty(Post::adi()) and $id==Post::id()){

            $data = [
                'id'       => Post::id(),
                'adi'       => Post::adi(),
                'renk'      => Post::renk()
            ];

            $kalemEkle = MasrafModel::anaKalemGuncelle($data);


            if($kalemEkle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('masraf');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }else{

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Gerekli alanları doldurunuz!.</div></div>'])->action(URL::prev());

        }

    }

    public function altKalemEkle()
    {
        if (!empty(Post::adi())) {

            if (!empty(Post::adi())){

                $data = [
                    'ust'       => Post::ust(),
                    'adi'       => Post::adi(),
                    'renk'      => Post::renk()
                ];

                $kalemEkle = MasrafModel::altKalemEkle($data);

                if($kalemEkle){
                    Redirect::insert(['bilgi'=>'<div class="callout callout-success">Masraf Kalemi başarı ile eklendi!</div>'])->action('masraf');
                }else{
                    Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Masraf Kalemi ekleme işlemi yapılamadı!</div>'])->action('masraf');
                }

            }else{
                Redirect::insert(['bilgi'=>'<div class="callout callout-warning">Gerekli alanları doldurunuz!</div>'])->action('masraf');
            }

        }


    }

    public function altKalemGuncelle($id){

        if (!empty(Post::adi()) and $id==Post::id()){

            $data = [
                'id'       => Post::id(),
                'ust'       => Post::ust(),
                'adi'       => Post::adi(),
                'renk'      => Post::renk()
            ];

            $kalemEkle = MasrafModel::altKalemGuncelle($data);


            if($kalemEkle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('masraf');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }else{

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Gerekli alanları doldurunuz!.</div></div>'])->action(URL::prev());

        }

    }

    public function kalemSil($id){

        $sil = MasrafModel::kalemSil($id);

        if($sil){
            Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Silme İşlemi Yapıldı !.</div></div>'])->action('masraf');
        }else{

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Silme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

        }

    }

    public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

            /*********************************************************/

            case "masrafSil":

                $sil = MasrafModel::giderSil($dataId);

                $data['title'] = "Gider Silme İşlemi";

                if($sil){

                    $kasaKaydinikaldir = KasaModel::odemeKaldir("masraf",$dataId);

                    $data['success'] = 'Silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = URL::site('masraf/tumKayitlar');

                }else{

                    $data['error'] = "Silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            /*********************************************************/


        }

    }

    public function s404()
    {
    }
}