<?php

class InternalPersonelModel extends Model
{
    static function detay($id)
    {
        $veri = DB::where('id',$id)->yonetim()->row();

        return $veri;
    }

    static function isim($id)
    {
        $veri = DB::where('id',$id)->yonetim()->row();

        return $veri->isim;
    }

    static function liste(){
        $veri= DB::limit(null,25)->yonetim();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function tumListe(){
        $veri= DB::yonetim()->result();

        return $veri;
    }

    static function calisanlar(){

            $veri= DB::where('ban','0')->orderby("isim","ASC")->yonetim()->result();

        return $veri;
    }

    static function ekle($data){

       $ekle = DB::insert('yonetim',[
            'username'      =>$data['username'],
            'password'      =>$data['password'],
            'email'         =>$data['email'],
            'isim'          =>$data['isim'],
            'resim'          =>$data['resim'],
            'telefon'       =>$data['telefon'],
            'notlar'        =>$data['notlar'],
            'unvan'         =>$data['unvan'],
            'ban'           =>$data['ban'],
            'aktiflik'      =>$data['aktiflik'],
            'panel_rengi'   =>$data['panel_rengi'],
            'aktivasyon'    =>$data['aktivasyon']
        ]);

       //echo DB::stringQuery();

       return $ekle;
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'username'      =>$data["username"],
                'email'         =>$data["email"],
                'isim'          =>$data["isim"],
                'resim'          =>$data["resim"],
                'telefon'       =>$data["telefon"],
                'notlar'        =>$data["notlar"],
                'unvan'         =>$data["unvan"],
                'ban'           =>$data["ban"],
                'aktivasyon'    =>$data['aktivasyon']
            ]);

        return $guncelle;
    }

    static function yetkiGuncelle($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'yetkiler'      =>$data["yetkiler"]
            ]);

        return $guncelle;
    }

    static function updatePassword($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'password'      =>$data["password"]
            ]);

        return $guncelle;
    }

    static function temaDegistir($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('yonetim',[
                'panel_rengi'      =>$data["panel_rengi"]
            ]);


        return $guncelle;
    }

    static function mesaiSaatleri($id,$sayfa=Null){

        $veri   = DB::where('personel',$id)->orderby('giris_tarihi','DESC')->limit($sayfa,31)->personel_mesai_takip();

        $data = ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];

        return $data;

    }

    static function aylikYolYemek($id,$ay,$yil){

        $data = DB::select('sum(yemek_hakedis) as yemekAdet','sum(ise_gelis_yol_ucreti) as iseGelisYol','sum(isten_cikis_yol_ucreti) as istenCikisYol')->where('ay',$ay)->where('yil',$yil)->where('personel',$id)->personel_mesai_takip()->row();

        //echo DB::stringQuery();

        return $data;
    }

    static function aylikCalismaSaatiToplamlari($id,$ay,$yil){

        $data   = DB::query('SELECT SUM(gunlukToplamCalismaSuresi) AS acs FROM personel_mesai_takip WHERE personel='.$id.' AND ay='.$ay.' AND yil='.$yil)->row();

        return $data;

    }

    static function haftalikCalismaSaatleri($id,$hafta,$ay,$yil){

        $data = DB::select(
            'SUM(gunlukToplamCalismaSuresi) AS haftalikToplamCalismaSaati',
            'SUM(gunlukCalismaSuresi) AS haftalikNormalCalismaSaati',
            'SUM(fazla_mesai_dakikasi) AS haftalikFazlaMesaiSaati'
            )->where('personel',$id)->where('yil',$yil)->where('ay',$ay)->where('hafta',$hafta)->personel_mesai_takip()->row();


        //echo DB::stringQuery();

        return $data;

    }

    static function gunlukMesaiSaatleriTarih($id,$gun){

        $data   = DB::where('personel',$id)->where('giris_tarihi',$gun)->personel_mesai_takip()->row();

        //echo DB::stringQuery();

        return $data;

    }

    static function mesaiEkle($data){

        $ekle = DB::insert('personel_mesai_takip',[
            'personel'                  =>$data['personel'],
            'yil'                       =>$data['yil'],
            'ay'                        =>$data['ay'],
            'hafta'                     =>$data['hafta'],
            'gun'                       =>$data['gun'],
            'giris_tarihi'              =>$data['giris_tarihi'],
            'giris_saati'               =>$data['giris_saati'],
            'giris_tarih_saat'          =>$data['giris_tarih_saat'],
            'cikis_tarihi'              =>$data['cikis_tarihi'],
            'cikis_saati'               =>$data['cikis_saati'],
            'cikis_tarih_saat'          =>$data['cikis_tarih_saat'],
            'fazla_mesai_dakikasi'      =>$data['fazla_mesai_dakikasi'],
            'fazla_mesai_sebebi'        =>$data['fazla_mesai_sebebi'],
            'gunlukCalismaSuresi'       =>$data['gunlukCalismaSuresi'],
            'gunlukToplamCalismaSuresi' =>$data['gunlukToplamCalismaSuresi'],
            'gunluk_not'                =>$data['gunluk_not'],
            'dakika_dusus_notlari'      =>$data['dakika_dusus_notlari'],
            'yemek_hakedis'             =>$data['yemek_hakedis'],
            'ise_gelis_yol_ucreti'      =>$data['ise_gelis_yol_ucreti'],
            'isten_cikis_yol_ucreti'    =>$data['isten_cikis_yol_ucreti'],
            'izin_durumu'               =>$data['izin_durumu'],
            'kayit_turu'                =>$data['kayit_turu'],
            'gec_gelme_durumu'          =>$data['gec_gelme_durumu'],
            'kayit_yapan_personel'      =>$data['kayit_yapan_personel']

        ]);

        echo DB::stringQuery();

        return $ekle;

    }

    static function mesaiSaatiSil($id){

        return DB::whereId($id)->delete('personel_mesai_takip');

    }

    static function delete($id){
        return DB::whereId($id)->delete('yonetim');
    }
}