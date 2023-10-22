<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter;
use InternalSektorModel as SektorModel,AyarModel;

class Sektorler extends Controller
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
        $listeData = SektorModel::liste();

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

            case "sektorSil":

                $sil = SektorModel::delete($dataId);

                $data['title'] = "Sektör Silme İşlemi";

                if($sil){

                    $data['success'] = 'Sektör silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/sektorler';

                }else{

                    $data['error'] = "Sektör silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "sektorEkle":

                $data['title'] = "Sektor Ekleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $ekleData = [
                        'sektor_adi'                  =>Post::sektor_adi(),
                        'sektor_sef'            =>Converter::urlWord(Post::sektor_adi()),
                        'durum'                 =>Post::durum()
                    ];

                    $ekle = SektorModel::ekle($ekleData);

                    $postaKodu = Post::posta_kodu_gerekliligi()=="1"?"Gerekli":"Degil";
                    $durum = Post::durum()=="1"?"Aktif":"Pasif  ";

                    if($ekle){

                        $data['success'] = 'Ekleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = '';
                        $data['addData'] = '<tr id="row-'.$ekle.'">
                                                <td>'.$ekle.'</td>
                                                <td>'.Post::sektor_adi().'</td>
                                                <td>'.Converter::urlWord(Post::sektor_adi()).'</td>
                                                <td>'.$durum.'</td>
                                                <td>
                                                    <a href="javascript:;" onclick="deleteAction(\''.$ekle.'\',\''.URL::site('Sektorler/ajax').'\',\'sektorSil\')" class="btn btn-sm btn-danger py-0">Sil</a>
                                                </td>
                                            </tr>';
                        $data['modalClose'] = "modals-add";

                    }else{

                        $data['error'] = "Ekleme işlemi yapılamadı!";

                    }

                }


                echo Json::encode($data);


                break;

            case "sektorGuncelle":

                $data['title'] = "Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

                    $updateData = [
                        'id'                    =>$id,
                        'sektor_adi'                  =>Post::sektor_adi(),
                        'sektor_sef'            =>Converter::urlWord(Post::sektor_adi()),
                        'durum'                 =>Post::durum()
                    ];

                    $guncelle = SektorModel::update($updateData);

                    if($guncelle){

                        $data['success'] = 'Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('Sektorler');
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