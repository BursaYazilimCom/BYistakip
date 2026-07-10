<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Kullanıcı İzinleri</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('personel')}}">Kullanıcılar</a>
                                <li class="breadcrumb-item"><a href="#">Kullanıcı İzinleri</a>
                                </li>
                                <li class="breadcrumb-item active">{{$detay->isim}}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-2">
                        <!-- account -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{URL::site('personel/form/')}}{{$detay->id}}">
                                <i data-feather="user" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Bilgiler</span>
                            </a>
                        </li>

                        <!-- notification -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{URL::site('personel/izinler/')}}{{$detay->id}}">
                                <i data-feather="bell" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Yetki Alanları</span>
                            </a>
                        </li>

                    </ul>

                    <!-- notifications -->
                    @Form::csrf()->action('personel/yetkiDuzenle/'.$detay->id)->open('submitForm')
                    <input type="hidden" name="id" value="{{$detay->id}}">
                    <div class="card brdt-primary">
                        {{ Redirect::select('bilgi',true) }}
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Yetki Alanları</h4>
                        </div>
                        <div class="card-body pt-2">
                            <h5 class="mb-0">
                                Kullanıcıya izin vermek istediğiniz bölümleri işaretleyebilirsiniz.
                            </h5>
                        </div>
                        <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                            <table class="table text-nowrap text-center border-bottom">
                                <thead>
                                <tr>
                                    <th class="text-start">Yetki Alanı</th>
                                    <th><i data-feather="check-circle" class="font-medium-3 me-50"></i>İzinli</th>
                                    <th> Açıklama</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($yetkiAlanlari as $ya)

                                <tr>
                                    <td class="text-start"><label class="col-sm-3 control-label" for="defaultCheck{{$ya->id}}">{{$ya->baslik}}</label></td>
                                    <td>
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" value="{{$ya->alan}}" name="yetki[]" type="checkbox" id="defaultCheck{{$ya->id}}"
                                                   @if(in_array($ya->alan,$yetkiler))
                                                    checked
                                                    @endif
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        {{$ya->aciklama}}
                                    </td>

                                </tr>

                                @endforeach


                                </tbody>
                            </table>
                        </div>
                        <div class="card-body mt-50">

                                <div class="row gy-2">
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary me-1">Kaydet</button>
                                    </div>
                                </div>

                        </div>
                    </div>
                    @Form::close()
                    <!--/ notifications -->
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->