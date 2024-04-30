<?php

class InternalPlanlamaModel extends Model
{

    static function hatirlatmalar($durum=""){

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

    /************************** */

    static function etkinlikTurDetay($id){

        $veri = DB::where('id',$id)->etkinlik_turleri()->row();

        return $veri;

    }

    static function etkinlikListe($baslangic="",$bitis=""){

        $veri = DB::where('durum','1')->orderby('id','DESC')->etkinlik();

        return ['liste'=>$veri->result()];

    }

    static function etkinlikDetay($id){

        $veri = DB::where('id',$id)->etkinlik()->row();

        return $veri;

    }



    /***************/


    
}