<?php
namespace Project\Controllers;

Use Encode,PersonelModel;

class Login extends Controller
{
	public function main()
	{

        Theme::active('Default');

        Masterpage::headPage("head");
        Masterpage::footerPage("footer");
/*
        $ekleData = [
            'username'      =>"admin",
            'password'      =>Encode::super("6447889"),
            'email'         =>"analizmail@gmail.com",
            'isim'          =>"Mustafa",
            'telefon'       =>"05322569555",
            'notlar'        =>"-",
            'ban'           =>"0"==''?'0':"0",
            'aktivasyon'    =>"0"==''?'0':"0",
            'aktiflik'      =>'1',
            'panel_rengi'   =>'light'
        ];

        $ekle = PersonelModel::ekle($ekleData);*/

	}

}