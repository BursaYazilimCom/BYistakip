<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Üyeler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('cari')}}">Cari Hesaplar</a>
                                <li class="breadcrumb-item active">{{$detay->adi}}
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
                                <a class="nav-link active" href="{{URL::site('cari/form/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Bilgiler</span>
                                </a>
                            </li>

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('cari')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Cariler</span>
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
                                        <h4 class="card-title">Cari Bilgileri</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="adi">Yetkili İsim</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vRequired()->id('adi')->placeholder('Yetkiki İsmi ve Soyismi')->text('adi',$detay->adi,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="email">E-Posta</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vEmail()->id('email')->placeholder('E-Posta')->text('email',$detay->email,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="gsm">Telefon</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vNumber()->id('gsm')->placeholder('05xxxxxxx')->number('gsm',$detay->gsm,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="gsm">TC Kimlik</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vNumber()->id('tc')->placeholder('00000000000')->number('tc',$detay->tc,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 mb-1">

                                            <div class="mb-1">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="il">Şehir</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="il" id="il" onchange="ilceList(this.value)">

                                                        <option value="{{AyarModel::defaultAyarlar('varsayilanSehir')}}">{{AyarModel::sehirAdi(AyarModel::defaultAyarlar('varsayilanSehir'))}}</option>
                                                        @foreach($sehirler['liste'] as $sehir)
                                                        <option {{$sehir->id==$detay->il?'selected':''}} value="{{$sehir->id}}">{{$sehir->il}}</option>
                                                        @endforeach

                                                    </select>


                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="bakiye">Mevcut Bakiye</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('bakiye')->placeholder('Mevcut Bakiye')->text('bakiye',$detay->bakiye,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="firma_adi">Yönetim Notu</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('yonetim_notu')->placeholder('Yönetim Notu')->textarea('yonetim_notu',$detay->yonetim_notu,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">İzinler</h4>
                                    </div>

                                        <div class="card-body">
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="firma_adi">Firma Adı</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('firma_adi')->placeholder('Firma Adı')->text('firma_adi',$detay->firma_adi,['class'=>'form-control'])

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="firma_adi">Fatura Adresi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('fatura_adresi')->placeholder('Fatura Adresi')->textarea('fatura_adresi',$detay->fatura_adresi,['class'=>'form-control'])

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-1">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label" for="vergi_dairesi">Vergi Dairesi</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            @Form::id('vergi_dairesi')->placeholder('Vergi Dairesi')->text('vergi_dairesi',$detay->vergi_dairesi,['class'=>'form-control'])
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label" for="gsm">Vergi Numarası</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            @Form::id('vergi_no')->placeholder('Vergi Numarası')->number('vergi_no',$detay->vergi_no,['class'=>'form-control'])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row custom-options-checkable g-1">
                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" value="0" name="durum" id="durum1" {{$detay->durum=='0'?'checked':''}} />
                                                    <label class="custom-option-item p-1" for="durum1">
                                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                                <span class="fw-bolder">Pasif</span>

                                            </span>
                                                        <small class="d-block">Cariye bildirim gönderilmez ve satış yapılmaz</small>
                                                    </label>
                                                </div>

                                                <div class="col-md-6">
                                                    <input class="custom-option-item-check" type="radio" name="durum" id="durum2" value="1" {{$detay->durum=='1'?'checked':''}} />
                                                    <label class="custom-option-item p-1" for="durum2">
                                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                                <span class="fw-bolder">Aktif Üye</span>

                                            </span>
                                                        <small class="d-block">Cariye ödeme ekstra bildirimler gönderilir ve satış yapılabilir</small>
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