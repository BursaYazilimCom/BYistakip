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
                                <li class="breadcrumb-item"><a href="{{URL::site('siparisler')}}">Siparişler</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Sipariş Ürünleri</a>
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
                    <form action="{{URL::site('siparisler/topluIslemler/urunler')}}" method="post">
                    <div class="col-12">
                        <div class="card brdt-danger">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">Sipariş Ürünleri</h4>
                                
                                </div>
                                <div class="dt-action-buttons text-end">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-select" name="islem">
                                                <option value="">--Toplu İşlemler--</option>
                                                <option value="islemYapildi">İşlem Yapıldı Olarak İşaretle</option>
                                                <option value="teslimEdildi">Teslim Edildi yap</option>
                                                <option value="sil">Seçilenleri Sil</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <button class="dt-button btn btn-primary" style="width: 100%;"><span><i data-feather="save"></i> İşlem Yap</span></button>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                            <div class="card-body">
                                {{ Redirect::select('bilgi',true) }}
                                <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                        <tr>
                                            <th><input class="form-check-input" type="checkbox" id="select-all" name="hepsi" value="1" /></th>
                                            <th>#</th>
                                            <th>Ürün</th>
                                            <th>Cari</th>
                                            <th>Not</th>
                                            <th>Periyod</th>
                                            <th>Başlanıç Tarihi</th>
                                            <th>Bitiş Tarihi</th>
                                            <th>Adet</th>
                                            <th>Toplam</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="addDataTable">
                                            
                                        @foreach($listele['liste'] as $s)
                                        <tr id="row-{{$s->id}}">
                                            <td><input class="form-check-input" type="checkbox" id="hepsi" name="sec[]" value="{{$s->id}}" /></td>
                                            <td><a href="{{URL::site('siparisler/urunDuzenle')}}/{{$s->id}}">{{$s->id}}</a></td>
                                            <td><a href="{{URL::site('siparisler/urunDuzenle')}}/{{$s->id}}">{{$s->urun_adi}}</a></td>
                                            <td>{{CariModel::cariAdi($s->cari)}}</td>
                                            <td>{{$s->notu}}</td>
                                            <td>{{AyarModel::odemePeriyodu($s->odeme_periyodu)}}</td>
                                            <td>{{Date::convert($s->baslangic_tarihi,'d.m.Y')}}</td>
                                            <td>{{Date::convert($s->bitis_tarihi,'d.m.Y')}}</td>
                                            <td>{{$s->adet}}</td>
                                            <td>{{number_format($s->toplam_fiyat,2)}}</td>
                                            <td>{{AyarModel::siparisDurumAdi($s->durum)}}</td>
                                            <td class="text-danger" style="font-weight: bold">
                                                @if($s->islem_gerekiyor=='1')
                                                {{$s->yapilacak_islem}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                            </form>

                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                {{$listele['sayfalama']}}
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice repeater -->
                </form>
                </div>
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->

