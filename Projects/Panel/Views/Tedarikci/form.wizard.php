<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Tedarikçiler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('tedarikci')}}">Tedarikçiler</a>
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
                                <div class="card brdt-success">
                                    <div class="card-header">
                                        <h4 class="card-title">Firma Bilgileri</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="adi">Görünecek İsim</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vRequired()->id('adi')->placeholder('Sistemde Görüntülenecek Adı')->text('adi',$detay->adi,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="ilgili_kisi">Yetkili İsim</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('ilgili_kisi')->placeholder('Yetkili İsim')->text('ilgili_kisi',$detay->ilgili_kisi,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="telefon">Telefon</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('telefon')->placeholder('05xxxxxxx')->number('telefon',$detay->telefon,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="ek_bilgiler">Ek Bilgiler</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('ek_bilgiler')->placeholder('Ek Bilgiler')->textarea('ek_bilgiler',$detay->ek_bilgiler,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="card brdt-success">
                                    <div class="card-header">
                                        <h4 class="card-title">Fatura Detayları</h4>
                                    </div>

                                    <div class="card-body">
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="firma_adi">Firma Fatura İsmi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('firma_adi')->placeholder('Firma Adı')->text('firma_adi',$detay->firma_adi,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="adres">Adresi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('adres')->placeholder('Adresi')->textarea('adres',$detay->adres,['class'=>'form-control'])

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

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="banka_hesaplari">Banka Hesapları</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('banka_hesaplari')->placeholder('Banka Hesapları')->textarea('banka_hesaplari',$detay->banka_hesaplari,['class'=>'form-control'])

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