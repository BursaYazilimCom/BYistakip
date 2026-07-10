<?php

class InternalRaporModel extends Model
{

    public function kasaToplami(){
        $toplam = DB::select('SUM(tutar)')->kasa_hesaplari()->value();
        return $toplam;
    }

    public function gelirGiderToplami(){
        $toplam = DB::select('SUM(gelir) as gelir','SUM(gider) as gider')->kasa_defteri()->row();
        return $toplam;
    }

    public function gununGelirleri($tarih){


            $veri = DB::select('SUM(gelir) as gelir')->where('tarih',$tarih)->where('islem','t')->kasa_defteri()->row();

            //echo DB::stringQuery();

            $data = $veri->gelir;
    

        return $data;

    }

    public function gununGiderleri($tarih){

        $veri = DB::select('SUM(tutar) as tutar')->where('odeme_tarihi',$tarih)->masraflar()->row();

        return $veri;

    }

    public function gunBazindaGelir($baslangic,$bitis){

        $veri = DB::select('SUM(gelir) as gelir','tarih')->where('tarih>=',$baslangic)->where('tarih<=',$bitis)->where('islem','t')->groupby('tarih')->kasa_defteri()->row();

        return $veri;

    }

    public function gunBazindaGider($baslangic,$bitis){

        $veri = DB::select('SUM(tutar) as tutar','odeme_tarihi')->where('odeme_tarihi>=',$baslangic)->where('odeme_tarihi<=',$bitis)->groupby('odeme_tarihi')->masraflar()->row();

        return $veri;

    }

    public function gunBazindaKasaToplami($baslangic,$bitis) {
        /**
        * SELECT MAX(mevcut_kasa_toplami) as mevcut_kasa_toplami, DATE(tarih) as gun
        *  FROM kasa_defteri
        * WHERE tarih BETWEEN '2024-01-01' AND '2024-01-30'
        * GROUP BY DATE(tarih)
        * ORDER BY gun DESC;
        */
    }

    public function gununKasaToplami($tarih){

        $veri = DB::select('MAX(mevcut_kasa_toplami) as mevcut_kasa_toplami','tarih')->where('tarih',$tarih)->kasa_defteri()->row();

        return $veri;

    }

    /************************ */

    public function ayinGelirleri($baslangic,$bitis){


        $veri = DB::select('SUM(gelir) as gelir')->where('tarih>=',$baslangic)->where('tarih<=',$bitis)->where('islem','t')->kasa_defteri()->row();

        //echo DB::stringQuery();

        $data = $veri->gelir;


        return $data;

    }

    public function ayinGiderleri($baslangic,$bitis){

        $veri = DB::select('SUM(tutar) as tutar')->where('odeme_tarihi>=',$baslangic)->where('odeme_tarihi<=',$bitis)->masraflar()->row();

        return $veri;

    }

    public function ayinKasaToplami($baslangic,$bitis){

         /**
        * SELECT MAX(mevcut_kasa_toplami) as mevcut_kasa_toplami, DATE(tarih) as gun
        *  FROM kasa_defteri
        * WHERE tarih BETWEEN '2024-01-01' AND '2024-01-30'
        * GROUP BY DATE(tarih)
        * ORDER BY gun DESC;
        */

        $veri = DB::select('MAX(mevcut_kasa_toplami) as mevcut_kasa_toplami')->whereBetween('tarih', $baslangic, $bitis)->kasa_defteri()->row();

        return $veri;

    }

    public function ayinHaftaIciGunleri($baslangicTarihi, $bitisTarihi) {

        $haftaIciGunleri = array();
        $currentDate = new DateTime($baslangicTarihi);
        $endDate = new DateTime($bitisTarihi);

        // Tarih aralığında döngü
        while ($currentDate <= $endDate) {
            // Hafta içi günleri kontrolü (Pazartesi'den Cuma'ya kadar)
            $gunHafta = $currentDate->format('N');
            if ($gunHafta >= 1 && $gunHafta <= 5) {
                $haftaIciGunleri[] = $currentDate->format('Y-m-d');
            }

            // Bir gün ekleyerek döngüyü devam ettir
            $currentDate->add(new DateInterval('P1D'));
        }

        return $haftaIciGunleri;
        
    }


}