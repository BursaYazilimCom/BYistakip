<?php

class InternalKasaModel extends Model
{

    public function hesapBilgi($id){

        $veri = DB::where('id',$id)->kasa_hesaplari()->row();

        return $veri;

    }


    public function kasaHesaplari(){

        $veri = DB::kasa_hesaplari()->result();

        return $veri;

    }

    public function turHesaplari($tur){
        $veri = DB::where('tur',$tur)->kasa_hesaplari()->result();

        return $veri;
    }

    public function hesapAdi($id){
        $veri = DB::where('id',$id)->kasa_hesaplari()->row();

        return $veri->adi;
    }


    public function kasaHesabiTutarGuncelle($tutar,$id){

        $guncelle = DB::where('id',$id)
            ->update('kasa_hesaplari',[
                'tutar'         =>$tutar
            ]);

        return $guncelle;
    }

    public function kasaToplami(){
        $toplam = DB::select('SUM(tutar)')->kasa_hesaplari()->value();
        return $toplam;
    }


    public function deftereKaydet($data){

        $ekle = DB::insert('kasa_defteri',[

            'kasa'                  =>$data['kasa'],
            'islem'                 =>$data['islem'],
            'hesap'                 =>$data['hesap'],
            'islem_turu'            =>$data['islem_turu'],
            'islem_tur_id'          =>$data['islem_tur_id'],
            'aciklama'              =>$data['aciklama'],
            'gelir'                 =>$data['gelir'],
            'gider'                 =>$data['gider'],
            'mevcut_kasa_toplami'   =>$data['mevcut_kasa_toplami'],
            'yil'                   =>$data['yil'],
            'tarih'                 =>$data['tarih'],
            'islem_yapan'           =>$data['islem_yapan']

        ]);

        //DB::insert('sql_kayitlari',['sql' => DB::stringQuery()]);

        return $ekle;

    }

    public function odemeKaydet($data){

        $ekle = DB::insert('odemeler',[

            'kasa'          =>$data['kasa'],
            'hesap_turu'    =>$data['hesap_turu'],
            'hesap_id'      =>$data['hesap_id'],
            'odeme_tarihi'  =>$data['odeme_tarihi'],
            'aciklama'      =>$data['aciklama'],
            'tutar'         =>$data['tutar']

        ]);

        //echo  DB::stringQuery();

        return $ekle;

    }

    /****ÖDEME İŞLEMLERİ********************ÖDEME İŞLEMLERİ*********************ÖDEME İŞLEMLERİ**********************ÖDEME İŞLEMLERİ***********************/

}