<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$detay->proje_adi}}</h3>
        <!--begin::Title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center flex-wrap py-2">

            <a href="{{URL::site('proje/calismalar/'.$detay->sef)}}" class="btn btn-info my-2 me-2 me-lg-6 d-print-none">Çalışmalar</a>

            <a href="javascript:void(0);" class="btn btn-primary my-2 me-2 me-lg-6 d-print-none" onclick="window.print()">Yazdır</a>

            @if($detay->durum=="2")

            <span class="btn btn-success my-2 d-print-none" tooltip="Tamamlandı">Tamamlandı</span>
            @else
            <span class="btn btn-warning my-2 d-print-none" tooltip="Devam Ediyor">Devam Ediyor</span>
            @endif
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
            <div class="card-header align-items-center border-0 mt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="fw-bolder text-dark fs-2">Proje Yol Haritası</span>
                </h3>
            </div>
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Layout-->
                <div class="row">

                    <div class=" col-xl-12">
                        <!--begin::Invoice sidebar-->
                        <div class="border border-dashed border-gray-300 card-rounded h-lg-60 p-9 bg-lighten">


                                <div class="col-sm-12">
                                    <!--end::Label-->
                                    <div class="fw-bold fs-7 text-gray-600 mb-1">Proje Başlangıç Tarihi: <span class="text-danger">{{Date::convert($detay->proje_baslangic_tarihi,'d.m.Y')}}</span> | Proje Termin Tarihi:<span class="text-danger">{{Date::convert($detay->tahmini_bitis_tarihi,'d.m.Y')}}</span></div>
                                    <!--end::Label-->
                                    <!--end::Col-->
                                </div>


                                <div class="fw-bold text-gray-600 fs-7">Açıklama:</div>
                                <div class="fs-6">{{$detay->aciklama}}</div>


                        </div>
                        <!--end::Invoice sidebar-->
                    </div>
                    <!--begin::Content-->
                    <div class="col-xl-12">
                        <!--begin::Head-->
                                    <!--begin::Item-->
                                    @foreach($yolHaritasi['liste'] as $yol)
                                    <div class="d-flex flex-stack item-border-hover px-3 py-2 ms-n4 mb-3">
                                        <!--begin::Section-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-40px symbol-circle me-4">
                                                @if($yol->durum=="1")
                                                <span class="btn btn-success rounded-pill fw-boldest" title="Tamamlandı"><i class="fa-solid fa-check"></i></span>
                                                @else
                                                <span class="btn btn-warning rounded-pill fw-boldest" title="Devam Ediyor" ><i class="fa-solid fa-code"></i></span>
                                                @endif
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Title-->
                                            <div class="ps-1 mb-1">
                                                <span class="fs-5 text-hover-primary fw-boldest
                                                     @if($yol->durum=="1")

                                                     text-success
                                                     @else
                                                     text-warning
                                                     @endif
                                                    ">{{$yol->baslik}}
                                                </span>
                                                <div class="text-gray-400 fw-bold">{{$yol->aciklama}}</div>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Section-->
                                        <!--begin::Label-->

                                        <!--end::Label-->
                                    </div>
                                    @endforeach

                                    <div class="rounded border border-primary bg-light-primary border-dashed px-6 py-5">
                                        <a href="#" class="link-primary fw-bolder fs-6 me-1">Önemli Not</a><br>
                                        <span class="text-gray-800 fw-bold fs-6">Proje Yol haritası; İlgili projenin gidişatını belirlemek ve Müşterilerimiz ile etkileşimli çalışmak için oluşturulmuştur. <br>Mevcut Yol Haritası projenin ihtiyaçlarına göre güncellenebilir. Lütfen Takip etmeyi ihmal etmeyin.<br>Yol haritası öğeleri planlanan gerçekleştirme sırasına göre listelenmiştir.<br> Bu sıralama yazılım gerekliliklerine göre değişiklik gösterebilir</span>

                                    </div>
                                    <!--end::Alert-->


                    </div>
                    <!--end::Content-->
                    <!--begin::Sidebar-->
                </div>

                    <!--end::Sidebar-->
                </div>
                <!--end::Layout-->

        </div>
        <!--end::Invoice-->
    </div>
    <!--end::Post-->
</div>