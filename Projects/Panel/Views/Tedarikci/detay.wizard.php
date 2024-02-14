<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="app-user-view-account">
                <div class="row">
                    <!-- User Sidebar -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <!-- User Card -->
                        <div class="card brdt-navy">
                            <div class="card-body">
                                <div class="user-avatar-section">
                                    <div class="d-flex align-items-center flex-column">
                                        <div class="user-info text-center">
                                            <h4>{{$cariDetay->adi}}</h4>
                                            <span class="badge bg-light-secondary">Müşteri</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around my-2 pt-75">
                                    <div class="d-flex align-items-start me-2">
                                            <span class="badge bg-light-primary p-75 rounded">
                                                <i data-feather="check" class="font-medium-2"></i>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Hesaplamalar Faturalar üzerinden yapılmaktadır. Ödeme yapıldı olarak işaretlenmiş faturaların toplamıdır.">{{$cariFaturaToplamlari['odenen']}} ₺</h4>
                                            <small>Ödemeler</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                            <span class="badge bg-light-primary p-75 rounded">
                                                <i data-feather="briefcase" class="font-medium-2"></i>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hesaplamalar Faturalar üzerinden yapılmaktadır. ÖDENMEDİ olarak işaretlenmiş olan faturaların toplamıdır.">{{$cariFaturaToplamlari['odenmeyen']}} ₺</h4>
                                            <small>Borçlar</small>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="fw-bolder border-bottom pb-50 mb-1">Bilgileri</h4>
                                <div class="info-container">
                                    <ul class="list-unstyled">
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">İsim :</span>
                                            <span>{{$cariDetay->adi}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">TC Kimlik :</span>
                                            <span>{{$cariDetay->tc}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Email:</span>
                                            <span>{{$cariDetay->email}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Firma Adı:</span>
                                            <span>{{$cariDetay->firma_adi}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Firma Adresi:</span>
                                            <span>{{$cariDetay->fatura_adresi}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Şehir:</span>
                                            <span>{{$cariDetay->id}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Vergi D. / No:</span>
                                            <span>{{$cariDetay->vergi_dairesi}} / {{$cariDetay->vergi_no}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Durum:</span>
                                            {{$cariDetay->durum=="1"?"<span class='badge bg-light-success'>Aktif Üye</span>":"<span class='badge bg-light-danger'>Pasif Üye</span>"}}
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Kayıt Tarihi:</span>
                                            <span>{{Date::convert($cariDetay->kayit_tarihi,'d.m.Y H:i')}}</span>
                                        </li>

                                    </ul>
                                    <div class="d-flex justify-content-center pt-2">
                                        <a href="{{URL::site('cari/form/'.$cariDetay->id)}}" class="btn btn-primary me-1" data-bs-target="#editUser" data-bs-toggle="modal">
                                            Düzenle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/ User Sidebar -->

                    <!-- User Content -->
                    <div class="col-xl-9 col-lg-8 col-md-6">
                        <!-- User Pills -->
                        <ul class="nav nav-pills mb-2">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{URL::site('cari/detay/'.$cariDetay->id)}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Detay</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('cari/form/'.$cariDetay->id)}}">
                                    <i data-feather="lock" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Düzenle</span>
                                </a>
                            </li>

                        </ul>
                        <!--/ User Pills -->

                        <!-- Project table -->
                        <div class="card">
                            <h4 class="card-header">Ürünleri</h4>
                            <div class="table-responsive">
                                <table class="table datatable-project">
                                    <thead>
                                    <tr>
                                        <th>Ürün Adı</th>
                                        <th>Açıklama</th>
                                        <th>Başlangıç</th>
                                        <th>Bitiş</th>
                                        <th>Durum</th>
                                        <th>Tutar</th>
                                        <th>Periyod</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($uyeUrunleri['liste'] as $uyeUrun)
                                        <tr>
                                            <td>{{$uyeUrun->urun_adi}}<br><small>{{urunModel::urunAdi($uyeUrun->urun)}}</small></td>
                                            <td>{{$uyeUrun->notu}}<br></td>
                                            <td>{{Date::convert($uyeUrun->baslangic_tarihi,'d.m.Y')}}</td>
                                            <td>{{Date::convert($uyeUrun->bitis_tarihi,'d.m.Y')}}</td>
                                            <td>{{AyarModel::siparisDurumAdi($uyeUrun->durum)}}</td>
                                            <td>{{$uyeUrun->toplam_fiyat}}</td>
                                            <td>{{AyarModel::odemePeriyodu($uyeUrun->odeme_periyodu)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <h4 class="card-header">Projeleri</h4>
                            <div class="table-responsive">
                                <table class="table datatable-project">
                                    <thead>
                                    <tr>
                                        <th>Proje Adı</th>
                                        <th>Başlangıç</th>
                                        <th>Termin</th>
                                        <th>Durum</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cariProjeleri['liste'] as $proje)
                                            <tr>
                                                <td>{{$proje->proje_adi}}</td>
                                                <td>{{Date::convert($proje->proje_baslangic_tarihi,'d.m.Y')}}</td>
                                                <td>{{Date::convert($proje->tahmini_bitis_tarihi,'d.m.Y')}}</td>
                                                <td>{{$proje->durum==1?'Aktif':'Pasif'}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <h4 class="card-header">Faturaları</h4>
                            <div class="table-responsive">
                                <table class="table datatable-project">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Oluşturma Tarihi</th>
                                        <th>Son Ödeme Tarihi</th>
                                        <th>Tutar</th>
                                        <th>Durum</th>
                                        <th>Ödeme</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($faturalar as $fatura)
                                    <tr>
                                        <td>{{$fatura->id}}</td>
                                        <td>{{$fatura->belge_tarihi}}</td>
                                        <td>{{$fatura->vade_tarihi}}</td>
                                        <td>{{$fatura->genel_toplam}} ₺</td>

                                        <td>
                                            @if($fatura->durum=="0")
                                            <span class="badge rounded-pill badge-light-danger"> İptal </span>
                                            @elseif($fatura->durum=="1")
                                            <span class="badge rounded-pill badge-light-warning"> Resmileşmemiş </span>
                                            @elseif($fatura->durum=="2")
                                            <span class="badge rounded-pill badge-light-success"> Resmi Faturalı </span>
                                            @else
                                            <span class="badge rounded-pill badge-light-primary"> Tanımsız </span>
                                            @endif
                                        </td>
                                        <td>@if($fatura->odeme=="1")
                                            <span class="badge rounded-pill badge-light-success"> Ödendi </span>
                                            @else
                                            <span class="badge rounded-pill badge-light-danger"> Ödenmedi </span>
                                            @endif</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <!--/ User Content -->
                </div>
            </section>
            <!-- Edit User Modal -->
            <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pb-5 px-sm-5 pt-50">
                            <div class="text-center mb-2">
                                <h1 class="mb-1">Edit User Information</h1>
                                <p>Updating user details will receive a privacy audit.</p>
                            </div>
                            <form id="editUserForm" class="row gy-1 pt-75" onsubmit="return false">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserFirstName">First Name</label>
                                    <input type="text" id="modalEditUserFirstName" name="modalEditUserFirstName" class="form-control" placeholder="John" value="Gertrude" data-msg="Please enter your first name" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserLastName">Last Name</label>
                                    <input type="text" id="modalEditUserLastName" name="modalEditUserLastName" class="form-control" placeholder="Doe" value="Barton" data-msg="Please enter your last name" />
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="modalEditUserName">Username</label>
                                    <input type="text" id="modalEditUserName" name="modalEditUserName" class="form-control" value="gertrude.dev" placeholder="john.doe.007" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserEmail">Billing Email:</label>
                                    <input type="text" id="modalEditUserEmail" name="modalEditUserEmail" class="form-control" value="gertrude@gmail.com" placeholder="example@domain.com" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserStatus">Status</label>
                                    <select id="modalEditUserStatus" name="modalEditUserStatus" class="form-select" aria-label="Default select example">
                                        <option selected>Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                        <option value="3">Suspended</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditTaxID">Tax ID</label>
                                    <input type="text" id="modalEditTaxID" name="modalEditTaxID" class="form-control modal-edit-tax-id" placeholder="Tax-8894" value="Tax-8894" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserPhone">Contact</label>
                                    <input type="text" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" placeholder="+1 (609) 933-44-22" value="+1 (609) 933-44-22" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserLanguage">Language</label>
                                    <select id="modalEditUserLanguage" name="modalEditUserLanguage" class="select2 form-select" multiple>
                                        <option value="english">English</option>
                                        <option value="spanish">Spanish</option>
                                        <option value="french">French</option>
                                        <option value="german">German</option>
                                        <option value="dutch">Dutch</option>
                                        <option value="hebrew">Hebrew</option>
                                        <option value="sanskrit">Sanskrit</option>
                                        <option value="hindi">Hindi</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserCountry">Country</label>
                                    <select id="modalEditUserCountry" name="modalEditUserCountry" class="select2 form-select">
                                        <option value="">Select Value</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Brazil">Brazil</option>
                                        <option value="Canada">Canada</option>
                                        <option value="China">China</option>
                                        <option value="France">France</option>
                                        <option value="Germany">Germany</option>
                                        <option value="India">India</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Israel">Israel</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Japan">Japan</option>
                                        <option value="Korea">Korea, Republic of</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Philippines">Philippines</option>
                                        <option value="Russia">Russian Federation</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Turkey">Turkey</option>
                                        <option value="Ukraine">Ukraine</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="United States">United States</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="form-check form-switch form-check-primary">
                                            <input type="checkbox" class="form-check-input" id="customSwitch10" checked />
                                            <label class="form-check-label" for="customSwitch10">
                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                            </label>
                                        </div>
                                        <label class="form-check-label fw-bolder" for="customSwitch10">Use as a billing address?</label>
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-2 pt-50">
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                        Discard
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Edit User Modal -->
            <!-- upgrade your plan Modal -->
            <div class="modal fade" id="upgradePlanModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-upgrade-plan">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5 pb-2">
                            <div class="text-center mb-2">
                                <h1 class="mb-1">Upgrade Plan</h1>
                                <p>Choose the best plan for user.</p>
                            </div>
                            <form id="upgradePlanForm" class="row pt-50" onsubmit="return false">
                                <div class="col-sm-8">
                                    <label class="form-label" for="choosePlan">Choose Plan</label>
                                    <select id="choosePlan" name="choosePlan" class="form-select" aria-label="Choose Plan">
                                        <option selected>Choose Plan</option>
                                        <option value="standard">Standard - $99/month</option>
                                        <option value="exclusive">Exclusive - $249/month</option>
                                        <option value="Enterprise">Enterprise - $499/month</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 text-sm-end">
                                    <button type="submit" class="btn btn-primary mt-2">Upgrade</button>
                                </div>
                            </form>
                        </div>
                        <hr />
                        <div class="modal-body px-5 pb-3">
                            <h6>User current plan is standard plan</h6>
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex justify-content-center me-1 mb-1">
                                    <sup class="h5 pricing-currency pt-1 text-primary">$</sup>
                                    <h1 class="fw-bolder display-4 mb-0 text-primary me-25">99</h1>
                                    <sub class="pricing-duration font-small-4 mt-auto mb-2">/month</sub>
                                </div>
                                <button class="btn btn-outline-danger cancel-subscription mb-1">Cancel Subscription</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ upgrade your plan Modal -->

        </div>
    </div>
</div>