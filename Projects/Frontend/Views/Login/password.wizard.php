<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{AyarModel::defaultAyarlar('siteAdi')}}</h3>
        <!--begin::Title-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">

    {{ Redirect::select('bilgi',true) }}
        <!--begin::Index-->
        <div class="card card-page">
            <!--begin::Card body-->
            <div class="card-body">
                {{ Redirect::select('bilgi',true) }}
                <!--begin::Row-->
                <div class="row gy-5 g-xl-8">
                    <!--begin::Col-->
   
                    <div class="col-xxl-6">


                    @if(AyarModel::defaultAyarlar('uyeGirisi')=='1')


                        <div class="card shadow-sm">
                            <div class="card-body">
                                @Form::csrf()->action('Login/renevPassword')->open('submitForm',['id'=>'submitForm'])

                                        <div class="mb-5">
                                            <label class="form-label">E-Mail Adresiniz</label>
                                            @Form::vRequired()->vMessage('Geçerli bir E-posta adresi olmalıdır')->id('email')->placeholder('E-Posta Adresiniz')->email('email','',['class'=>'form-control'])
                                        </div>

                                        <div class="mb-5">
                                            <label class="form-label">
                                                @Captcha::length(6)->borderColor('0|0|255')->bgColor('255|255|255')->textSize(35)->textCoordinate(50,22)->textColor('13|59|180')->create(true)
                                            </label>
                                                @Form::vCaptcha()->id('username')->placeholder('Resimdeki Kodu Giriniz')->text('text','',['class'=>'form-control'])
                                        </div>
                                        <div class="mb-5">
                                            <button href="#" class="btn btn-primary" style="width: 100%">Giriş Yap</button>
                                        </div>
                                @Form::close()
                            </div>
                        </div>

                    @else

                        <div class="alert alert-primary m-10">
                                <span>{{AyarModel::defaultAyarlar('musteripaneliKarsilamaYazisi')}}</span>
                        </div>

                    @endif

                    </div>

                    <div class="col-xxl-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="alert alert-primary">

                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-dark">Dikkat!</h4>
                                        <p>- Yandaki alana kayıtlı mail adresinizi yazarak şifrenizi sıfırlayabilirsiniz<br>
                                        - Eğer girdiğiniz mail kayıtlı bir mail adresi ise mail adresinize yenilenmiş şifreniz gönderilir. <br>
                                        - Size gönderilen şifre ile giriş yaptıktan sonra şifrenizi unutmayacağınız bir şifre ile değiştirebilirsiniz</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row gy-5 g-xl-8">
                <div class="col-12">
                    
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Index-->
    </div>
    <!--end::Post-->
</div>