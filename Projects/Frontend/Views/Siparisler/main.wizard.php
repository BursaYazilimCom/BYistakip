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
            

                <div class="row">

                    <div class="col-xxl-12">

                        <div class="card mb-5 mb-xl-8">
                                                    <!--begin::Header-->
                            <div class="card-header align-items-center border-0 mt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="fw-bolder text-dark fs-3">Tüm Ürünlerim</span>
                                </h3>
                                <div class="card-toolbar">
                                </div>
                            </div>  
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-1 p-5">
                                <table class="table table-hover table-rounded table-striped border gy-3 gs-3">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>Ürün</th>
                                            <th>Periyod</th>
                                            <th>Adet</th>
                                            <th>Fiyat</th>
                                            <th>Başalangıç Tarihi</th>
                                            <th>Son Ödeme</th>
                                            <th class="text-center">Durum</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listele['liste'] as $uu)
                                        <tr>
                                            <td><strong>{{$uu->notu}}</strong><br><small>{{$uu->urun_adi}}</small></td>
                                            <td>{{AyarModel::odemePeriyodu($uu->odeme_periyodu)}}</td>
                                            <td>{{$uu->adet}}</td>
                                            <td>{{number_format($uu->genel_toplam_tutari,2)}}</td>
                                            <td>{{Date::convert($uu->baslangic_tarihi,'d.m.Y')}}</td>
                                            <td>{{Date::convert($uu->bitis_tarihi,'d.m.Y')}}</td>
                                            <td class="text-center">{{AyarModel::siparisDurumAdi($uu->durum)}}</td>
                                            <td>
                                            <a href="{{URL::site('siparisler/urunDetay/'.$uu->id)}}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Ürün Görüntüle"><i class="bi bi-eye-fill fs-4 me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        
                                        @endforeach
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
                    {{$listele['sayfalama']}}
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Index-->
    </div>
    <!--end::Post-->
</div>