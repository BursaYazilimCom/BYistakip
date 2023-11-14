<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Invoice-->
        <div class="card">
            <!--begin::Body-->
            <div class="card-body p-lg-20">
                <!--begin::Layout-->
                <div class="d-flex flex-column flex-xl-row">
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">


                        <!--begin::Head-->
                        <div class="d-flex flex-stack mb-10 mb-lg-15">
                            <!--begin::Logo-->
                            <a href="#">
                                <img alt="Logo" class="h-40px" src="{{URl::site()}}Uploads/firma-logo/{{AyarModel::defaultAyarlar('firmaLogo')}}" />
                            </a>
                            <!--end::Logo-->
                        </div>
                        <!--end::Head-->
                        <!--begin::Wrapper-->
                        <div class="mb-0">

                            <!-- Ödeme formunun açılması için gereken HTML kodlar / Başlangıç -->
                            <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
                            <iframe src="https://www.paytr.com/odeme/guvenli/{[ echo $token;]}" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
                            <script>iFrameResize({},'#paytriframe');</script>
                            <!-- Ödeme formunun açılması için gereken HTML kodlar / Bitiş -->
                            <!--begin::Label-->

                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Content-->
                    <!--begin::Sidebar-->
                    <div class="m-0">
                        <!--begin::Invoice sidebar-->
                        <div class="fw-bolder fs-3 text-gray-800 mb-8">Fatura No <a href="{{URL::site()}}faturalar/detay/{{$detay->id}}"> #{{$detay->belge_no}}</a></div>
                        <!--end::Label-->
                        <!--begin::Row-->
                        <div class="row g-5 mb-11">
                            <!--end::Col-->
                            <div class="col-sm-6">
                                <!--end::Label-->
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Fatura Tarihi:</div>
                                <!--end::Label-->
                                <!--end::Col-->
                                <div class="fw-bolder fs-6 text-gray-800">{{$detay->belge_tarihi}}</div>
                                <!--end::Col-->
                            </div>
                            <!--end::Col-->
                            <!--end::Col-->
                            <div class="col-sm-6">
                                <!--end::Label-->
                                <div class="fw-bold fs-7 text-gray-600 mb-1">Son Ödeme Tarihi:</div>
                                <!--end::Label-->
                                <!--end::Info-->
                                <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                    <span class="pe-2">{{$detay->vade_tarihi}}</span>

                                    @if($detay->gecen_gun>0)
                                    <span class="fs-7 text-danger d-flex align-items-center">
											    	<span class="bullet bullet-dot bg-danger me-2"></span>{{$detay->gecen_gun}} gün geçmiş
                                                </span>
                                    @elseif($detay->gecen_gun==0)
                                    <span class="fs-7 text-primary d-flex align-items-center">
                                                <span class="bullet bullet-dot bg-primary me-2"></span>Bugün Ödeme Günü
                                                </span>
                                    @else
                                    <span class="fs-7 text-success d-flex align-items-center">
                                                    <span class="bullet bullet-dot bg-success me-2"></span>{{-$detay->gecen_gun}} gün var
                                                </span>
                                    @endif
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->

                        <!--end::Row-->
                        <!--begin::Content-->
                        <div class="flex-grow-1">
                            <!--begin::Table-->
                            <div class="table-responsive border-bottom mb-9">
                                <table class="table mb-3">
                                    <thead>
                                    <tr class="border-bottom fs-6 fw-bolder text-gray-400">
                                        <th class="min-w-175px pb-2">Ürün</th>
                                        <th class="min-w-100px text-end pb-2">Toplam</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {[
                                    $kdv10 = 0;
                                    $kdv20 = 0;
                                    $toplamTutar = 0;
                                    $araToplamTutar = 0;
                                    ]}

                                    @foreach($faturaUrunleri as $furun)

                                    {[
                                    $araToplamTutar = $araToplamTutar+($furun->fiyat*$furun->miktar);
                                    $toplamTutar    = $toplamTutar+$furun->tutar;
                                    ]}

                                    @if($furun->kdv=="10")
                                    {[
                                    $kdv10 = $kdv10+$furun->kdv_tutari;
                                    ]}
                                    @endif

                                    @if($furun->kdv=="20")
                                    {[
                                    $kdv20 = $kdv20+$furun->kdv_tutari;
                                    ]}
                                    @endif


                                    <tr class="fw-bolder text-gray-700 fs-5 text-end">
                                        <td class="d-flex align-items-center pt-6">
                                            {{$furun->urun_adi}}</td>
                                        <td class="pt-6 text-dark fw-boldest">{{number_format($furun->tutar,2)}} ₺</td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!--end::Table-->
                            <!--begin::Container-->
                            <div class="d-flex justify-content-end">
                                <!--begin::Section-->
                                <div class="mw-300px">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountname-->
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Ara Toplam:</div>
                                        <!--end::Accountname-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bolder fs-6 text-gray-800">{{number_format($araToplamTutar,2)}} ₺</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountname-->
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Kdv %10</div>
                                        <!--end::Accountname-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bolder fs-6 text-gray-800">{{number_format($kdv10,2)}} ₺</div>
                                        <!--end::Label-->
                                    </div>
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountname-->
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Kdv %20</div>
                                        <!--end::Accountname-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bolder fs-6 text-gray-800">{{number_format($kdv20,2)}} ₺</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountnumber-->
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Kdv Toplamı</div>
                                        <!--end::Accountnumber-->
                                        <!--begin::Number-->
                                        <div class="text-end fw-bolder fs-6 text-gray-800">{{number_format($kdv20+$kdv10,2)}} ₺</div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Code-->
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Genel Toplam</div>
                                        <!--end::Code-->
                                        <!--begin::Label-->
                                        <div class="text-end fw-bolder fs-6 text-gray-800">{{number_format($toplamTutar,2)}} ₺</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Content-->
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