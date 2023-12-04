<?php

class InternalTedarikciModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->tedarikci()->row();
    }

    static function tedarikciAdi($id='0')
    {
        if($id=='0'){
            return 'Tedarikci Yok';
        }
        $data = DB::where('id',$id)->tedarikci()->row();

        return $data->adi;
    }

    static function liste(){
        $veri= DB::limit(null,25)->tedarikci();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows(true)];
    }

    static function tumListe(){

        $veri= DB::tedarikci()->result();

        return $veri;
    }

    static function add($data){

       $ekle = DB::insert('tedarikci',[
            'adi'           =>$data['adi'],
            'ek_bilgiler'           =>$data['ek_bilgiler']
        ]);

       //echo DB::stringQuery();

       return $ekle;
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('tedarikci',[
                'adi'           =>$data['adi'],
                'ek_bilgiler'           =>$data['ek_bilgiler']
            ]);

        return $guncelle;
    }

    static function delete($id){
        return DB::whereId($id)->delete('tedarikci');
    }

    /**********************/

}