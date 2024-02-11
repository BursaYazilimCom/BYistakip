<?php

class InternalSiparisModel extends Model
{
    public function toplam(){
        $veri = DB::siparisler()->totalRows(true);

        return $veri;
    }

    public function liste($durum="",$sayfa=Null,$filtre=""){

        if ($filtre!=""){

            $filtreBol = explode("-",$filtre);

            $yer        = $filtreBol[0];
            $siralama   = $filtreBol[1];

        }else{
            $yer = "id";
            $siralama ="DESC";

        }

            $liste = DB::where('siparisler.kayit_sekli','1')
                ->orderby('siparisler.'.$yer,$siralama)
                ->limit(25)
                ->siparisler();

            //echo DB::stringQuery();

        $veri = ['liste'=>$liste->result(),'sayfalama'=>$liste->pagination()];

        return $veri;

    }

    public function tumSiparisler($durum="")
    {
        $veri = DB::where('durum',$durum)->siparisler()->result();

        return $veri;

    }

    public function yenilenecekSiparisler($tarih,$tur){

        $veri = DB::select('siparisler.*')
            ->where('siparisler.durum','1')
            ->where('siparis_urunleri.odeme_periyodu=',$tur)
            ->where('siparis_urunleri.bitis_tarihi<=',$tarih)
            ->innerjoin('siparis_urunleri.siparis','siparisler.id')->siparisler()->result();

       // echo DB::stringQuery();

        return $veri;

    }

    public function teklifler(){

            $liste = DB::where('siparisler.kayit_sekli','0')
                ->orderby('siparisler.id','DESC')
                ->limit(NULL,25)
                ->siparisler();

        $veri = ['liste'=>$liste->result(),'sayfalama'=>$liste->pagination()];

        //echo DB::stringQuery();

        return $veri;

    }

    public function uyeSiparisleri($id,$sayfa=""){

            $liste = DB::where('siparisler.cari',$id)
                ->orderby('siparisler.id','DESC')
                ->limit($sayfa,25)
                ->siparisler();

           // AyarModel::sqlHataEkle(DB::stringQuery());

        $veri = ['liste'=>$liste->result(),'sayfalama'=>$liste->pagination()];

        return $veri;

    }

    public function uyeSiparisUrunleri($id,$sayfa=""){

        $liste = DB::where('cari',$id)
            ->orderby('id','DESC')
            ->limit($sayfa,25)
            ->siparis_urunleri();


        // AyarModel::sqlHataEkle(DB::stringQuery());

        $veri = ['liste'=>$liste->result(),'sayfalama'=>$liste->pagination()];

        return $veri;

    }

    public function uyeDurumSiparisleri($id,$durum){

            $liste = DB::select(
                'siparisler.id as siparisId',
                'siparisler.uye as uye',
                'siparisler.siparis_adi as siparis_adi',
                'siparisler.siparis_notu as siparis_notu',
                'siparisler.dosya as dosya',
                'siparisler.toplam_fiyat as toplam_fiyat',
                'siparisler.siparis_yeri as siparis_yeri',
                'siparisler.grafik_hizmeti as grafik_hizmeti',
                'siparisler.montaj_hizmeti as montaj_hizmeti',
                'siparisler.toplam_tutar as toplam_tutar',
                'siparisler.indirim_tutar as indirim_tutar',
                'siparisler.ara_toplam_tutar as ara_toplam_tutar',
                'siparisler.kdv_tutari as kdv_tutari',
                'siparisler.genel_toplam_tutari as genel_toplam_tutari',
                'siparisler.durum as durum',
                'siparisler.olusturan as olusturan',
                'siparisler.kayit_sekli as kayit_sekli',
                'siparisler.tarih as tarih',
                'siparisler.teslim_tarihi as teslim_tarihi',
                'siparisler.periyodik_siparis as periyodik_siparis',
                'siparisler.odeme_periyodu as odeme_periyodu',
                'siparisler.fatura as fatura',
                'uyeler.adi as uyeAdi')
                ->innerjoin('uyeler.id','siparisler.uye')
                ->where('siparisler.uye',$id)->where('siparisler.durum',$durum)
                ->orderby('siparisler.id','DESC')
                ->limit(NULL,AyarModel::sayfalama('siparisler'))
                ->siparisler();

            echo $liste->stringQuery();

        $veri = ['liste'=>$liste->result(),'sayfalama'=>$liste->pagination(URI::segment(2))];

        return $veri;

    }

    public function detay($id)
    {
        $veri = DB::where('siparisler.id',$id)->siparisler()->row();

        return $veri;
    }

    public function ekle($data){

        $ekle = DB::insert('siparisler',[

                    'cari'              =>$data['cari'],
                    'odeme_yontemi'     =>$data['odeme_yontemi'],
                    'odeme_durumu'      =>$data['odeme_durumu'],
                    'olusturan'         =>$data['olusturan'],
                    'kayit_sekli'       =>$data['kayit_sekli'],
                    'siparis_notu'      =>$data['siparis_notu'],
                    'durum'             =>$data['durum']
                ]);

       // echo DB::stringQuery();

        return DB::insertID();
    }

