<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Muhasebe Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Masraf Yönetimi
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <a class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="grid"  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="İşlemler"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{URL::site('cari/form')}}"><i class="me-1" data-feather="plus"></i><span class="align-middle">Cari Ekle</span></a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <a  class="btn btn-primary waves-effect waves-float waves-light mb-2" data-bs-toggle="modal" data-bs-target="#anaKalemEkle">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Ana Gider Grubu Ekle</span>
                    </a>
                    <a  class="btn btn-secondary waves-effect waves-float waves-light mb-2" data-bs-toggle="modal" data-bs-target="#altKalemEkle">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Alt Gider Grubu Ekle</span>
                    </a>
                    <a  class="btn btn-success waves-effect waves-float waves-light mb-2"  data-bs-toggle="modal" data-bs-target="#masrafEkle">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Gider Ekle</span>
                    </a>
                    <a  class="btn btn-warning waves-effect waves-float waves-light mb-2" href="{{URL::site('masraf/tumKayitlar')}}">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Tüm Gider Kayıtları</span>
                    </a>

                    <div class="row">
                        {{ Redirect::select('bilgi',true) }}


                    @foreach($masrafKalemleri['anaKalemler'] as $anaKalem)
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <div>
                                        <h4 class="card-title">{{$anaKalem->adi}}</h4>
                                    </div>
                                    <div class="dropdown chart-dropdown">
                                        <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item anaKalemDuzenle"  data-action="anaKalemDuzenle" data-names="{{$anaKalem->adi}}" data-color="{{$anaKalem->renk}}" data-id="{{$anaKalem->id}}" href="#">Güncelle</a>
                                            <a href="{{URL::site('masraf/kalemSil/')}}{{$anaKalem->id}}" class="dropdown-item"><i data-feather="trash" class="me-50"></i><span>Sil</span></a>

                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    @foreach($masrafKalemleri['altKalemler'] as $altKalem)
                                    @if($altKalem->ust==$anaKalem->id)
                                    <div class="btn-group" >
                                        <a  data-toggle="tooltip" title="{{$altKalem->adi}} Kayıtlarını Gör" href="{{URL::site('masraf/kayitlar/')}}{{$altKalem->id}}" class="btn btn-{{$altKalem->renk}}">{{$altKalem->adi}}</a>
                                        <button type="button" class="btn btn-{{$altKalem->renk}} dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">{{$altKalem->adi}}</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item altKalemDuzenle"  data-action="altKalemDuzenle" data-names="{{$altKalem->adi}}" data-parent="{{$anaKalem->id}}" data-color="{{$altKalem->renk}}" data-id="{{$altKalem->id}}" href="#">Güncelle</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{URL::site('masraf/kalemSil/')}}{{$altKalem->id}}">
                                                <i data-feather="trash" class="me-50"></i>
                                                <span>Sil</span>
                                            </a>
                                        </div>

                                    </div>

                                    @endif
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    @endforeach
                    </div>


                </div>
            </div>
            <!-- Hoverable rows end -->



        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<div class="modal fade" id="anaKalemEkle" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{URL::site('masraf/anaKalemEkle')}}" method="post">
                <input type="hidden" name="update_id" id="update_id" value="">
                <input type="hidden" name="dataAction" id="dataAction" value="anaKalemEkle">
                <div class="modal-header">
                    <h4 class="modal-title">Ana Masraf Grubu Ekle</h4>
                </div>
                <div class="modal-body">


                    <div class="col-12">
                        <label for="adi" class="form-label">Adı:</label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" name="adi" required id="adia" placeholder="Adı">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Renk</label>
                        <div class="input-group input-group-merge">
                            <select class="form-control" id="renk" name="renk" required >
                                <option value="success">Yeşil</option>
                                <option value="info">Açık Mavi</option>
                                <option value="primary">Koyu Mavi</option>
                                <option value="warning">Sarı</option>
                                <option value="dark">Siyah</option>
                                <option value="danger">Kırmızı</option>
                            </select>
                        </div>
                        <!-- /.input group -->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="altKalemEkle" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="false"  data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{URL::site('masraf/altKalemEkle')}}" id="altKalemEkleForm" method="post">
                <input type="hidden" name="update_id" id="update_id" value="">
                <input type="hidden" name="dataAction" id="dataAction" value="altKalemEkle">
                <div class="modal-header">
                    <h4 class="modal-title">Alt Masraf Grubu Ekle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="input-group input-group-merge">
                        <label class="form-label">Ana Kalemler</label>
                        <div class="input-group input-group-merge">
                            <select class="form-control" id="ust" name="ust" required >
                                <option value="">--Seçiniz--</option>
                                @foreach($masrafKalemleri['anaKalemler'] as $ust)
                                <option value="{{$ust->id}}">{{$ust->adi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- /.input group -->
                    </div>

                    <div class="col-12">
                        <label class="form-label">Adı:</label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" id="adis" name="adi" placeholder="Adı" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Renk</label>
                        <div class="input-group input-group-merge">
                            <select class="form-control" name="renk" id="renk" required >
                                <option value="success">Yeşil</option>
                                <option value="info">Açık Mavi</option>
                                <option value="primary">Koyu Mavi</option>
                                <option value="warning">Sarı</option>
                                <option value="dark">Siyah</option>
                                <option value="danger">Kırmızı</option>
                            </select>
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="masrafEkle" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{URL::site('masraf/masrafEkle')}}" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Masraf Ekle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-6">
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Masraf K.</label>
                                <div class="input-group input-group-merge">
                                    <select class="form-control" name="kalem" required >
                                        <option value="">--Seçiniz--</option>
                                        @foreach($masrafKalemleri['anaKalemler'] as $ustList)
                                        <optgroup label="{{$ustList->adi}}">

                                            @foreach($masrafKalemleri['altKalemler'] as $altKalemList)

                                            @if($altKalemList->ust==$ustList->id)

                                            <option value="{{$altKalemList->id}}">{{$altKalemList->adi}}</option>

                                            @endif

                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Belge No:</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" name="belge_no" id="belge_no" placeholder="Fiş / Fatura No" value="">
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
                                <label class="form-label" for="modalAddCardNumber">Ödeme:</label>
                                <div class="input-group input-group-merge">
                                    <select name="odeme_durumu" id="gizleGoster" data-name="kasalar" required class="form-control">
                                        <option value="1">Ödendi</option>
                                        <option value="0">Ödenmedi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Ödeme Hesabı:</label>
                                <div class="input-group input-group-merge">
                                    <select name="kasa" required class="form-control">
                                        <option value="0">--Seçiniz--</option>
                                        <optgroup label="Kasa Hesapları">
                                            @foreach($kasaHesaplari as $kh)
                                            <option value="{{$kh->id}}">{{$kh->adi}}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Banka Hesapları">
                                            @foreach($bankaHesaplari as $bh)
                                            <option value="{{$bh->id}}">{{$bh->adi}}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="POS Hesapları">
                                            @foreach($posHesaplari as $ph)
                                            <option value="{{$ph->id}}">{{$ph->adi}}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Kredi Kartı Hesapları">
                                            @foreach($kkartiHesaplari as $kkh)
                                            <option value="{{$kkh->id}}">{{$kkh->adi}}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Veresiye Hesapları">
                                            @foreach($veresiyeHesaplari as $vh)
                                            <option value="{{$vh->id}}">{{$vh->adi}}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Diğer Hesaplar">
                                            @foreach($digerHesaplar as $dh)
                                            <option value="{{$dh->id}}">{{$dh->adi}}</option>
                                            @endforeach
                                        </optgroup>

                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Ödeme Tarihi:Güm.Ay.Yıl</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" name="odeme_tarihi" class="form-control" placeholder="24.10.2023"onkeyup="
        var v = this.value;
        if (v.match(/^\d{2}$/) !== null) {
            this.value = v + '.';
        } else if (v.match(/^\d{2}\.\d{2}$/) !== null) {
            this.value = v + '.';
        }" maxlength="10" value="{{Date::current()}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Tutar (TL):</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" onkeyup="$(this).val($(this).val().replace(/,/g, '.'));" name="tutar" id="belge_no" placeholder="Ödenen tutar" value="">
                                </div>
                            </div>

                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>