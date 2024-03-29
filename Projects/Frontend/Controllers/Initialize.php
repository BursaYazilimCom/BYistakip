<?php namespace Project\Controllers;

Use User;

class Initialize extends Controller
{
    /**
     * The codes to run at startup.
     * It enters the circuit before all controllers. 
     * You can change this setting in Config/Starting.php file.
     */
    const exclude = ["paytr","CronJob"];

    public function main()
    {

        if(CURRENT_CONTROLLER!="Login"){

            if(!User::isLogin()){
                redirect("Login");
            }else{
    
                $user = User::data();
    
                View::user($user);
    
            }

        }
       


        Theme::active('Default');

        Masterpage::headPage('head')
            ->bodyPage('body');
    }
}