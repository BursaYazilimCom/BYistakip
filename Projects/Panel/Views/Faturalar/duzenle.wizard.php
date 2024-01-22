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
                                <li class="breadcrumb-item"><a href="{{URL::site('faturalar')}}">Faturalar</a>
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
                        @Form::csrf()->action("faturalar/guncelle/".$detay->id)->open('submitForm',['class'=>'form form-horizontal'])

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-lg-2 col-md-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Sipariş Detayları</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Müşteri</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <strong>{{ CariModel::cariAdi($detay->musteri)}}</strong>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="belge_no">Fatura No</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-sm" name="belge_no" id="belge_no" value="{{$detay->belge_no}}" placeholder="Fatura No" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Fatura Tarihi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="text" name="belge_tarihi" class="form-control form-control-sm date-picker" id="belge_tarihi" value="{{$detay->belge_tarihi}}" placeholder="Fatura Tarihi" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="vade_tarihi">Ödeme Tarihi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-sm date-picker" name="vade_tarihi" value="{{$detay->vade_tarihi}}" id="vade_tarihi" placeholder="Ödeme Tarihi" />
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
                                            <label class="form-label" for="siparis_notu">Sipairş Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('siparis_notu')->placeholder('Sipariş Notu')->textarea('siparis_notu',$detay->aciklama,['class'=>'form-control'])
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-10 col-md-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Fatura Detayları
                                            @if($detay->odeme=="0")
                                            <span class="badge rounded-pill badge-light-warning">Ödeme Yapılmadı</span>
                                            @elseif($detay->odeme=="1")
                                            <span class="badge rounded-pill badge-light-success">Ödeme Yapıldı olarak işaretlendi</span>
                                            @endif
                                        </h4>
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
                                            <table class="table table-hover table-responsive">
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

                                                {[
                                                $kdv10 = 0;
                                                $kdv20 = 0;
                                                $toplamTutar = 0;
                                                $araToplamTutar = 0;
                                                ]}

                                                @foreach($faturaUrunleri as $furun)

                                                    {[
                                                        $araToplamTutar = $araToplamTutar+($furun->fiyat*$furun->miktar);
                                                        $toplamTutar    = $toplamTutar+$furun->tutar;
                                                    ]}

                                                    @if($furun->kdv=="10")
                                                    {[
                                                        $kdv10 = $kdv10+$furun->kdv_tutari;
                                                    ]}
                                                    @endif

                                                    @if($furun->kdv=="20")
                                                    {[
                                                        $kdv20 = $kdv20+$furun->kdv_tutari;
                                                    ]}
                                                    @endif

                                                <tr id="row-{{$furun->id}}">
                                                    <td>
                                                        <input type="hidden" name="id[]" id="id" value="{{$furun->id}}">
                                                        <input type="hidden" name="urun_adi[]" id="urun_adi" value="{{$furun->urun_adi}}">
                                                        <strong>{{$furun->urun_adi}}</strong><br><small><strong>Dönemi:</strong>{{Date::convert($furun->donem_baslangic_tarihi,'d.m.Y')}} {{Date::convert($furun->donem_bitis_tarihi,'d.m.Y')}}</small>
                                                    </td>
                                                    <td style="min-width: 400px">
                                                        <input type="text" name="aciklama[]" id="aciklama" value="{{$furun->aciklama}}" class="form-control">

                                                    </td>
                                                    <td>
                                                        @if($detay->durum=="2")
                                                        {{$furun->miktar}}
                                                        @else
                                                        <input type="number" name="miktar[]" id="miktar" value="{{$furun->miktar}}" class="miktar form-control">
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if($detay->durum=="2")
                                                        {{number_format($furun->fiyat,2)}}
                                                        @else
                                                        <div class="input-group">
                                                            <input type="text" name="fiyat[]" id="fiyat" value="{{number_format($furun->fiyat,2)}}" class="fiyat form-control">
                                                            <span class="input-group-text">₺</span>
                                                        </div>

                                                        @endif


                                                    </td>
                                                    <td>
                                                        @if($detay->durum=="2")
                                                        %{{$furun->kdv}}
                                                        @else
                                                        <select name="kdv[]" id="kdv" class="kdv form-control">
                                                            <option value="">--Seçiniz--</option>
                                                            <option value="0" {{$furun->kdv == "0" ? 'selected' : ''}}>%0</option>
                                                            <option value="10" {{$furun->kdv == "10" ? 'selected' : ''}}>%10</option>
                                                            <option value="20" {{$furun->kdv == "20" ? 'selected' : ''}}>%20</option>
                                                        </select>
                                                        @endif

                                                    </td>
                                                    <td>

                                                        @if($detay->durum=="2")
                                                        {{number_format($furun->tutar,2)}}
                                                        @else
                                                        <div class="input-group">
                                                            <input type="text" name="tutar" readonly id="tutar" value="{{number_format($furun->tutar,2)}}" class="tutar form-control">
                                                            <span class="input-group-text">₺</span>
                                                        </div>
                                                        @endif

                                                    </td>

                                                    <td>
                                                        <a class="text-danger" data-bs-toggle="tooltip" data-bs-title="Fatura Kalemini Sil" onclick="deleteAction('{{$furun->id}}','{{URL::site('faturalar/ajax')}}','faturaUrunSil')">
                                                            <i data-feather="trash" class="me-50"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                @endforeach

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="4"></th>

                                                    <th>Tanımlama</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td>Ara Toplam</td>
                                                    <td class="kdvsizTutar">{{number_format($araToplamTutar,2)}} ₺</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td>KDV %10</td>
                                                    <td class="kdvTutar10">{{number_format($kdv10,2)}} ₺</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td>KDV %20</td>
                                                    <td class="kdvTutar20">{{number_format($kdv20,2)}} ₺</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td>KDV Toplamı</td>
                                                    <td class="kdvlerToplam">{{number_format($kdv20+$kdv10,2)}} ₺</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td>Genel Toplam</td>
                                                    <td class="genel_toplam">{{number_format($toplamTutar,2)}} ₺</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    <script type="text/javascript">
                                        // Her satırdaki input alanlarını seç
                                        const miktarInputs = document.querySelectorAll('.miktar');
                                        const fiyatInputs = document.querySelectorAll('.fiyat');
                                        const kdvSelects = document.querySelectorAll('.kdv');
                                        const tutarInputs = document.querySelectorAll('.tutar');
                                        const kdvsizTutar = document.querySelector('.kdvsizTutar');
                                        const kdvTutar10 = document.querySelector('.kdvTutar10');
                                        const kdvTutar20 = document.querySelector('.kdvTutar20');
                                        const kdvlerToplam = document.querySelector('.kdvlerToplam');
                                        const genelToplam = document.querySelector('.genel_toplam');

                                        // Her input değiştiğinde hesaplama yap
                                        miktarInputs.forEach((miktarInput, index) => {
                                            miktarInput.addEventListener('input', function () {
                                                hesapla();
                                            });

                                            fiyatInputs[index].addEventListener('input', function () {
                                                hesapla();
                                            });

                                            kdvSelects[index].addEventListener('change', function () {
                                                hesapla();
                                            });
                                        });

                                        function hesapla() {
                                            let toplamTutar = 0;
                                            let kdvsizToplam = 0;
                                            let kdvTutar10Toplam = 0;
                                            let kdvTutar20Toplam = 0;

                                            miktarInputs.forEach((miktarInput, index) => {
                                                const miktar = parseFloat(miktarInput.value) || 0;
                                                const fiyat = parseFloat(fiyatInputs[index].value) || 0;
                                                const kdvOrani = parseFloat(kdvSelects[index].value) || 0;

                                                const toplam = miktar * fiyat;
                                                const kdvTutari = (toplam * kdvOrani) / 100;
                                                const tutar = toplam + kdvTutari;

                                                tutarInputs[index].value = tutar.toFixed(2);

                                                kdvsizToplam += toplam;

                                                if (kdvOrani === 10) {
                                                    kdvTutar10Toplam += kdvTutari;
                                                } else if (kdvOrani === 20) {
                                                    kdvTutar20Toplam += kdvTutari;
                                                }

                                                toplamTutar += tutar;
                                            });

                                            const kdvToplam = kdvTutar10Toplam+kdvTutar20Toplam;

                                            kdvsizTutar.textContent = kdvsizToplam.toFixed(2)+" ₺";
                                            kdvTutar10.textContent = kdvTutar10Toplam.toFixed(2)+" ₺";
                                            kdvTutar20.textContent = kdvTutar20Toplam.toFixed(2)+" ₺";
                                            kdvlerToplam.textContent = kdvToplam.toFixed(2)+" ₺";
                                            genelToplam.textContent = toplamTutar.toFixed(2)+" ₺";
                                        }
                                    </script>

                                        <hr>

                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-6">
                                                <button type="submit" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> DEĞİŞİKLİKLERİ KAYDET</span></button>
                                            </div>

                                            <div class="col-sm-6">
                                                @if($detay->durum!="2")
                                                <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$detay->id}}" data-action="faturayaUrunEkle" class="dt-button create-new btn btn-success">
                                                    <i data-feather="shopping-cart" class="me-50"></i>
                                                    Fatura Kalemi EKle
                                                </a>
                                                @else
                                                <small>Resmileştirilmiş faturaya yeni bir ürün kalemi ekleyemezsiniz. Bunun için yeni bir şipariş oluşturmalısınız </small><br><br>
                                                @endif
                                                <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$detay->id}}" data-action="faturaOdendiYap" class="dt-button create-new btn btn-info">
                                                    <i data-feather="plus" class="me-50"></i>
                                                    Ödeme EKle
                                                </a>
                                                @if($detay->durum=="1")
                                                <a  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$detay->id}}" data-action="faturaResmilestir"  href="#" class="dt-button create-new btn btn-warning"><span><i data-feather="airplay"></i> Faturayı Resmileştir</span></a>

                                                </a>
                                                @else
                                                <a href="#" class="dt-button create-new btn btn-danger"><span><i data-feather="x-circle"></i> Faturayı İptal Et</span></a>
                                                @endif

                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Faturaya Ait Muhasebe Kayıtları</h4>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-responsive">
                                            <thead>
                                            <tr>
                                                <th>Tarih</th>
                                                <th>Kasa</th>
                                                <th>İşlem</th>
                                                <th>Açıklama</th>
                                                <th>Tutar</th>
                                            </tr>
                                            </thead>
                                            <tbody id="addDataTable">

                                            @foreach($maliIslemler as $mi)

                                            {[
                                            if($mi->islem=="t"){
                                            $gelir = $gelir+$mi->gelir;

                                            }else{
                                            $gider = $gider+$mi->gider;
                                            }
                                            ]}

                                            <tr id="row-{{$mi->id}}">
                                                <td>{{Date::convert($mi->tarih,'d.m.Y')}}</td>
                                                <td>{{KasaModel::hesapAdi($mi->kasa)}}</td>
                                                <td>{{$mi->islem=="t"?"<span class='text-success'>Tahsilat</span> ":"<span class='text-danger'>Ödeme Yapıldı</span>"}}</td>
                                                <td>{{$mi->aciklama}}</td>
                                                <td>{{$mi->gelir}}</td>
                                            </tr>

                                            @endforeach

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3"></th>

                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td style="text-align: right">Toplam Tahsilat :</td>
                                                <td>{{$gelir}} ₺</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td style="text-align: right">Toplam İade :</td>
                                                <td>{{$gider}} ₺</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td style="text-align: right">Toplam Alınan :</td>
                                                <td>{{$gelir-$gider}} ₺</td>
                                            </tr>

                                            </tfoot>
                                        </table>
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

<div class="modal fade" id="openModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center mb-1" id="modalTitle">Fatura İşlemleri</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>