<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Tedarikçi Alış Faturası</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a></li>
                                <li class="breadcrumb-item"><a href="{{URL::site('tedarikci')}}">Tedarikçiler</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0)">{{$tedarikciDetay->adi}} Alış Faturası</a></li>
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
                        @Form::csrf()->action("faturalar/alisFaturasiKaydet")->open('submitForm',['class'=>'form form-horizontal','enctype'=>'multipart/form-data'])

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-lg-2 col-md-12 col-12">
                                <div class="card brdt-pink">
                                    <div class="card-header">
                                        <h4 class="card-title">Alış Fatura Detayları</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Tedarikçi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="form-select required select2 form-select-sm" name="tedarikci" id="tedarikci">
                                                        <option value="">Seciniz</option>
                                                        @foreach($tedarikciler as $tedarikci)
                                                        <option value="{{ $tedarikci->id }}" {{$tedarikci->id == $tedarikciDetay->id ? 'selected' : ''}}>{{$tedarikci->adi}}</option>
                                                        @endforeach
                                                    </select>
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
                                                    <input type="date" name="belge_tarihi" class="form-control form-control-sm" id="belge_tarihi" placeholder="Fatura Tarihi" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="vade_tarihi">Vade Tarihi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="date" class="form-control form-control-sm" name="vade_tarihi" placeholder="Ödeme Tarihi" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="tur">Ödeme Durumu</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" required name="odeme" id="odeme">

                                                        <option value="">--Seçiniz--</option>
                                                        <option  value="0">Yapılmadı</option>
                                                        <option  value="1" selected>Yapıldı</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="tur">Fatura Durumu</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" required name="durum" id="durum">

                                                        <option value="">--Seçiniz--</option>
                                                        <option  value="2">Resmileşmiş Fatura</option>
                                                        <option  value="1" selected>Resmileşmemiş Fatura</option>
                                                        <option  value="0">İptal</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-1 col-12">
                                            <label class="form-label" for="notu">Fatura Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('notu')->placeholder('Fatura Notu')->textarea('notu','',['class'=>'form-control'])
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="notu">Stok Kaydı</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="stok_kaydi" checked id="stok_kaydi" value="1" />
                                                <label class="form-check-label" for="stok_kaydi">İlgili Ürünleri Stoğa Ekle</label>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-10 col-md-12 col-12">
                                <div class="card brdt-pink">
                                    <div class="card-header">
                                        <h4 class="card-title">Fatura Ürünleri</h4>
                                    </div>
                                    <hr>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                <tr>
                                                    <th>Ürün Adı</th>
                                                    <th>İlgili Ürün</th>
                                                    <th>Açıklama</th>
                                                    <th>Adet</th>
                                                    <th>Birim Fiyat</th>
                                                    <th>Kdv</th>
                                                    <th>Toplam Tutar</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="addDataTableSupplierProduct">

                                                    <tr id="row0">

                                                        <td>
                                                            <input style="min-width: 200px" required type="text" name="urun[]" id="urun" class="form-control">
                                                        </td>
                                                        <td style="min-width: 300px">
                                                            <select style="min-width: 200px" class="select2 form-select" name="ilgili_urun[]" id="ilgili_urun">
                                                                <option value="">--Seçiniz--</option>
                                                                @foreach($tumUrunler as $urun)
                                                                    <option value="{{ $urun->id }}">{{ $urun->adi }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <input type="text" name="aciklama[]" id="aciklama" class="form-control">
                                                        </td>

                                                        <td>
                                                            <input type="number" name="miktar[]" id="miktar_0" value="1" class="form-control miktar">
                                                        </td>

                                                        <td>
                                                            <div class="input-group"><input type="text" name="fiyat[]" id="fiyat_0" class="form-control fiyat"><span class="input-group-text">₺</span></div>
                                                        </td>

                                                        <td>
                                                            <select name="kdv[]" id="kdv_0" class="kdv form-control">
                                                                <option value="">--Seçiniz--</option>
                                                                <option value="0">%0</option>
                                                                <option value="10">%10</option>
                                                                <option value="20" selected>%20</option>
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="tutar[]" readonly id="tutar_0" class="tutar form-control">
                                                                <span class="input-group-text">₺</span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <a class="text-danger btn_remove" id="0" data-bs-toggle="tooltip" data-bs-title="Sil">
                                                                <i data-feather="trash" class="me-50"></i>
                                                            </a>
                                                        </td>

                                                    </tr>

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="5"><button type="button" id="addRowSupplierProduct" class="btn btn-primary"><i data-feather="plus"></i> Ekle</button></th>

                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                              
                                                
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td>Toplam</td>
                                                    <td class="genel_toplam">0 ₺</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                    <div class="card" id="masrafGirisi">

                                        <div class="card-body">
                                            <div class="alert alert-warning">
                                                <div class="alert-body">
                                                    İlgili faturaya tam değil kısmı bir ödeme yapmışsanız; Bunu kayıt altına almak için faturayı ödenmedi olarak işaretleyip ardından, Yapılan ödemeyi masraflara harici olarak eklemelisiniz.<br>Burada yapacağınız işlem faturanın tamamını ödendi olarak kabul eder
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="col-12">
                                                        <label class="form-label" for="modalAddCardNumber">Masraf Kalemi</label>
                                                        <div class="input-group input-group-merge">
                                                            <select class="form-control" name="kalem" required >
                                                                <option value="">--Seçiniz--</option>
                                                                {[
                                                                foreach($masrafKalemleri['anaKalemler'] as $ustList){ ]}
                                                                <optgroup label="{[=$ustList->adi]}">
                                                                    {[
                                                                    foreach($masrafKalemleri['altKalemler'] as $altKalemList){


                                                                    if($altKalemList->ust==$ustList->id){

                                                                    ]}

                                                                    <option value="{[=$altKalemList->id]}">{[=$altKalemList->adi]}</option>
                                                                    {[
                                                                    }

                                                                    } ]}
                                                                </optgroup>
                                                                {[ } ]}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="modalAddCardNumber">Açıklama:</label>
                                                        <div class="input-group input-group-merge">
                                                            <textarea class="form-control" name="aciklama" placeholder="Açıklama"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="modalAddCardNumber">Dosya:</label>
                                                        <div class="input-group input-group-merge">
                                                            <label class="input-group-btn">
                                                                    <span class="btn btn-primary">
                                                                        <i class="fa fa-upload"></i> Masraf Belgesi Seç <input type="file" name="belge_dosya" style="display: none;">
                                                                    </span>
                                                            </label>
                                                            <input type="text" class="form-control" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="col-12">
                                                        <label class="form-label" for="modalAddCardNumber">Ödeme Hesabı:</label>
                                                        <div class="input-group input-group-merge">
                                                            <select name="kasa" required class="form-control">
                                                                <option value="0">--Seçiniz--</option>
                                                                <optgroup label="Kasa Hesapları">
                                                                    {[ foreach($kasaHesaplari as $kh){ ]}
                                                                    <option value="{[=$kh->id]}">{[=$kh->adi]}</option>
                                                                    {[ }]}
                                                                </optgroup>
                                                                <optgroup label="Banka Hesapları">
                                                                    {[ foreach($bankaHesaplari as $bh){ ]}
                                                                    <option value="{[=$bh->id]}">{[=$bh->adi]}</option>
                                                                    {[ } ]}
                                                                </optgroup>
                                                                <optgroup label="POS Hesapları">
                                                                    {[ foreach($posHesaplari as $ph){ ]}
                                                                    <option value="{[=$ph->id]}">{[=$ph->adi]}</option>
                                                                    {[ } ]}
                                                                </optgroup>
                                                                <optgroup label="Kredi Kartı Hesapları">
                                                                    {[ foreach($kkartiHesaplari as $kkh) { ]}
                                                                    <option value="{[=$kkh->id]}">{[=$kkh->adi]}</option>
                                                                    {[ } ]}
                                                                </optgroup>
                                                                <optgroup label="Veresiye Hesapları">
                                                                    {[ foreach($veresiyeHesaplari as $vh){ ]}
                                                                    <option value="{[=$vh->id]}">{[=$vh->adi]}</option>
                                                                    {[ } ]}
                                                                </optgroup>
                                                                <optgroup label="Diğer Hesaplar">
                                                                    {[ foreach($digerHesaplar as $dh){ ]}
                                                                    <option value="{[=$dh->id]}">{[=$dh->adi]}</option>
                                                                    {[ } ]}
                                                                </optgroup>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="modalAddCardNumber">Ödeme Tarihi:Güm.Ay.Yıl</label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="date" name="odeme_tarihi" class="form-control" value="{{Date::current()}}">
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>



                                        </div>

                                    </div>



                                        <hr>

                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <button type="submit" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> FATURAYI KAYDET</span></button>
                                            </div>

                                           

                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                        @Form::close()

                        <script>
                        // Function to calculate and update the total
                        function calculateTotal(rowId) {
                            // Get values from respective input fields
                            var miktar = parseFloat(document.getElementById('miktar_' + rowId).value);
                            var fiyat = parseFloat(document.getElementById('fiyat_' + rowId).value);
                            var kdv = parseFloat(document.getElementById('kdv_' + rowId).value);

                            // Calculate the total amount
                            var tutar = miktar * fiyat * (1 + kdv / 100);

                            // Update the total amount field
                            document.getElementById('tutar_' + rowId).value = tutar.toFixed(2);
                        }

                        // Add event listeners to inputs for automatic calculation
                        document.addEventListener('input', function(event) {
                            var element = event.target;
                            // Check if the input element belongs to the rows
                            if (element.classList.contains('miktar') || element.classList.contains('fiyat') || element.classList.contains('kdv')) {
                                var rowId = element.closest('tr').id.substr(3); // Extract row id from 'rowX'
                                calculateTotal(rowId);
                            }
                        });

                    </script>

                        

                    </div>

                </div>
            </section>

            


        </div>
    </div>
</div>