    public function guncelle($data){

        $guncelle = DB::where('id',$data['id'])->update('siparisler',[
            'siparis_notu'          =>$data['siparis_notu'],
            'odeme_durumu'          =>$data['odeme_durumu'],
            'durum'                 =>$data['durum']
        ]);

        return $guncelle;
    }

    public function sil($id){
        
        $guncelle = DB::where('id',$id)->delete('siparisler');

        return $guncelle;
    }

    public function odemeEkle($data){

        $guncelle = DB::where('id',$data['id'])->update('siparisler',[
            'alinan_odeme'          =>$data['alinan_odeme']
        ]);

        return $guncelle;
    }

    public function odemeDurumDegistir($id,$durum){

        $guncelle = DB::where('id',$id)
            ->update('siparisler',[
                'odeme_durumu'      =>$durum
            ]);

        return $guncelle;
    }

    public function toplamSiparisSayisi(){
        $veri = DB::siparisler()->totalRows(true);

        return $veri;
    }

    public function aramaFiltresi($search){

        $veri = DB::query('SELECT * FROM siparisler '.$search);

        $data = ['liste'=>$veri->result(),'kayitSAyisi'=>$veri->totalRows()];

        return $data;

    }

    public function siparisurunIslemGerekiyor($id,$gerekiyormu,$islem){

        $guncelle = DB::where('siparis',$id)
            ->update('siparis_urunleri',[
                'islem_gerekiyor'       =>$gerekiyormu,
                'yapilacak_islem'       => $islem
            ]);

        return $guncelle;

    }

    public function islemGerekenSiparisler(){
        $veri = DB::where('islem_gerekiyor','1')->siparis_urunleri();

        return  ['liste'=>$veri->result,'adet'=>$veri->totalRows()];
    }

    public function siparisUrunBilgi($id){

        $veri = DB::where('id',$id)->siparis_urunleri()->row();

        return $veri;

    }

    public function siparisUrunEkle($data){

        $detay = $this->detay($data['siparis']);

        $ekle = DB::insert('siparis_urunleri',[
            'siparis'           =>$data['siparis'],
            'urun'              =>$data['urun'],
            'urun_adi'          =>$data['urun_adi'],
            'tedarikci'          =>$data['tedarikci'],
            'cari'              =>$data['cari'],
            'adet'              =>$data['adet'],
            'notu'              =>$data['notu'],
            'odeme_periyodu'    =>$data['odeme_periyodu'],
            'para_birimi'       =>$data['para_birimi'],
            'gecerli_kur'       =>$data['gecerli_kur'],
            'fiyat_sabitle'     =>$data['fiyat_sabitle'],
            'birim_fiyat'       =>$data['birim_fiyat'],
            'kdv'               =>$data['kdv'],
            'kdv_tutari'        =>$data['kdv_tutari'],
            'toplam_fiyat'      =>$data['toplam_fiyat'],
            'siparis_tarihi'    =>$data['siparis_tarihi'],
            'baslangic_tarihi'  =>$data['baslangic_tarihi'],
            'bitis_tarihi'      =>$data['bitis_tarihi'],
            'durum'             =>$data['durum'],
        ]);

        //echo DB::stringQuery();

        return $ekle;

    }

    public function siparisUrunGuncelle($data){

        $guncelle = DB::where('id',$data['id'])->update('siparis_urunleri',[
            'odeme_periyodu'        => $data['odeme_periyodu'],
            'notu'                  => $data['siparis_notu'],
            'tedarikci'             => $data['tedarikci'],
            'fiyat_sabitle'         => $data['fiyat_sabitle'],
            'birim_fiyat'           => $data['birim_fiyat'],
            'kdv_tutari'            => $data['kdv_tutari'],
            'toplam_fiyat'          => $data['toplam_fiyat'],
            'baslangic_tarihi'      => $data['baslangic_tarihi'],
            'bitis_tarihi'          => $data['bitis_tarihi'],
            'durum'                 => $data['durum']
        ]);

        //echo DB::stringQuery();

        return $guncelle;

    }

    public function siparisUrunFiyatGuncelle($data){

        $guncelle = DB::where('id',$data['id'])->update('siparis_urunleri',[
            'adet'          => $data['adet'],
            'birim_fiyat'   => $data['birim_fiyat'],
            'kdv'           => $data['kdv'],
            'kdv_tutari'    => $data['kdv_tutari'],
            'toplam_fiyat'  => $data['toplam_fiyat']
        ]);

        //echo DB::stringQuery();

        return $guncelle;

    }

