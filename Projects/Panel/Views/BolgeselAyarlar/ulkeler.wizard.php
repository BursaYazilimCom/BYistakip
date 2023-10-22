<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Ülkeler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('BolgeselAyarlar')}}">Bölgesel Ayarlar</a>
                                <li class="breadcrumb-item"><a href="#">Ülkeler</a>
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
                                    <h4 class="card-title">Ülkeler</h4>
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
                                            <th>ISO 2</th>
                                            <th>ISO 3</th>
                                            <th>Posta Kodu Gereklimi</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($ulkeler['liste'] as $ulke)
                                        <tr id="row-{{$ulke->ulke_id}}">
                                            <td>{{$ulke->ulke_id}}</td>
                                            <td>{{$ulke->isim}}</td>
                                            <td>{{$ulke->iso_code_2}}</td>
                                            <td>{{$ulke->iso_code_3}}</td>
                                            <td class="table-{{$ulke->posta_kodu_gerekliligi=='1'?'success':'danger'}}">{{$ulke->posta_kodu_gerekliligi=="1"?"Gerekli":"Degil"}}</span></td>
                                            <td class="table-{{$ulke->durum=='1'?'success':'danger'}}">{{$ulke->durum=="1"?"Aktif":"Pasif"}}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{URL::site('BolgeselAyarlar/sehirler//')}}{{$ulke->ulke_id}}">
                                                            <i data-feather="list" class="me-50"></i>
                                                            <span>Şehirleri</span>
                                                        </a>
                                                        <a class="dropdown-item editButon" data-action="ulkeDuzenle">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                            <span>Düzenle</span>
                                                        </a>
                                                        <a class="dropdown-item" onclick="deleteAction('{{$ulke->ulke_id}}','{{URL::site('BolgeselAyarlar/ajax')}}','ulkeSil')">
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
                                {{$ulkeler['sayfalama']}}
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
                            <h1 class="text-center mb-1" id="modalTitle">Ülke Ekle/Güncelle</h1>

                            <!-- form -->
                                @Form::csrf()->prevent()->action('BolgeselAyarlar/ajax')->open('submitForm',['id'=>'submitForm','class'=>'row gy-1 gx-2 mt-75'])
                                <input type="hidden" name="dataAction" id="dataAction" value="ulkeEkle">
                                <input type="hidden" name="update_id" id="update_id" value="">
                                <div class="col-12">
                                    <label class="form-label" for="modalAddCardNumber">Ülke İsmi</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('isim')->placeholder('Ülke İsmi')->text('isim','',['class'=>'form-control'])
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="modalAddCardName">ISO 2</label>
                                    @Form::vRequired()->id('iso_code_2')->placeholder('ISO CODE 2')->text('iso_code_2','',['class'=>'form-control'])
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="modalAddCardName">ISO 3</label>
                                    @Form::vRequired()->id('iso_code_3')->placeholder('ISO CODE 3')->text('iso_code_3','',['class'=>'form-control'])
                                </div>

                            <div class="col-md-6">
                                <label class="form-label" for="posta_kodu_gerekliligi">Posta Kodu</label>
                                <select name="posta_kodu_gerekliligi" id="posta_kodu_gerekliligi" class="form-control">
                                    <option value="">--Seçiniz--</option>
                                    <option value="1">Gerekli</option>
                                    <option value="0">Değil</option>

                                </select>
                            </div>


                            <div class="col-md-6">
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

