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
                                <li class="breadcrumb-item"><a href="{{URL::site('masraf')}}">Gider Grupları</a>
                                </li>
                                <li class="breadcrumb-item active">Tüm Gider Kayıtları
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
                            <a class="dropdown-item" href="{{URL::site('masraf')}}"><i class="me-1" data-feather="plus"></i><span class="align-middle">Gider Ekle</span></a>
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
                                Gider yönetimi
                            </p>
                            {{ Redirect::select('bilgi',true) }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover  table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gider Grubu</th>
                                    <th>Kasa Hesabı</th>
                                    <th>Belge No</th>
                                    <th>Açıklama</th>
                                    <th>Ödeme Durumu</th>
                                    <th>Tutar</th>
                                    <th>Ödeme Tarihi</th>
                                    <th>İşlem Tarihi</th>
                                    <th>#</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($kayitlar['liste'] as $kayit)

                                <tr id="row-{{$kayit->id}}">
                                    <td>{{$kayit->id}}</td>
                                    <td><strong class="text-{{$kayit->renk}}"> {{$kayit->masrafKalemi}}</strong></td>
                                    <td>{{$kayit->kasaHesabi}}</td>
                                    <td>{{$kayit->belge_no}}
                                        @if($kayit->belge_dosya!="")
                                        <a target="_blank" href="" data-toggle="tooltip" title="Dosya İndir" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                        @endif
                                    </td>
                                    <td>{{$kayit->aciklama}}</td>
                                    <td class="text-{{$kayit->odeme_durumu=='1'?'success':'danger'}}">
                                        {{$kayit->odeme_durumu=="1"?"Ödeme Yapıldı":"Ödeme Yapılmadı"}}
                                    </td>
                                    <td>{{number_format($kayit->tutar,2)}}</td>
                                    <td>{{Date::convert($kayit->odeme_tarihi, '{dayNumber0}.{monthNumber0}.{year}')}}</td>
                                    <td>{{Date::convert($kayit->islem_tarihi, '{dayNumber0}.{monthNumber0}.{year}')}}</td>
                                    <td>
                                        <a class="btn btn-danger btn-sm" onclick="deleteAction('{{$kayit->id}}','{{URL::site('masraf/ajax')}}','masrafSil')">
                                            <i data-feather="trash" class="me-50"></i>
                                        </a>


                                    </td>

                                </tr>
                                @endforeach

                                </tbody>
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