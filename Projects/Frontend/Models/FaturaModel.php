<?php

class InternalFaturaModel extends Model
{

    static function detay($id)
    {
        return DB::where('id',$id)->faturalar()->row();
    }

    static function bengeNoDetay($no)
    {
        return DB::where('belge_no',$no)->faturalar()->row();
    }

    static function liste(){

        $veri = DB::orderby('id','DESC')->limit(NULL,10)->faturalar();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function siparisFaturalari($id){

        $veri = DB::whereGroup(
            ['siparis_id',$id],
            'and'
        )->whereGroup(
            ['tur','3','or'],
            ['tur','2',]
        )->orderby('id','DESC')->faturalar()->result();

        return ['liste'=>$veri];


    }

    static function uyeFaturalari($uye){

        $veri = DB::where('musteri',$uye)->faturalar()->result();

        return $veri;

    }




    static function odemeDurumDegistir($id, $durum,$odeme_tarihi=""){

        if ($odeme_tarihi != ""){
            $guncelle = DB::where('id',$id)
                ->update('faturalar',[
                    'odeme'  => $durum,
                    'odeme_tarihi'  => $odeme_tarihi
                ]);
        }else{
            $guncelle = DB::where('id',$id)
                ->update('faturalar',[
                    'odeme'  => $durum
                ]);
        }


        return $guncelle;
    }

    static function odemeEkle($data){

        $guncelle = DB::where('id',$data['id'])->update('faturalar',[
            'alinan_odeme'          =>$data['alinan_odeme']
        ]);

        return $guncelle;
    }




    static function faturaUrunleri($id){

        $data = DB::where('fatura',$id)->fatura_urunleri()->result();

        return $data;


    }

    static function faturaUrunDetay($id){

        $veri = DB::where('id',$id)->fatura_urunleri()->row();

        return $veri;

    }


    static function kdvHesapla($fiyat,$kdv){

        $kdv = ($fiyat/100)*$kdv;

        return $kdv;

    }

    static function kdvliFiyat($fiyat,$kdv){

        $kdvliFiyat = (($fiyat/100)*$kdv)+$fiyat;

        return $kdvliFiyat;

    }


}