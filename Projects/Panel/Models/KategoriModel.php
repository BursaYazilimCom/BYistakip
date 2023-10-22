<?php

class InternalKategoriModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->kategoriler()->row();
    }

    static function liste(){
        $veri= DB::limit(null,25)->kategoriler();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function tumListe(){
        $veri= DB::kategoriler()->result();

        return $veri;
    }

    static function ekle($data){

       $ekle = DB::insert('kategoriler',[
            'adi'       =>$data['adi'],
            'sef'       =>$data['sef'],
            'title'     =>$data['title'],
            'icon'      =>$data['icon'],
            'aciklama'  =>$data['aciklama'],
            'sira'      =>$data['sira'],
            'anasayfa'  =>$data['anasayfa'],
            'durum'     =>$data['durum']
        ]);

       //echo DB::stringQuery();

       return DB::insertID();
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('kategoriler',[
                'adi'       =>$data['adi'],
                'sef'       =>$data['sef'],
                'title'     =>$data['title'],
                'icon'      =>$data['icon'],
                'aciklama'  =>$data['aciklama'],
                'sira'      =>$data['sira'],
                'anasayfa'  =>$data['anasayfa'],
                'durum'     =>$data['durum']
            ]);

        return $guncelle;
    }


    static function delete($id){
        return DB::whereId($id)->delete('kategoriler');
    }
}