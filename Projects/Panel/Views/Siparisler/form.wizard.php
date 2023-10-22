<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Siparişler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('siparisler')}}">Siparişler</a>
                                <li class="breadcrumb-item active">Sipariş Ekle
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
                                <a class="nav-link active" href="{{URL::site('siparisler/form/')}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Sipariş Detayları</span>
                                </a>
                            </li>

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('sparisler')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Siparişler</span>
                                </a>
                            </li>

                        </ul>
                        @Form::csrf()->action("siparisler/kaydet")->open('submitForm',['class'=>'form form-horizontal'])

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-4 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Detayları</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="urun_kodu">Müşteri</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" id="select2-basic" name="cari">
                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($musteriler['liste'] as $musteri)
                                                        <option value="{{$musteri->id}}">{{$musteri->adi}}</option>
                                                        @endforeach


                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="email">Ödeme Yöntemi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="grup" id="grup">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($odemeYontemleri as $oy)
                                                        <option value="{{$oy->id}}">{{$oy->baslik}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="durum">Sipariş Durumları</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="durum" id="durum">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($siparisDurumlari as $sd)
                                                        <option  value="{{$sd->id}}">{{$sd->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="kayit_sekli" id="kayit_sekli" value="0" />
                                                        <label class="form-check-label" for="kayit_sekli">Teklif olarak kaydet</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="odeme_durumu" id="odeme_durumu" value="1" />
                                                        <label class="form-check-label" for="odeme_durumu">Ödeme Yapıldı</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="fatura" id="fatura" value="1" />
                                                        <label class="form-check-label" for="fatura">Fatura Oluştur</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="teslim_tarihi">Teslim tarihi</label>
                                            <div class="input-group input-group-merge">
                                                @Form::vRequired()->id('teslim_tarihi')->placeholder('Teslim tarihi')->text('teslim_tarihi','',['class'=>'form-control'])
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="siparis_notu">Sipairş Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('siparis_notu')->placeholder('Teslim tarihi')->textarea('siparis_notu','',['class'=>'form-control'])
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Ürünleri</h4>
                                    </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="urun_kodu">Ürün / Hizmet</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <select class="select2 form-select" id="select2-basic" name="cari">
                                                                <option value="">--Seçiniz--</option>
                                                                @foreach($urunler as $urun)
                                                                <option value="{{$urun->id}}">{{$urun->adi}}</option>
                                                                @endforeach


                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="odeme_periyodu">Ödeme Periyodu</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <select class="select2 form-select" id="odeme_periyodu" name="odeme_periyodu">
                                                                <option value="">--Seçiniz--</option>
                                                                <option value="0">Ücretsiz</option>
                                                                <option value="T">Tek Seferlik</option>
                                                                <option value="A">Aylık</option>
                                                                <option value="3A">3 Aylık</option>
                                                                <option value="6A">6 Aylık</option>
                                                                <option value="Y">Yıllık</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="adet">Adet</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input type="number" class="form-control" id="adet" name="adet" placeholder="Adet">

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="siparis_notu">Sipariş Notu</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control" id="siparis_notu" name="siparis_notu" placeholder="Sipariş Notu">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="farkli_fiyat">Farklı Fiyat</label>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <input type="text" class="form-control" id="farkli_fiyat" name="farkli_fiyat" placeholder="Farklı Fiyat">

                                                        </div>
                                                        <div class="col-sm-9">
                                                            Eğer farklı fiyat vermek istiyorsanız buradan geçerli fiyatı giriniz
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-2">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="kdv">Ürün KDV</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <select class="select2 form-select" id="kdv" name="kdv">
                                                                <option value="0">--Seçiniz--</option>
                                                                <option value="0">KDV (%0)</option>
                                                                <option value="10">KDV (%10)</option>
                                                                <option value="20">KDV (%20)</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-4">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-12">
                                                            <label class="col-form-label" for="gelecek_fiyat_secimi">Sonraki Faturalarda</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" name="gelecek_fiyat_secimi" id="gelecek_fiyat_secimi" value="0" />
                                                                <label class="form-check-label" for="gelecek_fiyat_secimi">Güncel ürün fiyatını önemseme</label>
                                                            </div>

                                                        </div>
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