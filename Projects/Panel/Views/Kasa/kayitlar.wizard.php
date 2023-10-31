<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Muhasebe Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('kasa')}}">Kasa Defteri</a>
                                </li>
                                <li class="breadcrumb-item active">Kasa Defteri Kayıtları
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <a class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="grid"  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="İşlemler"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{URL::site('kasa')}}"><i class="me-1" data-feather="plus"></i><span class="align-middle"><Kayıt></Kayıt> Ekle</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Gider Kayıtları</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Kasa Defteri Kayıtları ({{$detay->adi}} : {{$detay->tutar}})
                            </p>
                            {{ Redirect::select('bilgi',true) }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th>Tarih</th>
                                    <th>İşlem</th>
                                    <th>İşlem Yapan</th>
                                    <th>Hesap</th>
                                    <th>Açıklama</th>
                                    <th>Gelir</th>
                                    <th>Gider</th>
                                    <th>Tutar</th>
                                    <th>#</th>
                                </tr>
                                {[
                                $guncelTutar = $detay->tutar;
                                ]}
                                @foreach($kayitlar['liste'] as $kayit)

                                <tr>
                                    <td>{{Date::convert($kayit->tarih, '{dayNumber0}.{monthNumber0}.{year}')}}</td>
                                    <td class="text-{{$kayit->islem=='o'?'danger':'success'}}">{{$kayit->islem=="o"?"Ödeme":"Tahsilat"}}</td>
                                    <td>{{$kayit->islemYapaIsim}}</td>
                                    <td>{{$kayit->hesap}}</td>
                                    <td>{{$kayit->aciklama}}</td>
                                    <td class="text-success">{{$kayit->gelir=="0.0000"?"":number_format($kayit->gelir,2)}}</td>
                                    <td class="text-danger">{{$kayit->gider=="0.0000"?"":number_format($kayit->gider,2)}} </td>
                                    <td>{{number_format($guncelTutar,2)}}</td>
                                    <td>
                                        <a href="" data-toggle="tooltip" title="Kayıt Sil" class="btn btn-danger btn-xs"><i data-feather="trash"></i></a>
                                    </td>

                                </tr>
                                {[
                                $guncelTutar = $guncelTutar-$kayit->gelir;
                                $guncelTutar = $guncelTutar+$kayit->gider;
                                ]}
                                @endforeach
                            </table>

                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation">
                                {{$kayitlar['sayfalama']}}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hoverable rows end -->



        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>