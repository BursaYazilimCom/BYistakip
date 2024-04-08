<?php

class InternalDestekModel extends Model
{
    static function detay($id)
    {
        $veri =  DB::where('id',$id)
            ->destek_talepleri()
            ->row();
        return $veri;

    }

    static function liste(){
        $veri = DB::limit(NULL,25)
            ->orderby('id','DESC')->destek_talepleri();


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function departmanListe($id,$sayfa){
        $veri = DB::where('departman',$id)
            ->limit($sayfa,25)
            ->orderby('id','DESC')->destek_talepleri();


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function cariListe($id,$sayfa){
        $veri = DB::where('musteri',$id)
            ->limit($sayfa,25)
            ->orderby('id','DESC')->destek_talepleri();


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function ekle($data){

        $ekle = DB::insert('destek_talepleri',[
            'musteri'         =>$data['musteri'],
            'departman'       =>$data['departman'],
            'konu'            =>$data['konu'],
            'mesaj'           =>$data['mesaj'],
            'durum'           =>$data['durum']
        ]);

        return DB::insertID();
    }

    static function guncelle($data){

        $guncelle = DB::where('id',$data["id"])
                    ->update('destek_talepleri',[
                        'musteri'         =>$data['musteri'],
                        'departman'       =>$data['departman'],
                        'durum'           =>$data['durum']
                    ]);
       // echo DB::stringQuery();
        return $guncelle;
    }

    static function talepDurumDegistir($data){

        $guncelle = DB::where('id',$data["id"])
                    ->update('destek_talepleri',[
                        'durum'           =>$data['durum']
                    ]);
       // echo DB::stringQuery();
        return $guncelle;
    }

    static function sil($id){

        $sil        = DB::whereId($id)->delete('destek_talepleri');

        return $sil;
    }

    static function deparmanDetay($id)
    {
        return DB::where('id',$id)->destek_departman()->row();
    }

    static function departmanAdi($id)
    {
        $veri = DB::where('id',$id)->destek_departman()->row();

        return $veri->adi;
    }

    static function departmanlar(){
        $veri = DB::orderby('adi','ASC')->destek_departman()->result();

        return $veri;

    }

    static function departmanEkle($data){

        $ekle = DB::insert('destek_departman',[
            'adi'                   =>$data['adi'],
            'yetkili_personel'      =>$data['yetkili_personel'],
            'durum'                 =>$data['durum']
        ]);

        return DB::insertID();
    }

    static function departmanGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('destek_departman',[
                'adi'                   =>$data['adi'],
                'yetkili_personel'      =>$data['yetkili_personel'],
                'durum'                 =>$data['durum']
            ]);

            echo DB::stringQuery();

        return $guncelle;
    }

    static function departmanSil($id){
        return DB::whereId($id)->delete('destek_departman');
    }
    
    /*****************************************************/

    static function mesajlar($id){

        $veri = DB::where('talep_id',$id)->orderby('id','ASC')->destek_mesajlari()->result();

        return $veri;

    }

    static function mesajEkle($data){

        $ekle = DB::insert('destek_mesajlari',[
            'talep_id'      =>$data['talep_id'],
            'gonderen_id'   =>$data['gonderen_id'],
            'gonderen'      =>$data['gonderen'],
            'mesaj'         =>$data['mesaj'],
            'dosya_eki'     =>$data['dosya_eki']
        ]);

        return DB::insertID();
    }

    static function mesajSil($id){
        return DB::whereId($id)->delete('destek_mesajlari');
    }
}