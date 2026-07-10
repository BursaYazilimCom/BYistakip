<?php

class InternalReklamModel extends Model
{

    static function periyod($id="")
    {
        //o:oran, t:tek sefer,u:ucretsiz,g:gunluk,h:haftalik,a:aylık,3a:3aylik,6a:6 aylık,y:yillik
        $periyodlar = [
            'O' => 'Oran',
            'T' => 'Tek Seferlik',
            'U' => 'Ücretsiz',
            'G' => 'Günlük',
            'H' => 'Haftalik',
            'A' => 'Aylık',
            '3A' => '3 Aylık',
            '6A' => '6 Aylık',
            'Y' => 'Yıllık'
        ];
            if ($id==""){
                $veri = $periyodlar;
            }else{
                $veri = $periyodlar[$id];
            }

        return $veri;

    }

    static function platformlar(){
        $veri = DB::orderby('sira','asc')->reklam_platformlari()->result();

        return $veri;

    }

    static function hesap_durumlari(){
        $veri = DB::orderby('id','DESC')->reklam_hesap_durumlari()->result();

        return $veri;

    }

    static function hesapDetay($id)
    {
        $veri =  DB::where('reklam_hesaplari.id',$id)
            ->reklam_hesaplari()
            ->row();

        //DB::stringQuery();

        return $veri;

    }

