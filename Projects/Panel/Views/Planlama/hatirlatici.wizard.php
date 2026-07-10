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
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a></li>
                                <li class="breadcrumb-item"><a href="{{URL::site('planlama')}}">Planlama</a></li>
                                <li class="breadcrumb-item"><a href="#">Hatırlatıcı</a></li>
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
                        <div class="card brdt-navy">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Hatırlatıcı</h4>
                                </div>
                                <div class="dt-action-buttons text-end">

                                    <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$detay->id}}" data-action="hatirlatmaEkle" class="dt-button create-new btn btn-primary dropdown-item text-warning">
                                        <i data-feather="plus" class="me-50"></i>EKLE
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">

                                {{ Redirect::select('bilgi',true) }}

                                <section class="invoice-list-wrapper">
                                    <div class="card">
                                        <div class="card-datatable  table-responsive-sm table-responsive-md table-responsive-xl">
                                            <div id="DataTables_Table_0_wrapper"
                                                 class="dataTables_wrapper dt-bootstrap5 no-footer">

                                                <table class="invoice-list-table table dataTable no-footer dtr-column"
                                                       id="DataTables_Table_0" role="grid"
                                                       aria-describedby="DataTables_Table_0_info">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Detay</th>
                                                        <th>Periyod</th>
                                                        <th>Sonraki Tarih</th>
                                                        <th>Durum</th>
                                                        <th> </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($listele['liste'] as $p)
                                                    <tr id="row-{{$p->id}}">
                                                        <td>#{{$p->id}}</a></td>
                                                        <td>{{$p->aciklama}}</a></td>
                                                        <td>{{$p->periyod=="1"?"Tekrarlayan":"Tek Seferlik"}}</td>
                                                        <td>
                                                            @if($p->periyod=="1")
                                                            {{$p->ay=="0"?"Her Ay'ın":$p->ay}} {{$p->gun=="0"?"Her Gün'ü":$p->gun}} Saat: {{$p->saat}}
                                                            @else
                                                            {{$p->gun}}.{{$p->ay}}.{{$p->yil}}  Saat: {{$p->saat}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($p->durum=="0")
                                                                <span class="badge rounded-pill badge-light-danger"> Pasif </span>
                                                            @else
                                                                <span class="badge rounded-pill badge-light-success"> Aktif </span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <div class="d-flex align-items-center col-actions">
                                                                <div class="dropdown">
                                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                                        <i data-feather="more-vertical"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end">

                                                                        <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$p->id}}" data-action="hatirlatmaDuzenle" class="dropdown-item">
                                                                            <i data-feather="plus" class="me-50"></i>Düzenle
                                                                        </a>

                                                                        <a class="dropdown-item" onclick="deleteAction('{{$p->id}}','{{URL::site('Planlama/ajax')}}','hatirlatmaSil')">
                                                                            <i data-feather="trash" class="me-50"></i>
                                                                            <span>Sil</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <hr>

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
                <h1 class="text-center mb-1" id="modalTitle">Planlama Yönetimi</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>

