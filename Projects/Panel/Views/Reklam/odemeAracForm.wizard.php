<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Reklam Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a></li>
                                <li class="breadcrumb-item"><a href="{{URL::site('reklam')}}">Reklam Yönetimi</a>
                                <li class="breadcrumb-item active">{{$detay->cari==""?"Ödeme Aracı Ekle":'Ödeme Aracı Düzenle'}}</li>
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
                                <a class="nav-link active" href="{{URL::site('reklam/anlasmaForm/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Ödeme Aracı Detayları</span>
                                </a>
                            </li>

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('reklam/odemeAraclari')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Ödeme Araçları</span>
                                </a>
                            </li>

                        </ul>
                        @Form::csrf()->action($action)->open('submitForm',['class'=>'form form-horizontal','enctype'=>'multipart/form-data'])

                        @Form::hidden('id',$detay->id,['class'=>'form-control'])

                            {{ Redirect::select('bilgi',true) }}
                            <div class="card brdt-warning">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-6 col-12" style="border-right: 1px solid #dee2e6">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="platform">Tür</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" required name="tur" id="tur">

                                                            <option value="KK">Kredi Kartı</option>
                                                            <option value="HN">Hesap Numarası</option>


                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="numara">Numara</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('numara')->placeholder('Kart Numarası yada IBAN')->text('numara',$detay->numara,['class'=>'form-control'])

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="ucret">CVV</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('cvv')->placeholder('Kart CVV')->text('cvv',$detay->cvv,['class'=>'form-control'])

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="son_kullanim">Son Kullanım Tarihi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('son_kullanim')->text('son_kullanim',$detay->son_kullanim,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="sahibi">Sahibi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('sahibi')->text('sahibi',$detay->sahibi,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-6">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="durum">Durum</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" required name="durum" id="durum">

                                                            <option value="1" {{$detay->durum=="1"?"selected":""}}>Aktif</option>
                                                            <option value="0" {{$detay->durum=="0"?"selected":""}}>Pasif</option>

                                                        </select>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-md-6 col-12">

                                        <div class="row">

                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" id="aciklama" for="aciklama">Not</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('summernote')->placeholder('Not')->textarea('aciklama',$detay->aciklama,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                </div>
                                <div class="card-footer">
                                    <div class="col-sm-12">
                                        <button type="submit" style="width: 100%" class="btn btn-primary me-1">Kaydet</button>
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