<?php

class InternalUrunModel extends Model
{
    static function detay($id)
    {
        $veri =  DB::select(
                'urunler.id as id',
                'urunler.tedarikci as tedarikci',
                'urunler.urun_kodu as urun_kodu',
                'urunler.adi as adi',
                'urunler.resim as resim',
                'urunler.fiyat as fiyat',
                'urunler.aylik_fiyat as aylik_fiyat',
                'urunler.uc_aylik_fiyat as uc_aylik_fiyat',
                'urunler.alti_aylik_fiyat as alti_aylik_fiyat',
                'urunler.yillik_fiyat as yillik_fiyat',
                'urunler.fiyat_birim as fiyat_birim',
                'urunler.kdv as kdv',
                'urunler.odeme_turu as odeme_turu',
                'urunler.aciklama as aciklama',
                'urunler.detay as detay',
                'urunler.durum as durum',
                'urunler.guncel_stok as guncel_stok',
                'urunler.stoklu_urun as stoklu_urun',
                'urun_gruplari.id as grupId',
                'urun_gruplari.adi as grupAdi'
                )
            ->innerjoin('urun_gruplari.id','urunler.grup')
            ->where('urunler.id',$id)
            ->urunler()
            ->row();

        //DB::stringQuery();

        return $veri;

    }

