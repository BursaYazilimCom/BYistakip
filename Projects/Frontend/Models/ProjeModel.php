<?php

class InternalProjeModel extends Model
{
    static function detay($sef)
    {
        $veri =  DB::where('sef',$sef)->projeler()->row();

        return $veri;

    }

    static function liste(){
        $veri= DB::limit(null,25)->projeler();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows(true)];
    }

    static function devamEden(){
        $veri= DB::where('durum','0')->limit(null,25)->projeler();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows(true)];
    }
    
    /*****************************************************/
    static function yolHaritasi($id){
        $veri = DB::where('proje_id',$id)->orderby('sira','ASC')->proje_yol_haritasi();


        return ['liste'=>$veri->result()];

    }

    static function yapilanlar($id){
        $veri = DB::where('proje_id',$id)->orderby('tarih','DESC')->proje_yapilanlar();


        return ['liste'=>$veri->result()];

    }

    static function personeller($id){
        $veri = DB::where('proje_id',$id)->proje_calisanlari()->result();


        return $veri;

    }

    static function bildirimKaydet($data){

        $ekle = DB::insert('proje_geri_bildirimleri',[
            'proje_id'          =>$data['proje_id'],
            'detay'             =>$data['detay'],
            'talep_ip'          =>$data['talep_ip'],
            'talep_user_agent'  =>$data['talep_user_agent']
        ]);

        return DB::insertID();
    }


}