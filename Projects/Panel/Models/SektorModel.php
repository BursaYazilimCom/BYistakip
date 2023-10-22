<?php

class InternalSektorModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->sektorler()->row();
    }

    static function liste(){
        $veri= DB::limit(null,25)->sektorler();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function tumListe(){
        $veri= DB::orderby('sektor_adi','asc')->sektorler()->result();

        return $veri;
    }

    static function ekle($data){

       $ekle = DB::insert('sektorler',[
            'sektor_adi'      =>$data['sektor_adi'],
            'sektor_sef'      =>$data['sektor_sef'],
            'durum'         =>$data['durum']
        ]);

       //echo DB::stringQuery();

       return DB::insertID();
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('sektorler',[
                'sektor_adi'      =>$data['sektor_adi'],
                'sektor_sef'      =>$data['sektor_sef'],
                'durum'         =>$data['durum']
            ]);

        return $guncelle;
    }


    static function delete($id){
        return DB::whereId($id)->delete('sektorler');
    }

    static function firmaSektorTanimla($data){

        $ekle = DB::insert('firma_sektor',[
            'firma_id'      =>$data['firma_id'],
            'sektor_id'      =>$data['sektor_id']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();

    }

    static function firmaSektorSorgula($data){

        return DB::where('firma_id',$data['firma_id'])->where('sektor_id',$data['sektor_id'])->sektorler()->totalRows();

    }

    static function firmaTanimliSektorleri($id){

        $veri= DB::select('sektor_id')
            ->where('firma_id',$id)->firma_sektor();

        /*
         *
         *  $veri= DB::select(
            'sektorler.sektor_adi AS sektor_adi',
            'firma_sektor.sektor_id as sektor_id',
            'firma_sektor.id as tanim_id'
        )
        ->innerjoin('sektorler.id','firma_sektor.sektor_id')
            ->where('firma_sektor.firma_id',$id)->firma_sektor();
        */

        return $veri->result();

    }

    static function firmaSektorSil($id){

        return DB::whereId($id)->delete('firma_sektor');

    }

    static function firmaSektorTemizle($id){
        return DB::where('firma_id',$id)->delete('firma_sektor');
    }
}