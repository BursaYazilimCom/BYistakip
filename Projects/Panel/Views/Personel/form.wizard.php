<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Kullanıcılar</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('personel')}}">Kullanıcılar</a>
                                <li class="breadcrumb-item"><a href="#">Kullanıcı Düzenle</a>
                                </li>
                                <li class="breadcrumb-item active">{{$detay->isim}}
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
                                <a class="nav-link active" href="{{URL::site('personel/form/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Bilgiler</span>
                                </a>
                            </li>

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('personel/izinler/')}}{{$detay->id}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Yetki Alanları</span>
                                </a>
                            </li>

                        </ul>
                        <div class="card brdt-primary">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="card-header">
                                <h4 class="card-title">Kullanıcı Bilgileri</h4>
                            </div>
                            <div class="card-body">
                                

                                    @Form::csrf()->action($action)->open('submitForm',['class'=>'form form-horizontal','enctype'=>'multipart/form-data'])

                                    @Form::hidden('id',$detay->id,['class'=>'form-control'])

                                    <div class="row">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="username">Kullanıcı adı</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    @Form::vRequired()->id('username')->placeholder('Kullanıcı adınız')->text('username',$detay->username,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="pass">Şifre</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    @Form::id('pass')->placeholder('Şifre')->text('pass','',['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="resim">Resim</label>
                                                </div>
                                                <div class="col-sm-9">
                                        
                                                    @Form::id('resim')->placeholder('Kişi Resmi')->file('resim','',['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="isim">İsim</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    @Form::vRequired()->id('isim')->placeholder('Kişi İsmi')->text('isim',$detay->isim,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="email">E-Posta</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    @Form::vEmail()->id('email')->placeholder('Email')->text('email',$detay->email,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="telefon">Telefon</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    @Form::vNumber()->id('telefon')->placeholder('05xxxxxxx')->number('telefon',$detay->telefon,['class'=>'form-control'])
                                                </div>+
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="telefon">Ünvan</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    @Form::vNumber()->id('unvan')->placeholder('Genel Müdür, Muhaebe Sorumlusu vs')->text('unvan',$detay->unvan,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="telefon">Notlar</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <textarea name="notlar" class="form-control" id="telefon" placeholder="Not kaydedin">{{$detay->notlar}}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="telefon">Banlı mı?</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="ban" value="1" {{$detay->ban=='1'?'checked':''}} id="ban" />
                                                        <label class="form-check-label" for="ban">Evet</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="aktivasyon">E-Posta Aktivasyon ?</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="aktivasyon" class="form-check-input" value="1" id="aktivasyon" {{$detay->aktivasyon=='1'?'checked':''}} />
                                                        <label class="form-check-label" for="aktivasyon">İstenmesin</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-9 offset-sm-3">
                                            <button type="submit" class="btn btn-primary me-1">Kaydet</button>
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