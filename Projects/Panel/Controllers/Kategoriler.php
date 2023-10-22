<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter;
use InternalKategoriModel as KategoriModel,AyarModel;

class Kategoriler extends Controller
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
        $listeData = KategoriModel::liste();

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

            case "kategoriSil":

                $sil = KategoriModel::delete($dataId);

                $data['title'] = "Kategori Silme İşlemi";

                if($sil){

                    $data['success'] = 'Kategori silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/kategoriler';

                }else{

                    $data['error'] = "Kategori silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "kategoriEkle":

                $data['title'] = "Kategori Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'adi'            =>Post::adi(),
                        'sef'            =>Converter::urlWord(Post::adi()),
                        'title'          =>Post::title(),
                        'icon'           =>Post::icon(),
                        'aciklama'       =>Post::aciklama(),
                        'sira'           =>Post::sira(),
                        'anasayfa'       =>Post::anasayfa(),
                        'durum'          =>Post::durum()
                    ];

                    $ekle = KategoriModel::ekle($ekleData);

                    $durum = Post::durum()=="1"?"Aktif":"Pasif  ";
                    $anasayfa = Post::anasayfa()=="1"?"Gösteriliyor":"Gizli  ";

                    if($ekle){

                        $data['success'] = 'Ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                                <td>'.$ekle.'</td>
                                                <td>'.Post::adi().'</td>
                                                <td>'.Converter::urlWord(Post::adi()).'</td>
                                                <td>'.Post::title().'</td>
                                                <td>'.Post::icon().'</td>
                                                <td>'.Post::aciklama().'</td>
                                                <td>'.Post::sira().'</td>
                                                <td>'.$anasayfa.'</td>
                                                <td>'.$durum.'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('kategoriler/ajax').'\',\'kategoriSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "kategoriGuncelle":

                $data['title'] = "Guncelleme İşlemi";

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

            /*********************************************************/


        }

    }

    
    public function s404()
    {

    }
}