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
                                <li class="breadcrumb-item active">Sipariş Düzenle
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
                        @Form::csrf()->action("siparisler/guncelle")->open('submitForm',['class'=>'form form-horizontal'])

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
                                                    <label class="col-form-label" for="cari">Müşteri</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" id="select2-basic" name="cari">
                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($musteriler as $musteri)
                                                        <option value="{{$musteri->id}}" {{$musteri->id==$cariBilgi->id?'selected':''}}>{{$musteri->adi}}</option>
                                                        @endforeach


                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="odeme_yontemi">Ödeme Yöntemi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="odeme_yontemi" id="odeme_yontemi">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($odemeYontemleri as $oy)
                                                        <option value="{{$oy->id}}" {{$oy->id==$detay->odeme_yontemi?'selected':''}}>{{$oy->baslik}}</option>
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
                                                        <option  value="{{$sd->id}}" {{$sd->id==$detay->durum?'selected':''}}>{{$sd->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="siparis_notu">Sipairş Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('siparis_notu')->placeholder('Sipariş Notu')->textarea('siparis_notu','',['class'=>'form-control'])
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-8 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Ürünleri</h4>
                                        <a class="dt-button create-new btn btn-primary" tabindex="0" data-bs-toggle="modal" data-bs-target="#modals-add"><span><i data-feather="plus"></i>ÜRÜN EKLE</span></a>
                                    </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Ürün/Hizmet</th>
                                                        <th>Cari</th>
                                                        <th>Ödeme Periyodu</th>
                                                        <th>Adet</th>
                                                        <th>Sipariş Notu</th>
                                                        <th>Baş. Tar.</th>
                                                        <th>Geçerli Fiyat</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="addDataTable">
                                                    @foreach($urunler as $sUrun)
                                                    <tr>
                                                        <td>{{$sUrun->id}}</td>
                                                        <td>{{$sUrun->urun_adi}}</td>
                                                        <td>{{$sUrun->cari}}</td>
                                                        <td>{{$sUrun->odeme_periyodu}}</td>
                                                        <td>{{$sUrun->adet}}</td>
                                                        <td>{{$sUrun->notu}}</td>
                                                        <td>{{$sUrun->baslangic_tarihi}}</td>
                                                        <td>{{$sUrun->toplam_fiyat}} {{$sUrun->para_birimi}}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                    <i data-feather="more-vertical"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" onclick="deleteAction('{{$sUrun->id}}','{{URL::site('siparisler/ajax')}}','siparisUrunSil')">
                                                                        <i data-feather="trash" class="me-50"></i>
                                                                        <span>Sil</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>


                                            <hr>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-sm-12">
                                                    <button type="submit" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> KAYDET</span></button>
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



        </div>
    </div>
</div>