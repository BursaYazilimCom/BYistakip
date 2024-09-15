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
                                <img alt="Logo" class="h-40px" src="{{URL::site()}}Uploads/firma-logo/{{AyarModel::defaultAyarlar('firmaLogo')}}" />
                            </a>
                            <!--end::Logo-->
                            <!--begin::Actions-->
                            <div class="my-1">
                                @if($detay->durum=="2")
                                <a href="{{URL::site()}}../Uploads/faturalar/{{ $detay->resmi_fatura_dosyasi }}" class="btn btn-sm btn-warning me-2">Resmi Fatura İndir</a>
                                @endif
                                <a href="#" class="btn btn-sm btn-success me-2" onclick="window.print()">Yazdır</a>
                                @if($detay->odeme=="0")
                                <a href="{{URL::site()}}faturalar/odemeYap/{{$detay->id}}" class="btn btn-sm btn-primary">Kredi Kartı İle Öde</a>
                                @endif
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Head-->
                        <!--begin::Wrapper-->
                        <div class="mb-0">
                            {{$uyari}}
                            <!--begin::Label-->
                            <div class="fw-bolder fs-3 text-gray-800 mb-8">Fatura No #{{$detay->belge_no}}</div>
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
                            <div class="row g-5 mb-12">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Fatura Kesen:</div>
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
                                <div class="col-sm-6">
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


                                        <tr class="fw-bolder text-gray-700 fs-5 text-end">
                                            <td class="d-flex align-items-center pt-6">
                                                {{$furun->urun_adi}}</td>
                                            <td class="pt-6">{{$furun->miktar}}</td>
                                            <td class="pt-6">{{number_format($furun->fiyat,2)}}</td>
                                            <td class="pt-6">%{{$furun->kdv}}</td>
                                            <td class="pt-6 text-dark fw-boldest">{{number_format($furun->fiyat*$furun->miktar,2)}} ₺</td>
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
                    <!--begin::Sidebar-->
                    <div class="m-0">
                        <!--begin::Invoice sidebar-->
                        <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                            <!--begin::Labels-->
                            <div class="mb-8">
                                @if($detay->durum=="2")
                                <span class="badge badge-light-success me-2">Resmi Fatura</span>

                                @elseif($detay->durum=="1")
                                <span class="badge rounded-pill badge-light-warning">Resmileşmemiş</span>
                                @else
                                <span class="badge rounded-pill badge-light-danger">İptal Edilmiş</span>
                                @endif

                                @if($detay->odeme=="0")
                                    <span class="badge badge-light-warning">Ödeme Bekleniyor</span>
                                @elseif($detay->odeme=="1")
                                    <span class="badge rounded-pill badge-light-success">Ödeme Yapıldı</span>
                                @endif

                            </div>
                            <!--end::Labels-->

                            <!--begin::Item-->
                                <div class="mb-6">
                                    <div class="fw-bold text-gray-600 fs-7">Banka Hesaplarımız:</div>
                                    <div class="fw-bolder text-gray-800 fs-6">{{AyarModel::defaultAyarlar('bankaHesaplari')}}</div>
                                </div>

                            <div class="mb-12">

                                <div class="alert alert-primary">
                                    <h4 class="text-dark">Ödeme Uyarısı</h4>
                                    <!--end::Title-->
                                    <!--begin::Content-->
                                    <span>Fatura Ödemesini zamanında yapmanız<br> ürünlerinizin devamlılığı için önemlidir.<br> Zamanında yapılmayan ödemelerde<br> Ürünlerinizde yaşayacağınız kayıplardan<br> firmamız sorunlu değildir.</span>
                                    <!--end::Wrapper-->
                                </div>
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