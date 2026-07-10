<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Destek Talepleri</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('destek')}}">Destek Talepleri</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic Horizontal form layout section start -->
            <section id="basic-horizontal-layouts">

                <div class="row">
                    <div class="col-md-12 col-12">
                        <ul class="nav nav-pills mb-2">
                            <!-- account -->
                            <li class="nav-item">
                                <a class="nav-link active" href="{{URL::site('destek/form/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Destek Talep</span>
                                </a>
                            </li>

                        </ul>
                        
                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                           
                            <div class="col-md-6 col-12">
                                 

                                <div class="card brdt-warning">
                                    <div class="card-header">
                                        <h4 class="card-title">Destek Talep Detayları</h4>
                                    </div>
                                    <div class="card-body">
                                        

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="konu">Konu</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    {{$detay->konu}}
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label " id="mesaj" for="mesaj">Mesaj</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    {{Security::htmlDecode($detay->mesaj)}}
                                                </div>
                                            </div>
                                        </div>

                                        @Form::csrf()->action($action)->open('talepForm',['class'=>'form form-horizontal'])

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="tedarikci">Müşteri</label>
                                                    </div>
                                                    <div class="col-sm-12">

                                                        <select class="select2 form-select" name="musteri" id="musteri">
                                                            <option value="0">--Seçiniz--</option>
                                                            @foreach($musteriler as $musteri)
                                                            <option {{$musteri->id==$detay->musteri?'selected':''}} value="{{$musteri->id}}">{{$musteri->adi}}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="departman">Departman</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="departman" id="departman">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($departmanlar as $departman)
                                                        <option {{$departman->id==$detay->departman?'selected':''}} value="{{$departman->id}}">{{$departman->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="konu">Durum</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select name="durum" id="durum" class="form-select">
                                                        <option value="">--Seçiniz--</option>
                                                        <option {{$detay->durum==0?'selected':''}} value="0">Kapandı</option>
                                                        <option {{$detay->durum==1?'selected':''}} value="1">Yeni</option>
                                                        <option {{$detay->durum==2?'selected':''}} value="2">Yanıtlandı</option>
                                                        <option {{$detay->durum==3?'selected':''}} value="3">Müşteri Cevapladı</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                            
                                                <div class="col-sm-12">
                                                   <button type="submit" class="btn btn-primary me-1 mt-1" style="width: 100%">Kaydet</button>
                                                </div>
                                        @Form::close()
                                    </div>
                                </div>
                                
                            </div>

                            

                                <div class="col-md-6 col-12">
                                @Form::csrf()->action('destek/cevapla/'.$detay->id)->open('cevapForm',['class'=>'form form-horizontal','enctype'=>'multipart/form-data'])
                                    <div class="card">
                                    <h4 class="card-header">Mesajlar</h4>
                                    <div class="card-body pt-1">
                                        <ul class="timeline ms-50">

                                            <li class="timeline-item">
                                                <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                                <div class="timeline-event">
                                                    <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                        <h6>{{CariModel::cariAdi($detay->musteri)}}</h6>
                                                        <span class="timeline-event-time me-1">{{Date::convert($detay->tarih, 'd.m.Y H:i')}}</span>
                                                    </div>
                                                    <p>{{Security::htmlDecode($detay->mesaj)}}</p>
                                                </div>
                                            </li>


                                            @foreach($destekMesajlari as $mesaj)
                                            
                                                @if($mesaj->gonderen=='2')
                                                    <li class="timeline-item">
                                                        <span class="timeline-point timeline-point-indicator"></span>
                                                        <div class="timeline-event">
                                                            <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                                <h6>{{PersonelModel::isim($mesaj->gonderen_id)}}</h6>
                                                                <span class="timeline-event-time me-1">{{Date::convert($mesaj->tarih, 'd.m.Y H:i')}}</span>
                                                            </div>
                                                            <p>{{Security::htmlDecode($mesaj->mesaj)}}</p>
                                                            @if($mesaj->dosya_eki!="")
                                                                <div class="d-flex flex-row align-items-center mt-50">
                                                                    <img class="me-1" src="{{URL::site()}}../Uploads/SiteImg/attach.png" alt="Ekli Dosya" height="25" />
                                                                    <a href="{{URL::site()}}../Uploads/destek-talep/{{$mesaj->dosya_eki}}" download target="_blank">
                                                                    <h6 class="mb-0">{{$mesaj->dosya_eki}}</h6></a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="timeline-item">
                                                        <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                                        <div class="timeline-event">
                                                            <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                                <h6>{{CariModel::cariAdi($mesaj->gonderen_id)}}</h6>
                                                                <span class="timeline-event-time me-1">{{Date::convert($mesaj->tarih, 'd.m.Y H:i')}}</span>
                                                            </div>
                                                            <p>{{Security::htmlDecode($mesaj->mesaj)}}</p>
                                                            
                                                        </div>
                                                    </li>
                                                @endif

                                            @endforeach
                                            
                                            
                                            <li class="timeline-item">
                                           
                                                <span class="timeline-point timeline-point-success timeline-point-indicator"></span>
                                                    <div class="timeline-event">
                                                        @Form::id('summernote2')->placeholder('Cevap')->textarea('cevap','',['class'=>'form-control'])
                                                    </div>
                                                    <div class="timeline-event"><br>
                                                        <input type="file" name="dosya" id="dosya" class="form-control">

                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="submit" class="btn btn-success me-1 mt-1" style="width: 100%">Cevapla</button>
                                                    </div>

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @Form::close()
                            </div>
                        </div>

                        

                    </div>

                </div>
            </section>
            <!-- Basic Horizontal form layout section end -->

        </div>
    </div>
</div>
<!-- END: Content-->