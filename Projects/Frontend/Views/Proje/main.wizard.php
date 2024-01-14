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
                                <a href="{{URL::site('proje/calismalar/'.$detay->sef)}}" class="btn btn-sm btn-info me-2">Yapılan Çalışmalar</a>
                                <a href="#" class="btn btn-sm btn-success me-2" onclick="window.print()">Yazdır</a>

                            </div>
                            <!--end::Actions-->
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
                                    <!--begin::Item-->
                                    @foreach($yolHaritasi['liste'] as $yol)
                                    <div class="d-flex flex-stack item-border-hover px-3 py-2 ms-n4 mb-3">
                                        <!--begin::Section-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-40px symbol-circle me-4">
                                                @if($yol->durum=="1")
                                                            <span class="symbol-label bg-light-success">
																	<!--begin::Svg Icon | path: icons/duotune/files/fil023.svg-->
																	<span class="svg-icon svg-icon-1 svg-icon-success">
																		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																			<path opacity="0.3" d="M5 15C3.3 15 2 13.7 2 12C2 10.3 3.3 9 5 9H5.10001C5.00001 8.7 5 8.3 5 8C5 5.2 7.2 3 10 3C11.9 3 13.5 4 14.3 5.5C14.8 5.2 15.4 5 16 5C17.7 5 19 6.3 19 8C19 8.4 18.9 8.7 18.8 9C18.9 9 18.9 9 19 9C20.7 9 22 10.3 22 12C22 13.7 20.7 15 19 15H5ZM5 12.6H13L9.7 9.29999C9.3 8.89999 8.7 8.89999 8.3 9.29999L5 12.6Z" fill="black" />
																			<path d="M17 17.4V12C17 11.4 16.6 11 16 11C15.4 11 15 11.4 15 12V17.4H17Z" fill="black" />
																			<path opacity="0.3" d="M12 17.4H20L16.7 20.7C16.3 21.1 15.7 21.1 15.3 20.7L12 17.4Z" fill="black" />
																			<path d="M8 12.6V18C8 18.6 8.4 19 9 19C9.6 19 10 18.6 10 18V12.6H8Z" fill="black" />
																		</svg>
																	</span>
                                                    <!--end::Svg Icon-->
																</span>
                                                @else
                                                <span class="symbol-label bg-light-danger">
																	<!--begin::Svg Icon | path: icons/duotune/technology/teh008.svg-->
																	<span class="svg-icon svg-icon-1 svg-icon-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black" />
                                                        <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black" />
                                                    </svg>
                                                                        </span>
                                                    <!--end::Svg Icon-->
																</span>
                                                @endif
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Title-->
                                            <div class="ps-1 mb-1">
                                                <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-boldest">{{$yol->baslik}}</a>
                                                <div class="text-gray-400 fw-bold">{{$yol->aciklama}}</div>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Section-->
                                        <!--begin::Label-->
                                        @if($yol->durum=="1")
                                        <span class="badge badge-light-success rounded-pill fs-7 fw-boldest">Gerçekleştirildi</span>
                                        @else
                                        <span class="badge badge-light-warning rounded-pill fs-7 fw-boldest">Devam Ediyor</span>
                                        @endif
                                        <!--end::Label-->
                                    </div>
                                    @endforeach

                                    <div class="rounded border border-primary bg-light-primary border-dashed px-6 py-5">
                                        <a href="#" class="link-primary fw-bolder fs-6 me-1">Önemli Not</a><br>
                                        <span class="text-gray-800 fw-bold fs-6">Proje Yol haritası; İlgili projenin gidişatını belirlemek ve Müşterilerimiz ile etkileşimli çalışmak için oluşturulmuştur. <br>Mevcut Yol Haritası projenin ihtiyaçlarına göre güncellenebilir. Lütfen Takip etmeyi ihmal etmeyin.<br>Yol haritası öğeleri planlanan gerçekleştirme sırasına göre listelenmiştir.<br> Bu sıralama yazılım gerekliliklerine göre değişiklik gösterebilir</span>

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