    public function siparisUrunKontrolEdildi($data){

        $guncelle = DB::where('id',$data['id'])->update('siparis_urunleri',[
            'islem_gerekiyor'         => $data['islem_gerekiyor'],
            'yapilacak_islem'         => $data['yapilacak_islem']
        ]);

        //echo DB::stringQuery();

        return $guncelle;

    }

    public function siparisUrunSil($id){

        $sil = DB::whereId($id)->delete('siparis_urunleri');

        /*$client = new Client(new Version2X(AyarModel::defaultAyarlar('nodeIp').':'.AyarModel::defaultAyarlar('nodePort')));

        $client->initialize();
        $client->emit('siparisUrunSil', [
            'id'            =>$id
        ]);
        $client->close();*/

        return $sil;


    }

    public function siparisUrunleriAdet(){

        $veri = DB::siparis_urunleri();

        return ['liste'=>$veri->result(),'adet'=>$veri->totalRows(true)];

    }

    public function siparisUrunleri($id){

        $veri = DB::where('siparis',$id)->siparis_urunleri()->result();

        return $veri;

    }

    public function siparisYenilenecekUrunleri($id){

        $veri = DB::where('odeme_periyodu!=','t')->where('odeme_periyodu!=','u')->where('siparis',$id)->siparis_urunleri()->result();

        return $veri;

    }

    public function yenilenecekSiparisUrunleri($tarih,$siparis="",$tur=""){

        $veri = DB::select('siparis_urunleri.*')
            ->where('siparisler.durum','1')
            ->where('siparis_urunleri.odeme_periyodu=',$tur)
            ->where('siparis_urunleri.bitis_tarihi<=',$tarih)
            ->where('siparisler.id',$siparis)
            ->innerjoin('siparisler.id','siparis_urunleri.siparis')->siparis_urunleri()->result();

        return $veri;

    }


    public function siparisUrunleriListe($gurup=""){

        if ($gurup=="") {

            $veri = DB::orderby('bitis_tarihi','ASC')->limit(null,25)->siparis_urunleri();

        }else{
            $veri = DB::select('siparis_urunleri.*')->innerjoin('urunler.id','siparis_urunleri.urun')->where('urunler.grup',$gurup)->orderby('bitis_tarihi','ASC')->limit(null,25)->siparis_urunleri();
        }

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination(),'adet'=>$veri->totalRows(true)];

    }

    public function siparisUrunleriTariheGore($tarih)
    {
        $veri = DB::where('bitis_tarihi',$tarih)->siparis_urunleri()->result();

    }

    public function siparisUrunDetay($id){

        $veri = DB::where('id',$id)->siparis_urunleri()->row();

        return $veri;

    }

    public function siparisFaturalandi($id,$belgeNo=""){

        if($belgeNo!=""){
            $belgeNoGuncelle = DB::where('id',$id)->update('siparisler',
                ['belge_no'=>$belgeNo]
            );
        }

        $guncelle = DB::where('id',$id)->update('siparisler',
            ['fatura'=>'1']
        );
        return $guncelle;
    }

    public function siparisToplamTutarGuncelle($data){

        $guncelle = DB::where('id',$data['id'])->update('siparisler',[
            'toplam_tutar'          =>$data['toplam_tutar'],
            'kdv_tutari'            =>$data['kdv_tutari'],
            'genel_toplam_tutari'   =>$data['genel_toplam_tutari']
        ]);

        // AyarModel::sqlHataEkle(DB::stringQuery());

        return $guncelle;

    }

    public function durumDegistir($id,$durum){

        $guncelle = DB::where('id',$id)
            ->update('siparisler',[
                'durum'      =>$durum
            ]);

        return $guncelle;
    }

    public function urunDurumDegistir($id,$durum){

        $guncelle = DB::where('id',$id)
            ->update('siparis_urunleri',[
                'durum'      =>$durum
            ]);

        return $guncelle;
    }

    public function siparisGecmisi($id){

        $veri = DB::where('siparis_gecmisi.siparis',$id)
                ->orderby('siparis_gecmisi.id','DESC')
                ->siparis_gecmisi()->result();

        return $veri;

    }

    public function siparisGesmisEkle($data){

        $ekle = DB::insert('siparis_gecmisi',[
                    'cari'          =>$data['cari'],
                    'siparis'       =>$data['siparis'],
                    'aciklama'      =>$data['aciklama'],
                    'guncelleyen'   =>$data['guncelleyen']
                ]);

        return $ekle;
    }

    public function uyeSiparisDurumlari($uye){

        $liste = DB::select('siparisler.durum as durum','siparis_durumlari.adi as adi')
                ->where('siparisler.uye',$uye)
                ->innerjoin('siparis_durumlari.id','siparisler.durum')
                ->orderby('siparis_durumlari.id','ASC')
                ->groupby('siparis_durumlari.id')
                ->siparisler()->result();

        return $liste;

    }

    /*****************************************************/


}