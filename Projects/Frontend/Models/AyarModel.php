<?php


class InternalAyarModel extends Model
{

    function basarili($baslik,$aciklama,$url){

       return Redirect::insert(['bilgi'=>'<div class="alert alert-success" role="alert"><h4 class="alert-heading">'.$baslik.'</h4><div class="alert-body">'.$aciklama.'</div></div>'])->action($url);
    }

    function basarisiz($baslik,$aciklama,$url){

       return  Redirect::insert(['bilgi'=>'<div class="alert alert-danger" role="alert"><h4 class="alert-heading">'.$baslik.'</h4><div class="alert-body">'.$aciklama.'</div></div>'])->action($url);

    }


    static function defaultAyarlar($anahtar){

        $ayar = DB::select("deger")->where('anahtar',$anahtar)->ayarlar()->row();

        return $ayar->deger;

    }

    static function defaultAyarGuncelle($data){

        $guncelle = DB::where('anahtar',$data['anahtar'])
            ->update('ayarlar',[
                'deger'=>$data['deger'],
                'aciklama'=>$data['aciklama']
            ]);

        return $guncelle;

    }

    static function defaultAyarListe(){

        $liste = DB::where('duzenleme_izni','1')->orderby('sira','ASC')->limit(1000)->ayarlar()->result();
        return $liste;

    }

    public function odemePeriyodu($id){
        $periyodlar = [
            '0' => 'Ücretsiz',
            'T' => 'Tek Seferlik',
            'A' => 'Aylık',
            '3A' => '3 Aylık',
            '6A' => '6 Aylık',
            'Y' => 'Yıllık'
        ];

        return $periyodlar[$id];

    }

    public function odemePeriyoduEklenecekGun($id){
        $periyodlar = [
            '0' => 0,
            'T' => 0,
            'A' => 30,
            '3A' => 91,
            '6A' => 183,
            'Y' => 365
        ];

        return $periyodlar[$id];
    }

    public function tarihDuzelt($tarih){

        $t = explode(".",$tarih);

        $tarih = $t[2]."-".$t[1]."-".$t[0];

        return $tarih;

    }

    public function tarihGoster($tarih){

        $t = explode("-",$tarih);

        $tarih = $t[2].".".$t[1].".".$t[0];

        return $tarih;

    }
    public function hataEkle($uye,$controller,$hata){
        $ekle = DB::insert('hata_kayitlari',[
            'uye'       =>$uye,
            'controller'=>$controller,
            'hata'      =>$hata
        ]);

        return $ekle;
    }

    public function sqlHataEkle($hata){
        $ekle = DB::insert('sql_hata_kayitlari',[
            'hata'      =>$hata
        ]);

        return $ekle;
    }

    static function yetkiAlanlari(){
        $veri = DB::yetki_alanlari()->orderby('alan','asc')->result();

        return $veri;
    }

