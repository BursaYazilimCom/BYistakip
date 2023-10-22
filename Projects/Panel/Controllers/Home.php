<?php namespace Project\Controllers;

Use User,URL,PersonelModel;

class Home extends Controller
{
    /**
     * Home::main
     * 
     * Loads opening page.
     * Location: Views/Home/main.wizard.php
     */
    public function main(string ...$parameters)
    {

        Masterpage::title('Sağlık Raporları');
    }

    public function logout(){

        User::logout(URL::prev());

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