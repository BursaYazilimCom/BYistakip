<!-- BEGIN: Content-->

    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="auth-wrapper auth-basic px-2">
                <div class="auth-inner my-2">
                    <!-- Register basic -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="index.html" class="brand-logo">
                                <img src="images/logo/by_logo.png">
                            </a>

                            <h4 class="card-title mb-1">E-Ticaret Sistemleri</h4>

                            @Form::csrf()->prevent()->action('by/ajax')->open('submitForm',['id'=>'submitForm','class'=>'auth-register-form mt-2'])
                                <input type="hidden" name="action" value="login">
                                <div class="mb-1">
                                    <label for="register-username" class="form-label">Kullanıcı Adı</label>
                                    @Form::vRequired()->id('username')->placeholder('Kullanıcı adınız')->text('username','',['class'=>'form-control'])

                                </div>

                                <div class="mb-1">
                                    <label for="register-password" class="form-label">Şifre</label>

                                    <div class="input-group input-group-merge form-password-toggle">
                                        @Form::vRequired()->id('password')->placeholder('**********')->password('password','',['class'=>'form-control  form-control-merge'])
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>

                                <button class="btn btn-primary w-100" type="submit" tabindex="5">Giriş yap</button>
                            @Form::close()


                        </div>
                    </div>
                    <!-- /Register basic -->
                </div>
            </div>

        </div>
    </div>

<!-- END: Content-->

@view('footer')