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

    static function siparisOdenmemisFaturaToplami($id){

        $veri = DB::select('SUM(genel_toplam) AS toplam')->where('siparis_id',$id)->where('tur','2')->Where('odeme','0')->orderby('id','DESC')->faturalar()->row();

        return $veri;


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

    static function odemeIslemiEkle($data){

        $ekle = DB::insert('fatura_odeme_islemleri',[
            'fatura_id'     =>$data['fatura_id'],
            'tutar'         =>$data['tutar'],
            'aciklama'      =>$data['aciklama']
        ]);

        return DB::insertID();
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

    static function cariFaturalari($uye,$odeme="",$sayfa=Null){

        if($odeme==""){

            $veri = DB::where('musteri',$uye)->orderby('id','DESC')->limit($sayfa,20)->faturalar();

        }else{

            $veri = DB::where('musteri',$uye)->where('odeme',$odeme)->orderby('id','DESC')->limit($sayfa,20)->faturalar();

        }

        $data = ["liste"=>$veri->result(),"sayfalama"=>$veri->pagination()];

        return $data;

    }

    static function cariFaturaToplamlari($uye,$odeme=""){

        if($odeme==""){

            $veri = DB::select('SUM(genel_toplam) as toplam')->where('musteri',$uye)->faturalar()->row();

        }else{

            $veri = DB::select('SUM(genel_toplam) as toplam')->where('musteri',$uye)->where('odeme',$odeme)->faturalar()->row();

        }

        return $veri;

    }


}