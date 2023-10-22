<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Site Ayarları</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Site Ayarları
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
                            <a class="dropdown-item" href="{{URL::site('Personel/form')}}"><i class="me-1" data-feather="plus"></i><span class="align-middle">Kullanıcı Ekle</span></a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Ayarlar</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Sitenin Tüm ayarlarının kontrolüğ
                            </p>
                            {{ Redirect::select('bilgi',true) }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover  table-bordered">
                                <thead>
                                <tr>
                                    <th>Tanımlama</th>
                                    <th>Anahtar</th>
                                    <th>Değer</th>
                                    <th>Açıklama</th>

                                </tr>
                                </thead>
                                <form action="{{URL::site('ayarlar/guncelle')}}" method="post" enctype="multipart/form-data">
                                <tbody>
                                @foreach($ayarlar as $ayar)
                                <tr>
                                    <td>{{$ayar->baslik}}<small></small>
                                        <input type="hidden" name="anahtar[]" class="form-control" value="{{$ayar->anahtar}}">
                                        <input type="hidden" name="tur[]" class="form-control" value="{{$ayar->tur}}"></td>
                                    <td>{{$ayar->anahtar}}</td>
                                    <td>
                                        @if($ayar->tur=="file")

                                        <input type="file" name="deger[{{$ayar->anahtar}}]" class="form-control">{{$ayar->deger}}

                                        @elseif($ayar->tur=="enum")
                                        <select name="deger[{{$ayar->anahtar}}]" class="form-select">
                                            <option>--Seçiniz--</option>
                                            @foreach(Json::decode($ayar->tum_degerler) as $deger)
                                            <option value="{{$deger->value}}" {{$ayar->deger==$deger->value?"selected":""}}>{{$deger->name}}</option>
                                            @endforeach
                                        </select>
                                        @elseif($ayar->tur=="select")
                                        <select name="deger[{{$ayar->anahtar}}]"  class="form-select">
                                            <option>--Seçiniz--</option>
                                            @foreach(Json::decode($ayar->tum_degerler) as $deger)
                                            <option value="{{$deger->value}}" {{$ayar->deger==$deger->value?"selected":""}}>{{$deger->name}}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <input type="text" name="deger[{{$ayar->anahtar}}]" class="form-control" value="{{$ayar->deger}}">
                                        @endif


                                    </td>
                                    <td>

                                        <input type="text" name="aciklama[{{$ayar->anahtar}}]" class="form-control" value="{{$ayar->aciklama}}">

                                    </td>
                                </tr>
                                @endforeach


                                </tbody>
                                    <tr>
                                        <td colspan="4">
                                            <button class="btn btn-gradient-primary" type="submit" style="width:100%">Kaydet</button>
                                        </td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation">

                            </nav>
                        </div>
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