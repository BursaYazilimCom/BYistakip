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
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="card card-congratulation-medal">
                                <div class="card-body">
                                    <h5>Toplam Ciro</h5>
                                    <h3 class="mb-75 mt-2 pt-50">
                                        <a href="{{URL::site('kasa/tumKayitlar')}}">{{$kasaToplami}} ₺</a>
                                    </h3>
                                    <a href="{{URL::site('kasa/tumKayitlar')}}" class="btn btn-primary">İncele</a>

                                </div>
                            </div>
                        </div>
                        <!--/ Medal Card -->

                        <!-- Statistics Card -->
                        <div class="col-xl-8 col-md-6 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">
                                    <h4 class="card-title">İstatistikler</h4>
                                </div>
                                <div class="card-body statistics-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-primary me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="trending-up" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{$siparisurunleri}} Adet</h4>
                                                    <p class="card-text font-small-3 mb-0">Satış Yapılan Ürün</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-info me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="user" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{$musteriSayisi}} Adet</h4>
                                                    <p class="card-text font-small-3 mb-0">Müşteri</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-danger me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="box" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{$projeSayisi}} Adet</h4>
                                                    <p class="card-text font-small-3 mb-0">Proje</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-success me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0">{{$odenmeyenFaturaSayisi}} Adet</h4>
                                                    <p class="card-text font-small-3 mb-0">Ödeme Bekleyen Fatura</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>

                    <div class="row match-height">
                        <!-- Company Table Card -->
                        <div class="col-lg-8 col-12">
                            <div class="card card-company-table">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
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
                                                    <td>{{$s->odeme_durumu=="1"?"Ödendi":"Ödeme Bekliyor"}}</td>
                                                    <td>{{number_format($s->genel_toplam_tutari,2)}} ₺</td>
                                                    <td>{{ Date::convert($s->tarih, '{dayInMonth}.{monthInYear-}.{year}')}}</td>
                                                    <td>{{AyarModel::siparisDurumAdi($s->durum)}}</td>

                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Company Table Card -->


                        <!-- Transaction Card -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-transaction">
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
                                            @if($kh->tutar<0)
                                            <div class="fw-bolder text-danger">{{number_format($kh->tutar,2)}} ₺</div>
                                            @else
                                            <div class="fw-bolder text-success">{{number_format($kh->tutar,2)}} ₺</div>
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