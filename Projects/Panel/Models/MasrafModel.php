<?php

class InternalMasrafModel extends Model
{

    public function bilgi($id){

        $veri = DB::where('id',$id)->masraf_kalemleri()->row();

        return $veri;

    }

    public function kayitlar($id,$sayfa=Null){

        $kayitlar = DB::select(
            'masraf_kalemleri.adi as masrafKalemi',
            'kasa_hesaplari.adi as kasaHesabi',
            'masraflar.id as id',
            'masraflar.belge_no as belge_no',
            'masraflar.belge_dosya as belge_dosya',
            'masraflar.aciklama as aciklama',
            'masraflar.odeme_durumu as odeme_durumu',
            'masraflar.tutar as tutar',
            'masraflar.odeme_tarihi as odeme_tarihi',
            'masraflar.islem_tarihi as islem_tarihi'
        )
            ->innerjoin('masraf_kalemleri.id','masraflar.kalem')
            ->innerjoin('kasa_hesaplari.id','masraflar.kasa')
            ->where('masraflar.kalem',$id)
            ->orderby('id','DESC')
            ->limit($sayfa,25)->masraflar();

        $veri = ['liste'=>$kayitlar->result(),'sayfalama'=>$kayitlar->pagination()];

        return $veri;

    }

    public function tumKayitlar(){

        $kayitlar = DB::select(
            'masraf_kalemleri.adi as masrafKalemi',
            'masraf_kalemleri.renk as renk',
            'kasa_hesaplari.adi as kasaHesabi',
            'masraflar.id as id',
            'masraflar.belge_no as belge_no',
            'masraflar.belge_dosya as belge_dosya',
            'masraflar.aciklama as aciklama',
            'masraflar.odeme_durumu as odeme_durumu',
            'masraflar.tutar as tutar',
            'masraflar.odeme_tarihi as odeme_tarihi',
            'masraflar.islem_tarihi as islem_tarihi'
        )
            ->innerjoin('masraf_kalemleri.id','masraflar.kalem')
            ->innerjoin('kasa_hesaplari.id','masraflar.kasa')
            ->limit(NULL,25)
            ->orderby('id','DESC')->masraflar();

        $veri = ['liste'=>$kayitlar->result(),'sayfalama'=>$kayitlar->pagination()];

        return $veri;

    }

    public function masrafKalemleri(){

        $anaKalemler        = DB::where('ana_kalem','1')->masraf_kalemleri()->result();
        $altMasrafKalemleri = DB::where('ana_kalem','0')->masraf_kalemleri()->result();

        $veri = [
            'anaKalemler'=>$anaKalemler,
            'altKalemler'=>$altMasrafKalemleri
        ];

        return $veri;
    }

    public function anaKalemEkle($data){

        $ekle = DB::insert('masraf_kalemleri',[
            'ana_kalem' =>'1',
            'ust'       =>'0',
            'adi'       =>$data['adi'],
            'renk'      =>$data['renk']
        ]);

        return $ekle;
    }

    public function anaKalemGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('masraf_kalemleri',[
                'ana_kalem'     =>'1',
                'ust'           =>'0',
                'adi'           =>$data['adi'],
                'renk'          =>$data['renk']
            ]);

        return $guncelle;
    }

    public function anaKalemSil($id){
        return DB::whereId($id)->delete('masraf_kalemleri');
    }

    public function altKalemEkle($data){

        $ekle = DB::insert('masraf_kalemleri',[
            'ana_kalem' =>'0',
            'ust'       =>$data['ust'],
            'adi'       =>$data['adi'],
            'renk'      =>$data['renk']
        ]);

        return $ekle;
    }
    public function altKalemGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('masraf_kalemleri',[
                'ana_kalem'     =>'0',
                'ust'           =>$data['ust'],
                'adi'           =>$data['adi'],
                'renk'          =>$data['renk']
            ]);

        return $guncelle;
    }

    public function kalemSil($id){
        return DB::whereId($id)->delete('masraf_kalemleri');
    }

    public function masrafEkle($data){

        $ekle = DB::insert('masraflar',[
            'kalem'         =>$data['kalem'],
            'kasa'          =>$data['kasa'],
            'belge_no'      =>$data['belge_no'],
            'belge_dosya'   =>$data['belge_dosya'],
            'aciklama'      =>$data['aciklama'],
            'odeme_durumu'  =>$data['odeme_durumu'],
            'tutar'         =>$data['tutar'],
            'odeme_tarihi'  =>$data['odeme_tarihi']
        ]);
        //echo DB::stringQuery();
        return $ekle;
    }

    public function giderSil($id){
        return DB::whereId($id)->delete('masraflar');
    }

}