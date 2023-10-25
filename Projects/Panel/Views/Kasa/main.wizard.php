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
                                <li class="breadcrumb-item active">Kasa Hesapları
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <a  class="btn btn-primary waves-effect waves-float waves-light mb-2" data-bs-toggle="modal" data-bs-target="#hesapEkle">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Yeni Hesap Ekle</span>
                    </a>
                    <a  class="btn btn-secondary waves-effect waves-float waves-light mb-2" href="{{URL::site('kasa/tumKayitlar')}}">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Kasa Defteri</span>
                    </a>

                    <div class="row">
                        {{ Redirect::select('bilgi',true) }}

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-transaction">
                                <div class="card-header">
                                    <h4 class="card-title">Kasa Hesapları</h4>
                                </div>
                                <div class="card-body">
                                    @foreach($kasaHesaplari as $kh)
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <a class="avatar bg-light-primary rounded float-start"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$kh->id}}" data-action="kasaHesapBilgi">
                                                <div class="avatar-content">
                                                    <i data-feather="pocket" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </a>
                                            <div class="transaction-percentage">
                                                <h6 class="transaction-title">{{$kh->adi}}</h6>
                                                <small></small>
                                            </div>
                                        </div>
                                        <a class="fw-bolder text-danger" href="{{URL::site("kasa/kayitlar/")}}{{$kh->id}}">₺ {{$kh->tutar}} -></a>
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-transaction">
                                <div class="card-header">
                                    <h4 class="card-title">Banka Hesapları</h4>
                                </div>
                                <div class="card-body">
                                    @foreach($bankaHesaplari as $bh)
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <a class="avatar bg-light-primary rounded float-start"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$bh->id}}" data-action="kasaHesapBilgi">
                                                <div class="avatar-content">
                                                    <i data-feather="check" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </a>
                                            <div class="transaction-percentage">
                                                <h6 class="transaction-title">{{$bh->adi}}</h6>
                                                <small></small>
                                            </div>
                                        </div>
                                        <a class="fw-bolder text-danger" href="{{URL::site("kasa/kayitlar/")}}{{$bh->id}}">₺ {{$bh->tutar}}</a>
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-transaction">
                                <div class="card-header">
                                    <h4 class="card-title">Kredi Kartı Hesapları</h4>
                                </div>
                                <div class="card-body">
                                    @foreach($kkartiHesaplari as $kkh)
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <a class="avatar bg-light-warning rounded float-start"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$kkh->id}}" data-action="kasaHesapBilgi">
                                                <div class="avatar-content">
                                                    <i data-feather="credit-card" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </a>
                                            <div class="transaction-percentage">
                                                <h6 class="transaction-title">{{$kkh->adi}}</h6>
                                                <small></small>
                                            </div>
                                        </div>
                                        <a class="fw-bolder text-danger" href="{{URL::site("kasa/kayitlar/")}}{{$kkh->id}}">₺ {{$kkh->tutar}}</a>
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-transaction">
                                <div class="card-header">
                                    <h4 class="card-title">Pos Hesapları</h4>
                                </div>
                                <div class="card-body">
                                    @foreach($posHesaplari as $ph)
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <a class="avatar bg-light-success rounded float-start"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$ph->id}}" data-action="kasaHesapBilgi">
                                                <div class="avatar-content">
                                                    <i data-feather="credit-card" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </a>
                                            <div class="transaction-percentage">
                                                <h6 class="transaction-title">{{$ph->adi}}</h6>
                                                <small></small>
                                            </div>
                                        </div>
                                        <a class="fw-bolder text-danger" href="{{URL::site("kasa/kayitlar/")}}{{$ph->id}}">₺ {{$ph->tutar}}</a>
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-transaction">
                                <div class="card-header">
                                    <h4 class="card-title">Veresiye Hesapları</h4>
                                </div>
                                <div class="card-body">
                                    @foreach($veresiyeHesaplari as $vh)
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <a class="avatar bg-light-info rounded float-start"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$vh->id}}" data-action="kasaHesapBilgi">
                                                <div class="avatar-content">
                                                    <i data-feather="trending-down" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </a>
                                            <div class="transaction-percentage">
                                                <h6 class="transaction-title">{{$vh->adi}}</h6>
                                                <small></small>
                                            </div>
                                        </div>
                                        <a class="fw-bolder text-danger" href="{{URL::site("kasa/kayitlar/")}}{{$vh->id}}">₺ {{$vh->tutar}}</a>
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card card-transaction">
                                <div class="card-header">
                                    <h4 class="card-title">Diğer Hesaplar</h4>
                                </div>
                                <div class="card-body">
                                    @foreach($digerHesaplar as $dh)
                                    <div class="transaction-item">
                                        <div class="d-flex">
                                            <a class="avatar bg-light-dark rounded float-start"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$dh->id}}" data-action="kasaHesapBilgi">
                                                <div class="avatar-content">
                                                    <i data-feather="dollar-sign" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </a>
                                            <div class="transaction-percentage">
                                                <h6 class="transaction-title">{{$dh->adi}}</h6>
                                                <small></small>
                                            </div>
                                        </div>
                                        <a class="fw-bolder text-danger" href="{{URL::site("kasa/kayitlar/")}}{{$dh->id}}">₺ {{$dh->tutar}}</a>
                                    </div>

                                    @endforeach

                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <!-- Hoverable rows end -->



            <div class="modal fade" id="hesapEkle"  tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{URL::site('kasa/hesapEkle')}}" method="post">
                        <div class="modal-header">
                            <h4 class="modal-title">Hesap Ekle</h4>
                        </div>
                        <div class="modal-body">


                            <div class="col-12">
                                <label for="adi" class="form-label">Adı:</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="adi" required id="adi" placeholder="Adı" value="">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Hesap No:</label>
                                <div class="col-sm-12">
                                    <input type="text" name="hesap_no" class="form-control pull-right" placeholder="Hesap Numarası" >
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="col-12">
                                <label class="form-label">Hesap Türü</label>
                                <div class="date col-sm-12">
                                    <select class="form-control" name="tur" required >
                                        <option value="">--Seçiniz--</option>
                                        <option value="1">Kasa Hesabı</option>
                                        <option value="2">Banka Hesabı</option>
                                        <option value="3">Pos Hesabı</option>
                                        <option value="4">Kredi Kartı Hesabı</option>
                                        <option value="5">Veresiye Hesabı</option>
                                        <option value="6">Diğer Hesaplar</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>

                            <div class="col-12">
                                <label for="aciklama" class="form-label">Açıklama</label>
                                <div class="col-sm-12">
                                    <textarea name="aciklama" class="form-control" placeholder="Açıklama"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Durum</label>
                                <div class="date col-sm-12">
                                    <select class="form-control" name="durum">
                                        <option value="1">Aktif</option>
                                        <option value="0">Pasif</option>
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

        </div>
    </div>
</div>
<!-- END: Content-->



<div class="modal fade" id="openModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center mb-1" id="modalTitle">Kasa Hesap Bilgi</h1>

                <div class="fetched-data"></div>


                <div class="col-12 text-center">
                    <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                        Kapat
                    </button>
                </div>
                @Form::close()
            </div>
        </div>
    </div>
</div>



<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
