<div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                {{ Redirect::select('bilgi',true) }}
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <!-- Medal Card -->
                        <div class="col-xl-6 col-md-6 col-12">
                            <div class="row">
                                <style>
                                    .card-body{
                                        padding: 1.8rem 1.5rem !important;
                                    }
                                </style>
                              
                                        <div class="col-xl-4 col-md-4 col-sm-6">
                                            <div class="card text-center brdt-success">
                                                <div class="card-body">
                                                    <div class="avatar bg-light-success p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="arrow-up" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder">{{$kasaToplami['gelir']}} ₺</h2>
                                                    <p class="card-text">Toplam Gelir</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-6">
                                            <div class="card text-center brdt-danger">
                                                <div class="card-body">
                                                    <div class="avatar bg-light-danger p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="arrow-down" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder">{{$kasaToplami['gider']}} ₺</h2>
                                                    <p class="card-text">Toplam Gider</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-6">
                                            <div class="card text-center brdt-info">
                                                <div class="card-body">
                                                    <div class="avatar bg-light-info p-50 mb-1">
                                                        <div class="avatar-content">
                                                            <i data-feather="award" class="font-medium-5"></i>
                                                        </div>
                                                    </div>
                                                    <h2 class="fw-bolder">{{$kasaToplami['kazanc']}} ₺</h2>
                                                    <p class="card-text">Toplam Kazanç</p>
                                                </div>
                                            </div>
                                        </div>
                           

                            </div>
                        </div>
                        <!--/ Medal Card -->

                        <!-- Statistics Card -->
                        <div class="col-xl-6 col-md-6 col-12">
                            <div class="row">
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <div class="card text-center brdt-primary">
                                        <div class="card-body">
                                            <div class="avatar bg-light-primary p-50 mb-1">
                                                <div class="avatar-content">
                                                <i data-feather="trending-up" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <h2 class="fw-bolder">{{$siparisurunleri}} Adet</h2>
                                            <p class="card-text">Satılan Ürün</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <div class="card text-center brdt-success">
                                        <div class="card-body">
                                            <div class="avatar bg-light-success p-50 mb-1">
                                                <div class="avatar-content">
                                                <i data-feather="user" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <h2 class="fw-bolder">{{$musteriSayisi}} Adet</h2>
                                            <p class="card-text">Müşteri</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <div class="card text-center brdt-info">
                                        <div class="card-body">
                                            <div class="avatar bg-light-info p-50 mb-1">
                                                <div class="avatar-content">
                                                <i data-feather="box" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <h2 class="fw-bolder">{{$projeSayisi}} Adet</h2>
                                            <p class="card-text">Proje</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-3 col-sm-6">
                                    <div class="card text-center brdt-danger">
                                        <div class="card-body">
                                            <div class="avatar bg-light-danger p-50 mb-1">
                                                <div class="avatar-content">
                                                <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <h2 class="fw-bolder">{{$odenmeyenFaturaSayisi}} Adet</h2>
                                            <p class="card-text">Ödenmemiş Fatura</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                           
                        </div>
                        <!--/ Statistics Card -->
                    </div>

                    <div class="row match-height">
                        <!-- Company Table Card -->
                        <div class="col-lg-6 col-12">
                            <div class="card card-company-table brdt-success">
                                <div class="card-body p-0">
                                    <div class="table-responsive-sm table-responsive-md table-responsive-l">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Müşteri</th>
                                                <th>Ödeme Yöntemi</th>
                                                <th>Ödeme Durumu</th>
                                                <th>Toplam Tutar</th>
                                                <th>Sipariş Tarihi</th>
                                                <th>Durum</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($listele['liste'] as $s)

                                                <tr>
                                                    <td>
                                                        <a href="{{URL::site('siparisler/duzenle')}}/{{$s->id}}">{{$s->id}}</a>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <div class="fw-bolder">{{CariModel::cariAdi($s->cari)}}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span>{{AyarModel::odemeYontemiAdi($s->odeme_yontemi)}}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{$s->odeme_durumu=="1"?"<span class='text text-success'>Ödendi</span>":"<span class='text text-danger'>Ödeme Bekliyor</span>"}}</td>
                                                    <td>{{number_format($s->genel_toplam_tutari,2)}} ₺</td>
                                                    <td>{{ Date::convert($s->tarih, '{dayInMonth}.{monthInYear-}.{year}')}}</td>
                                                    <td>{{$s->durum=="1"?"<span class='text text-success'>Aktif</span>":"<span class='text text-danger'>Pasif</span>"}}</td>

                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Company Table Card -->
                        <style>
                            .card-user-timeline .timeline .timeline-item:not(:last-child) {
                                padding-bottom: 0.3rem;
                            }
                        </style>

                        <!-- Transaction Card -->
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-user-timeline brdt-warning">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <i data-feather="list" class="user-timeline-title-icon"></i>
                                        <h4 class="card-title"><a href="{{URL::site('planlama/hatirlatici')}}" >Hatırlatıcı</a></h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline ms-50">
                                        @foreach($hatirlatmalar['liste'] as $hatirlatma)
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <h6>
                                                    @if($hatirlatma->periyod=="1")
                                                    {{$hatirlatma->ay=="0"?"Her Ay'ın":$hatirlatma->ay}} {{$hatirlatma->gun=="0"?"Her Gün'ü":$hatirlatma->gun}} Saat: {{$hatirlatma->saat}}
                                                    @else
                                                    {{$hatirlatma->gun}}.{{$hatirlatma->ay}}.{{$hatirlatma->yil}}  Saat: {{$hatirlatma->saat}}
                                                    @endif
                                                </h6>
                                                <p>{{$hatirlatma->aciklama}}</p>
                                            </div>
                                        </li>
                                        @endforeach



                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card card-transaction brdt-pink">
                                <div class="card-header">
                                    <h4 class="card-title">Kasa Hesapları</h4>
                                </div>
                                <div class="card-body">

                                    @foreach($kasaHesaplari as $kh)
                                        <div class="transaction-item">
                                            <div class="d-flex">
                                                <div class="avatar bg-light-primary rounded float-start">
                                                    <div class="avatar-content">
                                                        <img src="images/icons/toolbox.svg" alt="Toolbar svg" />
                                                    </div>
                                                </div>
                                                <div class="transaction-percentage">
                                                    <h6 class="transaction-title">{{$kh->adi}}</h6>
                                                </div>
                                            </div>
                                            @if($kToplam[$kh->id]<0)
                                            <div class="fw-bolder text-danger">{{number_format($kToplam[$kh->id],2)}} ₺</div>
                                            @else
                                            <div class="fw-bolder text-success">{{number_format($kToplam[$kh->id],2)}} ₺</div>
                                            @endif
                                        </div>  
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <!--/ Transaction Card -->
                    </div>
                </section>
                <!-- Dashboard Ecommerce ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->