<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Sipariş Durumları</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('Ayarlar')}}">Ayarlar</a>
                                <li class="breadcrumb-item"><a href="#">Sipariş Durumları</a>
                                </li>
                                <li class="breadcrumb-item"><span id="resultJS"></span></li>
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
                        <div class="card brdt-navy">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Sipariş Durumları</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <button class="dt-button create-new btn btn-primary" tabindex="0" data-bs-toggle="modal" data-bs-target="#modals-add"><span><i data-feather="plus"></i>EKLE</span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ Redirect::select('bilgi',true) }}
                                <div class="table-responsive table-responsive-sm table-responsive-md table-responsive-xl">
                                    <table class="table table-hover  table-bordered ">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sıra</th>
                                            <th>Adı</th>
                                            <th>Uyarı Rengi</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                            <tfoot id="sort">
                                        @foreach($siparisDurumlari as $sd)
                                        <tr id="row-{{$sd->id}}">
                                            <td>{{$sd->id}}</td>
                                            <td>{{$sd->sira}}</td>
                                            <td>{{$sd->adi}}</td>
                                            <td><span class="badge bg-{{$sd->uyari}}"> {{$sd->uyari}}</span></td>

                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-warning btn-sm"  data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$sd->id}}" data-action="siparisDurumDuzenle">
                                                        <i data-feather="edit-2" class="me-50"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm" onclick="deleteAction('{{$sd->id}}','{{URL::site('Ayarlar/ajax')}}','siparisDurumSil')">
                                                        <i data-feather="trash" class="me-50"></i>
                                                    </a>
                                                </div>
                                               
                                            </td>
                                        </tr>
                                        @endforeach
                                            </tfoot>
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
                            <h4 class="modal-title">Sipariş Durumu Düzenle</h4>
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
                            <h1 class="text-center mb-1" id="addNewCardTitle">Sipariş Durumu</h1>

                                @Form::csrf()->action('ayarlar/siparisDurumEkle')->open('oyForm',['class'=>'row gy-1 gx-2 mt-75'])
                                <div class="col-12">
                                    <label class="form-label" for="modalAddCardNumber">Adı</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('adi')->placeholder('Başlık Giriniz')->text('adi','',['class'=>'form-control'])
                                    </div>
                                </div>

                            <div class="col-12">
                                <label class="form-label" for="sira">Sıra</label>
                                <div class="input-group input-group-merge">
                                    @Form::vRequired()->id('sira')->placeholder('Sıra')->text('sira','',['class'=>'form-control'])
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="uyari">Uyari Rengi</label>
                                <select name="uyari" id="uyari" class="form-control">
                                    <option value="primary">Mor</option>
                                    <option value="warning">Sarı</option>
                                    <option value="success">Yeşil</option>
                                    <option value="info">Mavi</option>
                                    <option value="secondary">Gri</option>
                                    <option value="danger">Kırmızı</option>
                                    <option value="dark">Siyah</option>
                                </select>
                            </div>


                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-1 mt-1">Kaydet</button>
                                    <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                                        Vazgeç
                                    </button>
                                </div>
                            @Form::close()


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->