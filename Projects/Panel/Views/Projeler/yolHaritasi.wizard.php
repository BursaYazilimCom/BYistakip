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
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a></li>
                                <li class="breadcrumb-item"><a href="{{URL::site('projeler')}}">Projeler</a></li>
                                <li class="breadcrumb-item"><a href="{{URL::site('projeler/form/'.$detay->id)}}">{{$detay->proje_adi}}</a></li>
                                <li class="breadcrumb-item"><a href="#">Proje Yol Haritası</a></li>
                                <li class="breadcrumb-item"><span id="resultJS"></span></li>
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
                        <div class="card brdt-info">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Proje Yol Haritasi</h4>
                                 
                                </div>
                                <div class="dt-action-buttons text-end">

                                    <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$detay->id}}" data-action="yolHaritasiEkle" class="dt-button create-new btn btn-primary dropdown-item text-warning">
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
                                                        <th>Sıra</th>
                                                        <th>Başlık</th>
                                                        <th>Açıklama</th>
                                                        <th>Durum</th>
                                                        <th class="cell-fit"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="sort">
                                                        
                                                    @foreach($listele['liste'] as $h)
                                                    <tr id="row-{{$h->id}}" 
                                                    @if($h->durum=="0")
                                                        style="background-color:#F8D2D2"
                                                    @else
                                                        style="background-color:#D5FFCD"
                                                    @endif
                                                    >
                                                        <td style="cursor:move;">#{{$h->id}}</a></td>
                                                        <td>{{$h->sira}}</a></td>
                                                        <td>{{$h->baslik}}</td>
                                                        <td>{{$h->aciklama}}</td>
                                                        <td>
                                                            @if($h->durum=="0")
                                                                <span class="badge rounded-pill badge-light-danger"> Beklemede </span>
                                                            @else
                                                                <span class="badge rounded-pill badge-light-primary"> Gerçekleştirildi </span>
                                                            @endif
                                                        </td>

                                                        <td>

                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <span data-bs-toggle="tooltip" title="Düzenle">
                                                                    <a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$h->id}}" data-action="yolHaritasiDuzenle" class="dropdown-item">
                                                                    <i data-feather="edit-2" class="me-50"></i>
                                                                </a>
                                                                </span>
                                                                <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Sil" onclick="deleteAction('{{$h->id}}','{{URL::site('Projeler/ajax')}}','yolHaritasiSil')"><i data-feather="trash" class="me-50"></i></a>
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
                <h1 class="text-center mb-1" id="modalTitle">Proje Yönetimi</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>

