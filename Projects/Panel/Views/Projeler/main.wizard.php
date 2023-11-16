<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Proje Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Projeler</a>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Projeler</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <a href="{{URL::site('projeler/form')}}" class="dt-button create-new btn btn-primary" tabindex="0" ><span><i data-feather="plus"></i>EKLE</span></a>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Proje Adı</th>
                                            <th>Müşteri</th>
                                            <th>Başlangıç Tarihi</th>
                                            <th>Termin Tarihi</th>
                                            <th>Bitiş Tarihi</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($listele['liste'] as $u)
                                        <tr id="row-{{$u->id}}">
                                            <td>{{$u->id}}</td>
                                            <td>{{$u->proje_adi}}</td>
                                            <td>{{$u->musteri}}</td>
                                            <td>{{$u->proje_baslangic_tarihi}}</td>
                                            <td>{{$u->tahmini_bitis_tarihi}}</td>
                                            <td>{{$u->bitis_tarihi}}</td>
                                            <td>
                                                @if($u->durum=="1")
                                                <span class="badge bg-success">Teslim Edildi</span>
                                                @else
                                                <span class="badge bg-info">Devam Ediyor</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="{{URL::site('projeler/form')}}/{{$u->id}}" class="dropdown-item">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                            <span>Düzenle</span>
                                                        </a>
                                                        <a class="dropdown-item" onclick="deleteAction('{{$u->id}}','{{URL::site('projeler/ajax')}}','projeSil')">
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
                                <hr>
                                {{$listele['sayfalama']}}
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice repeater -->
                </div>
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->