    static function hesapListe(){
        $veri = DB::select(
            'reklam_hesaplari.id as id',
            'reklam_hesaplari.cari as cari',
            'reklam_hesaplari.ads_id as ads_id',
            'reklam_hesaplari.mail_adresi as mail_adresi',
            'reklam_hesaplari.mail_adresi2 as mail_adresi2',
            'reklam_hesaplari.sifre as sifre',
            'reklam_hesaplari.reklam_url as reklam_url',
            'reklam_hesaplari.rvd as rvd',
            'reklam_hesaplari.durum as durum',
            'reklam_hesaplari.aciklama as aciklama',
            'reklam_hesaplari.ek_not as ek_not',
            'reklam_hesaplari.acilis_tarihi as acilis_tarihi',
            'reklam_hesaplari.dogrulama_tel as dogrulama_tel',
            'reklam_hesaplari.odeme_yontemi as odeme_yontemi',
            'reklam_hesaplari.odeme_araci as odeme_araci',
            'reklam_hesaplari.proxy as proxy',
            'reklam_platformlari.adi as platform_adi',
            'reklam_hesap_durumlari.adi as durum_adi',
            'reklam_hesap_durumlari.uyari as durum_uyari',
            'reklam_odeme_araclari.numara as odemeAraci',
            'cari.adi as cariAdi'

        )
            ->innerjoin('reklam_odeme_araclari.id','reklam_hesaplari.odeme_araci')
            ->innerjoin('reklam_platformlari.id','reklam_hesaplari.platform')
            ->innerjoin('reklam_hesap_durumlari.id','reklam_hesaplari.durum')
            ->innerjoin('cari.id','reklam_hesaplari.cari')
            ->limit(NULL,25)->orderby('reklam_hesaplari.id','DESC')->reklam_hesaplari();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function hesapTumListe(){
        $veri = DB::orderby('id','DESC')->reklam_hesaplari()->result();


        return $veri;

    }

    static function hesapEkle($data){

        $ekle = DB::insert('reklam_hesaplari',[
            'cari'          =>$data['cari'],
            'ads_id'        =>$data['ads_id'],
            'mail_adresi'   =>$data['mail_adresi'],
            'mail_adresi2'  =>$data['mail_adresi2'],
            'sifre'         =>$data['sifre'],
            'reklam_url'    =>$data['reklam_url'],
            'rvd'           =>$data['rvd'],
            'dogrulama_tel' =>$data['dogrulama_tel'],
            'odeme_yontemi' =>$data['odeme_yontemi'],
            'odeme_araci'   =>$data['odeme_araci'],
            'proxy'         =>$data['proxy'],
            'aciklama'      =>$data['aciklama'],
            'ek_not'        =>$data['ek_not'],
            'platform'      =>$data['platform'],
            'acilis_tarihi' =>$data['acilis_tarihi'],
            'durum'         =>$data['durum']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function hesapGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
                    ->update('reklam_hesaplari',[
                        'cari'          =>$data['cari'],
                        'ads_id'        =>$data['ads_id'],
                        'mail_adresi'   =>$data['mail_adresi'],
                        'mail_adresi2'  =>$data['mail_adresi2'],
                        'sifre'         =>$data['sifre'],
                        'reklam_url'    =>$data['reklam_url'],
                        'rvd'           =>$data['rvd'],
                        'dogrulama_tel' =>$data['dogrulama_tel'],
                        'odeme_yontemi' =>$data['odeme_yontemi'],
                        'odeme_araci'   =>$data['odeme_araci'],
                        'proxy'         =>$data['proxy'],
                        'aciklama'      =>$data['aciklama'],
                        'ek_not'        =>$data['ek_not'],
                        'platform'      =>$data['platform'],
                        'acilis_tarihi' =>$data['acilis_tarihi'],
                        'durum'         =>$data['durum']
                    ]);
       // echo DB::stringQuery();
        return $guncelle;
    }

    static function hesapSil($id){

        $sil        = DB::whereId($id)->delete('reklam_hesaplari');

        return $sil;
    }

    /******************************/

    static function anlasmaDetay($id)
    {
        $veri =  DB::where('reklam_anlasmalari.id',$id)
            ->reklam_anlasmalari()
            ->row();

        //DB::stringQuery();

        return $veri;

    }

    static function anlasmaListe(){
        $veri = DB::select(
            'reklam_anlasmalari.id as id',
            'reklam_anlasmalari.cari as cari',
            'reklam_anlasmalari.periyod as periyod',
            'reklam_anlasmalari.ucret as ucret',
            'reklam_anlasmalari.baslangic_tarihi as baslangic_tarihi',
            'reklam_anlasmalari.bitis_tarihi as bitis_tarihi',
            'reklam_anlasmalari.detay as detay',
            'reklam_anlasmalari.durum as durum',
            'cari.adi as cariAdi'

        )
            ->innerjoin('cari.id','reklam_anlasmalari.cari')
            ->limit(NULL,25)->orderby('reklam_anlasmalari.id','DESC')->reklam_anlasmalari();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function anlasmaEkle($data){

        $ekle = DB::insert('reklam_anlasmalari',[
            'cari'              =>$data['cari'],
            'periyod'           =>$data['periyod'],
            'ucret'             =>$data['ucret'],
            'baslangic_tarihi'  =>$data['baslangic_tarihi'],
            'bitis_tarihi'      =>$data['bitis_tarihi'],
            'detay'             =>$data['detay'],
            'durum'             =>$data['durum']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function anlasmaGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('reklam_anlasmalari',[
                'cari'              =>$data['cari'],
                'periyod'           =>$data['periyod'],
                'ucret'             =>$data['ucret'],
                'baslangic_tarihi'  =>$data['baslangic_tarihi'],
                'bitis_tarihi'      =>$data['bitis_tarihi'],
                'detay'             =>$data['detay'],
                'durum'             =>$data['durum']
            ]);
        // echo DB::stringQuery();
        return $guncelle;
    }

    static function anlasmaSil($id){

        $sil        = DB::whereId($id)->delete('reklam_anlasmalari');

        return $sil;
    }

    /************************************/

    static function odemeAracDetay($id)
    {
        $veri =  DB::where('reklam_odeme_araclari.id',$id)
            ->reklam_odeme_araclari()
            ->row();

        //DB::stringQuery();

        return $veri;

    }

    static function odemeAracListe(){
        $veri = DB::limit(NULL,25)->orderby('reklam_odeme_araclari.id','DESC')->reklam_odeme_araclari();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function odemeAracTumListe($durum=""){

        if($durum==""){
            $veri = DB::orderby('reklam_odeme_araclari.id','DESC')->reklam_odeme_araclari()->result();
        }else{
            $veri = DB::orderby('reklam_odeme_araclari.id','DESC')->where('durum',$durum)->reklam_odeme_araclari()->result();
        }

        return $veri;

    }

    static function odemeAracEkle($data){

        $ekle = DB::insert('reklam_odeme_araclari',[
            'tur'           =>$data['tur'],
            'numara'        =>$data['numara'],
            'cvv'           =>$data['cvv'],
            'son_kullanim'  =>$data['son_kullanim'],
            'sahibi'        =>$data['sahibi'],
            'aciklama'        =>$data['aciklama'],
            'durum'         =>$data['durum']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function odemeAracGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('reklam_odeme_araclari',[
                'tur'           =>$data['tur'],
                'numara'        =>$data['numara'],
                'cvv'           =>$data['cvv'],
                'son_kullanim'  =>$data['son_kullanim'],
                'sahibi'        =>$data['sahibi'],
                'aciklama'        =>$data['aciklama'],
                'durum'         =>$data['durum']
            ]);
        // echo DB::stringQuery();
        return $guncelle;
    }

    static function odemeAracSil($id){

        $sil        = DB::whereId($id)->delete('reklam_odeme_araclari');

        return $sil;
    }

    /************************************/

    static function ara($key){

        $veri = DB::where('durum','1')->where('adi like', DB::like($key, 'inside'))->urunler()->result();

        //AyarModel::sqlHataEkle(DB::stringQuery());

        return $veri;

    }
    
    /*****************************************************/



    /*****************************************************/



    /*****************************************************/


    
    /*****************************************************/
}