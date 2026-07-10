<?php namespace Project\Controllers;


use User,Method,Post,Redirect,Json,URL,Date,Validation,Converter,Security,Upload,Email,SmsModel;
use InternalDestekModel as DestekModel,AyarModel,InternalCariModel as CariModel,InternalPersonelModel as PersonelModel;

class Destek extends Controller
{

    public function main()
    {
        $user = User::data();
        $listeData = DestekModel::liste($user->id,$sayfa=null);

        View::listele($listeData);

    }


    public function detay($id){

        $user = User::data();

        $detay              = DestekModel::detay($id);
        $destekMesajlari    = DestekModel::mesajlar($id);

        if($user->id!=$detay->musteri){
            redirect('home');
        }

        View::detay($detay);
        View::destekMesajlari($destekMesajlari);

    }

    public function form(){
        $departmanlar       = DestekModel::departmanlar();

        View::departmanlar($departmanlar);

    }

    public function ekle(){
        $user = User::data();

        if(empty(Post::departman()) or empty(Post::konu()) or empty(Post::mesaj())){

            Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Ekleme işlemi sırasında hata oluştu ! Lütfen giriş yaptığınız bilgileri kontrol edin.<br></div></div>'])->action('destek');

        }else{

            $ekleData = [
                'musteri'           =>$user->id,
                'departman'         =>Post::departman(),
                'konu'              =>Security::htmlEncode(Post::konu()),
                'mesaj'             =>Security::htmlEncode(Post::mesaj()),
                'durum'             =>'1'
            ];

            $ekle = DestekModel::ekle($ekleData);

            if($ekle){


                $mailgonder = Email::subject('Bir Destek Talebi Oluşturuldu')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to(AyarModel::defaultAyarlar('iletisimEposta'))->template('by', [

                    'konu' => 'Yeni bir Destek Talebi Oluşturuldu',
                    'mesaj' => $user->adi.' isimli müşteri yeni bir destek talebi oluşturdu.<br> Destek talebini görmek ve yanıtlamak için aşağıdaki bağlantıyı tıklayınız.<br><br<hr>',
                    'link' => AyarModel::defaultAyarlar('siteUrl')."/Panel/destek",
                    'link_baslik' => 'Deste talebini Görüntülemek için Tıklayınız',
                    'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                    'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
                    'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                    'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                    'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                ])->send();

                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Destek Talebiniz Başarı İle Alındı. En kısa süre içerisinde tarafınıza dönüş sağlanacaktır !.</div></div>'])->action('destek');
            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Destek talebiniz alınamadı, Lütfen tekrar deneyiniz hata devam ederse lütfen bizimle iletişime geçiniz.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function cevapla($id){

        $user = User::data();
        $talepDetay = DestekModel::detay($id);

        $cariDetay = CariModel::detay($talepDetay->musteri);

        if($user->id!=$cariDetay->id){
            redirect('home');
        }

        if(empty(Post::cevap())){

            AyarModel::basarisiz('Hata!','Cevap gönderme işlemi sırasında bir hata olutu.',URL::prev());

        }else{

            /*if(Upload::isFile('dosya')) {

                Upload::convertName()
                    ->source('dosya')
                    ->target(REAL_BASE_DIR . 'Uploads/destek-talep/')
                    ->start();
                $dosyaBilgi = Upload::info();
    
                $dosya = $dosyaBilgi->encodeName;
            }else{
                $dosya = "";
            }*/

            $ekleData = [
                'talep_id'      =>$id,
                'gonderen_id'   =>$user->id,
                'gonderen'      =>'1',
                'mesaj'         =>Security::htmlEncode(Post::cevap()),
                'dosya_eki'     =>''
            ];

            $ekle = DestekModel::mesajEkle($ekleData);

            if($ekle){


                $mailgonder = Email::subject('Bir Destek Talebi Cevaplandı')->from(AyarModel::defaultAyarlar('iletisimEposta'))->to(AyarModel::defaultAyarlar('iletisimEposta'))->template('by', [

                    'konu' => 'Bir Destek Talebiniz Cevaplandı',
                    'mesaj' => $talepDetay->konu.' Konulu '.$cariDetay->adi.' isimli müşteriye ait Destek talebi yeniden iletildi.<br> Destek talep mesajını görmek ve yanıtlamak için aşağıdaki bağlantıyı tıklayınız.<br><br<hr>',
                    'link' => AyarModel::defaultAyarlar('siteUrl')."/Panel/destek/form/".$id,
                    'link_baslik' => 'Deste talebini Görüntülemek için Tıklayınız',
                    'firma' => AyarModel::defaultAyarlar('firmaAdi'),
                    'firma_link' => AyarModel::defaultAyarlar('siteUrl'),
                    'hakkimizda'=> AyarModel::defaultAyarlar('siteKisaAciklama'),
                    'adres' => AyarModel::defaultAyarlar('firmaAdresi'),
                    'telefon' => AyarModel::defaultAyarlar('firmaTel'),
                ])->send();
        

                $durumDegistir = DestekModel::talepDurumDegistir(['id'=>$id,'durum'=>'3']);


                AyarModel::basarili("Tekrikler",'Destek Talebi Başarı ile cevaplandi',URL::site('destek/detay/'.$id));
            
            }else{

                AyarModel::basarisiz("HATA",'Destek Talebi sırasında hata oluştu !',URL::site('destek/detay/'.$id));
            
            }

        }

    }

    public function kapat($id) {
        $user = User::data();
        $detay              = DestekModel::detay($id);

        if($user->id!=$detay->musteri){
            redirect('home');
        }

        $kapat = DestekModel::talepDurumDegistir(['id'=>$id,'durum'=>'0']);

        if($kapat){
            AyarModel::basarili("Tekrikler",'Destek Talebi Kapatıldı',URL::site('destek/detay/'.$id));
        }else{
            AyarModel::basarisiz("HATA",'Destek Talebi sırasında hata oluştu !',URL::site('destek/detay/'.$id));
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

            /*********************************************************/


        }

    }

    
    public function s404()
    {

    }
}