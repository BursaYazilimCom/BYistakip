<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$user->adi}}</h3>
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
                
                <div class="row g-5 g-xl-8">
                            <!--begin::Col-->
                            <div class="col-xxl-3">
                                <!--begin::Statistics Widget 1-->
                                <div class="card card-l-stretch-50 pb-5 mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <div class="text-success fw-boldest fs-2hx">{{$toplamlar['uyeUrunAdet']}}</div>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Toplam Ürün</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>
                            <!--end::Col-->
                            
                            <div class="col-xxl-3">
                                <!--begin::Statistics Widget 1-->
                                <div class="card pb-5  mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <div class="text-success fw-boldest fs-2hx">{{$toplamlar['talepler']}}</div>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Destek Talepleri</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>
                            <!--begin::Col-->
                            <div class="col-xxl-3">
                                <!--begin::Mixed Widget 2-->
                                <div class="card pb-5 mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <span class="text-danger fw-boldest fs-2hx">{{$toplamlar['odenmemisFaturaToplami']}} ₺</span>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Ödenmemiş Fatura</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                </div>

                            </div>


                            <div class="col-xxl-3">
                                <!--begin::Mixed Widget 2-->
                                <div class="card pb-5  mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <span class="text-primary fw-boldest fs-2hx">{{$toplamlar['odenenFaturaToplami']}} ₺</span>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Toplam Ödemeniz</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                </div>

                            </div>
                            <!--end::Col-->
                        </div>

                <div class="row">

                    <div class="col-xxl-6">

                        <div class="card mb-5 mb-xl-8">
                                                    <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="fw-bolder text-dark fs-3">Tarihi Yaklaşan Ürünleriniz</span>
                                </h3>
                                <div class="card-toolbar"><a class="badge badge-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Tüm Ürünlerimi Göster" href="{{URL::site('siparisler')}}"><strong>Tüm Ürünlerim</strong></a>
                                </div>
                            </div>  
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-1 p-1">
                                <table class="table table-hover table-rounded table-striped border gy-3 gs-3">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>Ürün</th>
                                            <th>Fiyat</th>
                                            <th>Son Ödeme</th>
                                            <th class="text-center">Durum</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--begin::Item-->
                                        {[ $u++; ]}
                                        @foreach($uyeUrunleri['liste'] as $uu)
                                        <tr>
                                            <td><strong>{{$uu->notu}}</strong><br><small>{{$uu->urun_adi}}</small></td>
                                            <td>{{number_format($uu->genel_toplam_tutari,2)}}</td>
                                            <td>{{Date::convert($uu->bitis_tarihi,'d.m.Y')}}</td>
                                            <td class="text-center">{{AyarModel::siparisDurumAdi($uu->durum)}}</td>
                                            <td>
                                            <a href="{{URL::site('siparisler/urunDetay/'.$uu->id)}}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Ürün Görüntüle"><i class="bi bi-eye-fill fs-4 me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        
                                        {[ $u++;
                                            if($u==6){break;}
                                            ]}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Body-->
                        </div>

                    </div>

                    <div class="col-xxl-6">

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
                                        @foreach($faturalar['liste'] as $fatura)
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
                            <!--end::Body-->
                        </div>

                    </div>

                    <div class="col-xxl-6">

                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                                                    <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="fw-bolder text-dark fs-2">Projeleriniz</span>
                                </h3>
                                <div class="card-toolbar"><a class="badge badge-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Tüm Faturalarımı Göster" href="{{URL::site('proje/liste')}}"><strong>Tüm Projeleriniz</strong></a>
                                </div>
                            </div>  
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-1 p-1">
                                <table class="table table-hover table-rounded table-striped border gy-3 gs-3">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>Proje Adı</th>
                                            <th>Başlangıç</th>
                                            <th>Termin</th>
                                            <th class="text-end">Durum</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--begin::Item-->
                                        @foreach($cariProjeleri['liste'] as $proje)
                                            <tr>
                                                <td>{{$proje->proje_adi}}</td>
                                                <td>{{Date::convert($proje->proje_baslangic_tarihi,'d.m.Y')}}</td>
                                                <td>{{Date::convert($proje->tahmini_bitis_tarihi,'d.m.Y')}}</td>
                                                <td>
                                                    <span class="badge badge-{{$proje->durum==1?'success':'primary'}}">{{$proje->durum==1?'Teslim Edildi':'Devam Ediyor'}}</span>
                                                </td>
                                               
                                                <td>
                                                    <a href="{{URL::site('proje/'.$proje->sef)}}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Proje Görüntüle" target="_blank" ><i class="bi bi-eye-fill fs-4 me-2 text-primary"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Body-->
                        </div>

                    </div>

                    <div class="col-xxl-6">

                        <div class="card card-xl-stretch mb-5 mb-xl-8">
                                                    <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="fw-bolder text-dark fs-2">Destek Taleplerim</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a class="badge badge-danger" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Tüm Faturalarımı Göster" href="{{URL::site('destek/olustur')}}"><strong>Talep Oluştur</strong></a> 
                                    <a class="badge badge-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Tüm Faturalarımı Göster" href="{{URL::site('destek')}}"><strong>Taleplerim</strong></a>
                                </div>
                            </div>  
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-1 p-1">
                                <table class="table table-hover table-rounded table-striped border gy-3 gs-3">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>Konu</th>
                                            <th>Talep Tarihi</th>
                                            <th class="text-center">Durum</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--begin::Item-->
                                        
                                            <tr>
                                                <td>Ödeme Hakkında Destek</td>
                                                <td>05.04.2024 15:25</td>
                                                <td>
                                                    <span class="badge badge-primary">Devam Ediyor</span>
                                                </td>
                                               
                                                <td class="text-center">
                                                    <a href="{{URL::site('destek/detay/'.$proje->sef)}}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Talep Görüntüle" target="_blank" ><i class="bi bi-eye-fill fs-4 me-2 text-primary"></i></a>
                                                </td>
                                            </tr>
                                      
                                    </tbody>
                                </table>
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