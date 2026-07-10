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

    static function liste($id,$sayfa){
        $veri = DB::where('musteri',$id)
            ->limit($sayfa,25)
            ->orderby('id','DESC')->destek_talepleri();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'toplam'=>$veri->totalRows(true)];
    }

    static function ekle($data){

        $ekle = DB::insert('destek_talepleri',[
            'musteri'         =>$data['musteri'],
            'departman'       =>$data['departman'],
            'konu'            =>$data['konu'],
            'mesaj'           =>$data['mesaj'],
            'durum'           =>$data['durum'],
            'guncelleme_tarihi'           =>Date::now()
        ]);

        return DB::insertID();
    }

    static function talepDurumDegistir($data){

        $guncelle = DB::where('id',$data["id"])
                    ->update('destek_talepleri',[
                        'durum'           =>$data['durum']
                    ]);
       // echo DB::stringQuery();
        return $guncelle;
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

}