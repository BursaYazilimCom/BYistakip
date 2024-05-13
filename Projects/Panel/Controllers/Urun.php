<?php namespace Project\Controllers;


use User,Method,Post,Redirect,Json,URL,Validation,Converter,Security,Upload;
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

    public function grup($id,$sayfa=0)
    {
        if(!is_numeric($id)){
            redirect('urun');
        } 

        $listeData = UrunModel::grupListe($id,$sayfa=0);

        View::listele($listeData);


    }

    public function grupForm($id=""){

        if($id){
            $data       = UrunModel::urunGrupDetay($id);
            $ozellikler = UrunModel::urunGrupOzellikleri($id);
            if (count($ozellikler) == 0) {

                $ozellikler = [];
            }

            View::detay($data);
            View::action("urun/grupGuncelle/".$id);

        }else{
            $data = (object)[
                'id'            =>'',
                'adi'           =>'',
                'aciklama'      =>'',
                'sira'          =>'',
                'durum'         =>'1'
            ];

            $ozellikler =(object) [];

            View::action("urun/grupEkle");
        }

        View::detay($data);
        View::ozellikler($ozellikler);

    }


    public function form($id=""){
        $gruplar = UrunModel::urunGrupListe();
        $paraBirimleri = AyarModel::paraBirimleri();
        $tedarikciler = TedarikciModel::tumListe();

        if($id){
            $data = UrunModel::detay($id);
            $urunOzellikleri = UrunModel::urunGrupOzellikleri($data->grupId);


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
            $urunOzellikleri = (object) [];
            View::detay($data);

            View::action("urun/ekle");
        }

        View::urunOzellikleri($urunOzellikleri);

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


            /*echo "<pre>";

            var_dump(Post::all());

            echo "</pre>";*/
            $ozellikler = Post::ozellik_id();
            $degerler   = Post::deger();

            for ($o=0; $o < count($ozellikler); $o++) {

                $ozellikId = $ozellikler[$o];

                $deger = $degerler[$ozellikId]==""?"":Security::htmlEncode($degerler[$ozellikId]);

                $ozellikDetay = UrunModel::urunGrupOzellikDetay($ozellikId);

                if($ozellikDetay->tur=="file"){

                    if(Upload::isFile('file_'.$ozellikId)){

                        Upload::mimes('application/pdf','application/zip','application/msword','application/rar','application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                            ->convertName()
                            ->encode('sha1')
                            ->encodeLength(10)
                            ->source('file_'.$ozellikId)
                            ->target(REAL_BASE_DIR . 'Uploads/urun-dosyalari/')
                            ->start();
                        $dosyaBilgi = Upload::info();
                        $deger   = $dosyaBilgi->encodeName;
                    }else{

                        $deger   = "";

                    }

                }

                if($ozellikDetay->tur=="image"){

                    if(Upload::isFile('image_'.$ozellikId)){

                        Upload::mimes('image/jpeg','image/png','image/gif')
                            ->convertName()
                            ->encode('sha1')
                            ->encodeLength(10)
                            ->source('image_'.$ozellikId)
                            ->target(REAL_BASE_DIR . 'Uploads/urun-dosyalari/')
                            ->start();
                        $dosyaBilgi = Upload::info();
                        $deger   = $dosyaBilgi->encodeName;
                    }else{

                        $deger   = "";

                    }

                }

                if($deger!=""){
                    //Özellike ekle
                    $ozellikData = [
                        'urun' => $ekle,
                        'ozellik' => $ozellikId,
                        'deger' => $deger
                    ];
                    $ozellikEkle = UrunModel::urunOzellikEkle($ozellikData);
                }

            }

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

            $updateData = [
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

            $update = UrunModel::guncelle($updateData);

            /*echo "<pre>";

            var_dump(Post::all());

            echo "</pre>";*/
            $ozellikler = Post::ozellik_id();
            $degerler   = Post::deger();

            for ($o=0; $o < count($ozellikler); $o++) {

                $ozellikId = $ozellikler[$o];
                $ozellikKontrolEt = UrunModel::urunOzellikKontrol($id,$ozellikler[$o]);


                $deger = $degerler[$ozellikId]==""?"":Security::htmlEncode($degerler[$ozellikId]);

                $ozellikDetay = UrunModel::urunGrupOzellikDetay($ozellikId);



                if($ozellikDetay->tur=="file"){

                    if(Upload::isFile('file_'.$ozellikId)){

                        Upload::mimes('application/pdf','application/zip','application/msword','application/rar','application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                            ->convertName()
                            ->encode('sha1')
                            ->encodeLength(10)
                            ->source('file_'.$ozellikId)
                            ->target(REAL_BASE_DIR . 'Uploads/urun-dosyalari/')
                            ->start();
                        $dosyaBilgi = Upload::info();
                        $deger   = $dosyaBilgi->encodeName;
                    }else{

                        $deger   = "";

                    }

                }

                if($ozellikDetay->tur=="image"){

                    if(Upload::isFile('image_'.$ozellikId)){

                        Upload::mimes('image/jpeg','image/png','image/gif')
                            ->convertName()
                            ->encode('sha1')
                            ->encodeLength(10)
                            ->source('image_'.$ozellikId)
                            ->target(REAL_BASE_DIR . 'Uploads/urun-dosyalari/')
                            ->start();
                        $dosyaBilgi = Upload::info();
                        $deger   = $dosyaBilgi->encodeName;
                    }else{

                        $deger   = "";

                    }

                }


                if ($ozellikKontrolEt->id=="") {
                    if($deger!=""){
                        //Özellike ekle
                        $ozellikData = [
                            'urun' => $id,
                            'ozellik' => $ozellikId,
                            'deger' => $deger
                        ];
                        $ozellikEkle = UrunModel::urunOzellikEkle($ozellikData);
                    }

                }else{
                    //Özellik güncelle
                    $ozellikData = [
                        'id' => $ozellikKontrolEt->id,
                        'deger' => $deger
                    ];
                    $ozellikGuncelle = UrunModel::urunOzellikGuncelle($ozellikData);
                }

            }

            if($update){

                Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Başarı İle Güncelleme İşlemi Yapıldı !.</div></div>'])->action('urun/form/'.$id);

            }else{

                Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Güncelleme işlemi sırasında hata oluştu !.</div></div>'])->action(URL::prev());

            }

        }

    }

    public function gruplar()
    {
        $listeData = UrunModel::urunGrupListe();
        $siralamaYeri = "urunGuruplari";

        View::siralamaYeri($siralamaYeri);
        View::listele($listeData);


    }

    public function grupEkle ()
    {

        if(!Validation::check()){

            $hata = str_replace('<br>',EOL,Validation::error('string'));
            AyarModel::basarisiz('Grup Ekleme İşlemi','Grup Ekleme işlemi sırasında hata oluştu<br>'.$hata,URL::prev());

        }else{

            $ekleData = [
                'adi'           =>Post::adi(),
                'aciklama'      =>Security::htmlEncode(Post::aciklama()),
                'sira'          =>Post::gsira(),
                'durum'         =>Post::gdurum()
            ];

            $ekle = UrunModel::urunGrupEkle($ekleData);

            if($ekle){

                $sira           = Post::sira();
                $tur            = Post::tur();
                $baslik         = Post::baslik();
                $gereklilik     = Post::gereklilik();
                $yer            = Post::yer();
                $durum          = Post::durum();

                for($i=0;$i<count($baslik);$i++){

                    $ozellikEkle = [
                        'grup'          => $ekle,
                        'sira'          => $sira[$i],
                        'baslik'        => $baslik[$i],
                        'tur'           => $tur[$i],
                        'gereklilik'    => $gereklilik[$i]==""?0:1,
                        'yer'           => $yer[$i],
                        'durum'         => $durum[$i]
                    ];

                    $ozellikEkle = UrunModel::urunGrupOzellikEkle($ozellikEkle);

                    $ozellikEkle ="";

                }

                AyarModel::basarili('Grup Ekleme İşlemi','Grup Ekleme işlemi başarı ile gerçekleştirildi',URL::site('urun/grupForm/'.$ekle));

            }else{

                AyarModel::basarisiz('Grup Ekleme İşlemi','Grup Ekleme işlemi sırasında hata oluştu',URL::prev());

            }

        }

    }

    public function grupGuncelle($id)
    {

        if(!Validation::check()){

            $hata = str_replace('<br>',EOL,Validation::error('string'));
            AyarModel::basarisiz('Grup Güncelleme İşlemi','Grup Güncelleme işlemi sırasında hata oluştu<br>'.$hata,URL::prev());

        }else{

            if (Post::id()!=$id) {
                redirect(URL::prev());
            }

            $guncellemeData = [
                'id'            =>$id,
                'adi'           =>Post::adi(),
                'aciklama'      =>Security::htmlEncode(Post::aciklama()),
                'sira'          =>Post::gsira(),
                'durum'         =>Post::gdurum()
            ];

            $guncelle = UrunModel::urunGrupGuncelle($guncellemeData);

            $oid            = Post::oid();
            $sira           = Post::sira();
            $tur            = Post::tur();
            $baslik         = Post::baslik();
            $gereklilik     = Post::gereklilik();
            $yer            = Post::yer();
            $durum          = Post::durum();

            for($i=0;$i<count($baslik);$i++){

                $ozellikData = [
                    'id'            => $oid[$i],
                    'grup'          => $id,
                    'sira'          => $sira[$i],
                    'baslik'        => $baslik[$i],
                    'tur'           => $tur[$i],
                    'gereklilik'    => $gereklilik[$i],
                    'yer'           => $yer[$i],
                    'durum'         => $durum[$i]
                ];

                if($oid[$i]=="0"){

                    UrunModel::urunGrupOzellikEkle($ozellikData);

                }else{

                    UrunModel::urunGrupOzellikGuncelle($ozellikData);
                }

                $ozellikData ="";

            }

            if($guncelle){

                AyarModel::basarili('Grup Ekleme İşlemi','Grup Ekleme işlemi başarı ile gerçekleştirildi',URL::site('urun/grupForm/'.$id));

            }else{

                AyarModel::basarisiz('Grup Ekleme İşlemi','Grup Ekleme işlemi sırasında hata oluştu',URL::prev());

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
            case "urunSil":

                $sil = UrunModel::sil($dataId);

                $data['title'] = "Ürün Silme İşlemi";

                if($sil){

                    $ozellikSil = UrunModel::urunOzellikleriniSil($dataId);

                    $data['success'] = 'Ürün silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Ürün silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "grupSil":

                $sil = UrunModel::urunGrupSil($dataId);

                $data['title'] = "Grup Silme İşlemi";

                if($sil){

                    $ozellikSil = UrunModel::urunGrupOzellikleriniSil($dataId);

                    $data['success'] = 'Grup silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Grup silme işlemi yapılamadı!";

                }

                echo Json::encode($data);

                break;

            case "urunGrupOzellikSil":

                $ozellikDetay = UrunModel::urunGrupOzellikDetay($dataId);

                $sil = UrunModel::urunGrupOzellikSil($dataId);


                $data['title'] = "Grup Özellik Silme İşlemi";

                if($sil){

                    $data['success'] = 'Grup Özellik silme işlemi başarı ile yapıldı!';
                    $data['redirect'] = '';

                }else{

                    $data['error'] = "Grup Özellik silme işlemi yapılamadı!";

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