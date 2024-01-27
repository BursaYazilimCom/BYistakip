<?php namespace Project\Controllers;


use User,Method,Post,Redirect,Json,URL,Validation,Converter,Security;
use InternalUrunModel as UrunModel,AyarModel,InternalTedarikciModel as TedarikciModel;

class Urun extends Controller
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
        $listeData = UrunModel::liste();

        View::listele($listeData);


    }


    public function form($id=""){
        $gruplar = UrunModel::urunGrupListe();
        $paraBirimleri = AyarModel::paraBirimleri();
        $tedarikciler = TedarikciModel::tumListe();

        if($id){
            $data = UrunModel::detay($id);

            View::detay($data);
            View::action("urun/update/".$id);

        }else{
            $data = (object)[
                'id'            =>'',
                'tedarikci'     =>'',
                'urun_kodu'     =>'',
                'grup'          =>'',
                'grupId'          =>'',
                'adi'           =>'',
                'fiyat'         =>'0.0000',
                'aylik_fiyat'         =>'0.0000',
                'uc_aylik_fiyat'         =>'0.0000',
                'alti_aylik_fiyat'         =>'0.0000',
                'yillik_fiyat'         =>'0.0000',
                'fiyat_birim'   =>'TL',
                'kdv'           =>'',
                'aciklama'      =>'',
                'detay'         =>'',
                'durum'         =>'1',
                'odeme_turu'    =>'T',
                'stoklu_urun'   =>'0',
                'guncel_stok'   =>'0',

            ];
            View::detay($data);

            View::action("urun/ekle");
        }

        View::tedarikciler($tedarikciler);
        View::gruplar($gruplar);
        View::paraBirimleri($paraBirimleri);

    }

    public function ekle(){

        if(empty(Post::adi()) or empty(Post::grup()) or empty(Post::fiyat()) or empty(Post::kdv())){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu ! Lütfen giriş yaptığınız bilgileri klontrol edin.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else{

            $ekleData = [
                'urun_kodu'         =>Post::urun_kodu(),
                'tedarikci'         =>Post::tedarikci(),
                'grup'              =>Post::grup(),
                'adi'               =>Post::adi(),
                'fiyat'             =>Post::fiyat()==""?"0.0000":Post::fiyat(),
                'aylik_fiyat'       =>Post::aylik_fiyat()==""?"0.0000":Post::aylik_fiyat(),
                'uc_aylik_fiyat'    =>Post::uc_aylik_fiyat()==""?"0.0000":Post::uc_aylik_fiyat(),
                'alti_aylik_fiyat'  =>Post::alti_aylik_fiyat()==""?"0.0000":Post::alti_aylik_fiyat(),
                'yillik_fiyat'      =>Post::yillik_fiyat()==""?"0.0000":Post::yillik_fiyat(),
                'fiyat_birim'       =>Post::fiyat_birim(),
                'kdv'               =>Post::kdv(),
                'aciklama'          =>Security::htmlEncode(Post::aciklama()),
                'detay'             =>Security::htmlEncode(Post::detay()),
                'durum'             =>Post::durum(),
                'odeme_turu'        =>Post::odeme_turu(),
                'stoklu_urun'       =>Post::stoklu_urun(),
                'guncel_stok'       =>Post::stoklu_urun()=="0"?"0":Post::guncel_stok(),
            ];

            $ekle = UrunModel::ekle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('urun');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function update($id){

        if(!Validation::check()){


            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else{

            $ekleData = [
                'id'                =>$id,
                'tedarikci'         =>Post::tedarikci(),
                'urun_kodu'         =>Post::urun_kodu(),
                'grup'              =>Post::grup(),
                'adi'               =>Post::adi(),
                'fiyat'             =>Post::fiyat()==""?"0.0000":Post::fiyat(),
                'aylik_fiyat'       =>Post::aylik_fiyat()==""?"0.0000":Post::aylik_fiyat(),
                'uc_aylik_fiyat'    =>Post::uc_aylik_fiyat()==""?"0.0000":Post::uc_aylik_fiyat(),
                'alti_aylik_fiyat'  =>Post::alti_aylik_fiyat()==""?"0.0000":Post::alti_aylik_fiyat(),
                'yillik_fiyat'      =>Post::yillik_fiyat()==""?"0.0000":Post::yillik_fiyat(),
                'fiyat_birim'       =>Post::fiyat_birim(),
                'kdv'               =>Post::kdv(),
                'aciklama'          =>Security::htmlEncode(Post::aciklama()),
                'detay'             =>Security::htmlEncode(Post::detay()),
                'durum'             =>Post::durum(),
                'odeme_turu'        =>Post::odeme_turu(),
                'stoklu_urun'       =>Post::stoklu_urun(),
                'guncel_stok'       =>Post::guncel_stok()
            ];

            $ekle = UrunModel::guncelle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('urun/form/'.$id);
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function gruplar()
    {
        $listeData = UrunModel::urunGrupListe();

        View::listele($listeData);


    }

        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){

                /*********************************************************/

            case "grupSil":

                $sil = UrunModel::urunGrupSil($dataId);

                $data['title'] = "Grup Silme İşlemi";

                if($sil){

                    $data['success'] = 'Grup silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/urun/gruplar';

                }else{

                    $data['error'] = "Grup silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "grupEkle":

                $data['title'] = "Grup Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'adi'           =>Post::adi(),
                        'sira'          =>Post::sira(),
                        'durum'         =>Post::durum()
                    ];

                    $ekle = UrunModel::urunGrupEkle($ekleData);

                    $durum = Post::durum()=="1"?"Aktif":"Pasif  ";

                    if($ekle){

                        $data['success'] = 'Ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                                <td>'.$ekle.'</td>
                                                <td>'.Post::sira().'</td>
                                                <td>'.Post::adi().'</td>
                                                <td>'.$durum.'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('urun/ajax').'\',\'grupSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "grupGuncelle":

                $data['title'] = "Grup Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

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

                    $guncelle = UrunModel::urunGrupGuncelle($updateData);

                    if($guncelle){

                        $data['success'] = 'Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('urun/gruplar');
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Guncelleme işlemi yapılamadı!";

                    }

                }

                echo Json::encode($data);


                break;

            case "urunOdemePeriodlari":



                break;

            /*********************************************************/


        }

    }

    
    public function s404()
    {

    }
}