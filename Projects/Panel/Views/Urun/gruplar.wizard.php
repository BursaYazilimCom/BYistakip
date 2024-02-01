<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0 ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Ürün Grup Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('urun')}}">Ürünler</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Ürün Grupları</a>
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
                        <div class="card">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Ürün Grupları</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <button class="dt-button create-new btn btn-primary" tabindex="0" data-bs-toggle="modal" data-bs-target="#modals-add"><span><i data-feather="plus"></i>EKLE</span></button>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sıra</th>
                                            <th>Adı</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($listele as $item)
                                        <tr id="row-{{$item->id}}">
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->sira}}</td>
                                            <td>{{$item->adi}}</td>
                                            <td class="table-{{$item->durum=='1'?'success':'danger'}}">{{$item->durum=="1"?"Aktif":"Pasif"}}</span></td>
                                            <td>

                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                     <span data-bs-toggle="tooltip" title="Düzenle">
                                                        <a class="btn btn-warning editButon" data-action="grupDuzenle">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                        </a>
                                                    </span>
                                                    <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Sil" onclick="deleteAction('{{$item->id}}','{{URL::site('urun/ajax')}}','grupSil')"><i data-feather="trash" class="me-50"></i></a>
                                                </div>

                                            </td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice repeater -->
                </div>
            </section>

            <div class="modal fade" id="modals-add" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                            <h1 class="text-center mb-1" id="modalTitle">Grup Ekle/Güncelle</h1>

                            <!-- form -->
                                @Form::csrf()->prevent()->action('Urun/ajax')->open('submitForm',['id'=>'submitForm','class'=>'row gy-1 gx-2 mt-75'])
                                <input type="hidden" name="dataAction" id="dataAction" value="grupEkle">
                                <input type="hidden" name="update_id" id="update_id" value="">
                                <div class="col-12">
                                    <label class="form-label" for="modalAddCardNumber">Adı</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('adi')->placeholder('Adı')->text('adi','',['class'=>'form-control'])
                                    </div>
                                </div>

                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Sıra</label>
                                <div class="input-group input-group-merge">
                                    @Form::vRequired()->id('sira')->placeholder('Sıra')->number('sira','',['class'=>'form-control'])
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="modalAddCardName">Durum</label>
                                <select name="durum" id="durum" class="form-control">
                                    <option value="">--Seçiniz--</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>

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

