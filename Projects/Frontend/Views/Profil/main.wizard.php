<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$user->adi}}</h3>
        <!--begin::Title-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
   

    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
	
        <!--begin::Index-->
        <div class="d-flex flex-column flex-xl-row">
								<!--begin::Sidebar-->
								<div class="flex-column flex-lg-row-auto w-100 w-xl-325px mb-10">
									<!--begin::Card-->
									<div class="card card-flush" data-kt-sticky="true" data-kt-sticky-name="account-navbar" data-kt-sticky-offset="{default: false, xl: '80px'}" data-kt-sticky-width="{lg: '250px', xl: '325px'}" data-kt-sticky-left="auto" data-kt-sticky-top="90px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
										<!--begin::Card header-->
										
										<!--end::Card header-->
										<!--begin::Card body-->
										<div class="card-body pt-10 p-10">
											<!--begin::Summary-->
											<div class="d-flex flex-center flex-column mb-10">
												<!--begin::Avatar-->
												<div class="symbol mb-3 symbol-100px symbol-circle">
													<img alt="Pic" src="media/avatars/no-avatar.jpg" />
												</div>
												<!--end::Avatar-->
												<!--begin::Name-->
												<a href="#" class="fs-2 text-gray-800 text-hover-primary fw-bolder mb-1">{{$user->adi}}</a>
												<!--end::Name-->
												<!--begin::Position-->
												<div class="fs-6 fw-bold text-gray-400 mb-2">{{$user->firma_adi}}</div>
												<!--end::Position-->
											</div>
											<!--end::Summary-->
											<!--begin::Menu-->
											<ul class="menu menu-column menu-pill menu-title-gray-700 menu-bullet-gray-300 menu-state-bg menu-state-bullet-primary fw-bolder fs-5 mb-10">
												<li class="menu-item mb-1">
													<!--begin::Menu link-->
													<a class="menu-link px-6 py-4 active" href="{{URL::site('profil')}}">
														<span class="menu-bullet">
															<span class="bullet"></span>
														</span>
														<span class="menu-title">Hesabım</span>
													</a>
													<!--end::Menu link-->
												</li>
												<li class="menu-item mb-1">
													<!--begin::Menu link-->
													<a class="menu-link px-6 py-4" href="{{URL::site('profil/sifre')}}">
														<span class="menu-bullet">
															<span class="bullet"></span>
														</span>
														<span class="menu-title">Şifre Değiştir</span>
													</a>
													<!--end::Menu link-->
												</li>
												<!--begin::Menu item-->
												<li class="menu-item mb-1">
													<!--begin::Menu link-->
													<a class="menu-link px-6 py-4" href="{{URL::site('siparisler')}}">
														<span class="menu-bullet">
															<span class="bullet"></span>
														</span>
														<span class="menu-title">Ürünlerim</span>
													</a>
													<!--end::Menu link-->
												</li>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<li class="menu-item mb-1">
													<!--begin::Menu link-->
													<a class="menu-link px-6 py-4" href="{{URL::site('Faturalar')}}">
														<span class="menu-bullet">
															<span class="bullet"></span>
														</span>
														<span class="menu-title">Faturalarım</span>
													</a>
													<!--end::Menu link-->
												</li>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<li class="menu-item mb-1">
													<!--begin::Menu link-->
													<a class="menu-link px-6 py-4" href="{{URL::site('proje/liste')}}">
														<span class="menu-bullet">
															<span class="bullet"></span>
														</span>
														<span class="menu-title">Projelerim</span>
													</a>
													<!--end::Menu link-->
												</li>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<li class="menu-item mb-1">
													<!--begin::Menu link-->
													<a class="menu-link px-6 py-4" href="{{URL::site('destek')}}">
														<span class="menu-bullet">
															<span class="bullet"></span>
														</span>
														<span class="menu-title">Taleplerim</span>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="flex-lg-row-fluid ms-lg-10">
									<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
									{{ Redirect::select('bilgi',true) }}
										<div class="card-header cursor-pointer">
											<div class="card-title m-0">
												<h3 class="fw-bolder m-0">Profil Detay</h3>
											</div>
										</div>
                                        @Form::csrf()->action('profil/guncelle')->open('submitForm',['id'=>'submitForm'])
										<div class="card-body p-9">
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">Adınız Soyadınız</label>
												<div class="col-lg-8">
                                                    @Form::vRequired()->id('adi')->placeholder('E-Posta Adresiniz')->text('adi',$user->adi,['class'=>'form-control form-control-solid'])
												</div>
											</div>
                                            <div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">E-Posta</label>
												<div class="col-lg-8">
                                                    @Form::vRequired()->id('email')->placeholder('TC Kimlik')->text('email',$user->email,['class'=>'form-control form-control-solid','readonly'=>'readonly'])
												</div>
											</div>
											<!--end::Row-->
											<!--begin::Input group-->
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">TC Kimlik</label>
												<div class="col-lg-8">
                                                    @Form::vIdentity()->vMessage("Lütfen Geçerli bir TC Kimlik Numarası Giriniz")->id('tc')->placeholder('TC Kimlik')->text('tc',$user->tc,['class'=>'form-control form-control-solid'])
												</div>
											</div>
                                            <div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">Cep Telefonu</label>
												<div class="col-lg-8">
                                                    @Form::vRequired()->id('gsm')->placeholder('GSM')->number('gsm',$user->gsm,['class'=>'form-control form-control-solid'])
												</div>
											</div>
											<hr>
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">Firma Adı</label>
												<div class="col-lg-8">
                                                    @Form::id('firma_adi')->placeholder('Firma Adı')->text('firma_adi',$user->firma_adi,['class'=>'form-control form-control-solid'])
												</div>
											</div>
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">Fatura Adresi</label>
												<div class="col-lg-8">
                                                    @Form::id('fatura_adresi')->placeholder('Firma Adı')->textarea('fatura_adresi',$user->fatura_adresi,['class'=>'form-control form-control-solid'])
												</div>
											</div>
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">İl</label>
												<div class="col-lg-8">
                                                    @Form::vRequired()->id('il')->placeholder('İl')->text('il',$user->il,['class'=>'form-control form-control-solid'])
												</div>
											</div>
											
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">Vergi No</label>
												<div class="col-lg-8">
                                                    @Form::id('vergi_no')->placeholder('Vergi No')->number('vergi_no',$user->vergi_no,['class'=>'form-control form-control-solid'])
												</div>
											</div>
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">Vergi Dairesi</label>
												<div class="col-lg-8">
                                                    @Form::id('vergi_dairesi')->placeholder('Vergi Dairesi')->text('vergi_dairesi',$user->vergi_dairesi,['class'=>'form-control form-control-solid'])
												</div>
											</div>
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted">Vergi No</label>
												<div class="col-lg-8">
                                                    @Form::id('vergi_no')->placeholder('Vergi No')->text('vergi_no',$user->vergi_no,['class'=>'form-control form-control-solid'])
												</div>
											</div>
											<div class="row mb-7">
												<label class="col-lg-4 fw-bold text-muted"></label>
												<div class="col-lg-8">
                                                    <button type="submit" class="btn btn-primary" style="width: 100%">Kaydet</button>
												</div>
											</div>
										</div>
                                        @Form::close()
										<!--end::Card body-->
									</div>

                                </div>
								<!--end::Content-->
							</div>
        <!--end::Index-->
    </div>
    <!--end::Post-->
</div>