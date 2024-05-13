<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-6">
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
                    <div class="col-6">
                        <div class="btn-group float-end" role="group" aria-label="Basic example">
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-warning dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i data-feather="chevron-down"></i> Filtre
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{URL::site('faturalar/main/odeme-0')}}" class="dropdown-item text-danger">
                                        <i class="far fa-thumbs-down me-50"></i>
                                        <span>Ödenmemiş Faturalar</span>
                                    </a>
                                    <a href="{{URL::site('faturalar/main/odeme-1')}}" class="dropdown-item text-success">
                                        <i class="far fa-thumbs-up me-50"></i>
                                        <span>Ödenmiş Faturalar</span>
                                    </a>

                                    <a href="{{URL::site()}}faturalar/main/durum-1" class="dropdown-item text-danger">
                                        <i class="fas fa-user-secret me-50"></i>
                                        <span>Resmi Olmayan Faturalar</span>
                                    </a>
                                    <a href="{{URL::site('faturalar/main/durum-2')}}" class="dropdown-item text-success">
                                        <i class="fas fa-file-invoice me-50"></i>
                                        <span>Resmi Faturalar</span>
                                    </a>
                                    <a href="{{URL::site('faturalar/main/durum-0')}}" class="dropdown-item text-active-dark">
                                        <i class="fas fa-file-invoice me-50"></i>
                                        <span>İptal Faturalar</span>
                                    </a>


                                </div>
                            </div>
                            <a href="{{URL::site('faturalar/olustur')}}"
                               class="dt-button create-new btn btn-sm btn-info"><span><i
                                            data-feather="plus"></i>Fatura Oluştur</span></a>

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
                        <div class="card brdt-pink">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Faturalar</h4>
                                </div>

                            </div>
                            <div class="card-body">

                                {{ Redirect::select('bilgi',true) }}

                                <section class="invoice-list-wrapper">
                                    <div class="card">
                                        <div class="card-datatable table-responsive-sm table-responsive-md table-responsive-xl">
                                            <div id="DataTables_Table_0_wrapper"
                                                 class="dataTables_wrapper dt-bootstrap5 no-footer">

                                                <table class="invoice-list-table table dataTable no-footer dtr-column"
                                                       id="DataTables_Table_0" role="grid"
                                                       aria-describedby="DataTables_Table_0_info">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>

                                                        <th>Tür</th>
                                                        <th>Cari</th>
                                                        <th>Tutar</th>
                                                        <th>Ödenen</th>
                                                        <th>Tarihler</th>
                                                        <th>Durum</th>
                                                        <th>Ödeme</th>
                                                        <th class="cell-fit"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($faturalar['liste'] as $fatura)
                                                    <tr class="odd">
                                                        <td class=""><a class="fw-bold" href="{{URL::site('faturalar/duzenle')}}/{{$fatura->id}}">#{{$fatura->id}}</a></td>
                                                        <td>
                                                            @if($fatura->tur=="1")
                                                            <span class="badge rounded-pill badge-light-warning"> Alış Faturası </span>
                                                            @elseif($fatura->tur=="2")
                                                            <span class="badge rounded-pill badge-light-success"> Satış </span>
                                                            @elseif($fatura->tur=="3")
                                                            <span class="badge rounded-pill badge-light-danger"> İade </span>
                                                            @else
                                                            <span class="badge rounded-pill badge-light-primary"> Tanımsız </span>
                                                            @endif
                                                            @if($fatura->tur!="1")
                                                                @if($fatura->satis_turu=="1")
                                                                <span class="badge rounded-pill badge-light-info"> İlk Sipariş Faturası </span>
                                                                @else
                                                                <span class="badge rounded-pill badge-light-primary"> Yenileme Faturası </span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-left align-items-center">
                                                                <div class="d-flex flex-column">
                                                                    <h6 class="user-name text-truncate mb-0">
                                                                        <strong>
                                                                        ({{$fatura->musteri!="0"?"Cari":"Tedarikçi"}})
                                                                        {{$fatura->musteri!="0"?CariModel::cariAdi($fatura->musteri):TedarikciModel::tedarikciAdi($fatura->tedarikci)}}
                                                                        </strong>
                                                                    </h6>
                                                                    @if($fatura->musteri!="0")
                                                                    <small data-bs-toggle="tooltip" data-bs-placement="top" title="{{$fatura->fatura_adi}}">
                                                                        Firma: {{mb_substr($fatura->fatura_adi,0,15)}}...</small>
                                                                    @else
                                                                    <small data-bs-toggle="tooltip" data-bs-placement="top" title="{{TedarikciModel::tedarikciFirma($fatura->tedarikci)}}">
                                                                        Firma: {{mb_substr(TedarikciModel::tedarikciFirma($fatura->tedarikci),0,15)}}...</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{number_format($fatura->genel_toplam,2)}} ₺</td>
                                                        <td>
                                                            @if($fatura->tur=="1" and $fatura->odeme=="1")
                                                            {{number_format($fatura->genel_toplam,2)}} ₺
                                                            @else
                                                            {{number_format($fatura->alinan_odeme,2)}} ₺
                                                            @endif

                                                        </td>
                                                        <td>
                                                            F: <span data-bs-target="tooltip" data-bs-placement="top" title="Fatura Tarihi">{{Date::convert($fatura->belge_tarihi,'d.m.Y')}}</span><br>
                                                            Ö: <strong data-bs-target="tooltip" data-bs-placement="top" title="Son Ödeme Tarihi">{{Date::convert($fatura->vade_tarihi,'d.m.Y')}}</strong><br>

                                                        </td>
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
                                                                        <i data-feather="chevron-down"></i> İşlemler
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a href="{{URL::site('siparisler/duzenle')}}/{{$fatura->siparis_id}}" class="dropdown-item">
                                                                            <i data-feather="send" class="me-50"></i>
                                                                            <span>Siparişe git</span>
                                                                        </a>

                                                                        <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$fatura->id}}" data-action="bildirimGonder" class="dropdown-item text-warning">
                                                                            <i data-feather="send" class="me-50"></i>Ödeme Hatırlat
                                                                        </a>

                                                                        <a href="{{URL::site()}}../faturalar/detay/{{$fatura->id}}" target="_blank" class="dropdown-item">
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
                                                                            <span class="text-success">Ödeme EKle</span>
                                                                        </a>

                                                                        @if($fatura->odeme=="1")

                                                                            <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$fatura->id}}" data-action="faturaOdenmediYap" class="dropdown-item">
                                                                                <i data-feather="edit-2" class="me-50"></i>
                                                                                <span class="text-danger">ÖdenMEdi Yap</span>
                                                                            </a>
                                                                        @else
                                                                            <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$fatura->id}}" data-action="faturayiOdendiYap" class="dropdown-item">
                                                                                <i data-feather="edit-2" class="me-50"></i>
                                                                                <span class="text-danger">Sadece Ödendi Yap</span>
                                                                            </a>
                                                                        @endif

                                                                    
                                                                        <a class="dropdown-item confirm" href="{{URL::site()}}faturalar/sil/{{$fatura->id}}">
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
                                        <div class="card-footer">
                                            {{$faturalar['sayfalama']}}
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

