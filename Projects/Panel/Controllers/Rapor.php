<?php namespace Project\Controllers;

Use User,URL,Post,Date;
use PersonelModel,AyarModel,KasaModel,InternalCariModel as CariModel,InternalProjeModel as ProjeModel,SiparisModel,InternalFaturaModel as FaturaModel,RaporModel;

class Rapor extends Controller
{
    /**
     * Home::main
     * 
     * Loads opening page.
     * Location: Views/Home/main.wizard.php
     */
    public function main($yil= "",$ay="")
    {
        if($yil==""){ 
            $yil = date('Y');
            $ay = date('m');
        }

        if($ay==""){

            $aykacCekiyor = cal_days_in_month(CAL_GREGORIAN, date('m'), $yil);

            $ayBaslangic = date($yil.'-'.date('m').'-01');

            $ayBitis = date($yil.'-'.date('m').'-'.$aykacCekiyor);

            $kategoriler = "";
            $gelir = "";
            $gider = "";
            $kasaToplami = "";

            $ay = date('m');

            for($i=1;$i<=12;$i++){


                if($i<10){
                    $i = "0".$i;
                }

                $aykacCekiyor = cal_days_in_month(CAL_GREGORIAN, $i, $yil);

            
                $baslangic  = date($yil.'-'.$i.'-01');
                $bitis      = date($yil.'-'.$i.'-').$aykacCekiyor;

                $kategoriler = $kategoriler."'".date($i.".".$yil)."',";

                $gelirSql = RaporModel::ayinGelirleri($baslangic,$bitis);
                
                $gelir = $gelir."".number_format($gelirSql,0,"","").",";

                $giderSql = RaporModel::ayinGiderleri($baslangic,$bitis);

                $gider = $gider.number_format($giderSql->tutar,0,"","").",";

                $kasaSql = RaporModel::ayinKasaToplami($baslangic,$bitis);

                $kasaToplami = $kasaToplami.number_format($kasaSql->mevcut_kasa_toplami,0,"","").",";


            }

        }else{


            $kasaToplami = KasaModel::gelirGiderToplami();
            $gelir = $kasaToplami->gelir;
            $gider = $kasaToplami->gider;
            $kasa = [
                'gelir'     => number_format($gelir,2),
                'gider'     => number_format($gider,2),
                'kazanc'    => number_format($gelir - $gider,2)
            ];

            $aykacCekiyor = cal_days_in_month(CAL_GREGORIAN, $ay, $yil);

            $ayBaslangic = date($yil.'-'.$ay.'-01');

            $ayBitis = date($yil.'-'.$ay.'-'.$aykacCekiyor);

            $kategoriler = "";
            $gelir = "";
            $gider = "";
            $kasaToplami = "";

            for($i=1;$i<=$aykacCekiyor;$i++){

                if($i<10){
                    $i = "0".$i;
                }

                $tarih = date($yil.'-'.$ay.'-').$i;

                $kategoriler = $kategoriler."'".date($i.".$ay.".$yil)."',";

                $gelirSql = RaporModel::gununGelirleri($tarih);
            
                $gelirVeri = $gelirSql;
                
                $gelir = $gelir."".number_format($gelirSql,0).",";

                $giderSql = RaporModel::gununGiderleri($tarih);

                $gider = $gider.number_format($giderSql->tutar,0).",";

                $kasaSql = RaporModel::gununKasaToplami($tarih);

                $kasaToplami = $kasaToplami.number_format($kasaSql->mevcut_kasa_toplami,0,"","").",";


            }
         

        }
        
      
        Masterpage::title(AyarModel::defaultAyarlar('siteAdi'));

        View::kasaToplami($kasa);
        View::kategoriler($kategoriler);
        View::gelir($gelir);
        View::yil($yil);
        View::ay($ay);
        View::gider($gider);
        View::kasaToplami($kasaToplami);
    }
    public function tarih()
    {

        $baslangic   = Post::baslangic();
        $bitis       = Post::bitis();
      
        $kategoriler = "";
        $gelir = "";
        $gider = "";
        $kasaToplami = "";

        $tarihFarki = Date::diffDayUp($baslangic, $bitis);

        for($i=0;$i<=$tarihFarki;$i++){

            $gun = Date::addDay($baslangic, $i);

            $kategoriler = $kategoriler."'".Date::convert($gun,"d.m.Y")."',";

            $gelirSql = RaporModel::gununGelirleri($gun);
        
            $gelirVeri = $gelirSql;
            
            $gelir = $gelir."".number_format($gelirSql,0).",";

            $giderSql = RaporModel::gununGiderleri($gun);

            $gider = $gider.number_format($giderSql->tutar,0).",";

            $kasaSql = RaporModel::gununKasaToplami($gun);

            $kasaToplami = $kasaToplami.number_format($kasaSql->mevcut_kasa_toplami,0,"","").",";


        }
      
        Masterpage::title(AyarModel::defaultAyarlar('siteAdi'));

    
        View::kategoriler($kategoriler);
        View::gelir($gelir);
        View::yil("");
        View::ay("");
        View::gider($gider);
        View::kasaToplami($kasaToplami);
    }

    /**
     * Home::s404
     * 
     * Loads show 404 page.
     * Location: Views/Home/s404.wizard.php
     */
    public function s404()
    {
        # Sets masterpage title.
        Masterpage::title('404! File Not Found');
    }
}