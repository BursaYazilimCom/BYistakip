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
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('reklam')}}">Reklam Yönetimi</a>
                                <li class="breadcrumb-item active">{{$detay->adi==""?"Reklam Hesap Ekle":$detay->mail_adresi.' Düzenle'}}
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
                                <a class="nav-link active" href="{{URL::site('reklam/hesapForm/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Hesap Detayları</span>
                                </a>
                            </li>

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('reklam/hesaplar')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Hesaplar</span>
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
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="platform">Platform</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" required name="platform" id="platform">

                                                            <option value="0">--Seçiniz--</option>
                                                            @foreach($platformlar as $platform)
                                                            <option {{$platform->id==$detay->platform?'selected':''}} value="{{$platform->id}}">{{$platform->adi}}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="ads_id">Hesap ID</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('ads_id')->placeholder('Hesap ID')->text('ads_id',$detay->ads_id,['class'=>'form-control'])

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="mail_adresi">Mail Adresi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('mail_adresi')->placeholder('Mail Adresi')->text('mail_adresi',$detay->mail_adresi,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="sifre">Şifre</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::vRequired()->id('sifre')->placeholder('Şifre')->text('sifre',$detay->sifre,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="mail_adresi2">Kurtarma Maili</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('mail_adresi2')->placeholder('Kurtarma Maili')->text('mail_adresi2',$detay->mail_adresi2,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="dogrulama_tel">2A Tel</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('dogrulama_tel')->placeholder('2A Tel')->text('dogrulama_tel',$detay->dogrulama_tel,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="aciklama">Açıklama</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('aciklama')->placeholder('Açıklama')->textarea('aciklama',$detay->aciklama,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label " id="detay" for="detay">Ek Not</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('summernote')->placeholder('Ek Not')->textarea('ek_not',$detay->ek_not,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-6 col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="platform">Hesap Sahibi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" required name="cari" id="cari">

                                                            <option value="0">--Seçiniz--</option>
                                                            @foreach($cariler as $cari)
                                                            <option {{$cari->id==$detay->cari?'selected':''}} value="{{$cari->id}}">{{$cari->adi}}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="proxy">Proxy</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('proxy')->placeholder('Proxy')->text('proxy',$detay->proxy,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="rvd">RV Doğrulaması</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('rvd')->placeholder('RV Doğrulaması')->text('rvd',$detay->rvd,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="reklam_url">Reklam URL</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('reklam_url')->placeholder('Reklam URL')->text('reklam_url',$detay->reklam_url,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="odeme_yontemi">Ödeme Yöntemi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('odeme_yontemi')->placeholder('Ödeme Yöntemi')->text('odeme_yontemi',$detay->odeme_yontemi,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="odeme_araci">Ödeme Aracı</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" required name="odeme_araci" id="odeme_araci">

                                                            <option value="0">--Seçiniz--</option>
                                                            @foreach($odemeAraclari as $oa)
                                                            <option {{$oa->id==$detay->odeme_araci?'selected':''}} value="{{$oa->id}}">{{$oa->numara}}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="acilis_tarihi">Açılış Tarihi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::id('acilis_tarihi')->placeholder('Açılış Tarihi')->text('acilis_tarihi',$detay->acilis_tarihi,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="durum">Durum</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" required name="durum" id="durum">

                                                            <option value="0">--Seçiniz--</option>
                                                            @foreach($hesap_durumlari as $hdurum)
                                                            <option {{$hdurum->id==$detay->durum?'selected':''}} value="{{$hdurum->id}}">{{$hdurum->adi}}</option>
                                                            @endforeach

                                                        </select>

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