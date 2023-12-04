<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center navbar-light" data-nav="brand-center">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand" href="{{URL::site()}}"><span class="brand-logo">
                            <img src="images/logo/logo-mini.png"></span>
                        <h2 class="brand-text mb-0">{{AyarModel::defaultAyarlar('siteAdi')}}</h2>
                    </a></li>
            </ul>
        </div>
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>

            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item dropdown dropdown-language">
                    <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="dollar-sign"></i><span class="selected-language">Kurlar</span></a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">

                        @foreach($paraBirimleri as $pb)
                        <a class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$pb->para}}">{{$pb->sembol}} {{number_format($pb->guncel_kur,2,',','.')}} TL</a>
                        @endforeach

                    </div>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link nav-link-style" id="clickAction" data-action="panelColor" action="{{URL::site('personel/ajax')}}" data-id="0">
                        @if(USER_PANEL_COLOR=="light")
                        <i class="ficon" data-feather="moon"></i>
                        @else
                        <i class="ficon" data-feather="sun"></i>
                        @endif
                    </a>
                </li>
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Aranacak Kelime..." tabindex="-1" data-search="search">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>
                

                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{$user->isim}}</span><span class="user-status">Yönetim</span></div><span class="avatar"><img class="round" src="/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{URL::site('personel/form/')}}{{$user->id}}"><i class="me-50" data-feather="user"></i> Profil Ayarları</a>
                        <a class="dropdown-item" href="{{URL::site('personel/yetkiler')}}"><i class="me-50" data-feather="settings"></i> Yetkiler</a>
                        <a class="dropdown-item" href="{{URL::site('home/logout')}}"><i class="me-50" data-feather="power"></i> Çıkış yap</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header--> 


    <!-- BEGIN: Main Menu-->
    <div class="horizontal-menu-wrapper">
        <div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-light navbar-shadow menu-border fixed-top" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item me-auto"><a class="navbar-brand" href="{{URL::site()}}"><span class="brand-logo">
                               <img src="images/logo/logo-mini.png"></span>
                            <h2 class="brand-text mb-0">{{AyarModel::defaultAyarlar('siteAdi')}}</h2>
                        </a></li>
                    <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i></a></li>
                </ul>
            </div>
            <div class="shadow-bottom"></div>
            <!-- Horizontal menu content-->
            <div class="navbar-container main-menu-content" data-menu="menu-container">
                <!-- include ../../../includes/mixins-->
                <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{URL::site()}}"><i data-feather="home"></i><span data-i18n="Dashboards">Panel</span></a>
                    </li>

                    <li class="nav-item"><a class="nav-link d-flex align-items-center" href="{{URL::site('cari')}}"><i data-feather="users"></i><span data-i18n="Misc">Müşteriler</span></a>
                    <li class="nav-item"><a class="nav-link d-flex align-items-center" href="{{URL::site('tedarikci')}}"><i data-feather="archive"></i><span data-i18n="Misc">Tedarikciler</span></a>
                    <li class="nav-item"><a class="nav-link d-flex align-items-center" href="{{URL::site('projeler')}}"><i data-feather="check-square"></i><span data-i18n="Misc">Projeler</span></a>
                    </li>


                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown"><i data-feather="shopping-bag"></i><span data-i18n="Misc">Ürünler</span></a>
                        <ul class="dropdown-menu" data-bs-popper="none">

                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('urun')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="shopping-cart"></i><span data-i18n="Raise Support">Ürünler</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('urun/form')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="plus"></i><span data-i18n="Raise Support">Ürün Ekle</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('urun/gruplar')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="shopping-bag"></i><span data-i18n="Raise Support">Ürün Grupları</span></a>
                            </li>

                        </ul>
                    </li>
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown"><i data-feather="shopping-bag"></i><span data-i18n="Misc">Siparişler</span></a>
                        <ul class="dropdown-menu" data-bs-popper="none">

                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('siparisler')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="shopping-cart"></i><span data-i18n="Raise Support">Siparişler</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('siparisler/form')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="plus"></i><span data-i18n="Raise Support">Sipariş Ekle</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('siparisler/teklifler')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="shopping-bag"></i><span data-i18n="Raise Support">Teklifler</span></a>
                            </li>

                        </ul>
                    </li>
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown"><i data-feather="shopping-bag"></i><span data-i18n="Misc">Sipariş Ürünleri</span></a>
                        <ul class="dropdown-menu" data-bs-popper="none">
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('siparisler/urunler')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="shopping-bag"></i><span data-i18n="Raise Support" class="text-success">Tüm Sipariş Ürünleri</span></a>
                            </li>
                            @foreach($urunGruplari as $ug)
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('siparisler/gruplar/'.$ug->id)}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="shopping-bag"></i><span data-i18n="{{$ug->adi}}">{{$ug->adi}}</span></a>
                            </li>
                            @endforeach

                        </ul>
                    </li>
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown"><i data-feather="edit-2"></i><span data-i18n="Misc">Muhasebe</span></a>
                        <ul class="dropdown-menu" data-bs-popper="none">

                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('Masraf')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="aperture"></i><span data-i18n="Raise Support">Gider Kayıtları</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('kasa')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="clock"></i><span data-i18n="Raise Support">Kasa Defteri</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('faturalar')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="layout"></i><span data-i18n="Raise Support">Faturalar</span></a>
                            </li>

                        </ul>
                    </li>

                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown"><i data-feather="settings"></i><span data-i18n="Misc">Ayarlar</span></a>
                        <ul class="dropdown-menu" data-bs-popper="none">
                            <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                                <a class="dropdown-item d-flex align-items-center dropdown-toggle" href="#" data-bs-toggle="dropdown" data-i18n="Kullanıcı Yönetimi"><i data-feather="user"></i><span data-i18n="Kullanıcı Yönetimi">Personel Yönetimi</span></a>
                                <ul class="dropdown-menu" data-bs-popper="none">
                                    <li data-menu=""><a class="dropdown-item d-flex align-items-center" href="{{URL::site('Personel')}}" data-bs-toggle="" data-i18n="Second Level"><i data-feather="user"></i><span data-i18n="Second Level">Personel Yönetimi</span></a>
                                    </li>
                                    <li class="dropdown" data-menu="dropdown-submenu">
                                        <a class="dropdown-item d-flex align-items-center " href="{{URL::site('Ayarlar/yetkiAlanlari')}}" data-bs-toggle="" data-i18n="Second Level"><i data-feather="key"></i><span data-i18n="Second Level">Yetki Alanları</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                                <a class="dropdown-item d-flex align-items-center dropdown-toggle" href="{{URL::site('BolgeselAyarlar')}}" data-bs-toggle="dropdown" data-i18n="Kullanıcı Yönetimi"><i data-feather="user"></i><span data-i18n="Kullanıcı Yönetimi">Bölgesel Ayarlar</span></a>
                                <ul class="dropdown-menu" data-bs-popper="none">

                                    <li class="dropdown" data-menu="dropdown-submenu">
                                        <a class="dropdown-item d-flex align-items-center " href="{{URL::site('BolgeselAyarlar/sehirler')}}" data-bs-toggle="" data-i18n="Second Level"><i data-feather='map'></i><span data-i18n="Second Level">Şehirler</span></a>
                                    </li>
                                    <li class="dropdown" data-menu="dropdown-submenu">
                                        <a class="dropdown-item d-flex align-items-center " href="{{URL::site('BolgeselAyarlar/paraBirimleri')}}" data-bs-toggle="" data-i18n="Second Level"><i data-feather='dollar-sign'></i><span data-i18n="Second Level">ParaBirimleri</span></a>
                                    </li>


                                </ul>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('ayarlar/odemeYontemleri')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="dollar-sign"></i><span data-i18n="Raise Support">Ödeme Yöntemleri</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('ayarlar/siparisDurumlari')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="shopping-bag"></i><span data-i18n="Raise Support">Sipariş Durumları</span></a>
                            </li>
                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="{{URL::site('ayarlar')}}" data-bs-toggle="" data-i18n="Raise Support"><i data-feather="settings"></i><span data-i18n="Raise Support">Site Ayarları</span></a>
                            </li>

                            <li data-menu="">
                                <a class="dropdown-item d-flex align-items-center" href="https://www.bursayazilim.com" data-bs-toggle="" data-i18n="Documentation"><i target="_blank" data-feather="folder"></i><span data-i18n="Yardım Destek">Yardım</span></a>
                            </li>


                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Main Menu-->