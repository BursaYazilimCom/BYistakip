<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">Teklif No #{{$detay->belge_no}}</h3>
        <!--begin::Title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center flex-wrap py-2">

            <a href="#" class="btn btn-sm btn-success me-2" onclick="window.print()">Yazdır</a>
            <!--end::Button-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Invoice-->
        <div class="card">
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Layout-->
                <div class="d-flex flex-column flex-xl-row">
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">

                        <!--begin::Wrapper-->
                        <div class="mb-0">
                        
                            <!--begin::Label-->
                            <div class="fw-bolder fs-3 text-gray-800 mb-8">Teklif No #{{$detay->belge_no}}</div>
                            

                            <div class="row g-5 mb-12">
                                <!--end::Col-->
                                <div class="col-sm-4">
                                    <!--end::Label-->
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Teklif Gönderen:</div>
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bolder fs-6 text-gray-800">{{AyarModel::defaultAyarlar('firmaAdi')}}</div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-bold fs-7 text-gray-600">{{AyarModel::defaultAyarlar('firmaAdresi')}}
                                        <br />{{AyarModel::defaultAyarlar('vergiDairesi')}}/{{AyarModel::defaultAyarlar('vergiNo')}}</div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                                <!--end::Col-->
                                <div class="col-sm-4">
                                    <!--end::Label-->
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Fatura Sahibi:</div>
                                    <!--end::Label-->
                                    <!--end::Text-->
                                    <div class="fw-bolder fs-6 text-gray-800">{{$detay->cariDetay->firma_adi}}</div>
                                    <!--end::Text-->
                                    <!--end::Description-->
                                    <div class="fw-bold fs-7 text-gray-600">{{$detay->cariDetay->fatura_adresi}}
                                        <br />{{$detay->cariDetay->vergi_dairesi}}/{{$detay->cariDetay->vergi_no}}</div>
                                    <!--end::Description-->
                                </div>
                                <div class="col-sm-2">
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Teklif Tarihi:</div>
                                    <!--end::Label-->
                                    <!--end::Col-->
                                    <div class="fw-bolder fs-6 text-gray-800">{{Date::convert($detay->ekleme_tarihi,"d.m.Y")}}</div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Son Geçerlilik Tarihi:</div>
                                    <!--end::Label-->
                                    <!--end::Col-->
                                    <div class="fw-bolder fs-6 text-gray-800">{{Date::convert( Date::addDay($detay->ekleme_tarihi, $detay->gecerlilik_suresi_gun),"d.m.Y")}}</div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Content-->
                            <div class="flex-grow-1">
                                <!--begin::Table-->
                                <div class="table-responsive border-bottom mb-9">
                                    <table class="table mb-3">
                                        <thead>
                                        <tr class="border-bottom fs-6 fw-bolder text-gray-400">
                                            <th class="min-w-175px pb-2">Ürün</th>
                                            <th class="min-w-70px text-end pb-2">Adet</th>
                                            <th class="min-w-80px text-end pb-2">Birim Fiyat</th>
                                            <th class="min-w-80px text-end pb-2">Kdv</th>
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


                                        <tr class="text-gray-700 fs-5">
                                            <td class="d-flex align-items-center pt-6">
                                                
                                                <table class="text-left">
                                                    <tr>
                                                        <td class="fw-bolder ">{{$furun->urun_adi}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left: 20px">
                                                            <small>{{str_replace('\n','<br>',$furun->aciklama)}}</small>
                                                        </td>
                                                    </tr>
                                                </table></td>
                                            <td class="pt-6 text-end fw-bolder ">{{$furun->miktar}}</td>
                                            <td class="pt-6 text-end fw-bolder ">{{number_format($furun->fiyat,2)}}</td>
                                            <td class="pt-6 text-end fw-bolder ">%{{$furun->kdv}}</td>
                                            <td class="pt-6 text-dark text-end fw-boldest">{{number_format($furun->fiyat*$furun->miktar,2)}} ₺</td>
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
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Layout-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Invoice-->
    </div>
    <!--end::Post-->
</div>