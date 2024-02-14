<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Kullanıcı Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Kullanıcılar
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">

                        <a  href="{{URL::site('Personel/form')}}" class="btn btn-primary">
                            <i data-feather="plus" ></i>Personel Ekle
                        </a>

                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card brdt-primary">
                        <div class="card-header">
                            <h4 class="card-title">Kullanıcılar</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Site yönetimi ile yetkili kişilerin liste ve kontrolü.
                            </p>
                            {{ Redirect::select('bilgi',true) }}
                        </div>
                        <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                            <table class="table table-hover  table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>İsim</th>
                                    <th>Kullanıcı adı</th>
                                    <th>Ünvan</th>
                                    <th>E-Posta</th>
                                    <th>Telefon</th>
                                    <th>Notlar</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($personeller['liste'] as $personel)
                                <tr id="row-{{$personel->id}}">
                                    <td>{{$personel->id}}</td>
                                    <td>{{$personel->isim}}</td>
                                    <td>{{$personel->username}}</td>
                                    <td>{{$personel->unvan}}</td>
                                    <td>{{$personel->email}}</td>
                                    <td>{{$personel->telefon}}</td>
                                    <td>{{$personel->notlar}}</td>
                                    <td>
                                        @if($personel->ban=="0")
                                            <span class="badge rounded-pill badge-light-primary me-1">Aktif</span>
                                        @else
                                        <span class="badge rounded-pill badge-light-warning me-1">Banlı</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Düzenle" href="{{URL::site('Personel/form/')}}{{$personel->id}}">
                                                <i data-feather="edit-2" class="me-50"></i>
                                            </a>
                                            <a class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="İzinler" href="{{URL::site('Personel/izinler/')}}{{$personel->id}}">
                                                <i data-feather="check" class="me-50"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Sil" onclick="deleteAction('{{$personel->id}}','{{URL::site('Personel/ajax')}}','personelSil')">
                                                <i data-feather="trash" class="me-50"></i>
                                            </a>
                                        </div>


                                    </td>
                                </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation">
                            {{$personeller['sayfalama']}}
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