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
                    <div class="col-xl-12">
                        <!--begin::Head-->
                        <div class="d-flex flex-stack mb-10 mb-lg-15">
                            <!--begin::Logo-->
                            <a href="#">
                                <img alt="Logo" class="h-40px" src="{{URL::site()}}Uploads/firma-logo/{{AyarModel::defaultAyarlar('firmaLogo')}}" />
                            </a>

                        </div>


                        <!--begin::Header-->
                        <div class="card-header align-items-center border-0 mt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="fw-bolder text-dark fs-2">{{$detay->proje_adi}}</span>
                                <span class="text-gray-400 mt-2 fw-bold fs-6">Proje Yol Haritası</span>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="rounded border p-10">

                                <form name="giris" action="{{URL::site('proje/login/'.$detay->sef)}}" class="form" method="post">
                                    <div class="mb-10">
                                        <label class="form-label">Proje Şifresini Giriniz</label>
                                        <input type="password" name="sifre" class="form-control">
                                    </div>

                                    <div class="mb-10">
                                        <button type="submit" class="btn btn-primary">Giriş Yap</button>
                                    </div>

                                </form>

                            </div>

                        </div>


                    </div>

                </div>
                <!--end::Layout-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Invoice-->
    </div>
    <!--end::Post-->
</div>