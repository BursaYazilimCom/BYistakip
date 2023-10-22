<?php

class InternalIlanModel extends Model
{
    static function elemanIlanDetay($id)
    {
        return DB::where('id',$id)->elaman_ilanlari()->row();
    }

    static function elemanIlanListe(){
        $veri= DB::limit(null,25)->elaman_ilanlari();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function elemanIlanEkle($data){

       $ekle = DB::insert('elaman_ilanlari',[
            'firma_id'          =>$data['firma_id'],
            'kategori'          =>$data['kategori'],
            'sektor'            =>$data['sektor'],
            'baslik'            =>$data['baslik'],
            'aciklama'          =>$data['aciklama'],
            'calisma_sekli'     =>$data['calisma_sekli'],
            'cinsiyet'          =>$data['cinsiyet'],
            'egitim_seviyesi'   =>$data['egitim_seviyesi'],
            'il'                =>$data['il'],
            'ilce'              =>$data['ilce'],
            'personel_sayisi'   =>$data['personel_sayisi'],
            'aylik_ucret'       =>$data['aylik_ucret']
        ]);

       //echo DB::stringQuery();

       return $ekle;
    }

    static function elemanIlanGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('elaman_ilanlari',[
                'firma_id'          =>$data['firma_id'],
                'kategori'          =>$data['kategori'],
                'sektor'            =>$data['sektor'],
                'baslik'            =>$data['baslik'],
                'aciklama'          =>$data['aciklama'],
                'calisma_sekli'     =>$data['calisma_sekli'],
                'cinsiyet'          =>$data['cinsiyet'],
                'egitim_seviyesi'   =>$data['egitim_seviyesi'],
                'il'                =>$data['il'],
                'ilce'              =>$data['ilce'],
                'personel_sayisi'   =>$data['personel_sayisi'],
                'aylik_ucret'       =>$data['aylik_ucret']
            ]);

        return $guncelle;
    }

    static function elemanIlanSil($id){
        return DB::whereId($id)->delete('elaman_ilanlari');
    }
}