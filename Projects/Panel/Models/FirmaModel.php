<?php

class InternalFirmaModel extends Model
{
    static function detay($id)
    {
        return DB::where('id',$id)->firmalar()->row();
    }

    static function adi($id="0")
    {
        if ($id!="0"){
            $data = DB::select("firma_adi")->where('id',$id)->firmalar()->row();

            $veri = $data->firma_adi;
        }else{

            $veri = "Firma Tanımlı Değil";

        }


        return $veri;
    }

    static function liste(){
        $veri= DB::limit(null,25)->firmalar();

        return ['liste'=>$veri->result(),'sayfalama'=>$veri->pagination()];
    }

    static function ara($aranacak){

        $data = DB::whereLike('firma_adi',$aranacak)->firmalar();

        return ['liste'=>$data->result(),'sayfalama'=>''];

    }

    static function tumListe(){

        $veri= DB::firmalar()->result();

        return $veri;
    }

    static function ekle($data){


       $ekle = DB::insert('firmalar',[
           'firma_adi'          =>$data['firma_adi'],
           'firma_sektor'       =>$data['firma_sektor'],
           'faaliyet_alani'     =>$data['faaliyet_alani'],
           'firma_email'        =>$data['firma_email'],
           'firma_yetkili'      =>$data['firma_yetkili'],
           'firma_yetkili_gsm'  =>$data['firma_yetkili_gsm'],
           'firma_vd'           =>$data['firma_vd'],
           'firma_vn'           =>$data['firma_vn'],
           'firma_sgk_sicil'    =>$data['firma_sgk_sicil'],
           'firma_fatura_adresi'=>$data['firma_fatura_adresi'],
           'il'                 =>$data['il'],
           'ilce'               =>$data['ilce'],
           'firma_sube'         =>$data['firma_sube'],
           'tehlike_sinifi'     =>$data['tehlike_sinifi'],
           'uname'              =>$data['uname'],
           'upass'              =>$data['upass']
        ]);

       //echo DB::stringQuery();

       return DB::insertID();
    }

    static function update($data){

        $guncelle = DB::where('id',$data["id"])
            ->update('firmalar',[
                'firma_adi'          =>$data['firma_adi'],
                'firma_sektor'       =>$data['firma_sektor'],
                'faaliyet_alani'     =>$data['faaliyet_alani'],
                'firma_email'        =>$data['firma_email'],
                'firma_yetkili'      =>$data['firma_yetkili'],
                'firma_yetkili_gsm'  =>$data['firma_yetkili_gsm'],
                'firma_vd'           =>$data['firma_vd'],
                'firma_vn'           =>$data['firma_vn'],
                'firma_sgk_sicil'    =>$data['firma_sgk_sicil'],
                'firma_fatura_adresi'=>$data['firma_fatura_adresi'],
                'il'                 =>$data['il'],
                'ilce'               =>$data['ilce'],
                'firma_sube'         =>$data['firma_sube'],
                'tehlike_sinifi'     =>$data['tehlike_sinifi'],
                'uname'              =>$data['uname'],
                'upass'              =>$data['upass']
            ]);

        echo DB::stringQuery();

        return $guncelle;
    }

    static function delete($id){
        return DB::whereId($id)->delete('firmalar');
    }
}