<?php

class InternalPersonelModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->yonetim()->row();
    }

    static function isim($id)
    {
        $veri = DB::where('id',$id)->yonetim()->row();

        return $veri->isim;
    }

    static function resim($id)
    {
        $veri = DB::where('id',$id)->yonetim()->row();

        return $veri->resim;
    }

    static function liste(){
        $veri= DB::limit(null,25)->yonetim();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function tumListe(){
        $veri= DB::yonetim()->result();

        return $veri;
    }



}