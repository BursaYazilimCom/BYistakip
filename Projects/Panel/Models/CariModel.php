<?php

class InternalCariModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->cari()->row();
    }

    static function cariAdi($id)
    {
        $data = DB::where('id',$id)->cari()->row();

        return $data->isim_soyisim;
    }

    static function liste(){
        $veri= DB::limit(null,25)->cari();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
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
            'gsm'           =>$data['gsm'],
            'il'            =>$data['il'],
            'tc'            =>$data['tc'],
            'firma_adi'     =>$data['firma_adi'],
            'fatura_adresi' =>$data['fatura_adresi'],
            'vergi_dairesi' =>$data['vergi_dairesi'],
            'vergi_no'      =>$data['vergi_no'],
            'bakiye'        =>$data['bakiye'],
            'yonetim_notu'        =>$data['yonetim_notu'],
            'durum'         =>$data['durum']
        ]);

       //echo DB::stringQuery();

       return $ekle;
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('cari',[
                'email'         =>$data['email'],
                'adi'           =>$data['adi'],
                'gsm'           =>$data['gsm'],
                'gsm'           =>$data['gsm'],
                'il'            =>$data['il'],
                'tc'            =>$data['tc'],
                'firma_adi'     =>$data['firma_adi'],
                'fatura_adresi' =>$data['fatura_adresi'],
                'vergi_dairesi' =>$data['vergi_dairesi'],
                'vergi_no'      =>$data['vergi_no'],
                'bakiye'        =>$data['bakiye'],
                'yonetim_notu'        =>$data['yonetim_notu'],
                'durum'         =>$data['durum']
            ]);

        return $guncelle;
    }

    static function delete($id){
        return DB::whereId($id)->delete('cari');
    }

    /**********************/

}