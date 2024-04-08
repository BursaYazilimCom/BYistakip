<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">Faturalarım</h3>
        <!--begin::Title-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Index-->
        <div class="card card-page">

            <div class="card-body" style="padding:20px !important">
                {{ Redirect::select('bilgi',true) }}
                
                

                <div class="row">

                    <div class="col-xxl-12">

                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                                                    <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="fw-bolder text-dark fs-2">Ödenmemiş Faturalarınız</span>
                                </h3>
                                <div class="card-toolbar"><a class="badge badge-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Tüm Faturalarımı Göster" href="{{URL::site('faturalarim')}}"><strong>Tüm Faturalarınız</strong></a>
                                </div>
                            </div>  
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-1 p-1">
                                <table class="table table-hover table-rounded table-striped border gy-3 gs-3">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                         
                                            <th>Oluşturma</th>
                                            <th>Son Ödeme</th>
                                            <th>Tutar</th>
                                            <th>Durum</th>
                                            <th>Ödeme</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--begin::Item-->
                                        @foreach($faturalar["liste"] as $fatura)
                                        <tr>
                                        <td>{{Date::convert($fatura->belge_tarihi,"d.m.Y")}}</td>
                                        <td>{{Date::convert($fatura->vade_tarihi,"d.m.Y")}}</td>
                                        <td>{{number_format($fatura->genel_toplam,2)}} ₺</td>

                                        <td>
                                            @if($fatura->durum=="0")
                                            <span class="badge rounded-pill badge-danger"> İptal </span>
                                            @elseif($fatura->durum=="1")
                                            <span class="badge rounded-pill badge-warning"> Resmileşmemiş </span>
                                            @elseif($fatura->durum=="2")
                                            <span class="badge rounded-pill badge-success"> Resmi Faturalı </span>
                                            @else
                                            <span class="badge rounded-pill badge-primary"> Tanımsız </span>
                                            @endif
                                        </td>
                                        <td>@if($fatura->odeme=="1")
                                            <span class="badge rounded-pill badge-success"> Ödendi </span>
                                            @else
                                            <span class="badge rounded-pill badge-danger"> Ödenmedi </span>
                                            @endif</td>
                                            <td>
                                            <a href="{{URL::site('faturalar/detay/'.$fatura->id)}}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Fatura Görüntüle" target="_blank" ><i class="bi bi-eye-fill fs-4 me-2 text-primary"></i></a>
                                            </td>
                                    </tr>
                                        
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                {{$faturalar["sayfalama"]}}
                            </div>
                            <!--end::Body-->
                        </div>

                    </div>

                </div>

            </div>
            <div class="row gy-5 g-xl-8">
                <div class="col-12">
                    
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Index-->
    </div>
    <!--end::Post-->
</div>