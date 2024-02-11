<?php

class InternalTeklifModel extends Model
{

    static function detay($id)
    {
        return DB::where('id',$id)->teklifler()->row();
    }


    static function liste(){

        $veri = DB::orderby('id','DESC')->limit(NULL,25)->teklifler();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function cariTeklifleri($uye){

        $veri = DB::where('musteri',$uye)->orderby('id','DESC')->teklifler()->result();

        return $veri;

    }

    static function ekle($data){

        $ekle = DB::insert('teklifler',[
            'belge_no'          =>$data['belge_no'],
            'fatura_adi'        =>$data['fatura_adi'],
            'fatura_adresi'     =>$data['fatura_adresi'],
            'musteri'           =>$data['musteri'],
            'durum'             =>$data['durum'],
            'aciklama'          =>$data['aciklama']
        ]);

        $eklenenId = DB::insertID();

        //AyarModel::sqlHataEkle(DB::stringQuery());

        echo DB::stringQuery();

        return $eklenenId;

    }

    static function guncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('teklifler',[
                'belge_no'          =>$data["belge_no"],
                'fatura_adi'        =>$data["fatura_adi"],
                'fatura_adresi'     =>$data["fatura_adresi"],
                'durum'             =>$data['durum'],
                'odeme_yontemi'     =>$data["odeme_yontemi"],
                'aciklama'          =>$data["aciklama"]
        ]);
        return $guncelle;
    }

    static function urunEkle($data)
    {

        $ekle = DB::insert('teklif_urunleri',[
            'teklif'                =>$data['teklif'],
            'urun'                  =>$data['urun'],
            'urun_adi'              =>$data['urun_adi'],
            'aciklama'              =>$data['aciklama'],
            'miktar'                =>$data['miktar'],
            'fiyat'                 =>$data['fiyat'],
            'kdv'                   =>$data['kdv'],
            'kdv_tutari'            =>$data['kdv_tutari'],
            'tutar'                 =>$data['tutar']
        ]);

        return DB::insertID();
    }



    static function teklifUrunleri($id){

        $data = DB::where('teklif',$id)->teklif_urunleri()->result();

        return $data;
        


    }

    static function teklifUrunDetay($id){

        $veri = DB::where('id',$id)->teklif_urunleri()->row();

        return $veri;



    }


    static function teklifUrunGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('teklif_urunleri',[
                'urun_adi'      => $data["urun_adi"],
                'aciklama'      => $data["aciklama"],
                'miktar'        => $data["miktar"],
                'fiyat'         => $data["fiyat"],
                'kdv'           => $data["kdv"],
                'kdv_tutari'    => $data["kdv_tutari"],
                'tutar'         => $data["tutar"]
            ]);

        return $guncelle;

    }


    static function teklifUrunSil($teklif,$urun){

        $urunSil = DB::where('teklif',$teklif)->where('id',$urun)->delete('teklif_urunleri');

        return $urunSil;
    }

    static function teklifUrunleriSil($teklif){

        $urunSil = DB::where('teklif',$teklif)->delete('teklif_urunleri');

        return $urunSil;
    }


    static function sil($id)
    {
        $teklifSil = DB::whereId($id)->delete('teklifler');

        $urunSil = DB::where('teklif',$id)->delete('teklif_urunleri');

        return $teklifSil;
    }

}