    public function yetkiAlaniGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yetki_alanlari',[
                'alan'          =>$data["alan"],
                'bolum'         =>$data["bolum"],
                'baslik'        =>$data["baslik"],
                'aciklama'      =>$data["aciklama"]
            ]);

    }

    static function yetkiAlaniEkle($data){

        $ekle = DB::insert('yetki_alanlari',[
            'alan'          =>$data["alan"],
            'baslik'        =>$data["baslik"],
            'aciklama'      =>$data["aciklama"]
        ]);

        return DB::insertID();
    }

    static function yetkiAlaniSil($id){

        return DB::whereId($id)->delete('yetki_alanlari');

    }

    /********************/

    public function diller(){

        $veri = DB::diller()->orderby('sira','asc')->result();

        return $veri;
    }

    public function dilGuncelle($data){

        $guncelle = DB::where('dil_id',$data["id"])
            ->update('diller',[
                'baslik'    =>$data["baslik"],
                'kod'      =>$data["kod"],
                'image'     =>$data["image"],
                'sira'      =>$data["sira"],
                'durum'     =>$data["durum"]
            ]);


        return $guncelle;

    }

    static function dilEkle($data){

        $ekle = DB::insert('diller',[
            'baslik'    =>$data["baslik"],
            'kod'      =>$data["kod"],
            'image'     =>$data["image"],
            'sira'      =>$data["sira"],
            'durum'     =>$data["durum"]
        ]);

        return DB::insertID();
    }

    static function dilSil($id){

        return DB::where('dil_id',$id)->delete('diller');

    }

    /********************/


    public function ulkeler($adet){

        $veri = DB::orderby('isim','asc')->limit(null,$adet)->ulkeler();

        $data = ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

        return $data;
    }

    public function ulkeGuncelle($data){

        $guncelle = DB::where('ulke_id',$data["id"])
            ->update('ulkeler',[
                'isim'                      =>$data["isim"],
                'iso_code_2'                =>$data["iso_code_2"],
                'iso_code_3'                =>$data["iso_code_3"],
                'posta_kodu_gerekliligi'    =>$data["posta_kodu_gerekliligi"],
                'durum'                     =>$data["durum"]
            ]);


        return $guncelle;

    }

    static function ulkeEkle($data){

        $ekle = DB::insert('ulkeler',[
            'isim'                      =>$data["isim"],
            'iso_code_2'                =>$data["iso_code_2"],
            'iso_code_3'                =>$data["iso_code_3"],
            'posta_kodu_gerekliligi'    =>$data["posta_kodu_gerekliligi"],
            'durum'                     =>$data["durum"]
        ]);

        return DB::insertID();
    }

    static function ulkeSil($id){

        return DB::where('ulke_id',$id)->delete('ulkeler');

    }

    /********************/

    /********************/


    public function sehirler($adet){

        $veri = DB::orderby('il','asc')->limit(null,$adet)->iller();

        $data = ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

        return $data;
    }

    public function sehirAdi($key){

        $veri = DB::where('id',$key)->iller()->row();

        $data = $veri->il;

        return $data;
    }

    public function sehirGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('iller',[
                'il'        =>$data["il"],
                'plaka'     =>$data["plaka"],
                'siralama'  =>$data["siralama"],
                'kod'       =>$data["kod"]
            ]);


        return $guncelle;

    }

    static function sehirEkle($data){

        $ekle = DB::insert('iller',[
            'il'        =>$data["il"],
            'plaka'     =>$data["plaka"],
            'siralama'  =>$data["siralama"],
            'kod'       =>$data["kod"]
        ]);

        return DB::insertID();
    }

    static function sehirSil($id){

        return DB::where('id',$id)->delete('iller');

    }

    /********************/


    public function ilceler($il){

        $veri = DB::where('il',$il)->orderby('ilce','asc')->ilceler()->result();

        $data = ['liste'=>$veri];

        return $data;
    }

    public function ilceleri($sehir){

        $veri = DB::where('il',$sehir)->orderby('ilce','asc')->ilceler();

        $data = $veri->result();

        return $data;
    }

    static function ilceAdi($id){

        $veri = DB::where('id',$id)->ilceler();

        $data = $veri->row();

        return $data->ilce;

    }

    public function ilceGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('ilceler',[
                'il'      =>$data["il"],
                'ilce'      =>$data["ilce"],
                'oylesine'      =>$data["oylesine"]
            ]);


        return $guncelle;

    }

    static function ilceEkle($data){

        $ekle = DB::insert('ilceler',[
            'il'        =>$data["il"],
            'ilce'      =>$data["ilce"],
            'oylesine'      =>$data["oylesine"]
        ]);

        return DB::insertID();
    }

    static function ilceSil($id){

        return DB::where('id',$id)->delete('ilceler');

    }

    /****************/

    /********************/


    public function paraBirimleri(){

        $veri = DB::orderby('id','asc')->para_birimleri()->result();

        return $veri;
    }

    public function paraBirimDetay($kod){
        $veri = DB::where('kod',$kod)->para_birimleri()->row();

        return $veri;
    }

    public function paraBirimiSembol($key){

        $veri = DB::where('id',$key)->para_birimleri()->row();

        $data = $veri->sembol;

        return $data;
    }

    public function paraBirimiGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('para_birimleri',[
                'para'        =>$data["para"],
                'kod'     =>$data["kod"],
                'sembol'  =>$data["sembol"],
                'guncel_kur'       =>$data["guncel_kur"],
                'guncelleme'       =>$data["guncelleme"]
            ]);


        return $guncelle;

    }

    static function paraBirimiEkle($data){

        $ekle = DB::insert('para_birimleri',[
            'para'          =>$data["para"],
            'kod'           =>$data["kod"],
            'sembol'        =>$data["sembol"],
            'guncel_kur'       =>$data["guncel_kur"],
            'guncelleme'       =>$data["guncelleme"]
        ]);

        return DB::insertID();
    }

    static function paraBirimiSil($id){

        return DB::where('id',$id)->delete('para_birimleri');

    }

    public function kurGuncelle($paraBirimiKodu,$guncelKkur){

        $guncelle = DB::where('kod',$paraBirimiKodu)
            ->update('para_birimleri',[
                'guncel_kur'=>$guncelKkur,
                'guncelleme'=>Date::now()
        ]);

        return $guncelle;

    }
    public function tlCevir($para,$birim){

        $kur = DB::where('kod',$birim)->para_birimleri()->row();

        $guncelKur = $para*$kur->guncel_kur;

        return $guncelKur;

    }

    /********************/

    static function siparisDurumDetay($id){

        $veri = DB::where('id',$id)->siparis_durumlari()->row();

        return $veri;
    }

    static function siparisDurumlari(){

        $veri = DB::orderby('sira','ASC')->siparis_durumlari()->result();

        return $veri;
    }

    static function siparisDurumAdi($id){

        $veri = DB::where('id',$id)->siparis_durumlari()->row();

        return $veri->adi;
    }

    static function siparisDurumGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('siparis_durumlari',[
                'adi'       =>$data["adi"],
                'uyari'     =>$data["uyari"],
                'sira'      =>$data["sira"]
            ]);


        return $guncelle;

    }

    static function siparisDurumEkle($data){

        $ekle = DB::insert('siparis_durumlari',[
            'adi'       =>$data["adi"],
            'uyari'     =>$data["uyari"],
            'sira'      =>$data["sira"]
        ]);

        return DB::insertID();
    }

    static function siparisDurumSil($id){

        return DB::where('id',$id)->delete('siparis_durumlari');

    }

    /****************/


    static function odemeYontemiDetay($id){

        $veri = DB::where('id',$id)->odeme_yontemleri()->row();

        return $veri;
    }

    static function odemeYontemleri(){

        $veri = DB::odeme_yontemleri()->result();

        return $veri;
    }

    static function odemeYontemiAdi($id){

        $veri = DB::where('id',$id)->odeme_yontemleri()->row();

        return $veri->baslik;
    }

    static function odemeYontemiGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('odeme_yontemleri',[
                'baslik'                    =>$data["baslik"],
                'kasa_hesabi'               =>$data["kasa_hesabi"],
                'entegrasyon_bilgileri'     =>$data["entegrasyon_bilgileri"],
                'durum'                     =>$data["durum"]
            ]);


        return $guncelle;

    }

    static function odemeYontemiEkle($data){

        $ekle = DB::insert('odeme_yontemleri',[
            'baslik'                    =>$data["baslik"],
            'kasa_hesabi'               =>$data["kasa_hesabi"],
            'entegrasyon_bilgileri'     =>$data["entegrasyon_bilgileri"],
            'durum'                     =>$data["durum"]
        ]);

        return DB::insertID();
    }

    static function odemeYontemiSil($id){

        return DB::where('id',$id)->delete('odeme_yontemleri');

    }

    /****************/


    /****************/

    public function yetkiKontrol($yetkiler,$yer,$altYer="")
    {

        if(empty($altYer)){

            if (!in_array($yer, $yetkiler)) {

                Redirect::insert(['bilgi' => '<div class="callout callout-danger">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div>'])->action('home');

            }
        }else{
            if (!in_array($yer.'/'.$altYer, $yetkiler)) {

                Redirect::insert(['bilgi' => '<div class="callout callout-danger">Yetkiniz olmayan bir alana ulaşmaya çalışıyorsunuz!</div>'])->action('home');

            }
        }

    }

    public function nelerOluyor($olay,$tur,$tur_id){

            $ekle = DB::insert('neler_oluyor',[
                'olay'     =>$olay,
                'tur'       =>$tur,
                'tur_id'    =>$tur_id
            ]);

        return $ekle;

    }

    public function nelerOluyorListe($adet){

        $veri = DB::limit(Null,$adet)->orderby('id','DESC')->neler_oluyor();

        $data = ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

        return $data;
    }



}