<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Reklam Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Reklam Anlaşmaları</a>
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
                        <div class="card brdt-warning">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Reklam Anlaşmaları</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <a href="{{URL::site('reklam/anlasmaForm')}}" class="dt-button create-new btn btn-primary" tabindex="0" ><span><i data-feather="plus"></i>ANLAŞMA EKLE</span></a>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Cari</th>
                                            <th>Periyod</th>
                                            <th>Ücret</th>
                                            <th>Başlangıç</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($liste['liste'] as $u)
                                        <tr class="table-{{$u->durum=='0'?'danger':''}}" id="row-{{$u->id}}">
                                            <td>{{$u->cariAdi}}</td>
                                            <td>{{ReklamModel::periyod($u->periyod)}}</td>
                                            <td>{{$u->ucret}}</td>
                                            <td>{{$u->baslangic_tarihi}}</td>
                                            <td>
                                                @if($u->durum=="0")
                                                    <span class="badge bg-danger">Pasif</span>
                                                @else
                                                    <span class="badge bg-success">Aktif</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">

                                                    <a class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Düzenle" href="{{URL::site('reklam/anlasmaForm')}}/{{$u->id}}">
                                                        <i data-feather="edit-2" class="me-50"></i>
                                                    </a>

                                                    <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Sil" onclick="deleteAction('{{$u->id}}','{{URL::site('reklam/ajax')}}','anlasmaSil')"><i data-feather="trash" class="me-50"></i></a>
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

<div class="modal fade" id="openModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center mb-1" id="modalTitle">Hesap İşlemleri</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>

