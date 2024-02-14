<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Tedarikci Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Tedarikciler
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <a  href="{{URL::site('tedarikci/form')}}" class=" btn btn-primary" >
                            <i data-feather="plus"></i> Tedarikci Ekle
                        </a>

                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card brdt-navy">
                        <div class="card-header">
                            <h4 class="card-title">Tedarikciler</h4>
                        </div>
                        <div class="card-body">
                            {{ Redirect::select('bilgi',true) }}
                        </div>
                        <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                            <table class="table table-hover  table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Adi</th>
                                    <th>Ek Bilgiler</th>
                                    <th>İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listeleme['liste'] as $cari)
                                <tr id="row-{{$cari->id}}">
                                    <td>{{$cari->id}}</td>
                                    <td><a href="{{URL::site('tedarikci/form/')}}{{$cari->id}}"> {{$cari->adi}}</a></td>
                                    <td>{{$cari->ek_bilgiler}}</td>

                                    <td>
                                        <a class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Düzenle" href="{{URL::site('tedarikci/form/')}}{{$cari->id}}"><i data-feather="edit-2" class="me-50"></i></a>

                                        <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Sil" onclick="deleteAction('{{$cari->id}}','{{URL::site('Tedarikci/ajax')}}','tedarikciSil')"><i data-feather="trash" class="me-50"></i></a>

                                    </td>
                                </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation">
                            {{$listeleme['sayfalama']}}
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