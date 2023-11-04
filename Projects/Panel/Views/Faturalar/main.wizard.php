<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Fatura Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Faturalar</a>
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
                                    <h4 class="card-title">Faturalar</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <a href="{{URL::site('siparisler/form')}}"
                                       class="dt-button create-new btn btn-primary" tabindex="0"><span><i
                                                    data-feather="plus"></i>EKLE</span></a>
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
                                                        <th>İlgili</th>
                                                        <th>Tür</th>
                                                        <th>Cari</th>
                                                        <th>Tutar</th>
                                                        <th>Ödenen</th>
                                                        <th>Fatura Tarihi</th>
                                                        <th>Durum</th>
                                                        <th>Ödeme</th>
                                                        <th class="cell-fit"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($faturalar['liste'] as $fatura)
                                                    <tr class="odd">
                                                        <td class=""><a class="fw-bold" href="{{URL::site('faturalar/duzenle')}}/{{$fatura->id}}">#Fatura-{{$fatura->id}}</a></td>
                                                        <td class=""><a class="fw-bold" href="{{URL::site('siparisler/duzenle/'.$fatura->siparis_id)}}">#{{$fatura->siparis_id}}</a></td>
                                                        <td>
                                                            @if($fatura->tur=="1")
                                                            <span class="badge rounded-pill badge-light-info"> Alış </span>
                                                            @elseif($fatura->tur=="2")
                                                            <span class="badge rounded-pill badge-light-success"> Satış </span>
                                                            @elseif($fatura->tur=="3")
                                                            <span class="badge rounded-pill badge-light-danger"> İade </span>
                                                            @else
                                                            <span class="badge rounded-pill badge-light-primary"> Tanımsız </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-left align-items-center">
                                                                <div class="d-flex flex-column"><h6 class="user-name text-truncate mb-0">({{$fatura->musteri!=""?"Cari":"Tedarikçi"}}) {{$fatura->musteri!=""?CariModel::cariAdi($fatura->musteri):$fatura->tedarikci}}</h6>
                                                                    <small>Firma: {{$fatura->fatura_adi}}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{number_format($fatura->genel_toplam,2)}} ₺</td>
                                                        <td>{{number_format($fatura->alinan_odeme,2)}} ₺</td>
                                                        <td>{{Date::convert($fatura->belge_tarihi,'d.m.Y')}}</td>
                                                        <td>
                                                            @if($fatura->durum=="0")
                                                                <span class="badge rounded-pill badge-light-danger"> İptal </span>
                                                            @elseif($fatura->durum=="1")
                                                                <span class="badge rounded-pill badge-light-warning"> Resmileşmemiş </span>
                                                            @elseif($fatura->durum=="2")
                                                                <span class="badge rounded-pill badge-light-success"> Resmi Faturalı </span>
                                                            @else
                                                                <span class="badge rounded-pill badge-light-primary"> Tanımsız </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($fatura->odeme=="1")
                                                            <span class="badge rounded-pill badge-light-success"> Ödendi </span>
                                                            @else
                                                            <span class="badge rounded-pill badge-light-danger"> Ödenmedi </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center col-actions">
                                                                <div class="dropdown">
                                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                                        <i data-feather="more-vertical"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a href="{{URL::site('siparisler/urunler')}}/{{$fatura->id}}" class="dropdown-item">
                                                                            <i data-feather="send" class="me-50"></i>
                                                                            <span>Bildirim Gönder</span>
                                                                        </a>
                                                                        <a href="{{URL::site('siparisler/hatirlatmaGonder')}}/{{$fatura->id}}" class="dropdown-item">
                                                                            <i data-feather="eye" class="me-50"></i>
                                                                            <span>Fatura Görüntüle</span>
                                                                        </a>
                                                                        <a href="{{URL::site('faturalar/duzenle')}}/{{$fatura->id}}" class="dropdown-item">
                                                                            <i data-feather="edit-2" class="me-50"></i>
                                                                            <span>Düzenle</span>
                                                                        </a>
                                                                        @if($fatura->durum=="1")
                                                                            <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$fatura->id}}" data-action="faturaResmilestir" class="dropdown-item">
                                                                                <i data-feather="edit-2" class="me-50"></i>
                                                                                <span class="text-success">Faturayı Resmileştir</span>
                                                                            </a>
                                                                        @endif

                                                                        <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$fatura->id}}" data-action="faturaOdendiYap" class="dropdown-item">
                                                                            <i data-feather="edit-2" class="me-50"></i>
                                                                            <span class="text-success">Ödenme EKle</span>
                                                                        </a>

                                                                        @if($fatura->odeme=="1")

                                                                            <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$fatura->id}}" data-action="faturaOdenmediYap" class="dropdown-item">
                                                                                <i data-feather="edit-2" class="me-50"></i>
                                                                                <span class="text-danger">ÖdenMEdi Yap</span>
                                                                            </a>

                                                                        @endif

                                                                        <a class="dropdown-item" onclick="deleteAction('{{$fatura->id}}','{{URL::site('siparisler/ajax')}}','siparisSil')">
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

