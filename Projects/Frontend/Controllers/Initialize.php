<?php namespace Project\Controllers;

Use User, InternalUrunModel as UrunModel;

class Initialize extends Controller
{
    /**
     * The codes to run at startup.
     * It enters the circuit before all controllers. 
     * You can change this setting in Config/Starting.php file.
     */
    const exclude = ["paytr","CronJob","Etkinlik"];

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

        $gruplar = UrunModel::urunGrupListe();

        View::gruplar($gruplar);
        Theme::active('Default');

        Masterpage::headPage('head')
            ->bodyPage('body');
    }
}