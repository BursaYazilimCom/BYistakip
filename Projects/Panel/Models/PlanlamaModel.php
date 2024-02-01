<?php

class InternalPlanlamaModel extends Model
{

    static function hatirlatmalar(){

        $veri = DB::orderby('id','DESC')->hatirlatmalar();

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
                'ay'        =>$data['ay'],
                'gun'       =>$data['gun'],
                'saat'      =>$data['saat'],
                'durum'     =>$data['durum']
            ]);
        return $guncelle;
    }

    static function hatirlatmaSil($id){

        $sil        = DB::whereId($id)->delete('hatirlatmalar');

        return $sil;
    }

    /***************/


    
}