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
                                <li class="breadcrumb-item"><a href="{{URL::site('urun/gruplar')}}">Ürün Grupları</a>
                                <li class="breadcrumb-item active">{{$detay->adi==""?"Grup Oluştur":$detay->adi." Grubu Düzenle"}}
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
                                    <span class="fw-bold">Grup Detayları</span>
                                </a>
                            </li>
                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::site('urun/gruplar')}}">
                                    <i data-feather="bell" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold">Tüm Gruplar</span>
                                </a>
                            </li>

                        </ul>
                        @Form::csrf()->action($action)->open('submitForm',['class'=>'form form-horizontal'])


                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-12 col-12">
                                <div class="card brdt-warning">
                                    <div class="card-header">
                                        <h4 class="card-title">Grup Bilgileri</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-12">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label" for="adi">Grup Adı</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            @Form::vRequired()->id('adi')->placeholder('Grup Adı')->text('adi',$detay->adi,['class'=>'form-control'])
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-1 row">
                                                        <div class="col-md-4">
                                                            <div class="col-sm-12">
                                                                <label class="col-form-label" for="gsira">Ürün Türü</label>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <select class="form-select" required name="turu" id="turu">
                                                                    <option value="">--Seçiniz--</option>
                                                                    <option value="domain" {{$detay->tur=='domain'?'selected':''}}>Domain</option>
                                                                    <option value="hosting" {{$detay->tur=='hosting'?'selected':''}}>Hosting</option>
                                                                    <option value="fiziksel" {{$detay->tur=='fiziksel'?'selected':''}}>Fiziksel Ürün</option>
                                                                    <option value="indirilebilir" {{$detay->tur=='indirilebilir'?'selected':''}}>İndirilebilir</option>
                                                                    <option value="sertifika" {{$detay->tur=='sertifika'?'selected':''}}>Sertifika</option>
                                                                    <option value="lisans" {{$detay->tur=='lisans'?'selected':''}}>Lisans</option>
                                                                    <option value="diger" {{$detay->tur=='diger'?'selected':''}}>Diğer</option>
                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="col-sm-12">
                                                                <label class="col-form-label" for="gsira">Ürün Görünümü</label>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <select class="form-select" required name="urun_gorunumu" id="urun_gorunumu">
                                                                    <option value="">--Seçiniz--</option>
                                                                    <option value="list" {{$detay->urun_gorunumu=='list'?'selected':''}}>Liste Görünümü</option>
                                                                    <option value="block" {{$detay->urun_gorunumu=='block'?'selected':''}}>Dikey Blok</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="col-sm-12">
                                                                <label class="col-form-label" for="gsira">Sıralama</label>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                @Form::vRequired()->id('gsira')->placeholder('Grup Sıralaması')->number('gsira',$detay->sira,['class'=>'form-control'])
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row custom-options-checkable g-1">
                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" value="0" name="gdurum" id="gdurum1" {{$detay->durum=='0'?'checked':''}} />
                                                        <label class="custom-option-item p-1" for="gdurum1">
                                                        <span class="d-flex justify-content-between flex-wrap mb-50">
                                                            <span class="fw-bolder">Pasif</span>

                                                        </span>
                                                            <small class="d-block">Satış Listesinde Görünmez</small>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input class="custom-option-item-check" type="radio" name="gdurum" id="gdurum2" value="1" {{$detay->durum=='1'?'checked':''}} />
                                                        <label class="custom-option-item p-1" for="gdurum2">
                                                        <span class="d-flex justify-content-between flex-wrap mb-50">
                                                            <span class="fw-bolder">Aktif</span>

                                                        </span>
                                                            <small class="d-block">Satış Listesinde Görünür</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-12">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label " id="detay" for="detay">Açıklama</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            @Form::id('summernote')->placeholder('Açıklama')->textarea('aciklama',$detay->aciklama,['class'=>'form-control'])

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    @Form::hidden('id',$detay->id,['class'=>'form-control'])
                                    <div class="table-responsive">
                                        <table class="table table-hover table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Sıra</th>
                                                    <th>Tür</th>
                                                    <th>Başlık</th>
                                                    <th>Gösterim Zamanı</th>
                                                    <th>Yer</th>
                                                    <th>Zorunlu</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                                <tbody id="addDataTableOptionProduct">
                                                    {[ $r = 0; ]}
                                                    @foreach($ozellikler as $ozellik)

                                                        <tr id="row-{{$ozellik->id}}">
                                                            <input type="hidden" name="oid[]" id="oid" value="{{$ozellik->id}}" class="form-control">
                                                            <td style="max-width: 100px">
                                                                <input type="text" name="sira[]" id="sira" value="{{$ozellik->sira}}" class="form-control">
                                                            </td>
                                                            <td>
                                                                <select class="form-select" name="tur[]" id="tur">
                                                                    <option value="">--Seçiniz--</option>
                                                                    <option value="text" {{$ozellik->tur=='text'?'selected':''}}>Metin</option>
                                                                    <option value="file" {{$ozellik->tur=='file'?'selected':''}}>Dosya</option>
                                                                    <option value="image" {{$ozellik->tur=='image'?'selected':''}}>Resim</option>
                                                                    <option value="link" {{$ozellik->tur=='link'?'selected':''}}>URL</option>
                                                                    <option value="icon" {{$ozellik->tur=='icon'?'selected':''}}>İcon</option>
                                                                    <option value="code" {{$ozellik->tur=='code'?'selected':''}}>Kod</option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="text" name="baslik[]" id="baslik" value="{{$ozellik->baslik}}" class="form-control">
                                                            </td>

                                                            <td>
                                                                <select class="form-select" name="durum[]" id="durum">
                                                                    <option value="0" {{$ozellik->durum=='0'?'selected':''}}>Her Zaman</option>
                                                                    <option value="1" {{$ozellik->durum=='1'?'selected':''}}>Sipariş Onay Sonrası</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select" name="yer[]" id="yer">
                                                                    <option value="0" {{$ozellik->yer=='0'?'selected':''}}>Listeleme</option>
                                                                    <option value="1" {{$ozellik->yer=='1'?'selected':''}}>Ürün Detay</option>
                                                                    <option value="2" {{$ozellik->yer=='2'?'selected':''}}>İkiside</option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <select class="form-select {{$ozellik->gereklilik=='1'?'text-danger':''}}" name="gereklilik[]" id="gereklilik">
                                                                    <option style="color: #ea0c0c" value="1" {{$ozellik->gereklilik=='1'?'selected':''}}>Zorunlu</option>
                                                                    <option value="0" {{$ozellik->gereklilik=='0'?'selected':''}}>Değil</option>
                                                                </select>

                                                            </td>

                                                            <td>
                                                                <a class="text-danger" onclick="deleteAction('{{$ozellik->id}}','{{URL::site('urun/ajax')}}','urunGrupOzellikSil')" data-bs-toggle="tooltip" data-bs-title="Sil">
                                                                    <i data-feather="trash" class="me-50"></i>
                                                                </a>
                                                            </td>

                                                        </tr>
                                                    {[ $r++; ]}
                                                    @endforeach



                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="7"><button type="button" id="addRowOptionProduct" class="btn btn-primary"><i data-feather="plus"></i> Ekle</button></th>
                                                    </tr>
                                                </tfoot>
                                        </table>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-sm-12">
                                            <button type="submit" style="width: 100%" class="btn btn-primary me-1">Kaydet</button>
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