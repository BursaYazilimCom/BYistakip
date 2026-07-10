<?php

class InternalCariModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->cari()->row();
    }

    static function mailDetay($email)
    {
        return DB::where('email',$email)->cari()->row();
    }

    static function cariAdi($id)
    {
        $data = DB::where('id',$id)->cari()->row();

        return $data->adi;
    }

    static function liste(){
        $veri= DB::limit(null,25)->cari();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows(true)];
    }

    static function tumListe(){

        $veri= DB::cari()->result();

        return $veri;
    }

    static function add($data){

       $ekle = DB::insert('cari',[
            'email'         =>$data['email'],
            'adi'           =>$data['adi'],
            'gsm'           =>$data['gsm'],
            'il'            =>$data['il'],
            'tc'            =>$data['tc'],
            'firma_adi'     =>$data['firma_adi'],
            'fatura_adresi' =>$data['fatura_adresi'],
            'vergi_dairesi' =>$data['vergi_dairesi'],
            'vergi_no'      =>$data['vergi_no'],
            'bakiye'        =>$data['bakiye'],
            'yonetim_notu'  =>$data['yonetim_notu'],
            'durum'         =>$data['durum']
        ]);

       //echo DB::stringQuery();

       return $ekle;
    }

    static function updatePassword($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('cari',[
                'pass'         =>$data['pass']
            ]);

        return $guncelle;
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('cari',[
                'adi'           =>$data['adi'],
                'gsm'           =>$data['gsm'],
                'il'            =>$data['il'],
                'tc'            =>$data['tc'],
                'firma_adi'     =>$data['firma_adi'],
                'fatura_adresi' =>$data['fatura_adresi'],
                'vergi_dairesi' =>$data['vergi_dairesi'],
                'vergi_no'      =>$data['vergi_no']
            ]);

        return $guncelle;
    }

    /**********************/

}