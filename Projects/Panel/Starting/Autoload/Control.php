<?php
use User;

if(User::isLogin()){

    $user = User::data();

    define('USER_PANEL_COLOR',$user->panel_rengi);

}else{
    define('USER_PANEL_COLOR','light');
}


