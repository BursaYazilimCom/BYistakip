<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Ödeme Yöntemleri</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('Ayarlar')}}">Ayarlar</a>
                                <li class="breadcrumb-item"><a href="#">Ödeme Yötemleri</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <section class="form-control-repeater">
                <div class="row">
                    <!-- Invoice repeater -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Ödeme Yöntemleri</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <button class="dt-button create-new btn btn-primary" tabindex="0" data-bs-toggle="modal" data-bs-target="#modals-add"><span><i data-feather="plus"></i>EKLE</span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ Redirect::select('bilgi',true) }}
                                <div class="table-responsive">
                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Başlık</th>
                                            <th>Kasa Hesabı</th>
                                            <th>Entegrasyon Bilgileri</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($odemeYontemleri as $oy)
                                        <tr id="row-{{$oy->id}}">
                                            <td>{{$oy->id}}</td>
                                            <td>{{$oy->baslik}}</td>
                                            <td>{{KasaModel::hesapAdi($oy->kasa_hesabi)}}</td>
                                            <td>{[

                                                $data = Json::decode($oy->entegrasyon_bilgileri);

                                                if (!empty($data) && is_array($data)) {
                                                foreach ($data as $item) {
                                                echo $item->key . ": " . $item->value . "<br>";
                                                }
                                                }

                                                ]}</td>
                                            <td>
                                                @if($oy->durum=="1")
                                                <span class="badge rounded-pill badge-light-primary me-1">Aktif</span>
                                                @else
                                                <span class="badge rounded-pill badge-light-warning me-1">Pasif</span>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$oy->id}}" data-action="odemeYontemiDuzenle">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                            <span>Düzenle</span>
                                                        </a>
                                                        <a class="dropdown-item" onclick="deleteAction('{{$oy->id}}','{{URL::site('Ayarlar/ajax')}}','odemeYontemiSil')">
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
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice repeater -->
                </div>
            </section>
            <div class="modal fade" id="openModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Ödeme Yöntemi Düzenle</h4>
                        </div>
                        <div class="modal-body">
                            <div class="fetched-data"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modals-add" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg"">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                            <h1 class="text-center mb-1" id="addNewCardTitle">Ödeme Yöntemi Ekle</h1>

                                @Form::csrf()->action('ayarlar/odemeYontemleriEkle')->open('oyForm',['class'=>'row gy-1 gx-2 mt-75'])
                                <div class="col-12">
                                    <label class="form-label" for="modalAddCardNumber">Başlık</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('baslik')->placeholder('Başlık Giriniz')->text('baslik','',['class'=>'form-control'])
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="modalAddCardName">Kasa Hesabı</label>
                                    <select name="kasa_hesabi" required id="kasa_hesabi" class="form-control">
                                        <option value="">--Seçiniz--</option>
                                        @foreach($kasaHesaplari as $kasa)
                                        <option value="{{$kasa->id}}">{{$kasa->adi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="col-md-12">
                                <label class="form-label" for="durum">Durum</label>
                                <select name="durum" id="durum" class="form-control">
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>
                            </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="modalAddCardName"><strong>Entegrasyon Bilgileri</strong></label><br>
                                    <small>Giriş yaparken ilk kutusa anahtar ikinci kotuya Değer giriniz</small>
                                    <br>
                                    <button type="button" class="btn btn-success" value="Satır Ekle" onclick="satirEkle()"><i data-feather="plus"></i> </button>
                                    <table id="satirEklemeTablosu">
                                        <tr>
                                            <td><input type="text" name="key[]" class="form-control" placeholder="key"></td>
                                            <td><input type="text" name="value[]" class="form-control" placeholder="value"></td>
                                            <td><button type="button" class="btn btn-danger" value="Sil" onclick="silSatir(this)">x<button</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-1 mt-1">Kaydet</button>
                                    <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                        Vazgeç
                                    </button>
                                </div>
                            @Form::close()

                            <script>
                                function satirEkle() {
                                    var tablo = document.getElementById("satirEklemeTablosu");
                                    var yeniSatir = tablo.insertRow(tablo.rows.length);
                                    var hucre1 = yeniSatir.insertCell(0);
                                    var hucre2 = yeniSatir.insertCell(1);
                                    var hucre3 = yeniSatir.insertCell(2);

                                    hucre1.innerHTML = '<input type="text" name="key[]" class="form-control" placeholder="Key">';
                                    hucre2.innerHTML = '<input type="text" name="value[]" class="form-control" placeholder="value">';
                                    hucre3.innerHTML = '<button type="button" class="btn btn-danger" value="Sil" onclick="silSatir(this)">x</button>';
                                }

                                function silSatir(button) {
                                    var satir = button.parentNode.parentNode;
                                    satir.parentNode.removeChild(satir);
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->