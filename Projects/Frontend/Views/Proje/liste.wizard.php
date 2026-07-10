<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">Projelerim</h3>
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
                                            <th class="d-none d-sm-block">Başlangıç</th>
                                            <th>Termin</th>
                                            <th class="d-none d-sm-block">Durum</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--begin::Item-->
                                        @foreach($cariProjeleri['liste'] as $proje)
                                            <tr>
                                                <td>{{$proje->proje_adi}}</td>
                                                <td class="d-none d-sm-block">{{Date::convert($proje->proje_baslangic_tarihi,'d.m.Y')}}</td>
                                                <td>{{Date::convert($proje->tahmini_bitis_tarihi,'d.m.Y')}}</td>
                                                <td class="d-none d-sm-block">
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
                            <div class="card-footer">
                                {{$cariProjeleri['sayfalama']}}
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