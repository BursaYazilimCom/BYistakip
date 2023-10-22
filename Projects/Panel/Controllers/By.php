<?php namespace Project\Controllers;

Use User,Validation,Post,Json,Encode,Upload,URL;
Use PersonelModel,AyarModel;

class By extends Controller
{
    /**
     * Home::main
     *
     * Loads opening page.
     * Location: Views/Home/main.wizard.php
     */
    public function ajax() : void
    {

        $islem = Post::action();

        $data = [];

        switch ($islem){

            case "login":

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