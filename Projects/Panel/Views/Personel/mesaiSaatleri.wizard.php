<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Personel Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Mesai Saatleri
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">

                        <a  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$personelDetay->id}}" data-action="mesaiSaatiEkle"  class="btn btn-success mb-2">
                        <i data-feather="plus"></i>
                        <span class="fw-bold">Mesai Ekle</span>
                    </a>

                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card brdt-primary">
                        <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                            <table class="table table-hover  table-bordered">
                                <thead>
                                <tr>
                                    <th>Ay</th>
                                    <th>Giriş Tarihi</th>
                                    <th>Giriş Saati</th>
                                    <th>Çıkış Tarihi</th>
                                    <th>Çıkış Saati</th>
                                    <th>Çalışma Saati</th>
                                    <th>Fazla Mesai</th>
                                    <th>Mesai Nedeni</th>
                                    <th>Notlar</th>
                                    <th>Geç Kalma</th>
                                    <th>İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($mesaiSaatleri['liste'] as $mesai)

                                    <tr id="row-{{$mesai->id}}">

                                        

                                        <td>{{AyarModel::ay((int)date("m",strtotime($mesai->giris_tarihi)))}}</td>

                                        <td>{{Date::convert($mesai->giris_tarihi, '{dayNumber0}.{monthNumber0}.{year}')}}</td>

                                        <td>{{$mesai->giris_saati}}</td>

                                        <td>{{Date::convert($mesai->cikis_tarihi, '{dayNumber0}.{monthNumber0}.{year}')}}</td>

                                        <td>{{$mesai->cikis_saati}}</td>

                                        <td>{{$mesai->gunlukCalismaSuresi/60}} Saat</td>

                                        <td>

                                            @if($mesai->fazla_mesai_dakikasi>0)

                                            {{$mesai->fazla_mesai_dakikasi}}dk. ({{$mesai->fazla_mesai_dakikasi/60}} s.)

                                            @endif

                                        </td>

                                        <td>{{$mesai->fazla_mesai_sebebi}}</td>

                                        <td>{{$mesai->gunluk_not}}</td>

                                        <td>{{$mesai->gec_gelme_durumu==0?'<span class="label label-success">Hayır</span>':'<span class="label label-danger">Evet</span> '}}</td>

                                        <td>
                                            <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Sil" onclick="deleteAction('{{$mesai->id}}','{{URL::site('Personel/ajax')}}','personelMesaiSil')">
                                                <i data-feather="trash" class="me-50"></i>
                                            </a>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation">
                            {{$mesaiSaatleri['sayfalama']}}
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

<div class="modal fade" id="openModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center mb-1" id="modalTitle">Mesai İşlemleri</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>