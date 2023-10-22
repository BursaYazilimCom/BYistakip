<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Date;
use InternalPersonelModel as PersonelModel,AyarModel;

class BolgeselAyarlar extends Controller
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



    }

    public function diller(){

        $diller = AyarModel::diller();
        $yukluDiller = Folder::files('../Uploads/flag/', 'svg', false);

        View::diller($diller);
        View::yukluDiller($yukluDiller);

    }

    public function ulkeler(){

        $ulkeler = AyarModel::ulkeler(25);

        View::ulkeler($ulkeler);

    }

    public function sehirler(){

        $sehirler = AyarModel::sehirler(25);
        $ulkeler = AyarModel::ulkeler(500);

        View::ulkeler($ulkeler);
        View::sehirler($sehirler);

    }

    public function ilceler($id){

        $ilceler = AyarModel::ilceler($id);
        $ilAdi = AyarModel::sehirAdi($id);

        View::ilceler($ilceler);
        View::ilAdi($ilAdi);
        View::id($id);

    }

    public function mahalleler(){

        $mahalleler = AyarModel::mahalleler(25);
        $ilceler = AyarModel::ilceler(1000);

        View::ilceler($ilceler);
        View::mahalleler($mahalleler);

    }

    public function paraBirimleri(){

        $paraBirimleri = AyarModel::paraBirimleri(25);

        View::paraBirimleri($paraBirimleri);

    }
    




        public function ajax():void
    {
        $user       = User::data();
        $dataAction = Post::dataAction();
        $dataId     = Post::dataId();
        $data       = [];

        switch ($dataAction){


            case "dilSil":

                $sil = AyarModel::dilSil($dataId);

                $data['title'] = "Dil Silme İşlemi";

                if($sil){

                    $data['success'] = 'Dil Alanı silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/BolgeselAyarlar/diller';

                }else{

                    $data['error'] = "Dil silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "dilEkle":

                $data['title'] = "Dil Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'baslik'    =>Post::baslik(),
                        'kod'      =>Post::kod(),
                        'image'     =>Post::image(),
                        'sira'      =>Post::sira(),
                        'durum'     =>Post::durum()
                    ];

                    $ekle = AyarModel::dilEkle($ekleData);

                    if($ekle){

                        $data['success'] = 'Dil Alanı ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                            <td>'.$ekle.'</td>
                                            <td>'.Post::baslik().'</td>
                                            <td>'.Post::kod().'</td>
                                            <td>'.Post::image().'</td>
                                            <td>'.Post::sira().'</td>
                                            <td>'.Post::durum().'</td>
                                            <td>
                                                <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('BolgeselAyarlar/ajax').'\',\'dilSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                            </td>
                                        </tr>';
                        $data['modalClose'] = "modals-add";



                    }else{

                        $data['error'] = "Dil ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "dilGuncelle":

                $data['title'] = "Dil Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

                    $updateData = [
                        'id'        =>$id,
                        'baslik'    =>Post::baslik(),
                        'kod'      =>Post::kod(),
                        'image'     =>Post::image(),
                        'sira'      =>Post::sira(),
                        'durum'     =>Post::durum()
                    ];

                    $guncelle = AyarModel::dilGuncelle($updateData);

                    if($guncelle){

                        $data['success'] = 'Dil Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('BolgeselAyarlar/diller');
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Dil Guncelleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;


            /*********************************************************/

            case "sehirSil":

                $sil = AyarModel::sehirSil($dataId);

                $data['title'] = "Şehir Silme İşlemi";

                if($sil){

                    $data['success'] = 'Şehir silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/BolgeselAyarlar/sehirler';

                }else{

                    $data['error'] = "Şehir silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "sehirEkle":

                $data['title'] = "Şehir Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'il'    =>Post::il(),
                        'plaka' =>Post::plaka(),
                        'siralama'  =>Post::siralama(),
                        'kod'    =>Post::kod()
                    ];

                    $ekle = AyarModel::sehirEkle($ekleData);

                    if($ekle){

                        $data['success'] = 'Şehir ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                                <td>'.$ekle.'</td>
                                                <td>'.Post::il().'</td>
                                                <td>'.Post::plaka().'</td>
                                                <td>'.Post::siralama().'</td>
                                                <td>'.Post::kod().'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('BolgeselAyarlar/ajax').'\',\'sehirSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Ülke ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "sehirGuncelle":

                $data['title'] = "Şehir Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

                    $updateData = [
                        'id'        =>$id,
                        'il'    =>Post::il(),
                        'plaka' =>Post::plaka(),
                        'siralama'  =>Post::siralama(),
                        'kod'    =>Post::kod()
                    ];

                    $guncelle = AyarModel::sehirGuncelle($updateData);

                    if($guncelle){

                        $data['success'] = 'Şehir Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('BolgeselAyarlar/sehirler');
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Ülke Guncelleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            /*********************************************************/

            case "ilceSil":

                $sil = AyarModel::ilceSil($dataId);

                $data['title'] = "İlçe Silme İşlemi";

                if($sil){

                    $data['success'] = 'İlçe silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/BolgeselAyarlar/ilceler';

                }else{

                    $data['error'] = "İlçe silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "ilceEkle":

                $data['title'] = "İlçe Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'il'        =>Post::il(),
                        'ilce'      =>Post::ilce(),
                        'oylesine'  =>Post::ilAdi()
                    ];

                    $ekle = AyarModel::ilceEkle($ekleData);

                    $hizmet = Post::hizmet()=="1"?"Veriliyor":"Verilmiyor  ";

                    if($ekle){

                        $data['success'] = 'İlçe ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                                <td>'.$ekle.'</td>
                                                <td>'.Post::ilce().'</td>
                                                <td>'.Post::ilAdi().'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('BolgeselAyarlar/ajax').'\',\'ilceSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "İlçe ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "ilceGuncelle":

                $data['title'] = "İlçe Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

                    $updateData = [
                        'id'            =>$id,
                        'il'        =>Post::il(),
                        'ilce'      =>Post::ilce(),
                        'oylesine'  =>Post::ilAdi()
                    ];

                    $guncelle = AyarModel::ilceGuncelle($updateData);

                    if($guncelle){

                        $data['success'] = 'İlçe Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('BolgeselAyarlar/ilceler/'.Post::il());
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "İlçe Guncelleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            /*********************************************************/

            case "mahalleSil":

                $sil = AyarModel::mahalleSil($dataId);

                $data['title'] = "Mahalle Silme İşlemi";

                if($sil){

                    $data['success'] = 'Mahalle silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/BolgeselAyarlar/ilceler';

                }else{

                    $data['error'] = "İlçe silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "mahalleEkle":

                $data['title'] = "Mahalle Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'mahalle_adi'      =>Post::mahalle_adi(),
                        'mahalle_key'      =>Post::mahalle_key(),
                        'ilce_key'        =>Post::ilce_key(),
                        'hizmet'        =>Post::hizmet()
                    ];

                    $ekle = AyarModel::mahalleEkle($ekleData);

                    $hizmet = Post::hizmet()=="1"?"Veriliyor":"Verilmiyor  ";

                    if($ekle){

                        $data['success'] = 'İlçe ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                                <td>'.$ekle.'</td>
                                                <td>'.Post::mahalle_adi().'</td>
                                                <td>'.Post::mahalle_key().'</td>
                                                <td>'.Post::ilce_key().'</td>
                                                <td>'.$hizmet.'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('BolgeselAyarlar/ajax').'\',\'mahalleSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Mahalle ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "mahalleGuncelle":

                $data['title'] = "Mahalle Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

                    $updateData = [
                        'id'            =>$id,
                        'mahalle_adi'      =>Post::mahalle_adi(),
                        'mahalle_key'      =>Post::mahalle_key(),
                        'ilce_key'        =>Post::ilce_key(),
                        'hizmet'        =>Post::hizmet()
                    ];

                    $guncelle = AyarModel::mahalleGuncelle($updateData);

                    if($guncelle){

                        $data['success'] = 'Mahalle Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('BolgeselAyarlar/mahalleler');
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Mahalle Guncelleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            /*********************************************************/

            case "paraBirimiSil":

                $sil = AyarModel::paraBirimiSil($dataId);

                $data['title'] = "Para Birimi Silme İşlemi";

                if($sil){

                    $data['success'] = 'Para Birimi silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/BolgeselAyarlar/paraBirimleri';

                }else{

                    $data['error'] = "Para Birimi silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "paraBirimiEkle":

                $data['title'] = "Para Birimi Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'para'          =>Post::para(),
                        'kod'           =>Post::kod(),
                        'sembol'        =>Post::sembol(),
                        'guncel_kur'    =>Post::guncel_kur(),
                        'guncelleme'    =>Date::now()
                    ];

                    $ekle = AyarModel::paraBirimiEkle($ekleData);

                    if($ekle){

                        $data['success'] = 'Para Birimi ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                                <td>'.$ekle.'</td>
                                                <td>'.Post::para().'</td>
                                                <td>'.Post::kod().'</td>
                                                <td>'.Post::sembol().'</td>
                                                <td>'.Post::guncel_kur().'</td>
                                                <td>'.Post::guncelleme().'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('BolgeselAyarlar/ajax').'\',\'sehirSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Para Birimi ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "paraBirimiGuncelle":

                $data['title'] = "Para Birimi Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

                    $updateData = [
                        'id'        =>$id,
                        'para'          =>Post::para(),
                        'kod'           =>Post::kod(),
                        'sembol'        =>Post::sembol(),
                        'guncel_kur'    =>Post::guncel_kur(),
                        'guncelleme'    =>Date::now()
                    ];

                    $guncelle = AyarModel::paraBirimiGuncelle($updateData);

                    if($guncelle){

                        $data['success'] = 'Para Birimi Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('BolgeselAyarlar/paraBirimleri');
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Para Birimi Guncelleme işlemi yapılamadı!";

                    }

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