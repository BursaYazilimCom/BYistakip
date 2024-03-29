<?php

class InternalSmsModel extends Model
{
    
static function gonder($kurum,$data) {
    
    if($kurum=="OZTEK"){

         header('Content-Type: text/html; charset=utf-8');
        $postUrl='http://www.sms-gonder.com/panel/smsgonder1Npost.php';
        $KULLANICINO=AyarModel::defaultAyarlar('smsGonderUyeNo'); 
        $KULLANICIADI=AyarModel::defaultAyarlar('smsGonderKullaniciAdi');
        $SIFRE=AyarModel::defaultAyarlar('smsGonderSifre');       
        $ORGINATOR=AyarModel::defaultAyarlar('smsGonderOrginator');	

        $TUR='Turkce';  // Normal yada Turkce
        $ZAMAN=Date::now(); 
        $ZAMANASIMI=Date::now(); 

        $mesaj1=$data['mesaj'];
        $numara1=$data['numara'];

        $xmlString='data=<sms>
        <kno>'. $KULLANICINO .'</kno> 
        <kulad>'. $KULLANICIADI .'</kulad> 
        <sifre>'.$SIFRE .'</sifre>    
        <gonderen>'.  $ORGINATOR .'</gonderen> 
        <mesaj>'. $mesaj1 .'</mesaj> 
        <numaralar>'. $numara1.'</numaralar>
        <tur>'. $TUR .'</tur> 
        </sms>';  

        // Xml içinde aşağıdaki alanlarıda gönderebilirsiniz.
        //<zaman>'. $ZAMAN.'</zaman> İleri tarih için kullanabilirsiniz
        //<zamanasimi>'. $ZAMANASIMI.'</zamanasimi>  Sms ömrünü belirtir

        $Veriler =  $xmlString;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Veriler);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;

    }elseif($kurum=="NETGSM"){


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://soap.netgsm.com.tr:8080/Sms_webservis/SMS?wsdl/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version="1.0"?>
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <SOAP-ENV:Body>
                    <ns3:smsGonder1NV2 xmlns:ns3="http://sms/">
                        <username>'.AyarModel::defaultAyarlar('netGsmUsername').'</username>
                        <password>'.AyarModel::defaultAyarlar('netGsmPass').'</password>
                        <header>'.AyarModel::defaultAyarlar('netGsmHeader').'</header>
                        <msg>'.$data['mesaj'].'</msg>
                        <gsm>'.$data['numara'].'</gsm>
                        <filter>0</filter>
                        <encoding>TR</encoding>
                        <appkey>'.AyarModel::defaultAyarlar('netGsmApiKey').'</appkey>
                    </ns3:smsGonder1NV2>
                </SOAP-ENV:Body>
            </SOAP-ENV:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
       

}


}