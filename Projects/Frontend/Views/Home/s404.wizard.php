<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">Hay Aksi !</h3>
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
                    <div class="col-xxl-12">
                        <!--begin::Table Widget 1-->
                        <div class="card card-xxl-stretch">
                            <!--begin::Header-->

                                <span class="alert alert-danger"><h3>Aradığın Sayfayı Bulamadık !</h3><br>Ulaşmaya çalıştığın sayfa hatalı yada yayından kaldırılmış. Eğer bunun bir hata olduğunu düşünüyorsan bizimle iletişime geçebilirsin !</span>


                        </div>
                        <!--end::Table Widget 1-->
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