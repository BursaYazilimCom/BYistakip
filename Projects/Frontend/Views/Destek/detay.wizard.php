<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$detay->konu}}</h3>
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

										<div class="card-body pt-10 p-10">
											<!--begin::Summary-->
											<div class="d-flex flex-center flex-column mb-10">
												<!--begin::Avatar-->
												<div class="symbol mb-3 symbol-100px symbol-circle">
													<img alt="Pic" src="media/avatars/no-avatar.jpg" />
												</div>

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
													<a class="menu-link px-6 py-4" href="{{URL::site('profil')}}">
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
													<a class="menu-link px-6 py-4 active" href="{{URL::site('destek')}}">
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
									<div class="card mb-5 mb-xl-10 p-5" id="kt_profile_details_view">
									{{ Redirect::select('bilgi',true) }}
										

										<div class="card-header align-items-center border-0">
											<h3 class="card-title align-items-start flex-column">
												<span class="fw-bolder text-dark fs-2">Destek Talep Detay</span>
											</h3>
											<div class="card-toolbar">

											@if($detay->durum=="0")
                                                <span class="btn btn-sm btn-primary">Kapandı</span>
                                                @elseif($detay->durum=="1")
                                                <span class="btn btn-sm btn-danger">Yeni</span>
                                                @elseif($detay->durum=="2")
                                                <span class="btn btn-sm btn-info">Yanıtlandı</span>
                                                @elseif($detay->durum=="3")
                                                <span class="btn btn-sm btn-warning">Müşteri Cevapladı</span>
                                                @else
                                                <span class="btn btn-sm btn-danger">Tanımsız</span>
                                                @endif
												@if($detay->durum!="0")
													<a href="{{URL::site()}}destek/kapat/{{$detay->id}}" class="btn btn-sm btn-success m-2">Talebi Kapat</a>
												@endif
											</div>
										</div> 

										<div class="my-3 p-4 bg-body rounded shadow-sm" style="background-color: #FFF9DE !important;">
											<h6 class="border-bottom pb-2 mb-0">{{$detay->konu}} (Siz)</h6>
											<br>

											{{Security::htmlDecode($detay->mesaj)}}
											<hr>
											<small style="color: #969696 !important;">{{Date::convert($detay->tarih,'d.m.Y H:i')}}</small>
										</div>

                                        @Form::csrf()->action('destek/cevapla/'.$detay->id)->open('submitForm',['class'=>'kt-form kt-form--label-right'])

										@foreach($destekMesajlari as $mesaj)

											@if($mesaj->gonderen=="2")

												<div class="my-3 p-4 bg-body rounded shadow-sm" style="background-color: #D7F4FD !important;">
													<h6 class="border-bottom pb-2 mb-0">{{PersonelModel::isim($mesaj->gonderen_id)}}</h6>
													<br>

													{{Security::htmlDecode($mesaj->mesaj)}}
													<hr>
													<small style="color: #969696 !important;">{{Date::convert($mesaj->tarih,'d.m.Y H:i')}}</small>
													@if($mesaj->dosya_eki!="")
												
															<a href="{{URL::site('../Uploads/destek-talep/'.$mesaj->dosya_eki)}}" target="_blank" class="badge badge-success" style="float: right;">Ekli Dosya İndir</a>
													
													@endif
												</div>

											@else

												<div class="my-3 p-4 bg-body rounded shadow-sm" style="background-color: #FFF9DE !important;">
													<h6 class="border-bottom pb-2 mb-0">{{CariModel::cariAdi($mesaj->gonderen_id)}}</h6>
													<br>

													{{Security::htmlDecode($mesaj->mesaj)}}
													<hr>
													<small style="color: #969696 !important;">{{Date::convert($mesaj->tarih,'d.m.Y H:i')}}</small>
												</div>

											@endif

										@endforeach


										<div class="card-body p-9">
											
                                           
											<div class="row mb-7">
												<label class="col-lg-12 fw-bold text-muted">Cevap</label>
												<div class="col-lg-12">
                                                    @Form::id('editor')->placeholder('Hala Desteğe İhtiyacınız varsa cevap yazabilirsiniz')->textarea('cevap','',['class'=>'form-control form-control-solid','rows'=>'5'])
												</div>
											</div>

											<div class="row mb-7">
												<div class="col-lg-12">
                                                    <button type="submit" class="btn btn-primary" style="width: 100%">
													@if($detay->durum=="0")
														Kapalı Talebi Yeniden Aç
													@else
														Cevap Gönder
													@endif
													</button>
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