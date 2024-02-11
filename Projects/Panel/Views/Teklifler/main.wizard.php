<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Teklif Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Teklifler</a>
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
                                    <h4 class="card-title">Teklifler</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <a href="{{URL::site('teklifler/olustur')}}"
                                       class="dt-button create-new btn btn-primary" tabindex="0"><span><i
                                                    data-feather="plus"></i>Teklif Oluştur</span></a>
                                </div>
                            </div>
                            <div class="card-body">

                                {{ Redirect::select('bilgi',true) }}

                                <section class="invoice-list-wrapper">
                                    <div class="card">
                                        <div class="card-datatable table-responsive">
                                            <div id="DataTables_Table_0_wrapper"
                                                 class="dataTables_wrapper dt-bootstrap5 no-footer">

                                                <table class="invoice-list-table table dataTable no-footer dtr-column"
                                                       id="DataTables_Table_0" role="grid"
                                                       aria-describedby="DataTables_Table_0_info">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Cari</th>
                                                        <th>Teklif Tarihi</th>
                                                        <th>Durum</th>
                                                        <th class="cell-fit"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($teklifler['liste'] as $teklif)
                                                    <tr class="odd">
                                                        <td class="">
                                                            <a class="fw-bold" href="{{URL::site('faturalar/duzenle')}}/{{$teklif->id}}">#Fatura-{{$teklif->id}}</a>
                                                        </td>
                                                 
                                                        
                                                        <td>
                                                            <div class="d-flex justify-content-left align-items-center">
                                                                <div class="d-flex flex-column">
                                                                    <h6 class="user-name text-truncate mb-0">
                                                                        <strong>
                                                                        {{CariModel::cariAdi($teklif->musteri)}}
                                                                        </strong>
                                                                    </h6>
                                                                    <small>Firma: {{$teklif->fatura_adi}}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                      
                                                        <td><strong>{{Date::convert($teklif->ekleme_tarihi,'d.m.Y')}}</strong></td>
                                                        <td>
                                                            @if($teklif->durum=="0")
                                                                <span class="badge rounded-pill badge-light-warning"> Değerlendirmede </span>
                                                            @elseif($teklif->durum=="1")
                                                                <span class="badge rounded-pill badge-light-success"> Siparişe Dönüştü </span>
                                                            @elseif($teklif->durum=="2")
                                                                <span class="badge rounded-pill badge-light-danger"> Siparişe Dönüşmedi </span>
                                                            @else
                                                                <span class="badge rounded-pill badge-light-primary"> Tanımsız </span>
                                                            @endif
                                                        </td>
                                                     
                                                        <td>
                                                            <div class="d-flex align-items-center col-actions">
                                                                <div class="dropdown">
                                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                                        <i data-feather="chevron-down"></i> İşlemler
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a href="{{URL::site()}}../teklif/detay/{{$teklif->id}}" target="_blank" class="dropdown-item">
                                                                            <i data-feather="eye" class="me-50"></i>
                                                                            <span>Teklif Görüntüle</span>
                                                                        </a>
                                                                        <a href="{{URL::site('teklifler/duzenle')}}/{{$teklif->id}}" class="dropdown-item">
                                                                            <i data-feather="edit-2" class="me-50"></i>
                                                                            <span>Düzenle</span>
                                                                        </a>
                                                                        <a class="dropdown-item confirm" href="{{URL::site()}}teklifler/sil/{{$teklif->id}}">
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
                <h1 class="text-center mb-1" id="modalTitle">Fatura İşlemleri</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>

