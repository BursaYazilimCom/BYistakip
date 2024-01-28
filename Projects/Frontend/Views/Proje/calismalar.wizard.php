<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$detay->proje_adi}}</h3>
        <!--begin::Title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center flex-wrap py-2">

            <a href="{{URL::site('proje/main/'.$detay->sef)}}" class="btn btn-info my-2 me-2 me-lg-6 d-print-none">Yol Haritası</a>

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
            <!--begin::Body-->
            <div class="card-header align-items-center border-0 mt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="fw-bolder text-dark fs-2">Projede  Yapılan Çalışmalar ve Devam Eden Çalışmalar</span>
                    <span class="text-gray-400 mt-2 fw-bold fs-6"></span>
                </h3>
            </div>
            <div class="card-body">
                <!--begin::Layout-->
                <div class="row">
                    <!--begin::Content-->
                    <div class="col-xl-12">

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
                                    <th colspan="2" style="border-bottom: solid 1px #7BB8F5; padding-left: 10px" class="bg-primary"><h3>{{Date::convert($yol->tarih,"d.m.Y")}}</h3></th>
                                </tr>


                                {[ } ]}

                                <tr>
                                    <td style="border-left: solid 1px #7BB8F5">
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