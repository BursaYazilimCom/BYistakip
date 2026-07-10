<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Sipariş Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Siparişler</a>
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
                        <div class="card brdt-danger">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Siparişler</h4>
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <a href="{{URL::site('siparisler/form')}}" class="dt-button create-new btn btn-primary" tabindex="0" ><span><i data-feather="plus"></i>EKLE</span></a>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ Redirect::select('bilgi',true) }}
                                <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Müşteri</th>
                                            <th>Ödeme Yöntemi</th>
                                            <th>Ödeme Durumu</th>
                                            <th>Toplam Tutar</th>
                                            <th>Sipariş Tarihi</th>
                                            <th>Sipariş Durumu</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                        @foreach($listele['liste'] as $s)
                                        <tr id="row-{{$s->id}}">
                                            <td><a href="{{URL::site('siparisler/duzenle')}}/{{$s->id}}">{{$s->id}}</a></td>
                                            <td><a href="{{URL::site('cari/detay')}}/{{$s->cari}}"><strong>{{CariModel::cariAdi($s->cari)}}</strong></a></td>
                                            <td>{{AyarModel::odemeYontemiAdi($s->odeme_yontemi)}}</td>

                                            <td>
                                                <span class='text text-{{AyarModel::odemeDurumu($s->odeme_durumu)['renk']}}'>{{AyarModel::odemeDurumu($s->odeme_durumu)['durum']}}</span>
                                            </td>
                                            <td>{{number_format($s->genel_toplam_tutari,2)}} ₺</td>
                                            <td>{{ Date::convert($s->tarih, '{dayInMonth}.{monthInYear-}.{year}')}}</td>
                                            <td>{{AyarModel::durum($s->durum)}}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">

                                                    <a href="{{URL::site('faturalar/siparis')}}/{{$s->id}}" data-bs-toggle="tooltip" title="Siparişin Faturaları" class="btn btn-info btn-sm">
                                                        <i data-feather="send" class="me-50"></i>
                                                    </a>
                                                    <a href="{{URL::site('siparisler/duzenle')}}/{{$s->id}}" data-bs-toggle="tooltip" title="Düzenle" class="btn btn-warning btn-sm">
                                                        <i data-feather="edit-2" class="me-50"></i>
                                                    </a>
                                                    <span data-bs-toggle="tooltip" title="Sil">
                                                        <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$s->id}}" data-action="siparisSil" class="btn btn-danger btn-sm">
                                                        <i data-feather="trash" class="me-50"></i>

                                                    </a>
                                                    </span>

                                                </div>


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

            <div class="modal fade" id="modals-add" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                            <h1 class="text-center mb-1" id="modalTitle">Kategori Ekle/Güncelle</h1>

                            <!-- form -->
                                @Form::csrf()->prevent()->action('Kategoriler/ajax')->open('submitForm',['id'=>'submitForm','class'=>'row gy-1 gx-2 mt-75'])
                                <input type="hidden" name="dataAction" id="dataAction" value="kategoriEkle">
                                <input type="hidden" name="update_id" id="update_id" value="">
                                <div class="col-12">
                                    <label class="form-label" for="modalAddCardNumber">Adı</label>
                                    <div class="input-group input-group-merge">
                                        @Form::vRequired()->id('adi')->placeholder('Adı')->text('adi','',['class'=>'form-control'])
                                    </div>
                                </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Başlık</label>
                                <div class="input-group input-group-merge">
                                    @Form::vRequired()->id('title')->placeholder('Başlık')->text('title','',['class'=>'form-control'])
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="modalAddCardNumber">İcon <a href="https://fontawesome.com/v5/search?o=r&m=free" target="_blank">Tıkla</a> </label>
                                <div class="input-group input-group-merge">
                                    @Form::vRequired()->id('icon')->placeholder('fas fa-layer-group')->text('icon','',['class'=>'form-control'])
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="modalAddCardNumber">Sıra</label>
                                <div class="input-group input-group-merge">
                                    @Form::vRequired()->id('sira')->placeholder('Sıra')->number('sira','',['class'=>'form-control'])
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Açıklama</label>
                                <div class="input-group input-group-merge">
                                    @Form::vRequired()->id('aciklama')->placeholder('Açıkalam Yazınız')->text('aciklama','',['class'=>'form-control'])
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="modalAddCardName">Anasayfa</label>
                                <select name="anasayfa" id="anasayfa" class="form-control">
                                    <option value="">--Seçiniz--</option>
                                    <option value="1">Göster</option>
                                    <option value="0">Gösterme</option>

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
<div class="modal fade" id="openModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center mb-1" id="modalTitle">Sipariş İşlemleri</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>

