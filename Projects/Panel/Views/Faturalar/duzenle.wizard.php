<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Fatura Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Faturalar</a>
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
                        @Form::csrf()->action("siparisler/kaydet")->open('submitForm',['class'=>'form form-horizontal'])

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-2 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Detayları</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Fatura No</label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <input type="text" class="form-control form-control-sm" id="colFormLabelSm" value="{{$detay->belge_no}}" placeholder="Fatura No" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Fatura Tarihi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-sm date-picker" id="colFormLabelSm" value="{{$detay->belge_tarihi}}" placeholder="Fatura Tarihi" />

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Ödeme Tarihi</label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <input type="text" class="form-control form-control-sm date-picker" value="{{$detay->vade_tarihi}}" id="colFormLabelSm" placeholder="Ödeme Tarihi" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Müşteri</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" id="select2-basic" name="cari">
                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($musteriler as $musteri)
                                                        <option value="{{$musteri->id}}" {{($musteri->id == $detay->musteri) ? 'selected' : ''}}>{{$musteri->adi}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="odeme_yontemi">Ödeme Yöntemi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="odeme_yontemi" id="odeme_yontemi">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($odemeYontemleri as $oy)
                                                        <option value="{{$oy->id}}" {{$oy->id == $detay->odeme_yontemi ? 'selected' : ''}}>{{$oy->baslik}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="durum">Fatura Türü Durumları</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="tur" id="tur">

                                                        <option value="">--Seçiniz--</option>
                                                        <option  value="1" {{$detay->tur == "1" ? 'selected' : ''}}>Alış Faturası</option>
                                                        <option  value="2" {{$detay->tur == "2" ? 'selected' : ''}}>Satış Faturası</option>
                                                        <option  value="3" {{$detay->tur == "3" ? 'selected' : ''}}>İade Faturası</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="siparis_notu">Sipairş Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('siparis_notu')->placeholder('Sipariş Notu')->textarea('siparis_notu',$detay->aciklama,['class'=>'form-control'])
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-10 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Fatura Detayları</h4>
                                        @if($detay->durum=="2")
                                            <span class="badge rounded-pill badge-light-success">Resmi Fatura <a href="{{URL::site()}}../Uploads/faturalar/{{ $detay->resmi_fatura_dosyasi }}" target="_blank" class="badge rounded-pill badge-light-info">Yüklenmiş Faturayı İndir</a></span>

                                        @elseif($detay->durum=="1")
                                            <span class="badge rounded-pill badge-light-warning">Resmileşmemiş</span>
                                        @else
                                            <span class="badge rounded-pill badge-light-danger">İptal Edilmiş</span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="card-title">Firma Detayları</h5>
                                                    <p class="card-text mb-25"><strong>{{AyarModel::defaultAyarlar('firmaAdi')}}</strong></p>
                                                    <p class="card-text mb-25">{{AyarModel::defaultAyarlar('firmaAdresi')}}</p>
                                                    <p class="card-text mb-0">Vergi D.: {{AyarModel::defaultAyarlar('vergiDairesi')}}<br>Vergi No: {{AyarModel::defaultAyarlar('vergiNo')}}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="card-title">Cari Detayları</h5>
                                                    <p class="card-text mb-25"><strong>{{$detay->cariDetay->firma_adi}}</strong> <a href="{{URL::site('cari/detay/').$detay->cariDetay->id}}" class="btn btn-primary btn-sm" target="_blank"><i data-feather="external-link"></i> </a> </p>
                                                    <p class="card-text mb-25">{{$detay->cariDetay->fatura_adresi}}</p>
                                                    <p class="card-text mb-0">Vergi D.: {{$detay->cariDetay->vergi_dairesi}}<br>Vergi No: {{$detay->cariDetay->vergi_no}}</p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Ürün</th>
                                                    <th>Not</th>
                                                    <th>Adet</th>
                                                    <th>Birim Fiyat</th>
                                                    <th>Kdv</th>
                                                    <th>Toplam Tutar</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="addDataTable">

                                                @foreach($faturaUrunleri as $furun)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="id[]" id="id" value="{{$furun->id}}">
                                                        {{$furun->urun_adi}}
                                                    </td>
                                                    <td style="min-width: 400px">
                                                        <input type="text" name="aciklama[]" id="aciklama" value="{{$furun->aciklama}}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="miktar[]" id="miktar" value="{{$furun->miktar}}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="fiyat[]" id="fiyat" value="{{number_format($furun->fiyat,2)}}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <select name="kdv[]" id="kdv" class="form-control">
                                                            <option value="">--Seçiniz--</option>
                                                            <option value="0" {{$furun->kdv == "0" ? 'selected' : ''}}>%0</option>
                                                            <option value="10" {{$furun->kdv == "10" ? 'selected' : ''}}>%10</option>
                                                            <option value="20" {{$furun->kdv == "20" ? 'selected' : ''}}>%20</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="tutar[]" id="tutar" value="{{number_format($furun->tutar,2)}}" class="form-control">
                                                    </td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" onclick="deleteAction('{{$furun->id}}','{{URL::site('faturalar/ajax')}}','faturaUrunSil')">
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

                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-6">
                                                <button type="submit" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> KAYDET</span></button>
                                            </div>

                                            <div class="col-sm-6">

                                                @if($detay->durum=="1")
                                                <a href="#" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> Faturayı Resmileştir</span></a>
                                                @else
                                                <a href="#" class="dt-button create-new btn btn-danger"><span><i data-feather="x-circle"></i> Faturayı İptal Et</span></a>
                                                @endif

                                                @if($detay->odeme=="0")
                                                <a href="#" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> Faturayı Ödendi Yap</span></a>
                                                @endif
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