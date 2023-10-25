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
                                                    <label class="col-form-label" for="cari">Müşteri</label>
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
                                                    <label class="col-form-label" for="odeme_yontemi">Ödeme Yöntemi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="odeme_yontemi" id="odeme_yontemi">

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
                                                        <input class="form-check-input" type="checkbox" name="kayit_sekli" id="kayit_sekli" value="1" />
                                                        <label class="form-check-label" for="kayit_sekli">Teklif olarak kaydet</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="odeme_durumu" id="odeme_durumu"  onclick="odemeAlindi()" value="1" />
                                                        <label class="form-check-label" for="odeme_durumu">Ödeme Yapıldı</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="fatura" id="fatura" value="1" onclick="faturaOlustur()" />
                                                        <label class="form-check-label" for="fatura">Fatura Oluştur</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12" id="kasaHesabiDiv" style="display: none">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="kasa_hesabı">Kasa Hesabı <span class="text-danger">*(Ödemenin Kasaya İşlenmesi için gereklidir)</span></label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="kasa_hesabı" id="kasa_hesabı">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($kasaHesaplari as $kh)
                                                        <option value="{{$kh->id}}">{{$kh->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12" id="faturaNoDiv" style="display: none">
                                            <label class="form-label" for="fatura_no">Fatura No  <span class="text-danger">*(Oluşturulan faturaya doğru numara verilmesi için gereklidir. Not:E-fatura kullanıyorsanız gerekyoktur.)</span></label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('fatura_no')->placeholder('Fatura No')->text('fatura_no','',['class'=>'form-control'])
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
                                                        <th>Kod</th>
                                                        <th>Ürün/Hizmet</th>
                                                        <th>Ödeme Periyodu</th>
                                                        <th>Adet</th>
                                                        <th>Sipariş Notu</th>
                                                        <th>Baş. Tar.</th>
                                                        <th>Geçerli Fiyat</th>
                                                        <th>Kdv</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="addDataTable">
                                                    @foreach(Cart::selectAll() as $sUrun)

                                                    <tr>
                                                        <td>{{$sUrun["serial"]}}</td>
                                                        <td>{{$sUrun["urun_adi"]}}</td>
                                                        <td>{{$sUrun["odemePeriyoduTanim"]}}</td>
                                                        <td>{{$sUrun["adet"]}}</td>
                                                        <td>{{$sUrun["siparis_notu"]}}</td>
                                                        <td>{{$sUrun["baslangic_tarihi"]}}</td>
                                                        <td>{{$sUrun["fiyat"]}}{{$sUrun["fiyat_birim"]}}</td>
                                                        <td>{{$sUrun["urunKdv"]}}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                    <i data-feather="more-vertical"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" onclick="deleteAction('{{$sUrun['serial']}}','{{URL::site('siparisler/ajax')}}','sepetUrunSil')">
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

            <div class="modal fade" id="modals-add" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h1 class="text-center mb-1" id="modalTitle">Ürün EKle</h1>

                            <!-- form -->
                            @Form::csrf()->prevent()->action('Siparisler/ajax')->open('submitForm',['id'=>'submitForm','class'=>'row gy-1 gx-2 mt-75'])
                            <input type="hidden" name="dataAction" id="dataAction" value="sepeteUrunEkle">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label" for="urun">Ürün / Hizmet</label>
                                        </div>
                                        <script type="text/javascript">
                                            function odemePeriodlari(urunId)
                                            {
                                                $("#odeme_periyodu").load("{{URL::site('By/urunOdemePeriodlari')}}/"+urunId);
                                            }

                                        </script>
                                        <div class="col-sm-12">
                                            <select class="select2 form-select" required id="urun" name="urun" onchange="odemePeriodlari(this.value)">
                                                <option value="">--Seçiniz--</option>
                                                @foreach($urunler as $urun)
                                                    <option value="{{$urun->id}}">{{$urun->adi}} (
                                                        @if($urun->fiyat>0)
                                                        Tek Seferlik:{{$urun->fiyat}},
                                                        @endif
                                                        @if($urun->aylik_fiyat>0)
                                                        Aylık:{{$urun->aylik_fiyat}},
                                                        @endif
                                                        @if($urun->uc_aylik_fiyat>0)
                                                        3 Aylık:{{$urun->uc_aylik_fiyat}},
                                                        @endif
                                                        @if($urun->alti_aylik_fiyat>0)
                                                        6 Aylık:{{$urun->alti_aylik_fiyat}},
                                                        @endif
                                                        @if($urun->yillik_fiyat>0)
                                                        Yıllık:{{$urun->yillik_fiyat}}
                                                        @endif )
                                                        ({{$urun->fiyat_birim}})
                                                        </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label" for="odeme_periyodu">Ödeme Periyodu</label>
                                        </div>
                                        <div class="col-sm-12">

                                            <select class="select2 form-select" id="odeme_periyodu" name="odeme_periyodu">
                                                <option value="">--Önce Ürün Seçiniz--</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label" for="adet">Adet</label>
                                        </div>
                                        <div class="col-sm-12">
                                            @Form::vNumeric()->id('adet')->placeholder('Adet')->number('adet','1',['class'=>'form-control'])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label" for="kdv">Ürün KDV</label>
                                        </div>
                                        <div class="col-sm-12">
                                            <select class="select2 form-select" id="kdv" name="kdv">
                                                <option value="0">--Seçiniz--</option>
                                                <option value="0">KDV (%0)</option>
                                                <option value="10">KDV (%10)</option>
                                                <option value="20" selected>KDV (%20)</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="siparis_notu">Ürün Sipariş Notu</label>
                                    </div>
                                    <div class="col-sm-12">
                                        @Form::id('siparis_notu')->placeholder('Sipariş Notu')->textarea('siparis_notu','',['class'=>'form-control'])
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label" for="baslangic_tarihi">Ürün/Hizmet Başlangıç Tarihi <span class="text-danger">(Boş bırakırsanız bugünün tarihini alır)</span></label>
                                    <div class="input-group input-group-merge">
                                        @Form::id('baslangic_tarihi')->placeholder(Date::current())->text('baslangic_tarihi','',['class'=>'form-control'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label" for="fiyat_sabitle">Sonraki Faturalarda</label>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="fiyat_sabitle" id="fiyat_sabitle" value="1" />
                                                <label class="form-check-label" for="fiyat_sabitle">Sonraki faturalarda güncel ürün fiyatını önemseme</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label" for="fiyat">Fiyat</label>
                                        </div>
                                        <div class="col-sm-3">
                                            @Form::id('fiyat')->number('fiyat','0',['class'=>'form-control'])
                                        </div>
                                        <div class="col-sm-9">
                                            Eğer farklı fiyat vermek istiyorsanız buradan geçerli fiyatı giriniz. Eğer boş bırakırsanız ürünün geçerli fiyatı alınır.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Kaydet</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                    Vazgeç
                                </button>
                            </div>
                            @Form::close()
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->
<script type="text/javascript">
    function odemeAlindi() {
        // Get the checkbox
        var checkBox = document.getElementById("odeme_durumu");
        // Get the output text
        var text = document.getElementById("kasaHesabiDiv");

        // If the checkbox is checked, display the output text
        if (checkBox.checked == true){
            text.style.display = "block";
            $("#kasa_hesabı").attr('required',true);
        } else {
            text.style.display = "none";
            $("#kasa_hesabı").attr('required',false);
        }
    }
    function faturaOlustur() {
        // Get the checkbox
        var checkBox = document.getElementById("fatura");
        // Get the output text
        var text = document.getElementById("faturaNoDiv");

        // If the checkbox is checked, display the output text
        if (checkBox.checked == true){
            text.style.display = "block";
            $("#fatura_no").attr('required',true);
        } else {
            text.style.display = "none";
            $("#fatura_no").attr('required',false);
        }
    }
</script>