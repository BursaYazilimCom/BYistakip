<?php

class InternalPlanlamaModel extends Model
{

    static function hatirlatmalar($durum="",$user){

        if($durum!=""){
            $veri = DB::where('personel',$user)->where('durum',$durum)->orderby('id','DESC')->hatirlatmalar();
        }else{
            $veri = DB::where('personel',$user)->orderby('id','DESC')->hatirlatmalar();
        }

        return ['liste'=>$veri->result()];

    }

    static function tumHatirlatmalar($durum=""){

        if($durum!=""){
            $veri = DB::where('durum',$durum)->orderby('id','DESC')->hatirlatmalar();
        }else{
            $veri = DB::orderby('id','DESC')->hatirlatmalar();
        }

        return ['liste'=>$veri->result()];

    }

    static function hatirlatmaDetay($id){

        $veri = DB::where('id',$id)->hatirlatmalar()->row();

        return $veri;

    }

    static function hatirlatmaEkle($data){

        $ekle = DB::insert('hatirlatmalar',[
            'aciklama'  =>$data['aciklama'],
            'periyod'   =>$data['periyod'],
            'yil'        =>$data['yil'],
            'ay'        =>$data['ay'],
            'gun'       =>$data['gun'],
            'saat'      =>$data['saat'],
            'durum'     =>$data['durum']
        ]);

        return DB::insertID();
    }

    static function hatirlatmaGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('hatirlatmalar',[
                'aciklama'  =>$data['aciklama'],
                'periyod'   =>$data['periyod'],
                'yil'        =>$data['yil'],
                'ay'        =>$data['ay'],
                'gun'       =>$data['gun'],
                'saat'      =>$data['saat'],
                'durum'     =>$data['durum']
            ]);
        return $guncelle;
    }

    static function hatirlatmaDurumGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('hatirlatmalar',[
                'durum'     =>$data['durum']
            ]);
        return $guncelle;
    }

    static function hatirlatmaSil($id){

        $sil        = DB::whereId($id)->delete('hatirlatmalar');

        return $sil;
    }

    /************************** */

    static function etkinlikTurleri(){


        $veri = DB::orderby('tur','ASC')->etkinlik_turleri();

        return ['liste'=>$veri->result()];

    }

    static function etkinlikTurDetay($id){

        $veri = DB::where('id',$id)->etkinlik_turleri()->row();

        return $veri;

    }

    static function etkinlikTurEkle($data){

        $ekle = DB::insert('etkinlik_turleri',[
            'tur'  =>$data['tur'],
            'renk'  =>$data['renk']
        ]);

        return DB::insertID();
    }

    static function etkinlikTurGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('etkinlik_turleri',[
                'tur'  =>$data['tur'],
                'renk'  =>$data['renk']
            ]);
        return $guncelle;
    }

    static function etkinlikTurSil($id){

        $sil        = DB::whereId($id)->delete('etkinlik_turleri');

        return $sil;
    }

    static function etkinlikListe($baslangic="",$bitis=""){

        if($baslangic!="" and $bitis!=""){
            $veri = DB::where('durum','1')->where('baslangic_tarih_saat>',$baslangic)->where('baslangic_tarih_saat<',$bitis)->orderby('id','DESC')->etkinlik();
        }else{
            $veri = DB::where('durum','1')->orderby('id','DESC')->etkinlik();
        }
    

        return ['liste'=>$veri->result()];

    }

    static function etkinlikDetay($id){

        $veri = DB::where('id',$id)->etkinlik()->row();

        return $veri;

    }

    static function etkinlikEkle($data){

        $ekle = DB::insert('etkinlik',[
            'baslik'                =>$data['baslik'],
            'tur'                   =>$data['tur'],
            'baslangic_tarihi'      =>$data['baslangic_tarihi'],
            'baslangic_saati'       =>$data['baslangic_saati'],
            'baslangic_tarih_saat'  =>$data['baslangic_tarih_saat'],
            'bitis_tarihi'          =>$data['bitis_tarihi'],
            'bitis_saat'            =>$data['bitis_saat'],
            'bitis_tarih_saat'      =>$data['bitis_tarih_saat'],
            'url'                   =>$data['url'],
            'katilimcilar'          =>$data['katilimcilar'],
            'konum'                 =>$data['konum'],
            'aciklama'              =>$data['aciklama'],
            'mail_bilgilendirme'    =>$data['mail_bilgilendirme'],
            'sms_bilgilendirme'     =>$data['sms_bilgilendirme']
        ]);

        echo DB::stringQuery();

        return DB::insertID();
    }

    static function etkinlikGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('etkinlik',[
                'baslik'                =>$data['baslik'],
                'tur'                   =>$data['tur'],
                'baslangic_tarihi'      =>$data['baslangic_tarihi'],
                'baslangic_saati'       =>$data['baslangic_saati'],
                'baslangic_tarih_saat'  =>$data['baslangic_tarih_saat'],
                'bitis_tarihi'          =>$data['bitis_tarihi'],
                'bitis_saat'            =>$data['bitis_saat'],
                'bitis_tarih_saat'      =>$data['bitis_tarih_saat'],
                'url'                   =>$data['url'],
                'katilimcilar'          =>$data['katilimcilar'],
                'konum'                 =>$data['konum'],
                'aciklama'              =>$data['aciklama'],
                'mail_bilgilendirme'    =>$data['mail_bilgilendirme'],
                'sms_bilgilendirme'     =>$data['sms_bilgilendirme']
            ]);
        return $guncelle;
    }

    static function etkinlikSil($id){

        $sil        = DB::whereId($id)->delete('etkinlik');

        return $sil;
    }

    /***************/


    
}