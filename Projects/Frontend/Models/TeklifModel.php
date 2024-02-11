<?php

class InternalTeklifModel extends Model
{

    static function detay($id)
    {
        return DB::where('id',$id)->teklifler()->row();
    }


    static function liste(){

        $veri = DB::orderby('id','DESC')->limit(NULL,25)->teklifler();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }





    static function teklifUrunleri($id){

        $data = DB::where('teklif',$id)->teklif_urunleri()->result();

        return $data;
        


    }



}