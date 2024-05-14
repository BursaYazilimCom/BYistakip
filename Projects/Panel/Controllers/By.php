<?php namespace Project\Controllers;

Use User,Validation,Post,Json,Encode,Upload,URL;
Use PersonelModel,AyarModel,UrunModel,TedarikciModel;

class By extends Controller
{
    /**
     * Home::main
     *
     * Loads opening page.
     * Location: Views/Home/main.wizard.php
     */
    public function login(){

        if(!Validation::check()){

            $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

        }else{

            $status = User::login(Post::username(),Post::password());

            if( $status === true ) {
                AyarModel::basarili('Giriş işleminiz başarılı', 'Hoşgeldiniz', URL::site('home'));

            }else{
               AyarModel::basarisiz('Başarısız Giriş','Giriş Sırasında bir hata oluştu:<br>'.User::error(), URL::site('login'));
            }

        }

    }
    public function ajax() : void
    {

        $islem = Post::action();

        $data = [];

        switch ($islem){

            case "login":

                echo "test";

                $data['title'] = "Panel Girişi";

                if(!Validation::check()){

                    $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

                }else{

                    $status = User::login(Post::username(),Post::password());

                    if( $status === true ) {
                        $data['success'] = 'Giriş işleminiz başarılı, Panelinize Yönlendiriliyorsunuz';
                        $data['redirect'] = 'home';

                    }else{
                        $data['error'] = 'Giriş Sırasında bir hata oluştu:<br>'.User::error();
                    }

                }

                echo Json::encode($data);

                break;
        }

    }

    public function ilceleri($id):void
    {


        $ilceler = AyarModel::ilceleri($id);

        echo '<select class="select2 form-select" required id="ilce" name="ilce">';

        foreach ($ilceler as $ilce) {
            echo '<option value="'.$ilce->id.'">'.$ilce->ilce.'</option>';
        }

        echo '</select>';

    }

    public function mahalleleri($id):void
    {

        $mahalleler = AyarModel::mahalleleri($id);

        echo '<select class="select2 form-select" required name="mahalle" id="mahalle">';

        foreach ($mahalleler as $mahalle) {
            echo '<option value="'.$mahalle->mahalle_key.'">'.$mahalle->mahalle_adi.'</option>';
        }

        echo '</select>';

    }

    public function urunOdemePeriodlari($id):void{
        $urunDetay = UrunModel::detay($id);

        echo '<select class="select2 form-select" required id="odeme_periyodu" name="odeme_periyodu">';

            if ($urunDetay->fiyat >0) {
                echo '<option value="T">Tek Seferlik ('.$urunDetay->fiyat.' '.$urunDetay->fiyat_birim.') </option>';
            }

            if ($urunDetay->aylik_fiyat >0) {
                echo '<option value="A">Aylık ('.$urunDetay->aylik_fiyat.' '.$urunDetay->fiyat_birim.') </option>';
            }

            if ($urunDetay->uc_aylik_fiyat >0) {
                echo '<option value="3A">3 Aylık ('.$urunDetay->uc_aylik_fiyat.' '.$urunDetay->fiyat_birim.') </option>';
            }

            if ($urunDetay->alti_aylik_fiyat >0) {
                echo '<option value="6A">6 Aylık ('.$urunDetay->alti_aylik_fiyat.' '.$urunDetay->fiyat_birim.') </option>';
            }

            if ($urunDetay->yillik_fiyat >0) {
                echo '<option value="Y">Yıllık ('.$urunDetay->yillik_fiyat.' '.$urunDetay->fiyat_birim.') </option>';
            }
        echo '<option value="0">Ücretsiz</option>';
        echo '</select>';

    }

    public function grupUrunleri($id): void{
        $urunler = UrunModel::grupUrunleriTumListe($id);

        ?>
        <select class="select2 form-select" required id="urun" name="urun" onchange="odemePeriodlari(this.value)">
                <option value="">--Seçiniz--</option>
            <?php foreach($urunler["liste"] as $urun){ ?>
                <option value="<?=$urun->id?>"><?=$urun->adi?></option>
            <?php } ?>
        </select>

        <?php
    }
    public function uruntedarikcileri($id): void{
        $urunDetay = UrunModel::detay($id);
        $tedarikciler   = TedarikciModel::tumListe();

        ?>
        <select class="select2 form-select" id="tedarikci" name="tedarikci">
            <option value="0">--Ürünün Tedarikçisi--</option>
            <?php foreach($tedarikciler as $tedarikci) { ?>
            <option value="<?=$tedarikci->id?>" <?=$tedarikci->id==$urunDetay->tedarikci?'selected':''?>><?=$tedarikci->adi?></option>
            <?php } ?>
        </select>

        <?php
    }


    public function main(){

    }

    public function editorUpload()
    {

        $result = "";

        if(Upload::isFile('files')){

            Upload::source('files')
                ->target(REAL_BASE_DIR . 'Uploads/dosyalar/')
                ->start();

            $dosyaBilgi = Upload::info();

            $dosya = $dosyaBilgi->encodeName;

            if($dosya){
                $result = [
                    'success' => true,
                    'time' => date("Y-m-d H:i:s"),
                    'data' => [
                        'files' => [$dosya],
                        'baseurl' => str_replace('Panel/','',URL::site()).'Uploads/dosyalar/',
                        'isImages' => [true],
                        'code' => 220
                    ]
                ];

            }

            exit(Json::encode($result));

        }else{

        }

    }

    /**
     * Home::s404
     * Loads show 404 page.
     * Location: Views/Home/s404.wizard.php
     */
    public function s404()
    {
        # Sets masterpage title.
        Masterpage::title('404! File Not Found');
    }
}