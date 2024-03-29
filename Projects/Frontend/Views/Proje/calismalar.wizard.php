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

                            {[
                                $tarih = "";
                                $i = 1;
                                ]}

                            @foreach($calismalar['liste'] as $yol)

                                {[

                                if($yol->tarih != $tarih){

                                    echo "</div>";

                                $tarih = $yol->tarih;

                                $i = 1;

                                }

                                if($i==1){

                                ]}

                               
                                <div class="my-3 p-4 bg-body rounded shadow-sm">
                                <h6 class="border-bottom pb-2 mb-0">{{Date::convert($yol->tarih,"d.m.Y")}}</h6><br>

                                {[ } ]}

                                        <div class="d-flex text-body-secondary p-2 border-bottom" data-toogle="tooltips" 
                                                @if($yol->tur=="1")
                                                title="Değişiklik" 
                                                style="background-color: #E4E4FD; border-bottom: 1px solid #d4d4f3 !important;"
                                                
                                                @elseif($yol->tur=="2")
                                                title="Hata Çözümü" 
                                                style="background-color: #FFE2D0; border-bottom: 1px solid #FFA07A !important;"
                                               
                                                @elseif($yol->tur=="3")
                                                title="Yenilik" 
                                                style="background-color: #DDFFDD; border-bottom: 1px solid #c9f3c9  !important;"
                                            
                                                @endif
                                        style="background-color: antiquewhite;"
                                        >
                                           
                                            <p class="p-1 mb-0 lh-sm">
                                                @if($yol->tur=="1")
                                                <span class="badge badge-info"><i style="color:#fff" class="fa-solid fa-repeat"></i></span>
                                                @elseif($yol->tur=="2")
                                                <span class="badge badge-danger"><i style="color:#fff" class="fa-solid fa-circle-exclamation"></i></span>
                                                @elseif($yol->tur=="3")
                                                <span class="badge badge-success"><i style="color:#fff" class="fa-solid fa-plus"></i></span>
                                                @endif

                                                &nbsp;&nbsp; {{$yol->islem}}
                                                @if($yol->link!="")
                                               <a style="background-color: dimgrey; padding: 4px; border-radius: 10px; color: #fff;font-size: 11px;" target="_blank" href="{{$yol->link}}">İncele</a>
                                               @endif
                                            </p>
                                        </div>


                                  

                            {[ $i++; ]}
                            @endforeach

                           
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