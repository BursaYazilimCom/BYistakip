<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{AyarModel::defaultAyarlar('siteAdi')}} Bazı İstatistikler</h3>
        <!--begin::Title-->
        <!--begin::Actions-->

        <!--end::Actions-->
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
            <!--begin::Card body-->
            <div class="card-body">
                {{ Redirect::select('bilgi',true) }}
                <!--begin::Row-->
                <div class="row gy-5 g-xl-8">
                    <!--begin::Col-->
                    <div class="col-xxl-6">
                        <!--begin::Table Widget 1-->
                        <div class="card card-xxl-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5 pb-3">
                                <!--begin::Card title-->
                                <h3 class="card-title fw-bolder text-gray-800 fs-2">Giriş Yap</h3>
                                <!--end::Card title-->

                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body py-0">

                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Table Widget 1-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xxl-6">
                        <!--begin::Row-->
                        <div class="row g-5 g-xl-8">
                            <!--begin::Col-->
                            <div class="col-xxl-6">
                                <!--begin::Statistics Widget 1-->
                                <div class="card card-l-stretch-50 pb-5 mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <div class="text-success fw-boldest fs-2hx">620 Adet</div>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Mutlu Müşteri</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-xxl-6">
                                <!--begin::Mixed Widget 2-->
                                <div class="card pb-5 mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <span class="text-primary fw-boldest fs-2hx">3 Adet</span>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Devam Eden Proje</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                </div>

                            </div>

                            <div class="col-xxl-6">
                                <!--begin::Statistics Widget 1-->
                                <div class="card card-l-stretch-50 pb-5 mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <div class="text-success fw-boldest fs-2hx">1620 Adet</div>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Verilen Hizmet</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                    <!--end::Body-->
                                </div>
                            </div>

                            <div class="col-xxl-6">
                                <!--begin::Mixed Widget 2-->
                                <div class="card pb-5 mb-xl-8">
                                    <!--begin::Body-->
                                    <div class="card-body d-flex flex-column justify-content-between p-0">
                                        <!--begin::Hidden-->
                                        <div class="d-flex flex-column px-9 pt-5">
                                            <!--begin::Number-->
                                            <span class="text-primary fw-boldest fs-2hx">75 Adet</span>
                                            <!--end::Number-->
                                            <!--begin::Description-->
                                            <span class="text-gray-400 fw-bold fs-6">Tamamlanan Proje</span>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Hidden-->

                                    </div>
                                </div>

                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>





            </div>
            <!--end::Card body-->
        </div>
        <!--end::Index-->
    </div>
    <!--end::Post-->
</div>