    static function liste(){
        $veri = DB::select(
            'urunler.id as id',
            'urunler.tedarikci as tedarikci',
            'urunler.urun_kodu as urun_kodu',
            'urunler.adi as adi',
            'urunler.resim as resim',
            'urunler.fiyat as fiyat',
            'urunler.aylik_fiyat as aylik_fiyat',
            'urunler.uc_aylik_fiyat as uc_aylik_fiyat',
            'urunler.alti_aylik_fiyat as alti_aylik_fiyat',
            'urunler.yillik_fiyat as yillik_fiyat',
            'urunler.fiyat_birim as fiyat_birim',
            'urunler.kdv as kdv',
            'urunler.odeme_turu as odeme_turu',
            'urunler.aciklama as aciklama',
            'urunler.detay as detay',
            'urunler.durum as durum',
            'urunler.guncel_stok as guncel_stok',
            'urunler.stoklu_urun as stoklu_urun',
            'urun_gruplari.id as grupId',
            'urun_gruplari.adi as grupAdi'
            )
            ->innerjoin('urun_gruplari.id','urunler.grup')
            ->limit(NULL,25)
            ->orderby('id','DESC')->urunler();


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function grupListe($id,$sayfa){
        $veri = DB::select(
            'urunler.id as id',
            'urunler.tedarikci as tedarikci',
            'urunler.urun_kodu as urun_kodu',
            'urunler.adi as adi',
            'urunler.resim as resim',
            'urunler.fiyat as fiyat',
            'urunler.aylik_fiyat as aylik_fiyat',
            'urunler.uc_aylik_fiyat as uc_aylik_fiyat',
            'urunler.alti_aylik_fiyat as alti_aylik_fiyat',
            'urunler.yillik_fiyat as yillik_fiyat',
            'urunler.fiyat_birim as fiyat_birim',
            'urunler.kdv as kdv',
            'urunler.odeme_turu as odeme_turu',
            'urunler.aciklama as aciklama',
            'urunler.detay as detay',
            'urunler.durum as durum',
            'urunler.guncel_stok as guncel_stok',
            'urunler.stoklu_urun as stoklu_urun',
            'urun_gruplari.id as grupId',
            'urun_gruplari.adi as grupAdi'
            )
            ->innerjoin('urun_gruplari.id','urunler.grup')
            ->where('urunler.grup',$id)
            ->limit($sayfa,25)
            ->orderby('id','DESC')->urunler();


        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

    }

    static function urunAdi($id){
        $veri =  DB::select(
            'urunler.adi as adi'
        )
            ->where('urunler.id',$id)
            ->urunler()
            ->row();

        return $veri->adi;
    }

    static function tumListe(){
        $veri = DB::select(
            'urunler.id as id',
            'urunler.tedarikci as tedarikci',
            'urunler.urun_kodu as urun_kodu',
            'urunler.adi as adi',
            'urunler.resim as resim',
            'urunler.fiyat as fiyat',
            'urunler.aylik_fiyat as aylik_fiyat',
            'urunler.uc_aylik_fiyat as uc_aylik_fiyat',
            'urunler.alti_aylik_fiyat as alti_aylik_fiyat',
            'urunler.yillik_fiyat as yillik_fiyat',
            'urunler.fiyat_birim as fiyat_birim',
            'urunler.kdv as kdv',
            'urunler.odeme_turu as odeme_turu',
            'urunler.aciklama as aciklama',
            'urunler.detay as detay',
            'urunler.durum as durum',
            'urunler.guncel_stok as guncel_stok',
            'urunler.stoklu_urun as stoklu_urun',
            'urun_gruplari.id as grupId',
            'urun_gruplari.adi as grupAdi'
        )
            ->innerjoin('urun_gruplari.id','urunler.grup')
            ->orderby('id','DESC')->urunler()->result();


        return $veri;

    }

    static function grupUrunleriTumListe($id){
        $veri = DB::select(
            'urunler.id as id',
            'urunler.tedarikci as tedarikci',
            'urunler.urun_kodu as urun_kodu',
            'urunler.adi as adi',
            'urunler.resim as resim',
            'urunler.fiyat as fiyat',
            'urunler.aylik_fiyat as aylik_fiyat',
            'urunler.uc_aylik_fiyat as uc_aylik_fiyat',
            'urunler.alti_aylik_fiyat as alti_aylik_fiyat',
            'urunler.yillik_fiyat as yillik_fiyat',
            'urunler.fiyat_birim as fiyat_birim',
            'urunler.kdv as kdv',
            'urunler.odeme_turu as odeme_turu',
            'urunler.aciklama as aciklama',
            'urunler.detay as detay',
            'urunler.durum as durum',
            'urunler.guncel_stok as guncel_stok',
            'urunler.stoklu_urun as stoklu_urun',
            'urun_gruplari.id as grupId',
            'urun_gruplari.adi as grupAdi'
        )
            ->innerjoin('urun_gruplari.id','urunler.grup')
            ->where('urunler.grup',$id)
            ->orderby('id','DESC')->urunler();


        return ['liste'=>$veri->result()];

    }

    static function ekle($data){

        $ekle = DB::insert('urunler',[
            'tedarikci'     =>$data['tedarikci'],
            'urun_kodu'     =>$data['urun_kodu'],
            'grup'          =>$data['grup'],
            'adi'           =>$data['adi'],
            'resim'           =>$data['resim'],
            'fiyat'         =>$data['fiyat'],
            'aylik_fiyat'         =>$data['aylik_fiyat'],
            'uc_aylik_fiyat'         =>$data['uc_aylik_fiyat'],
            'alti_aylik_fiyat'         =>$data['alti_aylik_fiyat'],
            'yillik_fiyat'         =>$data['yillik_fiyat'],
            'fiyat_birim'   =>$data['fiyat_birim'],
            'kdv'           =>$data['kdv'],
            'odeme_turu'    =>$data['odeme_turu'],
            'aciklama'      =>$data['aciklama'],
            'detay'      =>$data['detay'],
            'durum'         =>$data['durum'],
            'stoklu_urun'   =>$data['stoklu_urun'],
            'guncel_stok'   =>$data['guncel_stok']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function guncelle($data){

        $guncelle = DB::where('id',$data["id"])
                    ->update('urunler',[
                        'urun_kodu'     =>$data['urun_kodu'],
                        'tedarikci'     =>$data['tedarikci'],
                        'grup'          =>$data['grup'],
                        'adi'           =>$data['adi'],
                        'resim'           =>$data['resim'],
                        'fiyat'         =>$data['fiyat'],
                        'aylik_fiyat'         =>$data['aylik_fiyat'],
                        'uc_aylik_fiyat'         =>$data['uc_aylik_fiyat'],
                        'alti_aylik_fiyat'         =>$data['alti_aylik_fiyat'],
                        'yillik_fiyat'         =>$data['yillik_fiyat'],
                        'fiyat_birim'   =>$data['fiyat_birim'],
                        'kdv'           =>$data['kdv'],
                        'odeme_turu'    =>$data['odeme_turu'],
                        'aciklama'      =>$data['aciklama'],
                        'detay'      =>$data['detay'],
                        'durum'         =>$data['durum'],
                        'stoklu_urun'   =>$data['stoklu_urun'],
                        'guncel_stok'   =>$data['guncel_stok']
                    ]);
       // echo DB::stringQuery();
        return $guncelle;
    }

    static function sil($id){

        $sil        = DB::whereId($id)->delete('urunler');

        return $sil;
    }

    static function ara($key){

        $veri = DB::where('durum','1')->where('adi like', DB::like($key, 'inside'))->urunler()->result();

        //AyarModel::sqlHataEkle(DB::stringQuery());

        return $veri;

    }
    
    /*****************************************************/

    static function stokluUrunStokGuncelle($urun,$miktar){

        $guncelle = DB::where('id',$urun)
            ->update('urunler',[
                'guncel_stok'  =>$miktar
            ]);

        return $guncelle;

    }



    /*****************************************************/
    static function urunOzellikKontrol($urun,$ozellik)
    {

        $veri =  DB::where('urun',$urun)->where('ozellik',$ozellik)->urun_ozellikleri()->row();

        return $veri;

    }

    static function urunOzellikDeger($urun,$ozellik)
    {

        $veri =  DB::where('urun',$urun)->where('ozellik',$ozellik)->urun_ozellikleri()->row();

        return $veri->deger;

    }

    static function urunOzellikEkle($data){

        $ekle = DB::insert('urun_ozellikleri',[
            'urun'     =>$data['urun'],
            'ozellik'  =>$data['ozellik'],
            'deger'    =>$data['deger']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function urunOzellikGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('urun_ozellikleri',[
                'deger'    =>$data['deger']
            ]);
        // echo DB::stringQuery();
        return $guncelle;
    }



    /*****************************************************/



    static function urunGrupDetay($id)
    {
        return DB::select(
            'urun_gruplari.id as id',
            'urun_gruplari.adi as adi',
            'urun_gruplari.aciklama as aciklama',
            'urun_gruplari.sira as sira',
            'urun_gruplari.urun_gorunumu as urun_gorunumu',
            'urun_gruplari.tur as tur',
            'urun_gruplari.durum as durum'
            )
            ->where('urun_gruplari.id',$id)->urun_gruplari()->row();
    }

    static function urunGrupOzellikDetay($id)
    {
        return DB::where('id',$id)->urun_grup_ozellikleri()->row();
    }

    static function urunGrupOzellikleri($id)
    {
        $veri =  DB::where('grup',$id)->urun_grup_ozellikleri()->result();

        //echo DB::stringQuery();

        return $veri;
    }

    static function urunGrupOzellikEkle($data){

        $ekle = DB::insert('urun_grup_ozellikleri',[
            'grup'          =>$data['grup'],
            'sira'          =>$data['sira'],
            'tur'           =>$data['tur'],
            'baslik'        =>$data['baslik'],
            'gereklilik'    =>$data['gereklilik'],
            'yer'           =>$data['yer'],
            'durum'         =>$data['durum']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function urunGrupOzellikGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('urun_grup_ozellikleri',[
                'sira'          =>$data['sira'],
                'tur'           =>$data['tur'],
                'baslik'        =>$data['baslik'],
                'gereklilik'    =>$data['gereklilik'],
                'yer'           =>$data['yer'],
                'durum'         =>$data['durum']
            ]);

        return $guncelle;
    }

    static function urunGrupOzellikSil($id){
        return DB::whereId($id)->delete('urun_grup_ozellikleri');
    }

    static function urunGrupOzellikleriniSil($id){
        return DB::where('grup',$id)->delete('urun_grup_ozellikleri');
    }
    static function urunOzellikleriniSil($id){
        return DB::where('urun',$id)->delete('urun_ozellikleri');
    }

    static function urunGrupListe(){
        $veri = DB::select(
                           'urun_gruplari.id as id',
                           'urun_gruplari.adi as adi',
                           'urun_gruplari.aciklama as aciklama',
                           'urun_gruplari.sira as sira',
                           'urun_gruplari.urun_gorunumu as urun_gorunumu',
                           'urun_gruplari.tur as tur',
                           'urun_gruplari.durum as durum'
                            )
               ->orderby('urun_gruplari.sira','ASC')->urun_gruplari()->result();

        return $veri;

    }

    static function urunGrupEkle($data){

        $ekle = DB::insert('urun_gruplari',[
            'adi'               =>$data['adi'],
            'aciklama'          =>$data['aciklama'],
            'sira'              =>$data['sira'],
            'urun_gorunumu'     =>$data['urun_gorunumu'],
            'tur'               =>$data['tur'],
            'durum'             =>$data['durum']
        ]);

        //echo DB::stringQuery();

        return DB::insertID();
    }

    static function urunGrupGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('urun_gruplari',[
                'adi'               =>$data['adi'],
                'aciklama'          =>$data['aciklama'],
                'sira'              =>$data['sira'],
                'urun_gorunumu'     =>$data['urun_gorunumu'],
                'tur'               =>$data['tur'],
                'durum'             =>$data['durum']
            ]);

        return $guncelle;
    }

    static function urunGrupSiraGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('urun_gruplari',[
                'sira'      =>$data['sira']
            ]);

        return $guncelle;
    }

    static function urunGrupSil($id){
        return DB::whereId($id)->delete('urun_gruplari');
    }
    
    /*****************************************************/
}