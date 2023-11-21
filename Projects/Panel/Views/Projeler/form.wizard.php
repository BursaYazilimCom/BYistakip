<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Projeler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('projeler')}}">Projeler</a>
                                <li class="breadcrumb-item active">{{$detay->proje_adi==""?"":$detay->proje_adi}}
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
                                <a class="nav-link active" href="{{URL::site('projeler/form/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Proje Detayları</span>
                                </a>
                            </li>

                            @if($detay->id!="")

                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('projeler/yolHaritasi/'.$detay->id)}}">
                                    <i data-feather="activity" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Proje Yol Haritası</span>
                                </a>
                            </li>

                            @endif

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('projeler')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Projeler</span>
                                </a>
                            </li>


                        </ul>
                        @Form::csrf()->action($action)->open('submitForm',['class'=>'form form-horizontal'])

                        @Form::hidden('id',$detay->id,['class'=>'form-control'])
                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Proje Bilgileri</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="proje_adi">Proje Adı</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vRequired()->id('proje_adi')->placeholder('Proje Adı')->text('proje_adi',$detay->proje_adi,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="email">Tanımlı Cari</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="musteri" id="musteri">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($musteriler as $musteri)
                                                        <option {{$musteri->id==$detay->musteri?'selected':''}} value="{{$musteri->id}}">{{$musteri->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="aciklama">Açıklama</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('aciklama')->placeholder('Açıklama')->textarea('aciklama',$detay->aciklama,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="sifre">Proje Giriş Şifresi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('sifre')->placeholder('Proje Giriş Şifresi')->text('sifre','',['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Detaylar</h4>
                                    </div>

                                        <div class="card-body">

                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="proje_baslangic_tarihi">Proje Başlangıç Tarihi</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            @Form::vRequired()->id('proje_baslangic_tarihi')->placeholder('Proje Başlangıç Tarihi')->text('proje_baslangic_tarihi',Date::convert($detay->proje_baslangic_tarihi,'d.m.Y'),['class'=>'form-control  date-picker'])

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="tahmini_bitis_tarihi">Söz Verilen Teslim Tarihi</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            @Form::vRequired()->id('tahmini_bitis_tarihi')->placeholder('Söz Verilen Teslim Tarihi')->text('tahmini_bitis_tarihi',Date::convert($detay->tahmini_bitis_tarihi,'d.m.Y'),['class'=>'form-control  date-picker'])
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="bitis_tarihi">Proje Bitiş Tarihi</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input type="text" name="bitis_tarihi" class="form-control date-picker" value="{{Date::convert($detay->bitis_tarihi,'d.m.Y')}}">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row custom-options-checkable g-1">
                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" value="0" name="durum" id="durum1" {{$detay->durum=='0'?'checked':''}} />
                                                    <label class="custom-option-item p-1" for="durum1">
                                                        <span class="d-flex justify-content-between flex-wrap mb-50">
                                                            <span class="fw-bolder">Devam Ediyor</span>

                                                        </span>
                                                                </label>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <input class="custom-option-item-check" type="radio" name="durum" id="durum2" value="1" {{$detay->durum=='1'?'checked':''}} />
                                                                <label class="custom-option-item p-1" for="durum2">
                                                        <span class="d-flex justify-content-between flex-wrap mb-50">
                                                            <span class="fw-bolder">Tamamlandı</span>

                                                        </span>
                                                    </label>
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