<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Ürünler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('urun')}}">Ürünler</a>
                                <li class="breadcrumb-item active">{{$detay->adi==""?"":$detay->adi}}
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
                                <a class="nav-link active" href="{{URL::site('urun/form/')}}{{$detay->id}}">
                                    <i data-feather="user" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Ürün Detayları</span>
                                </a>
                            </li>

                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('urun')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Ürünler</span>
                                </a>
                            </li>

                        </ul>
                        @Form::csrf()->action($action)->open('submitForm',['class'=>'form form-horizontal','enctype'=>'multipart/form-data'])

                        @Form::hidden('id',$detay->id,['class'=>'form-control'])

                            {{ Redirect::select('bilgi',true) }}
                            <div class="card brdt-warning">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-6 col-12" style="border-right: 1px solid #dee2e6">


                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="tedarikci">Tedarikci (Sipariş Sırasında değiştirilebilir)</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" name="tedarikci" id="tedarikci">

                                                            <option value="0">--Seçiniz--</option>
                                                            @foreach($tedarikciler as $tedarikci)
                                                            <option {{$tedarikci->id==$detay->tedarikci?'selected':''}} value="{{$tedarikci->id}}">{{$tedarikci->adi}}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="urun_kodu">Ürün Kodu</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @Form::vRequired()->id('urun_kodu')->placeholder('Ürün Kodu')->text('urun_kodu',$detay->urun_kodu,['class'=>'form-control'])

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <style>
                                            /* Yükleme animasyonu */
                                            .loading-animation {
                                                opacity: 0; /* Başlangıçta görünmez yap */
                                                transition: opacity 1s ease-in-out; /* Geçiş efekti: 1 saniyede yavaşça görünür hale getir */
                                            }

                                            /* Kaldırma animasyonu */
                                            .remove-animation {
                                                opacity: 1; /* Başlangıçta görünür yap */
                                                transition: opacity 1s ease-in-out; /* Geçiş efekti: 1 saniyede yavaşça kaybolur */
                                            }
                                        </style>

                                        <script type="text/javascript">
                                            function optionList(groupId, productId="0") {
                                                // Yükleme işlemi başlamadan önce efekti başlat
                                                $("#proOptions").html('<div class="loading-animation">Yükleniyor...</div>');

                                                // Yükleme tamamlandığında efekti kaldır
                                                $("#proOptions").load("{{URL::site('Ajax/urunGrupOzellikGetir')}}/"+groupId+"/"+productId, function() {
                                                    $("#proOptions .loading-animation").addClass('remove-animation'); // Kaldırma animasyonunu etkinleştir
                                                    setTimeout(function() {
                                                        $("#proOptions .loading-animation").remove(); // Animasyon tamamlandığında öğeyi kaldır
                                                    }, 10000); // 1 saniye sonra kaldır
                                                });

                                                // Yükleme animasyonunu göstermek için opacity değerini yavaşça artır
                                                setTimeout(function() {
                                                    $("#proOptions .loading-animation").css('opacity', '1');
                                                }, 1000); // 0.1 saniye sonra görünür hale getir
                                            }

                                        </script>

                                        <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="email">Ürün Grubu</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    {[ $urunID = $detay->id; ]}
                                                    <select class="select2 form-select" name="grup" id="grup" onchange="optionList(this.value,{{$urunID==""?0:$detay->id}})">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($gruplar as $grup)
                                                        <option {{$grup->id==$detay->grupId?'selected':''}} value="{{$grup->id}}">{{$grup->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="resim">Resim (Sadece .jpg, .png)</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-9">
                                                           <input type="file" id="resim" accept="image/jpeg, image/png" name="resim" class="form-control" >
                                                        </div>
                                                        <div class="col-sm-3">
                                                            @if($detay->resim!='')
                                                            <img id="resim" class="img-thumbnail" style="height: 40px" src="{{URL::site()}}../Uploads/urun-resimleri/{{$detay->resim}}">
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="col-12">
                                            <div class=" row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="adi">Ürün Adı</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::vRequired()->id('adi')->placeholder('Ürün Adı')->text('adi',$detay->adi,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="aciklama">Açıklama</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('aciklama')->placeholder('Açıklama')->textarea('aciklama',$detay->aciklama,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label " id="detay" for="detay">Detay</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('summernote')->placeholder('Detay')->textarea('detay',$detay->detay,['class'=>'form-control'])

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-6 col-12">

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="fiyat">Stoklu Ürün</label>
                                                    </div>
                                                    <div class="col-sm-12">

                                                        <script type="text/javascript">
                                                            function getval(sel)
                                                            {

                                                                if (sel.value == 1) {
                                                                    //document.getElementById('guncel_stok').removeAttribute('disabled');
                                                                    $('#guncel_stok').prop('disabled', false);
                                                                    $('#guncel_stok').prop('required', true);

                                                                }
                                                                if (sel.value == 0) {
                                                                    $('#guncel_stok').prop('disabled', true);
                                                                    $('#guncel_stok').prop('required', false);

                                                                }
                                                            }
                                                        </script>

                                                        <select class="select2 form-select" onchange="getval(this);" name="stoklu_urun" id="stoklu_urun">

                                                            <option value="1"{{$detay->stoklu_urun=='1'?'selected':''}}>Evet</option>
                                                            <option value="0"{{$detay->stoklu_urun=='0'?'selected':''}}>Hayır</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="guncel_stok">Güncel Stok</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @if($detay->id=="")
                                                        @Form::id('guncel_stok')->placeholder('Güncel Stok')->number('guncel_stok',$detay->guncel_stok,['class'=>'form-control','disabled'=>'disabled'])
                                                        @else
                                                        @Form::id('guncel_stok')->placeholder('Güncel Stok')->number('guncel_stok',$detay->guncel_stok,['class'=>'form-control'])
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row custom-options-checkable g-1">
                                            <script type="text/javascript">
                                                $(".odeme_turuU").click(function(){
                                                    $("#tek").hide();
                                                    $("#yenilenen").hide();
                                                });
                                                $(".odeme_turuT").click(function(){
                                                    $("#tek").show();
                                                    $("#yenilenen").hide();
                                                });
                                                $(".odeme_turuy").click(function(){
                                                    $("#tek").hide();
                                                    $("#yenilenen").show();
                                                });
                                            </script>
                                            <div class="col-md-4">
                                                <input class="custom-option-item-check" type="radio" value="U" name="odeme_turu" id="odeme_turuU" {{$detay->odeme_turu=='U'?'checked':''}} />
                                                <label class="custom-option-item p-1 odeme_turuU" for="odeme_turuU">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50">
                                                        <span class="fw-bolder">Ücretsiz</span>

                                                    </span>
                                                    <small class="d-block">Ücretsiz Olarak Satışı Yapılır</small>
                                                </label>
                                            </div>

                                            <div class="col-md-4">
                                                <input class="custom-option-item-check" type="radio" name="odeme_turu" id="odeme_turuT" value="T" {{$detay->odeme_turu=='T'?'checked':''}} />
                                                <label class="custom-option-item p-1 odeme_turuT" for="odeme_turuT">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50">
                                                        <span class="fw-bolder">Tek Seferlik</span>
                                                    </span>
                                                    <small class="d-block">Tek Seferlik Ödeme Alınır</small>
                                                </label>
                                            </div>

                                            <div class="col-md-4">
                                                <input class="custom-option-item-check" type="radio" name="odeme_turu" id="odeme_turuY" value="Y" {{$detay->odeme_turu=='Y'?'checked':''}} />
                                                <label class="custom-option-item p-1 odeme_turuY" for="odeme_turuY">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50">
                                                        <span class="fw-bolder">Yenilenen</span>

                                                    </span>
                                                    <small class="d-block">Periyoda Göre Fatura Oluşur</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="fiyat">Fiyat Birim</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" name="fiyat_birim" id="fiyat_birim">

                                                            <option value="">--Seçiniz--</option>
                                                            @foreach($paraBirimleri as $para)
                                                            <option {{$para->kod==$detay->fiyat_birim?'selected':''}} value="{{$para->kod}}">{{$para->para}}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="guncel_stok">Ürün KDV</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select class="select2 form-select" name="kdv" id="kdv">
                                                            <option value="0">--Seçiniz--</option>
                                                            <option value="0" {{$detay->kdv=='0'?'selected':''}}>%0</option>
                                                            <option value="10" {{$detay->kdv=='10'?'selected':''}}>%10</option>
                                                            <option value="20" {{$detay->kdv=='20'?'selected':''}}>%20</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-12" id="tek" style="display: {{$detay->odeme_turu=='T'?'block':'none'}}">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="fiyat">Fiyat</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    @Form::id('fiyat')->placeholder('Tek Seferlik Fiyat')->text('fiyat',$detay->fiyat,['class'=>'form-control'])
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row" id="yenilenen" style="display:  {{$detay->odeme_turu=='Y'?'block':'none'}}">

                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="aylik_fiyat">Aylik</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @Form::id('aylik_fiyat')->placeholder('Aylik Fiyat')->text('aylik_fiyat',$detay->aylik_fiyat,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="uc_aylik_fiyat">3 Aylik</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @Form::id('uc_aylik_fiyat')->placeholder('3 Aylik Fiyat')->text('uc_aylik_fiyat',$detay->uc_aylik_fiyat,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="alti_aylik_fiyat">6 Aylik</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @Form::id('alti_aylik_fiyat')->placeholder('6 Aylik Fiyat')->text('alti_aylik_fiyat',$detay->alti_aylik_fiyat,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="yillik_fiyat">Yıllık</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @Form::id('yillik_fiyat')->placeholder('Yıllık Fiyat')->text('yillik_fiyat',$detay->yillik_fiyat,['class'=>'form-control'])
                                                    </div>
                                                </div>
                                            </div>


                                        </div>



                                        <div class="row custom-options-checkable g-1">
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" value="0" name="durum" id="durum1" {{$detay->durum=='0'?'checked':''}} />
                                                <label class="custom-option-item p-1" for="durum1">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50">
                                                        <span class="fw-bolder">Pasif</span>

                                                    </span>
                                                                <small class="d-block">Satış Listesinde Görünmez</small>
                                                            </label>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <input class="custom-option-item-check" type="radio" name="durum" id="durum2" value="1" {{$detay->durum=='1'?'checked':''}} />
                                                            <label class="custom-option-item p-1" for="durum2">
                                                    <span class="d-flex justify-content-between flex-wrap mb-50">
                                                        <span class="fw-bolder">Aktif</span>

                                                    </span>
                                                    <small class="d-block">Satış Listesinde Görünür</small>
                                                </label>
                                            </div>
                                        </div>

                                    </div>

                                        <div class="col-md-12 mt-2" style="border-top: 1px solid #dee2e6">
                                            <div class="mb-1 row" id="proOptions">
                                                @foreach($urunOzellikleri as $ozellik)
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="detay">{{$ozellik->gereklilik=="1"?"<strong class='text-danger'>*</strong>":""}} {{$ozellik->baslik}} ({{$ozellik->tur}})
                                                            @if(($ozellik->tur=="image" or $ozellik->tur=="file") and UrunModel::urunOzellikDeger($detay->id,$ozellik->id)!="")
                                                            <a href="{{URL::site()}}..//Uploads/urun-dosyalari/{{UrunModel::urunOzellikDeger($detay->id,$ozellik->id)}}" target="_blank"><strong class="text-success">Yüklenmiş Dosyayı Görüntüle</strong></a>
                                                            @endif
                                                        </label>
                                                        <input type="hidden" name="ozellik_id[]" value="{{$ozellik->id}}">
                                                        @if($ozellik->tur=="text")
                                                            <input name="deger[{{$ozellik->id}}]" {{$ozellik->gereklilik=='1'?'required':''}} id="deger" data-bs-toggle="tooltip" title="Gireceğiniz Değer Yazı Olmalıdır" class="form-control" type="text" value="{{UrunModel::urunOzellikDeger($detay->id,$ozellik->id)}}">
                                                        @elseif($ozellik->tur=="file")
                                                            <input name="file_{{$ozellik->id}}" {{$ozellik->gereklilik=='1'?'required':''}} id="deger" data-bs-toggle="tooltip" title="Yükleyeceğiniz Dosya zip,rar,pdf,doc,docx gibi uzantılı dosya olmalıdır" class="form-control" type="file" accept=".zip,.rar,.pdf,.doc,.docx">
                                                        @elseif($ozellik->tur=="image")
                                                            <input name="image_{{$ozellik->id}}" {{$ozellik->gereklilik=='1'?'required':''}} id="deger" data-bs-toggle="tooltip" title="Yükleyeceğiniz Resim jpg,jpeg,png,webp uzantılı dosya olmalıdır" class="form-control" type="file" accept=".gif,.jpg,.jpeg,.png">
                                                        @elseif($ozellik->tur=="link")
                                                            <input name="deger[{{$ozellik->id}}]" {{$ozellik->gereklilik=='1'?'required':''}} id="deger" data-bs-toggle="tooltip" title="Bağlantı Tam adres olmalıdır" class="form-control" type="text" value="{{UrunModel::urunOzellikDeger($detay->id,$ozellik->id)}}">
                                                        @elseif($ozellik->tur=="icon")
                                                            <input name="deger[{{$ozellik->id}}]" {{$ozellik->gereklilik=='1'?'required':''}} id="deger" data-bs-toggle="tooltip" title="iconlar font-awesome 5.x  desteklemektedir. Örn: fa fa-user olarak girmelisiniz" class="form-control" type="text" value="{{UrunModel::urunOzellikDeger($detay->id,$ozellik->id)}}">
                                                        @elseif($ozellik->tur=="code")
                                                            <input name="deger[{{$ozellik->id}}]" {{$ozellik->gereklilik=='1'?'required':''}} id="deger" data-bs-toggle="tooltip" title="Gireceğiniz kodlar HTML kodları olmalıdır. Harici kodlar çalışmayacaktır." class="form-control" type="text"  value="{{UrunModel::urunOzellikDeger($detay->id,$ozellik->id)}}">
                                                        @else
                                                            <input name="deger[{{$ozellik->id}}]" {{$ozellik->gereklilik=='1'?'required':''}} id="deger" data-bs-toggle="tooltip" title="Gireceğiniz Değer Yazı Olmalıdır" class="form-control" type="text"  value="{{UrunModel::urunOzellikDeger($detay->id,$ozellik->id)}}">
                                                        @endif
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                </div>
                                </div>
                                <div class="card-footer">
                                    <div class="col-sm-12">
                                        <button type="submit" style="width: 100%" class="btn btn-primary me-1">Kaydet</button>
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