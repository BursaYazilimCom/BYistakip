<?php

class InternalFaturaModel extends Model
{

    static function detay($id)
    {
        return DB::where('id',$id)->faturalar()->row();
    }

    static function siparisFaturaDetay($id)
    {
        return DB::where('siparis_id',$id)->where('satis_turu','1')->faturalar()->row();
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

    static function faturaListele($sorgu){

        if ($sorgu == 'tum') {
            $veri = DB::orderby('id','DESC')->faturalar();
        }elseif($sorgu == 'odenmeyen'){
            $veri = DB::where('odeme','0')->orderby('id','DESC')->faturalar();
        }elseif($sorgu == 'odenen'){
            $veri = DB::where('odeme','1')->orderby('id','DESC')->faturalar();
        }else{

        }

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows()];

    }

    static function cariFaturalari($uye,$odeme=""){

        if($odeme==""){

            $veri = DB::where('musteri',$uye)->orderby('id','DESC')->faturalar()->result();

        }else{

            $veri = DB::where('musteri',$uye)->where('odeme',$odeme)->orderby('id','DESC')->faturalar()->result();

        }

        return $veri;

    }

    static function cariFaturaToplamlari($uye,$odeme=""){

        if($odeme==""){

            $veri = DB::select('SUM(genel_toplam) as toplam')->where('musteri',$uye)->orderby('id','DESC')->faturalar()->row();

        }else{

            $veri = DB::select('SUM(genel_toplam) as toplam')->where('musteri',$uye)->where('odeme',$odeme)->orderby('id','DESC')->faturalar()->row();

        }

        return $veri;

    }

    static function faturaTekrarKonrolu($baslangic,$bitis,$urun){
        $veri = DB::where('donem_baslangic_tarihi',$baslangic)
            ->where('donem_bitis_tarihi',$bitis)
            ->where('siparis_urun_id',$urun)->orderby('id','DESC')->limit(1)->fatura_urunleri();
        
        $data = ['data'=>$veri->row(),'adet'=>$veri->totalRows()];

        //echo DB::stringQuery()."<br>";

        //print_r($data);

        return $data;
    }

    static function ekle($data){

        $ekle = DB::insert('faturalar',[
            'tur'               =>$data['tur'],
            'satis_turu'        =>$data['satis_turu'],
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
            'vade_tarihi'       =>$data['vade_tarihi'],
            'durum'             =>$data['durum'],
            'odeme'             =>$data['odeme'],
            'odeme_yontemi'     =>$data['odeme_yontemi'],
            'aciklama'          =>$data['aciklama']
        ]);

        $eklenenId = DB::insertID();

        //AyarModel::sqlHataEkle(DB::stringQuery());

        echo DB::stringQuery();

        return $eklenenId;

    }

    static function guncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('faturalar',[
                'belge_no'          =>$data["belge_no"],
                'fatura_adi'        =>$data["fatura_adi"],
                'fatura_adresi'     =>$data["fatura_adresi"],
                'vergi_dairesi'     =>$data["vergi_dairesi"],
                'vergi_no'          =>$data["vergi_no"],
                'tedarikci'         =>$data["tedarikci"],
                'musteri'           =>$data["musteri"],
                'belge_tarihi'      =>$data["belge_tarihi"],
                'vade_tarihi'       =>$data["vade_tarihi"],
                'odeme_yontemi'     =>$data["odeme_yontemi"],
                'aciklama'          =>$data["aciklama"]
        ]);
        return $guncelle;
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
            'fatura'                =>$data['fatura'],
            'urun'                  =>$data['urun'],
            'siparis_urun_id'       =>$data['siparis_urun_id'],
            'eklenecek_gun_sayisi'  =>$data['eklenecek_gun_sayisi'],
            'donem_baslangic_tarihi'=>$data['donem_baslangic_tarihi'],
            'donem_bitis_tarihi'    =>$data['donem_bitis_tarihi'],
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

    /**
     * @param integer $id Fatura ID verisi, Faturalar veri tabanında ki fatura id'si ile fatura_urunleri tablosundan verileri çekmek için
     */

    static function faturaResmilestir($data){
        $guncelle = DB::where('id',$data['id'])->update('faturalar',[
            'belge_no'  => $data['belge_no'],
            'resmi_fatura_dosyasi'  => $data['fatura_dosya'],
            'durum'  => $data['durum']
        ]);

        return $guncelle;

    }

    static function faturaTutarGuncelle($data){
        $guncelle = DB::where('id',$data['id'])->update('faturalar',[
            'toplam_tutar'  => $data['toplam_tutar'],
            'kdv_toplami'   => $data['kdv_toplami'],
            'genel_toplam'  => $data['genel_toplam']
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


    static function faturaUrunGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('fatura_urunleri',[
                'urun_adi'  => $data["urun_adi"],
                'aciklama' => $data["aciklama"],
                'miktar'    => $data["miktar"],
                'fiyat'     => $data["fiyat"],
                'kdv'       => $data["kdv"],
                'kdv_tutari'       => $data["kdv_tutari"],
                'tutar'     => $data["tutar"]
            ]);

        return $guncelle;

    }

    static function siparisFaturaUrunFiyatGuncelle($data){

        $guncelle = DB::where('fatura',$data["fatura_id"])->where('siparis_urun_id',$data["siparis_urun_id"])
            ->update('fatura_urunleri',[
                'fiyat'         => $data["fiyat"],
                'kdv_tutari'    => $data["kdv_tutari"],
                'tutar'         => $data["tutar"]
            ]);

        return $guncelle;

    }

    static function faturaUrunSil($fatura,$urun){

        $urunSil = DB::where('fatura',$fatura)->where('id',$urun)->delete('fatura_urunleri');

        return $urunSil;
    }

    static function faturaUrunieriSil($fatura){

        $urunSil = DB::where('fatura',$fatura)->delete('fatura_urunleri');

        return $urunSil;
    }

    static function tarihSonrasiOdenmemisFaturalar($tarih){

        $veri = DB::where('odeme','0')->where('vade_tarihi<',$tarih)->orderby('id','DESC')->faturalar();

        return $veri->result();

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

    static function siparisinFaturalariniSil($id)
    {

        $urunSil = DB::where('fatura',$id)->delete('fatura_urunleri');

        return $faturaSil;
    }

}