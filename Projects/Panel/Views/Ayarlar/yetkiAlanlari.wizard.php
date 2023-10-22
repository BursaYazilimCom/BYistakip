<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Yetki Alanları</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('Ayarlar')}}">Ayarlar</a>
                                <li class="breadcrumb-item"><a href="#">Yetki Alanları</a>
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
                                    <h4 class="card-title">Yetki Alanları</h4>
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
                                            <th>Yer</th>
                                            <th>Açıklama</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($yetkiAlanlari as $ya)
                                        <tr id="row-{{$ya->id}}">
                                            <td>{{$ya->id}}</td>
                                            <td>{{$ya->baslik}}</td>
                                            <td>{{$ya->alan}}</td>
                                            <td>{{$ya->aciklama}}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{URL::site('Ayarlar/form/')}}{{$ya->id}}">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                            <span>Düzenle</span>
                                                        </a>
                                                        <a class="dropdown-item" onclick="deleteAction('{{$ya->id}}','{{URL::site('Ayarlar/ajax')}}','yetkiAlaniSil')">
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
                            <h1 class="text-center mb-1" id="addNewCardTitle">Yetki Alanı Ekle</h1>
                            <p class="text-center">Ekleme Yaparken Yer kutucuğunun URL Control dosyası tam ismi olduğuna dikkat ediniz</p>

                            <!-- form -->
                                @Form::csrf()->prevent()->action('ayarlar/ajax')->open('submitForm',['id'=>'submitForm','class'=>'row gy-1 gx-2 mt-75'])
                                <input type="hidden" name="dataAction" value="yetkiAlaniEkle">
                                <div class="col-12">
                                    <label class="form-label" for="modalAddCardNumber">Başlık</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('baslik')->placeholder('Başlık Giriniz')->text('baslik','',['class'=>'form-control'])
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="modalAddCardName">Yer</label>
                                    @Form::vRequired()->id('alan')->placeholder('Controller')->text('alan','',['class'=>'form-control'])
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="modalAddCardName">Açıklama</label>
                                    @Form::id('aciklama')->placeholder('Bilgilendirme notu')->text('aciklama','',['class'=>'form-control'])
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