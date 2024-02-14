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

                        @Form::csrf()->action("siparisler/guncelle/".$detay->id)->open('submitForm',['class'=>'form form-horizontal'])

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-3 col-12">
                                <div class="card brdt-danger">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Detayları (#{{$detay->id}})</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="cari">Müşteri</label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <strong><a href="{{URL::site()}}cari/detay/{{$cariBilgi->id}}"> {{$cariBilgi->adi}}</a></strong><br> {{$cariBilgi->gsm}} - {{$cariBilgi->email}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="odeme_yontemi">Ödeme Yöntemi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        {{AyarModel::odemeYontemiAdi($detay->odeme_yontemi)}}

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="odeme_yontemi">Sipariş Tarihi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        {{Date::convert($detay->tarih, '{dayInMonth}.{monthInYear-}.{year}')}}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="odeme_durumu">Ödeme Durumu</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="form-select" name="odeme_durumu" id="odeme_durumu">

                                                        <option value="">--Seçiniz--</option>
                                                        <option  value="0" {{$detay->odeme_durumu=="0"?'selected':''}}>Ödeme Bekleniyor</option>
                                                        <option  value="1" {{$detay->odeme_durumu=="1"?'selected':''}}>Ödeme Yapıldı</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="odeme_yontemi">İlk Sipariş Fiyatı</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td>Toplam Tutar</td>
                                                                <td style="text-align: right">{{number_format($detay->toplam_tutar,2)}} ₺</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Toplam KDV</td>
                                                                <td style="text-align: right">{{number_format($detay->kdv_tutari,2)}} ₺</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Genel Toplam</td>
                                                                <td style="text-align: right">{{number_format($detay->genel_toplam_tutari,2)}} ₺</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="durum">Sipariş Durumları</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="durum" id="durum">

                                                        <option value="">--Seçiniz--</option>
                                                        <option  value="0" {{$detay->durum=="0"?'selected':''}}>Pasif</option>
                                                        <option  value="1" {{$detay->durum=="1"?'selected':''}}>Aktif</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="urunDurumDegistir" id="urunDurumDegistir" value="1" />
                                                        <label class="form-check-label" for="urunDurumDegistir">Durum değişikliğini sipariş ürünerine de yansıt</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="siparis_notu">Sipairş Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('siparis_notu')->placeholder('Sipariş Notu')->textarea('siparis_notu',$detay->siparis_notu,['class'=>'form-control'])
                                            </div>
                                        </div>

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

                            <div class="col-md-9 col-12">
                                <div class="card brdt-danger">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Ürünleri <small>Bu listedeki fiyatlar KDV dahil fiyatlardır. Fiyat değişikliği için faturayı düzenleyin</small></h4>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{URL::site()}}siparisler/iptal/{{$detay->id}}" class="btn btn-danger waves-effect waves-float waves-light">İptal Et</a>
                                            <a href="{{URL::site()}}faturalar/hatirlat/{{$detay->id}}" class="btn btn-info waves-effect waves-float waves-light">Ödeme Hatırlat</a>
                                            <a href="{{URL::site()}}faturalar/siparis/{{$detay->id}}" class="btn btn-warning waves-effect waves-float waves-light">Faturaları</a>
                                        </div>
                                    </div>
                                        <div class="card-body">
                                            <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Ürün/Hizmet</th>

                                                        <th>Ödeme Periyodu</th>
                                                        <th>Adet</th>
                                                        <th>Sipariş Notu</th>
                                                        <th>Baş. Tar.</th>
                                                        <th>Geçerli Fiyat</th>
                                                        <th>Durum</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="addDataTable">
                                                    @foreach($urunler as $sUrun)
                                                    <tr>
                                                        @if($sUrun->islem_gerekiyor == "1")
                                                        <td rowspan="2" style="color: #ff0000; font-weight: bold">{{$sUrun->id}}</td>
                                                        @else
                                                        <td>{{$sUrun->id}}</td>
                                                        @endif
                                                        <td><a href="{{URL::site()}}siparisler/urunDuzenle/{{$sUrun->id}}"> {{$sUrun->urun_adi}}</a></td>

                                                        <td>{{AyarModel::odemePeriyodu($sUrun->odeme_periyodu)}}</td>
                                                        <td>{{$sUrun->adet}}</td>
                                                        <td>{{$sUrun->notu}}</td>
                                                        <td>{{Date::convert($sUrun->baslangic_tarihi, '{dayInMonth}.{monthInYear-}.{year}')}}</td>
                                                        <td>{{number_format($sUrun->toplam_fiyat,2)}} ₺  </td>
                                                        <td>{{AyarModel::siparisDurumAdi($sUrun->durum)}}</td>
                                                        <td>
                                                            <a class=" btn btn-info btn-sm" data-bs-toggle="tooltip" title="Detay" href="{{URL::site()}}siparisler/urunDuzenle/{{$sUrun->id}}" >
                                                                <i data-feather="eye"></i></a>

                                                        </td>

                                                    </tr>
                                                    @if($sUrun->islem_gerekiyor == "1")
                                                    <tr style="border-bottom: solid 1px #ff0000; color: #ff0000;"> <td colspan="9">
                                                        <strong>Yapılması Gereken İşlem var!: </strong>{{$sUrun->yapilacak_islem}} <a class="badge bg-info" href="{{URL::site()}}siparisler/urunKontrolEdildi/{{$sUrun->id}}" data-bs-toggle="tooltip" title="Bu işlem; bu ürün için tedarik, ödeme, satın alma vs işlemler yapılacaksa bunları yapmanızı hatırlatmak için vardır."> Kontrol sağlandı İşlem Yapıldı</a> </td></tr>
                                                    @endif

                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <div class="card-footer">Not: Bu fiyatlar ilk sipariş fiyatlarıdır. Ürünlerin ödeme periyodları geldiğinde (Eğer ürün fiyatını  sabitleMEmişseniz), Sistem yeni faturaları güncel fiyatlarla oluşturur.</div>



                                </div>


                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Geçmişi</h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @foreach($siparisGecmisi as $sg)
                                            <li class="list-group-item"><strong>{{Date::convert($sg->tarih, '{dayInMonth}.{monthInYear-}.{year} {hour}:{minute}')}}</strong> -> {{$sg->aciklama}}</li>
                                            @endforeach

                                        </ul>
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