<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Planlama Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Planlama</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <section class="form-control-repeater">
                <div class="row">
                    <!-- Invoice repeater -->


                        {{ Redirect::select('bilgi',true) }}

                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <a  href="{{URL::site()}}planlama/hatirlatici" class="card text-center">
                                <div class="card-body">
                                    <div  class="avatar bg-light-info p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="bell" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">Hatırlatıcı</h2>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <a  href="{{URL::site()}}planlama/gorevler" class="card text-center">
                                <div class="card-body">
                                    <div  class="avatar bg-light-success p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="flag" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">Görevler</h2>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-2 col-md-3 col-sm-6">
                            <a  href="{{URL::site()}}planlama/gorevler" class="card text-center">
                                <div class="card-body">
                                    <div  class="avatar bg-light-warning p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="calendar" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">Etkinlikler</h2>
                                </div>
                            </a>
                        </div>


                    <!-- /Invoice repeater -->
                </div>
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->

