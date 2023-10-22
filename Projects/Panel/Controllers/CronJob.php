<?php namespace Project\Controllers;

use User,Method,DB,Post,Get,Date,XML,CURL,Json,Time,Email;
use AyarModel,PersonelModel,SiparisModel;

class CronJob extends Controller
{
    public function main(){}

    /****************GÖREV ATAMALARI***************/

    public function dovizKurlari(){

        $veri =XML::parseSimpleURL('http://www.tcmb.gov.tr/kurlar/today.xml');

        $dolarKur = $veri->Currency[0]->BanknoteSelling;
        $dolarGuncelle = AyarModel::kurGuncelle('USD',(float)$dolarKur);
        if($dolarGuncelle){ echo "Dolar Güncellendi<br>"; }else{  echo "Dolar Güncellemede HATA OLUŞTU<br>"; }

        $euroKur = $veri->Currency[3]->BanknoteSelling;
        $euroGuncelle = AyarModel::kurGuncelle('EUR',(float)$euroKur);
        if($euroGuncelle){ echo "Euro Güncellendi"; }else{  echo "EURO Güncellemede HATA OLUŞTU<br>"; }

    }


    // "value" => 10 yazan kısmı herkese farklı id vermek için kullanabilirsiniz.
    //bu durumda  OneSignalNotfiy.html dosyasında "user_id",10, yazan yeri değiştirmk gerekiyor.

    public function s404(){}
}