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

    static function uyeFaturalari($uye){

        $veri = DB::where('musteri',$uye)->faturalar()->result();

        return $veri;

    }

    static function ekle($data)
    {

        $ekle = DB::insert('faturalar',[
            'tur'               =>$data['tur'],
            'belge_no'          =>$data['belge_no'],
            'fatura_adi'        =>$data['fatura_adi'],
            'fatura_adresi'     =>$data['fatura_adresi'],
            'vergi_dairesi'     =>$data['vergi_dairesi'],
            'vergi_no'          =>$data['vergi_no'],
            'tedarikci'         =>$data['tedarikci'],
            'musteri'           =>$data['musteri'],
            'siparis_id'        =>$data['siparis_id'],
            'toplam_tutar'      =>$data['toplam_tutar'],
            'kdv_toplami'       =>$data['kdv_toplami'],
            'genel_toplam'      =>$data['genel_toplam'],
            'belge_tarihi'      =>$data['belge_tarihi'],
            'durum'             =>$data['durum'],
            'odeme'             =>$data['odeme'],
            'aciklama'          =>$data['aciklama']
        ]);

        //AyarModel::sqlHataEkle(DB::stringQuery());

        //echo DB::stringQuery();

        return DB::insertID();

    }

    static function urunDuzenlemeSonrasiGuncelleme($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('faturalar',[
                'toplam_tutar'  => $data["toplam_tutar"],
                'kdv_toplami'   => $data["kdv_toplami"],
                'genel_toplam'  => $data["genel_toplam"]
            ]);

        return $guncelle;

    }

    static function urunEkle($data)
    {

        $ekle = DB::insert('fatura_urunleri',[
            'fatura'    =>$data['fatura'],
            'urun'      =>$data['urun'],
            'urun_adi'  =>$data['urun_adi'],
            'aciklama'  =>$data['aciklama'],
            'miktar'    =>$data['miktar'],
            'fiyat'     =>$data['fiyat'],
            'kdv'       =>$data['kdv'],
            'kdv_tutari'       =>$data['kdv_tutari'],
            'tutar'     =>$data['tutar']
        ]);

        return DB::insertID();
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

    public function odemeEkle($data){

        $guncelle = DB::where('id',$data['id'])->update('faturalar',[
            'alinan_odeme'          =>$data['alinan_odeme']
        ]);

        return $guncelle;
    }

    /**
     * @param integer $id Fatura ID verisi, Faturalar veri tabanında ki fatura id'si ile fatura_urunleri tablosundan verileri çekmek için
     */

    static function faturaUrunleri($id){

        $data = DB::where('fatura',$id)->fatura_urunleri()->result();

        return $data;


    }

    static function faturaUrunDetay($id){

        $veri = DB::where('id',$id)->fatura_urunleri()->row();

        return $veri;



    }

    static function faturaUrunGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('fatura_urunleri',[
                'urun_adi'  => $data["urun_adi"],
                'miktar'    => $data["miktar"],
                'fiyat'     => $data["fiyat"],
                'kdv'       => $data["kdv"],
                'kdv_tutari'       => $data["kdv_tutari"],
                'tutar'     => $data["tutar"]
            ]);

        return $guncelle;

    }

    static function kdvHesapla($fiyat,$kdv){

        $kdv = ($fiyat/100)*$kdv;

        return $kdv;

    }

    static function kdvliFiyat($fiyat,$kdv){

        $kdvliFiyat = (($fiyat/100)*$kdv)+$fiyat;

        return $kdvliFiyat;

    }

     /**
     * @param integer $id Tedarikçi id
     * @param integer $tur Fatura türü 1 - Alış Faturası, 2 - Satış Faturası, 3 - İade Faturası
     */

    static function tedarikciFaturalari($id,$tur=""){
        //$tur = 1 - Alış Faturası
        //$tur = 2 - Satış Faturası
        //$tur = 3 - İade Faturası

        $veri = DB::where('tedarikci',$id)->where('tur',$tur)->orderby('id','desc')->faturalar();

        $data = ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

        return $data;


    }

    static function sil($id)
    {
        $faturaSil = DB::whereId($id)->delete('faturalar');

        $urunSil = DB::where('fatura',$id)->delete('fatura_urunleri');

        return $faturaSil;
    }

}