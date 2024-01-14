<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Invoice-->
        <div class="card">
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Layout-->
                <div class="row">
                    <!--begin::Content-->
                    <div class="col-xl-9">
                        <!--begin::Head-->
                        <div class="d-flex flex-stack mb-10 mb-lg-15">
                            <!--begin::Logo-->
                            <a href="#">
                                <img alt="Logo" class="h-40px" src="{{URL::site()}}Uploads/firma-logo/{{AyarModel::defaultAyarlar('firmaLogo')}}" />
                            </a>
                            <!--end::Logo-->
                            <!--begin::Actions-->
                            <div class="my-1">
                                @if($detay->durum=="2")
                                <span class="btn btn-sm btn-success me-2">Tamamlandı</span>
                                @else
                                <span class="btn btn-sm btn-success me-2">Devam Ediyor</span>
                                @endif
                                <a href="{{URL::site('proje/main/'.$detay->sef)}}" class="btn btn-sm btn-info me-2">Yol Haritası</a>
                                <a href="#" class="btn btn-sm btn-success me-2" onclick="window.print()">Yazdır</a>

                            </div>
                            <!--end::Actions-->
                        </div>


                        <!--begin::Header-->
                        <div class="card-header align-items-center border-0 mt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="fw-bolder text-dark fs-2">{{$detay->proje_adi}}</span>
                                <span class="text-gray-400 mt-2 fw-bold fs-6">Projede  Yapılan Çalışmalar</span>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <table class="table table-borderless">
                            <!--begin::Item-->
                                {[
                                $tarih = "";
                                $i = 1;
                                ]}

                            @foreach($calismalar['liste'] as $yol)
                                {[

                                if($yol->tarih != $tarih){

                                $tarih = $yol->tarih;

                                $i = 1;

                                }


                                if($i==1){

                                ]}

                                <tr>
                                    <th colspan="2" style="border-bottom: solid 1px #379bff; padding-left: 10px" class="bg-primary"><h3>{{Date::convert($yol->tarih,"d.m.Y")}}</h3></th>
                                </tr>


                                {[ } ]}

                                <tr>
                                    <td style="border-left: solid 1px #379bff">
                                        @if($yol->tur=="1")
                                        <span class="badge badge-light-info rounded-pill fs-7 fw-boldest">Değişiklik</span>
                                        @elseif($yol->tur=="2")
                                        <span class="badge badge-light-danger rounded-pill fs-7 fw-boldest">Hata Çözümü</span>
                                        @elseif($yol->tur=="3")
                                        <span class="badge badge-light-success rounded-pill fs-7 fw-boldest">Yenilik</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="ps-1 mb-1">
                                            <div class="text-black">{{$yol->islem}}</div>
                                        </div>

                                    </td>

                                </tr>



                            {[ $i++; ]}
                            @endforeach

                            </table>
                            <div class="rounded border border-primary bg-light-primary border-dashed px-6 py-5">
                                <a href="#" class="link-primary fw-bolder fs-6 me-1">Önemli Not</a><br>
                                <span class="text-gray-800 fw-bold fs-6">Bu sayfa; Projenizin ilerleyişi için detaylı bilgilendirme ve gelişmelerden haberdar olmanız için yapılan çalışmaları içermektedir.</span>

                            </div>
                            <!--end::Alert-->
                        </div>


                    </div>
                    <!--end::Content-->
                    <!--begin::Sidebar-->
                    <div class=" col-xl-3">
                        <!--begin::Invoice sidebar-->
                        <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 p-9 bg-lighten">
                            <!--begin::Labels-->
                            <div class="mb-8">

                                <div class="col-sm-12">
                                    <!--end::Label-->
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Proje Başlangıç Tarihi:</div>
                                    <!--end::Label-->
                                    <!--end::Col-->
                                    <div class="fw-bolder fs-6 text-gray-800">{{Date::convert($detay->proje_baslangic_tarihi,'d.m.Y')}}</div>
                                    <!--end::Col-->
                                </div>

                                <div class="col-sm-12">
                                    <!--end::Label-->
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Proje Termin Tarihi:</div>
                                    <!--end::Label-->
                                    <!--end::Col-->
                                    <div class="fw-bolder fs-6">{{Date::convert($detay->tahmini_bitis_tarihi,'d.m.Y')}}</div>
                                    <!--end::Col-->
                                </div>

                            </div>
                            <!--end::Labels-->

                            <!--begin::Item-->
                            <div class="mb-6">
                                <div class="fw-bold text-gray-600 fs-7">Açıklama:</div>
                                <div class="fs-6">{{$detay->aciklama}}</div>
                            </div>

                            <!--end::Item-->
                            <!--begin::Item-->

                        </div>
                        <!--end::Invoice sidebar-->
                    </div>
                    <!--end::Sidebar-->
                </div>
                <!--end::Layout-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Invoice-->
    </div>
    <!--end::Post-->
</div>