<?php

class InternalHaberModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->haberler()->row();
    }

    static function liste(){
        $veri= DB::limit(null,25)->haberler();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }


    static function resimleri($id){

        $veri= DB::where('haber_id',$id)->haber_resimleri();

        return $veri->result();
    }

    static function haberResimEkle($data){

        $ekle = DB::insert('haber_resimleri',[
            'haber_id'      =>$data['haber_id'],
            'resim'      =>$data['resim']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();

    }

    static function haberResimData($id){

        $veri =  DB::where('id',$id)->haber_resimleri()->row();

        return $veri;

    }

    static function haberResimSil($id){

        return DB::whereId($id)->delete('haber_resimleri');

    }

    static function ekle($data){


       $ekle = DB::insert('haberler',[
           'kategori'        =>$data['kategori'],
           'firma_adi'       =>$data['firma_adi'],
           'sef'             =>$data['sef'],
           'resim'           =>$data['resim'],
           'baslik'          =>$data['baslik'],
           'aciklama'        =>$data['aciklama'],
           'durum'           =>$data['durum']
        ]);

       //echo DB::stringQuery();

       return DB::insertID();
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('haberler',[
                'kategori'        =>$data['kategori'],
                'firma_adi'       =>$data['firma_adi'],
                'sef'             =>$data['sef'],
                'resim'           =>$data['resim'],
                'baslik'          =>$data['baslik'],
                'aciklama'        =>$data['aciklama'],
                'durum'           =>$data['durum']
            ]);

        echo DB::stringQuery();

        return $guncelle;
    }

    static function delete($id){
        return DB::whereId($id)->delete('haberler');
    }

    /************Haber kategorileri**************/

    static function kategoriDetay($id)
    {
        return DB::where('id',$id)->haber_kategori()->row();
    }

    static function kategoriAdi($id)
    {
            $data = DB::select("baslik")->where('id',$id)->haber_kategori()->row();

            $veri = $data->baslik;


        return $veri;
    }

    static function kategoriListe(){
        $veri= DB::orderby("baslik","asc")->haber_kategori();

        return ['liste'=>$veri->result()];
    }



    static function kategoriEkle($data){


        $ekle = DB::insert('haber_kategori',[
            'sef'        => $data['sef'],
            'baslik'     => $data['baslik'],
            'aciklama'   => $data['aciklama'],
            'durum'      => $data['durum']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function kategoriGuncellee($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('haber_kategori',[
                'sef'        => $data['sef'],
                'baslik'     => $data['baslik'],
                'aciklama'   => $data['aciklama'],
                'durum'      => $data['durum']
            ]);

        echo DB::stringQuery();

        return $guncelle;
    }

    static function kateogriSil($id){
        return DB::whereId($id)->delete('haber_kategori');
    }


    /************Haber kategorileri**************/
}