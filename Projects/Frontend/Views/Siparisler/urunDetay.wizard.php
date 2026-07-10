<!--end::Header-->
<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Title-->
        <h3 class="text-white fw-bolder fs-2qx me-5">{{$urunDetay->notu}}</h3><small>{{$urunDetay->urun_adi}}</small>
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
												<h3 class="fw-bolder m-0">Ürün Detayları</h3>
											</div>
                                            <a href="#" class="btn btn-primary align-self-center">Siparişi Görüntüle</a>
											<!--end::Card title-->
										</div>
										<!--begin::Card header-->
										<!--begin::Card body-->
										<div class="card-body p-9">
											<!--begin::Row-->
                                            
                                            <div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Ürün Grubu</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8">
													<span class="fw-bolder fs-6 text-dark">{{$urunDetay->urun_adi}}</span>
												</div>
												<!--end::Col-->
											</div>
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Ürün Tanımı</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8">
													<span class="fw-bolder fs-6 text-dark">{{$urunDetay->notu}}</span>
												</div>
												<!--end::Col-->
											</div>
											<!--end::Row-->
											<!--begin::Input group-->
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Başlangıç Tarihi</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8 fv-row">
													<span class="fw-bold fs-6">{{Date::convert($urunDetay->baslangic_tarihi,'d.m.Y')}}</span>
												</div>
												<!--end::Col-->
											</div>
                                            <div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Bitiş Tarihi</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8 fv-row">
													<span class="fw-bold fs-6">{{Date::convert($urunDetay->bitis_tarihi,'d.m.Y')}}</span>
												</div>
												<!--end::Col-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Durumu</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8 d-flex align-items-center">
													<span class="fw-bold fs-6">{{AyarModel::siparisDurumAdi($urunDetay->durum)}}</span>
												</div>
												<!--end::Col-->
											</div>
											<!--end::Input group-->
											<!--begin::Input group-->
											<div class="row mb-7">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Ödeme Periyodu</label>
												<!--end::Label-->
												<!--begin::Col-->
												<div class="col-lg-8">
                                                <span class="fw-bold fs-6">{{AyarModel::odemePeriyodu($urunDetay->odeme_periyodu)}}</span>
												</div>
												<!--end::Col-->
											</div>

											<div class="row mb-10">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Sipariş Fiyat Bilgisi</label>
												<!--begin::Label-->
												<!--begin::Label-->
												<div class="col-lg-8">
                                                <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td>İlk Sipariş Döviz Kuru</td>
                                                                <td style="text-align: left"> {{number_format((float)$urunDetay->gecerli_kur,4)}} ₺</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Birim Fiyat</td>
                                                                <td style="text-align: left">{{number_format((float)$urunDetay->birim_fiyat,2)}} ₺</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Toplam KDV (% {{$urunDetay->kdv}})</td>
                                                                <td style="text-align: left">{{number_format((float)$urunDetay->kdv_tutari,2)}} ₺</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Genel Toplam</td>
                                                                <td style="text-align: left">{{number_format((float)$urunDetay->toplam_fiyat,2)}} ₺</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

												</div>
												<!--begin::Label-->
											</div>

                                            <div class="row mb-10">
												<!--begin::Label-->
												<label class="col-lg-4 fw-bold text-muted">Güncel Fiyat</label>
												<!--begin::Label-->
												<!--begin::Label-->
												<div class="col-lg-8">
                                                <table class="table table-bordered">
                                                        <tbody>

                                                            @if ($anaUrunDetay->fiyat >0)
                                                                <tr>
                                                                    <td>Tek Seferlik</td>
                                                                    <td style="text-align: left"> 
                                                                        {{number_format((float)$anaUrunDetay->fiyat,2)}} {{$anaUrunDetay->fiyat_birim}}
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($anaUrunDetay->aylik_fiyat >0)
                                                                <tr>
                                                                    <td>Aylık</td>
                                                                    <td style="text-align: left"> 
                                                                        {{number_format((float)$anaUrunDetay->aylik_fiyat,2)}} {{$anaUrunDetay->fiyat_birim}}
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($anaUrunDetay->uc_aylik_fiyat >0)
                                                                <tr>
                                                                    <td>3 Aylık</td>
                                                                    <td style="text-align: left"> 
                                                                        {{number_format((float)$anaUrunDetay->uc_aylik_fiyat,2)}} {{$anaUrunDetay->fiyat_birim}}
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($anaUrunDetay->alti_aylik_fiyat >0)
                                                                <tr>
                                                                    <td>6 Aylık</td>
                                                                    <td style="text-align: left"> 
                                                                        {{number_format((float)$anaUrunDetay->alti_aylik_fiyat,2)}} {{$anaUrunDetay->fiyat_birim}}
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @if ($anaUrunDetay->yillik_fiyat >0)
                                                                <tr>
                                                                    <td>Yıllık</td>
                                                                    <td style="text-align: left"> 
                                                                        {{number_format((float)$anaUrunDetay->yillik_fiyat,2)}} {{$anaUrunDetay->fiyat_birim}}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>

												</div>
												<!--begin::Label-->
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