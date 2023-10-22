<?php namespace Project\Controllers;

use User,Method,Post,Request,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Security,File;
use InternalFirmaModel as FirmaModel,AyarModel,InternalUyeModel as UyeModel,InternalSektorModel as SektorModel,InternalKategoriModel as KategoriModel,InternalOsgbModel as OsgbModel, InternalBelgeModel as BelgeModel;

class Firmalar extends Controller
{

    public function __construct()
    {
        $user                   = User::data();
        $yetkiler               = \Json::decode($user->yetkiler);

        if(!in_array(CURRENT_CONTROLLER,$yetkiler)){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger"><div class="alert-body">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div></div>'])->action('home');

        }
    }

    public function main()
    {

        $listele = FirmaModel::liste();

        View::listele($listele);

    }
    
    public function update($id){

        $data['title'] = "Firma Güncelleme";

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            if($id!=Post::id()){

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger"><div class="alert-body">Beklenmeyen bir hata ile karşılaşıldı, Lütfen herşeyi doğru yaptığınıza emin olun!</div></div>'])->action('firmalar/form/'.$id);

            }

            $detay = FirmaModel::detay($id);


            $guncelData = [
                'id'                =>$id,
                'firma_adi'         =>Post::firma_adi(),
                'firma_sektor'      =>Post::firma_sektor(),
                'firma_adi'         =>Post::firma_adi(),
                'faaliyet_alani'    =>Post::faaliyet_alani(),
                'firma_email'       =>Post::firma_email(),
                'firma_yetkili'     =>Post::firma_yetkili(),
                'firma_yetkili_gsm' =>Post::firma_yetkili_gsm(),
                'firma_vd'          =>Post::firma_vd(),
                'firma_vn'          =>Post::firma_vn(),
                'firma_sgk_sicil'   =>Post::firma_sgk_sicil(),
                'firma_fatura_adresi'=>Post::firma_fatura_adresi(),
                'il'                =>Post::il(),
                'ilce'              =>Post::ilce(),
                'firma_sube'        =>Post::firma_sube(),
                'tehlike_sinifi'    =>Post::tehlike_sinifi(),
                'uname'             =>Post::kullaniciAdi(),
                'upass'             =>Post::upass()==""?$detay->upass:Encode::super(Post::upass()),
                'durum'             =>Post::durum(),

            ];

            $guncelle = FirmaModel::update($guncelData);

            if($guncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Bilgiler başarı ile güncellendi !.</div></div>'])->action('firmalar/form/'.Post::id());
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Bilgi güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('firmalar/form/'.Post::id());

            }

        }

        print_r($data);
        
    }

    public function form($id=""){

        $subeler = OsgbModel::birimlerTumListe();
        $uyeler = UyeModel::tumListe();
        $sektorler = SektorModel::tumListe();
        $sehirler = AyarModel::sehirler(1000);
        $ilceler = AyarModel::ilceleri(AyarModel::defaultAyarlar('varsayilanSehir'));


        View::uyeler($uyeler);
        View::subeler($subeler);
        View::sektorler($sektorler);
        View::sehirler($sehirler);
        View::ilceler($ilceler);

        if($id){
            $data = FirmaModel::detay($id);

           View::detay($data);
           View::action("firmalar/update/".$id);

        }else{

            $data = (object)[
                'id'                =>"",
                'firma_adi'         =>"",
                'firma_sektor'      =>"",
                'firma_adi'         =>"",
                'faaliyet_alani'    =>"",
                'firma_email'       =>"",
                'firma_yetkili'     =>"",
                'firma_yetkili_gsm' =>"",
                'firma_vd'          =>"",
                'firma_vn'          =>"",
                'firma_sgk_sicil'   =>"",
                'firma_fatura_adresi'=>"",
                'il'                =>"",
                'ilce'              =>"",
                'firma_sube'        =>"",
                'tehlike_sinifi'    =>"",
                'uname'             =>"",
                'upass'             =>"",
                'durum'             =>"1",
            ];
            View::detay($data);
            View::action("firmalar/add");

        }



    }

    public function add(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{


            $ekleData = [
                'firma_adi'         =>Post::firma_adi(),
                'firma_sektor'      =>Post::firma_sektor(),
                'firma_adi'         =>Post::firma_adi(),
                'faaliyet_alani'    =>Post::faaliyet_alani(),
                'firma_email'       =>Post::firma_email(),
                'firma_yetkili'     =>Post::firma_yetkili(),
                'firma_yetkili_gsm' =>Post::firma_yetkili_gsm(),
                'firma_vd'          =>Post::firma_vd(),
                'firma_vn'          =>Post::firma_vn(),
                'firma_sgk_sicil'   =>Post::firma_sgk_sicil(),
                'firma_fatura_adresi'=>Post::firma_fatura_adresi(),
                'il'                =>Post::il(),
                'ilce'              =>Post::ilce(),
                'firma_sube'        =>Post::firma_sube(),
                'tehlike_sinifi'    =>Post::tehlike_sinifi(),
                'uname'             =>Post::uname(),
                'upass'             =>Encode::super(Post::upass()),
                'durum'             =>Post::durum(),

            ];

            $ekle = FirmaModel::ekle($ekleData);


            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('firmalar');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function delete($id){

        $sil = FirmaModel::delete($id);

        if($sil){

            Redirect::insert(['bilgi'=>'<div class="callout callout-success">Personel silme işlemi başarı ile yapıldı!</div>'])->action('firmalar');

        }else{

            Redirect::insert(['bilgi'=>'<div class="callout callout-danger">Personel silme işlemi yapılamadı!</div>'])->action('firmalar');

        }

    }

    public function belgeleri($id){

        $belgeler       = BelgeModel::liste();
        $data           = FirmaModel::detay($id);
        $firmaBelgeleri = BelgeModel::firmaTanimliBelgeler($id);

        $fbelgeler = [];
        foreach ($firmaBelgeleri as $fb) {
            $fbelgeler[] = $fb->belge_id;
        }

        View::belgeler($belgeler);
        View::firmaBelgeleri($fbelgeler);
        View::detay($data);


    }

    public function belgeTananimla($id){

        $belgeTemizle = BelgeModel::firmabelgeTemizle($id);
        $belge = Post::belge();
        $fiyat = Post::fiyat();
        $kimOdeyecek = Post::kim_odeyecek();

        $belgeSayisi = count($belge);

        for ($i = 0; $i < $belgeSayisi; $i++) {

            $belgeNo = $belge[$i];
            $fiyatTL = $fiyat[$belgeNo];
            $odeyecek = $kimOdeyecek[$belgeNo];

            $tananimla = BelgeModel::firmaBelgeTanimla([
                'firma_id'      => $id,
                'belge_id'      => $belgeNo,
                'fiyat'         => $fiyatTL,
                'kim_odeyecek'  => $odeyecek
            ]);

        }

        //$tananimla = BelgeModel::firmaBelgeTanimla($id);

        //View::tananimla($tananimla);

    }
    public function search($aranacak=""){

        $aranacak = Request::ara();

        $listele = FirmaModel::ara($aranacak);

        View::listele($listele);

    }

        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){



            case "firmaSil":

                $sil = FirmaModel::delete($dataId);

                $data['title'] = "Firma Silme İşlemi";

                if($sil){

                    $data['success'] = 'Firma silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = 'firmalar';

                }else{

                    $data['error'] = "Firma silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

        }


    }


    
    public function s404()
    {

    }
}