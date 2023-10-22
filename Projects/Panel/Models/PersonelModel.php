<?php

class InternalPersonelModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->yonetim()->row();
    }

    static function liste(){
        $veri= DB::limit(null,25)->yonetim();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function tumListe(){
        $veri= DB::yonetim()->result();

        return $veri;
    }

    static function ekle($data){

       $ekle = DB::insert('yonetim',[
            'username'      =>$data['username'],
            'password'      =>$data['password'],
            'email'         =>$data['email'],
            'isim'          =>$data['isim'],
            'telefon'       =>$data['telefon'],
            'notlar'        =>$data['notlar'],
            'ban'           =>$data['ban'],
            'aktiflik'      =>$data['aktiflik'],
            'panel_rengi'   =>$data['panel_rengi'],
            'aktivasyon'    =>$data['aktivasyon']
        ]);

       //echo DB::stringQuery();

       return $ekle;
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'username'      =>$data["username"],
                'email'         =>$data["email"],
                'isim'          =>$data["isim"],
                'telefon'       =>$data["telefon"],
                'notlar'        =>$data["notlar"],
                'ban'           =>$data["ban"],
                'aktivasyon'    =>$data['aktivasyon']
            ]);

        return $guncelle;
    }

    static function yetkiGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'yetkiler'      =>$data["yetkiler"]
            ]);

        return $guncelle;
    }

    static function updatePassword($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'password'      =>$data["password"]
            ]);

        return $guncelle;
    }

    static function temaDegistir($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'panel_rengi'      =>$data["panel_rengi"]
            ]);


        return $guncelle;
    }

    static function delete($id){
        return DB::whereId($id)->delete('yonetim');
    }
}