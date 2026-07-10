<?php namespace Project\Controllers;


use User,Method,Post,Redirect,Json,URL,Validation,Converter,Security,Upload,Email,SmsModel;
use InternalDestekModel as DestekModel,AyarModel,InternalCariModel as CariModel,InternalPersonelModel as PersonelModel;

class Destek extends Controller
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
        $listeData = DestekModel::liste();

        View::listele($listeData);


    }

    public function departman($id,$sayfa=0)
    {
        if(!is_numeric($id)){
            redirect('urun');
        } 

        $listeData = DestekModel::departmanListe($id,$sayfa=0);

        View::listele($listeData);


    }


    public function form($id=""){
        $departmanlar       = DestekModel::departmanlar();
        $musteriler         = CariModel::tumListe();
        $destekMesajlari    = DestekModel::mesajlar($id);

        if($id){

            $data = DestekModel::detay($id);
            View::detay($data);
            View::action("destek/update/".$id);

        }else{
            $data = (object)[
                'id'            =>'',
                'musteri'       =>'',
                'departman'     =>'',
                'konu'          =>'',
                'mesaj'          =>'',
                'durum'         =>'1',

            ];
            View::detay($data);

            View::action("destek/ekle");
        }

        View::departmanlar($departmanlar);
        View::destekMesajlari($destekMesajlari);
        View::musteriler($musteriler);

    }

    public function ekle(){

        if(empty(Post::musteri()) or empty(Post::departman()) or empty(Post::konu()) or empty(Post::mesaj())){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu ! Lütfen giriş yaptığınız bilgileri kontrol edin.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else{

            $ekleData = [
                'musteri'           =>Post::musteri(),
                'departman'         =>Post::departman(),
                'konu'              =>Security::htmlEncode(Post::konu()),
                'mesaj'             =>Security::htmlEncode(Post::mesaj()),
                'durum'             =>Post::durum()
            ];

            $ekle = DestekModel::ekle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('destek');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function update($id){

        if(!is_numeric(Post::musteri()) or !is_numeric(Post::departman()) or !is_numeric(Post::durum())){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

        }else{

            $ekleData = [
                'id'                =>$id,
                'musteri'           =>Post::musteri(),
                'departman'         =>Post::departman(),
                'durum'             =>Post::durum()
            ];

            $ekle = DestekModel::guncelle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('destek/form/'.$id);
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function departmanEkle() {

        if(empty(Post::adi()) or empty(Post::yetkili_personel()) or empty(Post::durum())){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu ! Lütfen gerekli alanları doldurunuz.</div></div>'])->action('destek/departmanlar');

        }else{

            $ekleData = [
                'adi'               =>Post::adi(),
                'yetkili_personel'  =>Post::yetkili_personel(),
                'durum'             =>Post::durum()
            ];
    
            $ekle = DestekModel::departmanEkle($ekleData);

            if($ekle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Ekleme İşlemi Yapıldı !.</div></div>'])->action('destek/departmanlar');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu !.</div></div>'])->action('destek/departmanlar');

            }

        }
        

       

    }

    public function departmanGuncelle($id) {

        if(empty(Post::adi()) or empty(Post::yetkili_personel())){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu ! Lütfen gerekli alanları doldurunuz.</div></div>'])->action('destek/departmanlar');

        }else{

            $updateData = [
                'id'                =>$id,
                'adi'               =>Post::adi(),
                'yetkili_personel'  =>Post::yetkili_personel(),
                'durum'             =>Post::durum()
            ];

            $guncelle = DestekModel::departmanGuncelle($updateData);


            if($guncelle){
                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('destek/departmanlar');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action('destek/departmanlar');

            }

        }
        
    }

    public function departmanlar()
    {
        $listeData = DestekModel::departmanlar();
        $personeller = PersonelModel::liste();

        View::listele($listeData);
        View::personeller($personeller);

    }

    public function cevapla($id){
        $talepDetay = DestekModel::detay($id);

        $cariDetay = CariModel::detay($talepDetay->musteri);

        $user = User::data();

        if(empty(Post::cevap())){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Cevap yazmadınız.Bilgileri kontrol edin.<br>'.str_replace('<br>',EOL,Validation::error('string')).'</div></div>'])->action(URL::prev());

        }else{

            if(Upload::isFile('dosya')) {

                Upload::convertName()
                    ->source('dosya')
                    ->target(REAL_BASE_DIR . 'Uploads/destek-talep/')
                    ->start();
                $dosyaBilgi = Upload::info();
    
                $dosya = $dosyaBilgi->encodeName;
            }else{
                $dosya = "";
            }

            $ekleData = [
                'talep_id'      =>$id,
                'gonderen_id'   =>$user->id,
                'gonderen'      =>'2',
                'mesaj'         =>Security::htmlEncode(Post::cevap()),
                'dosya_eki'     =>$dosya
            ];

            $ekle = DestekModel::mesajEkle($ekleData);

            if($ekle){


                $mailgonder = Email::subject('Destek Talebiniz Cevaplandı')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to($cariDetay->email)->template('by', [

                    'konu' => 'Destek Talebiniz Cevaplandı',
                    'mesaj' => $talepDetay->konu.' Konulu destek talebiniz cevaplandı.<br> Destek talep mesajını görmek ve yanıtlamak için aşağıdaki bağlantıyı tıklayınız.<br><br<hr>',
                    'link' => AyarModel::defaultAyarlar('siteUrl')."/destek/".$id,
                    'link_baslik' => 'Deste talebini Görüntülemek için Tıklayınız',
                    'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                    'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
                    'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                    'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                    'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                ])->send();
        
                //SMS GÖNDERİMİ
                if(AyarModel::defaultAyarlar('smsGonderim')=="1"){
        
                    $smsData = [
                        'mesaj'=>$talepDetay->konu.' Konulu talebiniz cevaplandı. Talebinizi incelemek için '.AyarModel::defaultAyarlar('siteUrl').' giriş yapıp Taleplerim bölümünden görüntüleyebilirsiniz',
                        'numara'=>$cariDetay->gsm
                    ];
        
                    $smsGonder = SmsModel::gonder(AyarModel::defaultAyarlar('smsEntegreFirma'),$smsData);
        
                }
                //SMS GÖNDERİMİ

                $durumDegistir = DestekModel::talepDurumDegistir(['id'=>$id,'durum'=>'2']);


                AyarModel::basarili("Tekrikler",'Destek Laebi Başarı ile cevaplandi',URL::site('destek/form/'.$id));
            
            }else{

                AyarModel::basarisiz("HATA",'Destek Laebi sırasında hata oluştu !',URL::site('destek/form/'.$id));
            
            }

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

            case "departmanSil":

                $sil = DestekModel::departmanSil($dataId);

                $data['title'] = "Departman Silme İşlemi";

                if($sil){

                    $data['success'] = 'Departman silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '/destek/departmanlar';

                }else{

                    $data['error'] = "Grup silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "departmanGuncelle":

                $data['title'] = "Departman Guncelleme İşlemi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{
                    $id = Post::update_id();

                    $updateData = [
                        'id'             =>$id,
                        'adi'               =>Post::adi(),
                        'yetkili_personel'  =>Post::yetkili_personel(),
                        'durum'             =>Post::durum()
                    ];

                    $guncelle = DestekModel::departmanGuncelle($updateData);

                    if($guncelle){

                        $data['success'] = 'Guncelleme işlemi başarı ile yapıldı!';
                        $data['redirect'] = URL::site('destek/departmanlar');
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