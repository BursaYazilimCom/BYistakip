<?php namespace Project\Controllers;


use User,Method,Post,Session,Cookie,Redirect,DB,Upload,Json,Import,Encode,URL,Validation,Folder,Converter,Security;
use InternalProjeModel as ProjeModel,AyarModel,InternalCariModel as CariModel,PersonelModel;

class Proje extends Controller
{

    public function __construct()
    {

    }

    public function main($sef="")
    {
        $projeDetay = ProjeModel::detay($sef);
        if (empty($projeDetay->id)) {
            Redirect::action('s404');
            exit();
        }

        if (Session::select('kullanici')!=$projeDetay->musteri.'-'.$projeDetay->id) {
            Session::insert('proje',$sef);
            Redirect::action('proje/login/'.$sef);
            exit();
        }


        if ($sef=="") {
            Redirect::action('home');
            exit();
        }


        $yolharitasi = ProjeModel::yolHaritasi($projeDetay->id);
        $projeEkibi = ProjeModel::personeller($projeDetay->id);

        View::detay($projeDetay);
        View::projeEkibi($projeEkibi);
        View::yolHaritasi($yolharitasi);


    }

    public function calismalar($sef)
    {
        $projeDetay = ProjeModel::detay($sef);

        if (empty($projeDetay->sef)) {
            Redirect::action('s404');
            exit();
        }

        $calismalar = ProjeModel::yapilanlar($projeDetay->id);

        if (Session::select('kullanici')!=$projeDetay->musteri.'-'.$projeDetay->id) {
            Session::insert('proje',$sef);
            Redirect::action('proje/login/'.$sef);
            exit();
        }

        View::detay($projeDetay);
        View::calismalar($calismalar);
    }

    public function bildirimYap($proje) {

            if(!Validation::check()){

                $data['error'] = str_replace('<br>',EOL,Validation::error('string'));

            }else{

                $projeDetay = ProjeModel::detay(Security::htmlTagClean(Security::injectionEncode($proje)));

                if (empty($projeDetay->sef)) {
                    Redirect::action('s404');
                    exit();
                } 

                if( ! Security::validTimeOnStay(5) ) # 6 saniyeden erken istek yapılmış ise yönlendir.
                {
                    redirect('proje/'.$projeDetay->sef);
                }     


                if($proje != Post::proje()){

                    Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Geri bildirimiz sırasında bir hata oluştu lütfen tekrar deneyiniz</div></div>'])->action(URL::prev());

                    exit();
                }
                $ekleData = [
                    'proje_id'          =>$projeDetay->id,
                    'detay'             =>Post::detay(),
                    'talep_ip'          =>User::ip(),
                    'talep_user_agent'  =>User::agent()
                   
                ];

                $ekle = ProjeModel::bildirimKaydet($ekleData);

                if($ekle){

                    Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">Başarılı İşlem</h4><div class="alert-body">Geri bildiriminizi başarı ile kaydettik en kısa sürede size dönüş yapacağız. !.</div></div>'])->action('proje/'.$projeDetay->sef);

                }else{

                    Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Başarısız İşlem</h4><div class="alert-body">Geri bildirim sırasında bir hata oluştur. Lütfen tekrar deneyiniz. Eğer sorun devam ederse bizimle iletişime geçebilirsiniz.</div></div>'])->action(URL::prev());

                }

        }
    }

    public function login($sef){

        $projeDetay = ProjeModel::detay($sef);

        if (empty($projeDetay->id)) {
            Redirect::action('s404');
            exit();
        }

        if (Post::sifre()!=""){
            $sifre = Encode::type(Post::sifre(), 'md5');

            if ($sifre == $projeDetay->sifre) {
                Session::insert('kullanici',$projeDetay->musteri.'-'.$projeDetay->id);
                Redirect::action('proje/main/'.$sef);
            }else{
                Redirect::insert(['bilgi'=>'<div class="alert alert-danger">Proje Şifresi Hatalıdır. Lütfen Şifreyi Doğru Giriniz</div>'])->action('proje/login/'.$sef);
            }

        }

        View::detay($projeDetay);

    }
    
    public function s404()
    {

    }
}