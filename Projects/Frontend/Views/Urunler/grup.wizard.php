<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$grupDetay->adi}} Ürünleri</h3>
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
                                    <span class="fw-bolder text-dark fs-2">{{$grupDetay->adi}} Ürünleri</span>
                                </h3>
                                <!--<div class="card-toolbar"><a class="badge badge-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Tüm Faturalarımı Göster" href="{{URL::site('faturalarim')}}"><strong>Tüm Faturalarınız</strong></a>
                                </div>-->
                            </div>  
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-0">

                                <div id="kt_ecommerce_report_views_table_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer"><div id="" class="table-responsive"><table class="table align-middle table-row-dashed fs-6 gy-5 dataTable" id="kt_ecommerce_report_views_table" style="width: 1160px;"><colgroup><col data-dt-column="0" style="width: 288.266px;"><col data-dt-column="1" style="width: 184.938px;"><col data-dt-column="2" style="width: 184.938px;"><col data-dt-column="3" style="width: 184.938px;"><col data-dt-column="4" style="width: 131.969px;"><col data-dt-column="5" style="width: 184.953px;"></colgroup>
                                            <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0" role="row"><th class="min-w-150px dt-orderable-asc dt-orderable-desc" data-dt-column="0" rowspan="1" colspan="1" aria-label="Product: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Product</span><span class="dt-column-order"></span></th><th class="text-end min-w-100px dt-type-numeric dt-orderable-asc dt-orderable-desc" data-dt-column="1" rowspan="1" colspan="1" aria-label="SKU: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">SKU</span><span class="dt-column-order"></span></th><th class="text-end min-w-100px dt-orderable-asc dt-orderable-desc" data-dt-column="2" rowspan="1" colspan="1" aria-label="Rating: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Rating</span><span class="dt-column-order"></span></th><th class="text-end min-w-100px dt-type-numeric dt-orderable-asc dt-orderable-desc" data-dt-column="3" rowspan="1" colspan="1" aria-label="Price: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Price</span><span class="dt-column-order"></span></th><th class="text-end min-w-70px dt-type-numeric dt-orderable-asc dt-orderable-desc" data-dt-column="4" rowspan="1" colspan="1" aria-label="Viewed: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Viewed</span><span class="dt-column-order"></span></th><th class="text-end min-w-100px dt-type-numeric dt-orderable-asc dt-orderable-desc" data-dt-column="5" rowspan="1" colspan="1" aria-label="Percent: Activate to sort" tabindex="0"><span class="dt-column-title" role="button">Percent</span><span class="dt-column-order"></span></th></tr>
                                            </thead>
                                            <tbody class="fw-semibold text-gray-600"><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/23.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 23</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">02124003</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-2" data-filter="rating-2">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$253.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>268800</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    26.88%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/8.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 8</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">03757009</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-5" data-filter="rating-5">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$131.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>214340</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    21.434%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/20.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 20</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">04799001</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-4" data-filter="rating-4">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$251.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>178972</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    17.8972%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/33.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 33</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">03352006</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-1" data-filter="rating-1">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$63.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>138678</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    13.86776%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/49.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 49</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">02368007</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-5" data-filter="rating-5">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$286.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>39842</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    3.98421%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/36.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 36</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">04327008</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-4" data-filter="rating-4">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$264.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>31874</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    3.18737%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/13.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 13</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">01125001</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-5" data-filter="rating-5">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$123.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>25499</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    2.54989%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/7.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 7</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">01540004</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-5" data-filter="rating-5">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$197.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>20399</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    2.03991%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/16.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 16</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">01337001</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-2" data-filter="rating-2">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$86.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>16319</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    1.63193%
                                                </td>
                                            </tr><tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(/ceres-html-pro/assets/media//stock/ecommerce/38.png);"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->

                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a href="/ceres-html-pro/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-kt-ecommerce-product-filter="product_name">Product 38</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span class="fw-bold">03220001</span>
                                                </td>
                                                <td class="text-end pe-0" data-order="rating-2" data-filter="rating-2">
                                                    <div class="rating justify-content-end">
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label checked">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                        <div class="rating-label ">
                                                            <i class="ki-duotone ki-star fs-6"></i>                            </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>$149.00</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    <span>13056</span>
                                                </td>
                                                <td class="text-end pe-0 dt-type-numeric">
                                                    1.30555%
                                                </td>
                                            </tr></tbody>
                                            <tfoot></tfoot></table></div><div id="" class="row"><div id="" class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar"><div><select name="kt_ecommerce_report_views_table_length" aria-controls="kt_ecommerce_report_views_table" class="form-select form-select-solid form-select-sm" id="dt-length-0"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select><label for="dt-length-0"></label></div></div><div id="" class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"><div class="dt-paging paging_simple_numbers"><ul class="pagination"><li class="dt-paging-button page-item disabled"><a class="page-link previous" aria-controls="kt_ecommerce_report_views_table" aria-disabled="true" aria-label="Previous" data-dt-idx="previous" tabindex="-1"><i class="previous"></i></a></li><li class="dt-paging-button page-item active"><a href="#" class="page-link" aria-controls="kt_ecommerce_report_views_table" aria-current="page" data-dt-idx="0" tabindex="0">1</a></li><li class="dt-paging-button page-item"><a href="#" class="page-link" aria-controls="kt_ecommerce_report_views_table" data-dt-idx="1" tabindex="0">2</a></li><li class="dt-paging-button page-item"><a href="#" class="page-link" aria-controls="kt_ecommerce_report_views_table" data-dt-idx="2" tabindex="0">3</a></li><li class="dt-paging-button page-item"><a href="#" class="page-link" aria-controls="kt_ecommerce_report_views_table" data-dt-idx="3" tabindex="0">4</a></li><li class="dt-paging-button page-item"><a href="#" class="page-link" aria-controls="kt_ecommerce_report_views_table" data-dt-idx="4" tabindex="0">5</a></li><li class="dt-paging-button page-item"><a href="#" class="page-link next" aria-controls="kt_ecommerce_report_views_table" aria-label="Next" data-dt-idx="next" tabindex="0"><i class="next"></i></a></li></ul></div></div></div></div>



                            </div>
                            <div class="card-footer">



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