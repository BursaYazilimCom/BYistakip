<?php namespace Project\Controllers;

Use User;

class Initialize extends Controller
{

    const exclude = ["By","Login"];
    
    
    public function main()
    {
        if(!User::isLogin()){
            redirect("Login");
        }else{

            $user = User::data();

            View::user($user);

        }

        Theme::active('Default');
        
        Masterpage::headPage('head')
                  ->bodyPage('body')
                  ->browserIcon(FILES_DIR . 'favicon.ico');
    }
}