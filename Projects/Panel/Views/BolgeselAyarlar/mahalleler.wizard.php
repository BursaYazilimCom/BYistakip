<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Mahalleler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('BolgeselAyarlar')}}">Bölgesel Ayarlar</a>
                                <li class="breadcrumb-item"><a href="#">Mahalleler</a>
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
                                    <h4 class="card-title">Mahalleler</h4>
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
                                            <th>Mahalle Adı</th>
                                            <th>Mahalle Key</th>
                                            <th>İlçe Key</th>
                                            <th>Hizmet Durumu</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($mahalleler['liste'] as $mahalle)
                                        <tr id="row-{{$mahalle->id}}">
                                            <td>{{$mahalle->id}}</td>
                                            <td>{{$mahalle->mahalle_adi}}</td>
                                            <td>{{$mahalle->mahalle_key}}</td>
                                            <td>{{$mahalle->ilce_key}}</td>

                                            <td class="table-{{$mahalle->hizmet=='1'?'success':'danger'}}">{{$mahalle->hizmet=="1"?"Veriliyor":"Verilmiyor"}}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{URL::site('BolgeselAyarlar/ilceler//')}}{{$mahalle->id}}">
                                                            <i data-feather="list" class="me-50"></i>
                                                            <span>Mahalleleri</span>
                                                        </a>
                                                        <a class="dropdown-item editButon" data-action="mahalleDuzenle">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                            <span>Düzenle</span>
                                                        </a>
                                                        <a class="dropdown-item" onclick="deleteAction('{{$mahalle->id}}','{{URL::site('BolgeselAyarlar/ajax')}}','mahalleSil')">
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
                                <hr>
                                {{$mahalleler['sayfalama']}}
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
                            <h1 class="text-center mb-1" id="modalTitle">Mahalle Ekle/Güncelle</h1>

                            <!-- form -->
                                @Form::csrf()->prevent()->action('BolgeselAyarlar/ajax')->open('submitForm',['id'=>'submitForm','class'=>'row gy-1 gx-2 mt-75'])
                                <input type="hidden" name="dataAction" id="dataAction" value="mahalleEkle">
                                <input type="hidden" name="update_id" id="update_id" value="">
                                <div class="col-12">
                                    <label class="form-label" for="mahalle_adi">Mahalle İsmi</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('mahalle_adi')->placeholder('Mahalle İsmi')->text('mahalle_adi','',['class'=>'form-control'])
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mahalle_key">Key</label>
                                    @Form::vNumber()->id('mahalle_key')->placeholder('Key')->number('mahalle_key','',['class'=>'form-control'])
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ilce_key">İlçe Key</label>
                                    <select name="ilce_key" id="ilce_key" class="form-control" required>
                                        <option value="">--Seçiniz--</option>
                                        @foreach($ilceler['liste'] as $ilce)
                                            <option value="{{$ilce->ilce_key}}">{{$ilce->ilce_adi}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="hizmet">Hizmet Veriliyor mu?</label>
                                    <select name="hizmet" id="hizmet" class="form-control">
                                        <option value="">--Seçiniz--</option>
                                        <option value="1">Veriliyor</option>
                                        <option value="0">Verilmiyor</option>

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

