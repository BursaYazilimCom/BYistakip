<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Haberler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('haberler')}}">Haberler</a>
                                <li class="breadcrumb-item active">{{$detay->baslik==""?"Haber Ekle":$detay->baslik}}
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
                                <a class="nav-link active" href="{{URL::site('haberler/form/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Haber Detayları</span>
                                </a>
                            </li>

                            @if($detay->id!="")

                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('haberler/resimleri/')}}{{$detay->id}}">
                                    <i data-feather="image" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">{{$detay->baslik}} Resimleri</span>
                                </a>
                            </li>

                            @endif

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('haberler')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Haberler</span>
                                </a>
                            </li>

                        </ul>
                        @Form::csrf()->action($action)->enctype('multipart')->open('submitForm',['class'=>'form form-horizontal'])

                        @Form::hidden('id',$detay->id,['class'=>'form-control'])
                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Haber Detayları</h4>
                                    </div>
                                    <div class="card-body">


                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="uye_id">Tanımlı Firma</label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <select class="select2 form-select" name="uye_id" id="uye_id">

                                                        <option value="0">--Tanımsız--</option>
                                                        @foreach($firmalar as $firma)
                                                        <option {{$firma->id==$detay->firma_id?'selected':''}} value="{{$firma->id}}">{{$firma->firma_adi}}</option>
                                                        @endforeach

                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="kategori">Kategori</label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <select class="select2 form-select" name="kategori" id="kategori">

                                                        <option>--Seçiniz--</option>
                                                        @foreach($kategoriler["liste"] as $kat)
                                                        <option {{$kat->id==$detay->kategori?'selected':''}} value="{{$kat->id}}">{{$kat->baslik}}</option>
                                                        @endforeach

                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="firma_adi">Haber Başlık</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vRequired()->id('baslik')->placeholder('Haber Başlığı')->text('baslik',$detay->baslik,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>





                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="aciklama">Haber Açıklama</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('aciklama')->placeholder('Haber Açıklama')->textarea('aciklama',$detay->aciklama,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="aciklama">Haber Açıklama</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('aciklama')->placeholder('Haber Açıklama')->textarea('aciklama',$detay->aciklama,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-12 mb-1">

                                            {[
                                            $konum = explode("/",$detay->konum);
                                            ]}

                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 mb-1 mb-sm-0">
                                                    <input id="lat" name="lat" value="{{$konum[0]}}" class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <input id="long" name="long" value="{{$konum[1]}}" class="form-control" readonly>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card-body">
                                            <div class="row custom-options-checkable g-1">
                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" value="1" name="durum" id="durum1" {{$detay->durum=='1'?'checked':''}} />
                                                    <label class="custom-option-item p-1" for="durum1">
                                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                                <span class="fw-bolder">Onaylandı</span>

                                            </span>
                                                        <small class="d-block">Firma Sitede Listelenir</small>
                                                    </label>
                                                </div>

                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" name="durum" id="durum2" value="0" {{$detay->durum=='0'?'checked':''}} />
                                                    <label class="custom-option-item p-1" for="durum2">
                                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                                <span class="fw-bolder">Onay Bekliyor</span>

                                            </span>
                                                        <small class="d-block">Firma Sitede listelenmez.</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div class="row custom-options-checkable g-1">
                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" value="1" name="vitrin" id="vitrin1" {{$detay->vitrin=='1'?'checked':''}} />
                                                    <label class="custom-option-item p-1" for="vitrin1">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50">
                                                        <span class="fw-bolder">Vitrinde Göster</span>
                                                    </span>
                                                        <small class="d-block">Firma Anasayfa Vitrinde Görünür!</small>
                                                    </label>
                                                </div>

                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" name="vitrin" id="vitrin2" value="0" {{$detay->vitrin=='0'?'checked':''}} />
                                                    <label class="custom-option-item p-1" for="vitrin2">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50">
                                                        <span class="fw-bolder">Vitrinde GösterME</span>
                                                    </span>
                                                        <small class="d-block">Firma Anasayfa Vitrinde Gösterilmez!</small>
                                                    </label>
                                                </div>

                                            </div>

                                        </div>






                                    </div>
                                </div>
                            </div>




                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="aciklama">Firma Resimleri</label>
                                                    </div>
                                                    <div class="col-sm-12">

                                                        <div class="row">

                                                            <div class="col-md-2 mb-1">

                                                                <div class="mb-1">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-form-label" for="facebook">Firma Logo</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input class="form-control" name="logo" type="file" id="logo" />

                                                                    </div>
                                                                    <div class="col-sm-8">


                                                                        <div class="card ecommerce-card">
                                                                            <div class="item-img text-center">
                                                                                <img class="img-fluid card-img-top" src="{{URL::site()}}../Uploads/firma-logo/{{$detay->logo}}" style="height: 150px; width: 150px" />
                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-10 mb-1">
                                                                <div class="mb-1">
                                                                    <div class="col-sm-3">
                                                                        <label class="col-form-label" for="instagram">Diğer Resimler</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input class="form-control" type="file" name="resimler[]" id="formFileMultiple" multiple />
                                                                    </div>

                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            @foreach($firmaResimleri as $fr)
                                                                            <div class="col-sm-2">

                                                                                <div class="card ecommerce-card">
                                                                                    <div class="item-img text-center">
                                                                                        <img class="img-fluid card-img-top" src="{{URL::site()}}../Uploads/firma-resimleri/{{$fr->resim}}" style="height: 150px; width: 150px" />
                                                                                    </div>

                                                                                    <div class="item-options text-center">
                                                                                        <a href="{{URL::site()}}firmalar/resimSil/{{$detay->id}}/{{$fr->id}}" class="btn btn-warning btn-cart">
                                                                                            <i data-feather="x"></i>
                                                                                            <span class="add-to-cart">Sil</span>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="aciklama">Firma TANITIM</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('tanitim')->placeholder('Firma Hakkında Kısa Açıklama')->textarea('tanitim',Security::htmlEncode($detay->tanitim),['class'=>'form-control editor'])

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">


                                            <div class="col-sm-12">
                                                <button type="submit" style="width: 100%" class="btn btn-primary me-1">Kaydet</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @Form::close()

                    </div>

                </div>
            </section>
            <!-- Basic Horizontal form layout section end -->

        </div>
    </div>
</div>
<!-- END: Content-->