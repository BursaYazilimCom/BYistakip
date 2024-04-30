<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$detay->baslik}}</h3><small style="color:white">{{$etkinlikTur->tur}}</small>
        <!--begin::Title-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
							<!--begin::Layout - Overview-->
							<div class="d-flex flex-column flex-xl-row">
								<!--begin::Sidebar-->
							
								<!--end::Sidebar-->
								<!--begin::Content-->
								<div class="flex-lg-row-fluid">
									<!--begin::details View-->
									<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
										<!--begin::Card header-->
										<div class="card-header cursor-pointer">
											<!--begin::Card title-->
											<div class="card-title m-0">
												<h3 class="fw-bolder m-0">{{$etkinlikTur->tur}} Detayları</h3>
											</div>
											<!--end::Card title-->
										</div>
										<!--begin::Card header-->
										<!--begin::Card body-->
										<div class="card-body p-9">
											<!--begin::Row-->
                                            
                                            <div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Başlık</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8">
													<span class="fw-bolder fs-6 text-dark">{{$detay->baslik}}</span>
												</div>
												<!--end::Col-->
											</div>
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Başlangıç Zamanı</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8">
													<span class="fw-bolder fs-6 text-dark">{{Date::convert($detay->baslangic_tarih_saat,"d.m.Y H:i")}}</span>
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
											<!--begin::Input group-->
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Bitiş Zamanı</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8 fv-row">
													<span class="fw-bold fs-6">{{Date::convert($detay->bitis_tarih_saat,'d.m.Y H:i')}}</span>
												</div>
												<!--end::Col-->
											</div>
                                            <div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Etkinlik Yeri</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8 fv-row">
													<span class="fw-bold fs-6">
														@if(strstr($detay->konum,"https://") || strstr($detay->konum,"http://"))
															<a href="{{$detay->konum}}" class="btn btn-primary align-self-center" target="_blank">{{$detay->konum}}</a>
														@else
															{{$detay->konum}}
														@endif
													</span>
												</div>
												<!--end::Col-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">İnternet Sitesi</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8 d-flex align-items-center">
													<span class="fw-bold fs-6">{{$detay->url}}</span>
												</div>
												<!--end::Col-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Açıklama</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8">
                                                <span class="fw-bold fs-6">{{$detay->aciklama}}</span>
												</div>
												<!--end::Col-->
											</div>

											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Katılımcılar</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8">
													<ul style="list-style: number;">
														@for($k=0;count($katilimcilar)>$k;$k++)
															<li>
																@if($katilimcilar[$k]==$user)
																	<strong>Siz : </strong> {{$katilimcilar[$k]}}
																@else
																	{{AyarModel::gizle($katilimcilar[$k])}}
																@endif
														</li>
														@endfor
													</ul>		
												</div>
												<!--end::Col-->
											</div>


										</div>
										<!--end::Card body-->
									</div>

								</div>
								<!--end::Content-->
							</div>
							<!--end::Layout - Overview-->
						</div>
    <!--end::Post-->
</div>