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


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function ekle($data){

        $ekle = DB::insert('projeler',[
            'musteri'                   =>$data['musteri'],
            'proje_adi'                 =>$data['proje_adi'],
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
                        'aciklama'                  =>$data['aciklama'],
                        'proje_baslangic_tarihi'    =>$data['proje_baslangic_tarihi'],
                        'tahmini_bitis_tarihi'      =>$data['tahmini_bitis_tarihi'],
                        'bitis_tarihi'              =>$data['bitis_tarihi'],
                        'durum'                     =>$data['durum']
                    ]);
        return $guncelle;
    }

    static function sil($id){

        $sil        = DB::whereId($id)->delete('projeler');

        return $sil;
    }
    
    /*****************************************************/
}