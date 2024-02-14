<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Teklif Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('teklifler')}}">Teklifler</a>
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
                        @Form::csrf()->action("teklifler/teklifKaydet")->open('submitForm',['class'=>'form form-horizontal'])

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-lg-2 col-md-12 col-12">
                                <div class="card brdt-info">
                                    <div class="card-header">
                                        <h4 class="card-title">Teklif Detayları</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="cari">Müşteri</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="form-select select2 form-select-sm" name="musteri" id="musteri">
                                                        <option value="">Seciniz</option>
                                                        @foreach($musteriler as $cari)
                                                        <option value="{{ $cari->id }}">{{$cari->adi}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="belge_no">Teklif No</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-sm" name="belge_no" id="belge_no" value="{{$detay->belge_no}}" placeholder="Fatura No" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="odeme_yontemi">Ödeme Yöntemi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" required name="odeme_yontemi" id="odeme_yontemi">

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
                                                <div class="col-sm-12">
                                                    <label class="col-form-label" for="durum">Durum</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" required name="durum" id="durum">

                                                        <option  value="0" selected>Değerlendirmede</option>
                                                        <option  value="1">Siparişe Dönüştü</option>
                                                        <option  value="2">Siparişe Dönüşmedi</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                      
                                       
                                        <div class="col-12">
                                            <label class="form-label" for="aciklama">Teklif Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('aciklama')->placeholder('Teklif Notu')->textarea('aciklama','',['class'=>'form-control'])
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-10 col-md-12 col-12">
                                <div class="card brdt-info">
                                    <div class="card-header">
                                        <h4 class="card-title">Teklif Detayları</h4>
                                    </div>
                                    <hr>
                                        <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                <tr>
                                                    <th>Ürün Adı</th>
                                                    <th>Açıklama</th>
                                                    <th>Adet</th>
                                                    <th>Birim Fiyat</th>
                                                    <th>Kdv</th>
                                                    <th>Toplam Tutar</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="addDataTable">

                                                    <tr id="row0">

                                                        <td>
                                                            <input style="min-width: 300px" type="text" name="urun[]" id="urun" class="form-control">
                                                        </td>

                                                        <td style="min-width: 400px">
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
                                                    <th colspan="4"><button type="button" id="addRow" class="btn btn-primary"><i data-feather="plus"></i> Ekle</button></th>

                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                              
                                                
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td>Toplam</td>
                                                    <td class="genel_toplam">0 ₺</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>


                                        <hr>

                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <button type="submit" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> TEKLİF KAYDET</span></button>
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