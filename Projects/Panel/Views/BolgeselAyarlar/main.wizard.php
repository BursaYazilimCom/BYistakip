<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Bölgesel Ayarlar</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="URL::site()">Home</a></li>
                                <li class="breadcrumb-item active">Bölgesel Ayarlar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Statistics card section -->
            <section id="statistics-card">

                <!-- Stats Vertical Card -->
                <div class="row">

                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="avatar bg-light-success p-50 mb-1">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="font-medium-5"></i>
                                    </div>
                                </div>
                                <a href="{{URL::site('BolgeselAyarlar/paraBirimleri')}}"><h4 class="fw-bolder">Para Birimleri</h4></a>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="avatar bg-light-danger p-50 mb-1">
                                    <div class="avatar-content">
                                        <i data-feather="map" class="font-medium-5"></i>
                                    </div>
                                </div>
                                <a href="{{URL::site('BolgeselAyarlar/sehirler')}}"><h4 class="fw-bolder">Şehirler</h4></a>
                            </div>
                        </div>
                    </div>


                </div>

            </section>
            <!--/ Statistics Card section-->

        </div>
    </div>
</div>
<!-- END: Content-->