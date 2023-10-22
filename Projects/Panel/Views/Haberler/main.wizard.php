<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Haber Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Haberler
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
                            <a class="dropdown-item" href="{{URL::site('haberler/form')}}"><i class="me-1" data-feather="plus"></i><span class="align-middle">Haber Ekle</span></a>
                            <a class="dropdown-item" href="{{URL::site('haberler/kategoriler')}}"><i class="me-1" data-feather="plus"></i><span class="align-middle">Kategoriler</span></a>

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
                            <h4 class="card-title">Haberler</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Haber yönetimi
                            </p>
                            {{ Redirect::select('bilgi',true) }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover  table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Firma</th>
                                    <th>Kategori</th>
                                    <th>Haber Başlığı</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listele['liste'] as $veri)
                                <tr id="row-{{$veri->id}}">
                                    <td>{{$veri->id}}</td>
                                    <td>{{FirmaModel::adi($veri->firma_id)}}</td>
                                    <td>{{HaberModel::kategoriAdi($veri->kategori)}}</td>
                                    <td>{{$veri->baslik}}</td>


                                    <td>
                                        @if($veri->durum=="1")
                                            <span class="badge rounded-pill badge-light-primary me-1">Aktif</span>
                                        @else
                                        <span class="badge rounded-pill badge-light-warning me-1">Pasif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{URL::site('haberler/form/')}}{{$veri->id}}">
                                                    <i data-feather="edit-2" class="me-50"></i>
                                                    <span>Düzenle</span>
                                                </a>

                                                <a class="dropdown-item" onclick="deleteAction('{{$veri->id}}','{{URL::site('haberler/ajax')}}','haberSil')">
                                                    <i data-feather="trash" class="me-50"></i>
                                                    <span>Sil</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation">
                            {{$listele['sayfalama']}}
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