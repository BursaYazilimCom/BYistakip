<?php

class InternalProjeModel extends Model
{
    static function detay($id)
    {
        $veri =  DB::where('id',$id)
            ->projeler()
            ->row();

        //AyarModel::sqlHataEkle(DB::stringQuery());

        return $veri;

    }

    static function liste(){
        $veri = DB::limit(NULL,25)
            ->orderby('id','DESC')->projeler();


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows(true)];

    }

    static function CariProjeleri($id,$sayfa){
        $veri = DB::where('musteri',$id)->limit($sayfa,25)
            ->orderby('id','DESC')->projeler();


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows(true)];

    }

    static function ekle($data){

        $ekle = DB::insert('projeler',[
            'musteri'                   =>$data['musteri'],
            'proje_adi'                 =>$data['proje_adi'],
            'sef'                       =>$data['sef'],
            'aciklama'                  =>$data['aciklama'],
            'proje_baslangic_tarihi'    =>$data['proje_baslangic_tarihi'],
            'tahmini_bitis_tarihi'      =>$data['tahmini_bitis_tarihi'],
            'bitis_tarihi'              =>$data['bitis_tarihi'],
            'durum'                     =>$data['durum']
        ]);

        return DB::insertID();
    }

    static function guncelle($data){

        $guncelle = DB::where('id',$data["id"])
                    ->update('projeler',[
                        'musteri'                   =>$data['musteri'],
                        'proje_adi'                 =>$data['proje_adi'],
                        'sef'                       =>$data['sef'],
                        'aciklama'                  =>$data['aciklama'],
                        'proje_baslangic_tarihi'    =>$data['proje_baslangic_tarihi'],
                        'tahmini_bitis_tarihi'      =>$data['tahmini_bitis_tarihi'],
                        'bitis_tarihi'              =>$data['bitis_tarihi'],
                        'durum'                     =>$data['durum']
                    ]);
        return $guncelle;
    }

    static function sifreGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('projeler',[
                'sifre'                   =>$data['sifre']
            ]);
        return $guncelle;
    }

    static function sil($id){

        $sil        = DB::whereId($id)->delete('projeler');

        return $sil;
    }
    
    /*****************************************************/
    static function yolHaritasi($id){
        $veri = DB::where('proje_id',$id)->orderby('sira','ASC')->proje_yol_haritasi();


        return ['liste'=>$veri->result()];

    }

    static function yolHaritasiDetay($id){

        $veri = DB::where('id',$id)->proje_yol_haritasi()->row();


        return $veri;

    }

    static function yolHaritasiEkle($data){

        $ekle = DB::insert('proje_yol_haritasi',[
            'proje_id'                   =>$data['proje_id'],
            'baslik'                 =>$data['baslik'],
            'aciklama'                  =>$data['aciklama'],
            'sira'                  =>$data['sira'],
            'durum'                     =>$data['durum']
        ]);

        return DB::insertID();
    }

    static function yolHaritasiGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('proje_yol_haritasi',[
                'baslik'                 =>$data['baslik'],
                'aciklama'                  =>$data['aciklama'],
                'sira'                  =>$data['sira'],
                'durum'                     =>$data['durum']
            ]);
        return $guncelle;
    }

    static function yolHaritasiSil($id){

        $sil        = DB::whereId($id)->delete('proje_yol_haritasi');

        return $sil;
    }

    /***************/

    /*****************************************************/
    static function yapilanlar($id){
        $veri = DB::where('proje_id',$id)->orderby('tarih','DESC')->proje_yapilanlar();


        return ['liste'=>$veri->result()];

    }

    static function yapilanIslemDetay($id){

        $veri = DB::where('id',$id)->proje_yapilanlar()->row();


        return $veri;

    }

    static function yapilanIslemEkle($data){

        $ekle = DB::insert('proje_yapilanlar',[
            'proje_id'  =>$data['proje_id'],
            'tur'       =>$data['tur'],
            'islem'     =>$data['islem']
        ]);

        return DB::insertID();
    }

    static function yapilanIslemGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('proje_yapilanlar',[
                'tur'       =>$data['tur'],
                'islem'     =>$data['islem']
            ]);
        return $guncelle;
    }

    static function yapilanIslemSil($id){

        $sil        = DB::whereId($id)->delete('proje_yapilanlar');

        return $sil;
    }
}