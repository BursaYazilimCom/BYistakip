<?php

class InternalReklamModel extends Model
{

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
            'reklam_hesap_durumlari.uyari as durum_uyari'

        )
            ->innerjoin('reklam_platformlari.id','reklam_hesaplari.platform')
            ->innerjoin('reklam_hesap_durumlari.id','reklam_hesaplari.durum')
            ->limit(NULL,25)->orderby('reklam_hesaplari.id','DESC')->reklam_hesaplari();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }





    static function hesapTumListe(){
        $veri = DB::orderby('id','DESC')->reklam_hesaplari()->result();


        return $veri;

    }

    static function hesapEkle($data){

        $ekle = DB::insert('reklam_hesaplari',[
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