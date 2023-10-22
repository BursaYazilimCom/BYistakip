<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Dil Seçenekleri</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('BolgeselAyarlar')}}">Bölgesel Ayarlar</a>
                                <li class="breadcrumb-item"><a href="#">Diller</a>
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
                                    <h4 class="card-title">Dil Seçenekleri</h4>
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
                                            <th>Başlık</th>
                                            <th>Kod</th>
                                            <th>Resim</th>
                                            <th>Sıra</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($diller as $dil)
                                        <tr id="row-{{$dil->dil_id}}">
                                            <td>{{$dil->dil_id}}</td>
                                            <td>{{$dil->baslik}}</td>
                                            <td>{{$dil->kod}}</td>
                                            <td>{{$dil->image}}</td>
                                            <td>{{$dil->sira}}</td>
                                            <td class="table-{{$dil->durum=='1'?'success':'danger'}}">{{$dil->durum=="1"?"Aktif":"Pasif"}}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item editButon" data-action="dilDuzenle">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                            <span>Düzenle</span>
                                                        </a>
                                                        <a class="dropdown-item" onclick="deleteAction('{{$dil->dil_id}}','{{URL::site('BolgeselAyarlar/ajax')}}','dilSil')">
                                                            <i data-feather="trash" class="me-50"></i>
                                                            <span>Sil</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
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
                            <h1 class="text-center mb-1" id="modalTitle">Dil Ekle/Güncelle</h1>
                            <p class="text-center">Ekleme yaparken istediğiniz bayrak listede yoksa <strong>Uploads/Flag</strong> klasörünün içerisine dosyayı yükleyiniz </p>

                            <!-- form -->
                                @Form::csrf()->prevent()->action('BolgeselAyarlar/ajax')->open('submitForm',['id'=>'submitForm','class'=>'row gy-1 gx-2 mt-75'])
                                <input type="hidden" name="dataAction" id="dataAction" value="dilEkle">
                                <input type="hidden" name="update_id" id="update_id" value="">
                                <div class="col-12">
                                    <label class="form-label" for="modalAddCardNumber">Başlık</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('baslik')->placeholder('Başlık Giriniz')->text('baslik','',['class'=>'form-control'])
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="modalAddCardName">Kod</label>
                                    @Form::vRequired()->id('kod')->placeholder('Dil Kodu - (tr-tr)')->text('kod','',['class'=>'form-control'])
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="modalAddCardName">Resim</label>

                                    <select name="image" id="image" class="form-control">
                                        <option value="">--Seçiniz--</option>
                                        @foreach($yukluDiller as $dilResim)
                                        <option value="{{$dilResim}}">{{$dilResim}}</option>
                                        @endforeach

                                    </select>

                                </div>
                            <div class="col-md-6">
                                <label class="form-label" for="modalAddCardName">Sira</label>
                                @Form::vRequired()->id('sira')->placeholder('Sıra')->text('sira','',['class'=>'form-control'])
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

