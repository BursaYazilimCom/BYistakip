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


    public function hesapEkle($data){

        $ekle = DB::insert('kasa_hesaplari',[

            'adi'       =>$data['adi'],
            'hesap_no'  =>$data['hesap_no'],
            'aciklama'  =>$data['aciklama'],
            'tur'       =>$data['tur'],
            'durum'     =>$data['durum']

        ]);

       //echo  DB::stringQuery();

        return $ekle;
    }

    public function hesapGuncelle($data){

        $guncelle = DB::where('id',$data['id'])
            ->update('kasa_hesaplari',[
                'adi'         =>$data['adi'],
                'hesap_no'    =>$data['hesap_no'],
                'aciklama'    =>$data['aciklama'],
                'tur'         =>$data['tur'],
                'durum'       =>$data['durum'],
                'tutar'       =>$data['tutar']
            ]);

        return $guncelle;
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

    public function gelirGiderToplami(){
        $toplam = DB::select('SUM(gelir) as gelir','SUM(gider) as gider')->kasa_defteri()->row();
        return $toplam;
    }

    public function sil($id){
        return DB::whereId($id)->delete('kasa_hesaplari');
    }

    /****KASA DEFTERİ****/


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

        echo  DB::stringQuery();

        return $ekle;

    }

    public function kayitlar($id,$sayfa=Null){

        $kayitlar = DB::select(
            'kasa_hesaplari.adi as KasaAdi',
            'kasa_defteri.kasa as kasa',
            'kasa_defteri.islem as islem',
            'kasa_defteri.hesap as hesap',
            'kasa_defteri.islem_turu as islem_turu',
            'kasa_defteri.islem_tur_id as islem_tur_id',
            'kasa_defteri.aciklama as aciklama',
            'kasa_defteri.gelir as gelir',
            'kasa_defteri.gider as gider',
            'kasa_defteri.mevcut_kasa_toplami as mevcut_kasa_toplami',
            'kasa_defteri.yil as yil',
            'kasa_defteri.tarih as tarih'
            )
            ->innerjoin('kasa_hesaplari.id','kasa_defteri.kasa')
            ->where('kasa_defteri.kasa',$id)
            ->limit($sayfa,25)
            ->orderby('kasa_defteri.id','DESC')->kasa_defteri();

        $veri = ['liste'=>$kayitlar->result(),'sayfalama'=>$kayitlar->pagination()];

        //echo DB::stringQuery();

        return $veri;

    }

    public function tumKayitlar(){

        $kayitlar = DB::select(
            'kasa_hesaplari.adi as KasaAdi',
            'kasa_defteri.kasa as kasa',
            'kasa_defteri.islem as islem',
            'kasa_defteri.hesap as hesap',
            'kasa_defteri.islem_turu as islem_turu',
            'kasa_defteri.islem_tur_id as islem_tur_id',
            'kasa_defteri.aciklama as aciklama',
            'kasa_defteri.gelir as gelir',
            'kasa_defteri.gider as gider',
            'kasa_defteri.mevcut_kasa_toplami as mevcut_kasa_toplami',
            'kasa_defteri.yil as yil',
            'kasa_defteri.tarih as tarih',
            'kasa_defteri.islem_yapan as islemYapan',
        )
            ->innerjoin('kasa_hesaplari.id','kasa_defteri.kasa')
            ->limit(Null,25)
            ->orderby('kasa_defteri.id','DESC')->kasa_defteri();

        $veri = ['liste'=>$kayitlar->result(),'sayfalama'=>$kayitlar->pagination()];

       // echo DB::stringQuery();

        return $veri;

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

    public function odemeKaldir($tur,$id){

        $kaldır = DB::where('islem_turu',$tur)->where('islem_tur_id',$id)->delete('kasa_defteri');

        return $kaldır;
    }

    public function maliSorgu($islemTuru,$islem_tur_id){

        $veri = DB::where('islem_turu',$islemTuru)->where('islem_tur_id',$islem_tur_id)->kasa_defteri()->result();

        return $veri;

    }

    public function kasaDefteriKayitSil($tur,$id){

        $sil = DB::where('islem_tur_id',$id)->where('islem_turu',$tur)->delete('kasa_defteri');

        return $sil;
        

    }

    /****ÖDEME İŞLEMLERİ********************ÖDEME İŞLEMLERİ*********************ÖDEME İŞLEMLERİ**********************ÖDEME İŞLEMLERİ***********************/

}