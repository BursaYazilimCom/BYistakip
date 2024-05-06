<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Proje Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Projeler</a>
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
                        <div class="card brdt-info">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Projeler</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <a href="{{URL::site('projeler/form')}}" class="dt-button create-new btn btn-primary" tabindex="0" ><span><i data-feather="plus"></i>EKLE</span></a>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ Redirect::select('bilgi',true) }}
                                <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Proje Adı</th>
                                            <th>Müşteri</th>
                                            <th>Başlangıç Tarihi</th>
                                            <th>Termin Tarihi</th>
                                            <th>Bitiş Tarihi</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($listele['liste'] as $u)
                                        <tr id="row-{{$u->id}}">
                                            <td>{{$u->id}}</td>
                                            <td>{{$u->proje_adi}} <a href="{{URL::site()}}../proje/{{$u->sef}}" class="badge badge-light-info rounded-pill" target="_blank"><i data-feather="external-link" class="me-50"></i></a> </td>
                                            <td>{{CariModel::cariAdi($u->musteri)}}</td>
                                            <td>{{Date::convert($u->proje_baslangic_tarihi,'{dayInMonth}.{monthInYear-}.{year}')}}</td>
                                            <td>{{Date::convert($u->tahmini_bitis_tarihi,'{dayInMonth}.{monthInYear-}.{year}')}}</td>
                                            <td>
                                                @if($u->bitis_tarihi!="0000-00-00")

                                                    {{Date::convert($u->bitis_tarihi,'{dayInMonth}.{monthInYear-}.{year}')}}

                                                @endif
                                            </td>
                                            <td>
                                                @if($u->durum=="1")
                                                <span class="badge bg-success">Teslim Edildi</span>
                                                @else
                                                <span class="badge bg-info">Devam Ediyor</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-sm" data-bs-toggle="tooltiptooltip" title="Yol Haritası" href="{{URL::site('projeler/yolHaritasi/'.$u->id)}}"><i data-feather="activity" class="me-50"></i></a>

                                                <a class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Yapılan Çalışmalar" href="{{URL::site('projeler/yapilanlar/'.$u->id)}}"><i data-feather="tool" class="me-50"></i></a>

                                                <a class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Geri Bildirimler" href="{{URL::site('projeler/geriBildirimler/'.$u->id)}}"><i data-feather="alert-triangle" class="me-50"></i></a>

                                                <a class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" title="Proje Çalışanları" href="{{URL::site('projeler/personeller/'.$u->id)}}"><i data-feather="user" class="me-50"></i></a>

                                                <a class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Düzenle" href="{{URL::site('projeler/form/')}}{{$u->id}}"><i data-feather="edit-2" class="me-50"></i></a>

                                                <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Sil" onclick="deleteAction('{{$u->id}}','{{URL::site('projeler/ajax')}}','projeSil')"><i data-feather="trash" class="me-50"></i></a>


                                            </td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                {{$listele['sayfalama']}}
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice repeater -->
                </div>
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->

