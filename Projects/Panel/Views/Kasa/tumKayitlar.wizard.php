<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Muhasebe Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('kasa')}}">Kasa Defteri</a>
                                </li>
                                <li class="breadcrumb-item active">Tüm Kasa Defteri Kayıtları
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                   
                      
                   
                            <a class="btn btn-info" href="{{URL::site('kasa')}}"><i class="me-1" data-feather="plus"></i><span class="align-middle">Kayıt Ekle</span></a>
                       
                  
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card brdt-pink">
                        {{ Redirect::select('bilgi',true) }}
                        <div class="card-header text-center">
                            <h4 class="card-title" style="margin: 0 auto;">
                            Gelirler: <span class="text-success">{{number_format((float)$gelirGiderToplami->gelir,2)}}</span>
                            Giderler: <span class="text-danger">{{number_format((float)$gelirGiderToplami->gider,2)}}</span>
                            Kasa Bakiyesi: <strong>{{number_format((float)$gelirGiderToplami->gelir-(float)$gelirGiderToplami->gider,2)}} ₺</strong></h4>
                        </div>
                        <div class="card-body">
                            <div class=" table-responsive-sm table-responsive-md table-responsive-xl">
                                <table class="table table-hover table-bordered">
                                    <tr>
                                        <th>Tarih</th>
                                        <th>İşlem</th>
                                        <th>Kasa</th>
                                        <th>Hesap</th>
                                        <th>Açıklama</th>
                                        <th>Gelir</th>
                                        <th>Gider</th>
                                    
                                        <th>#</th>
                                    </tr>

                                    @foreach($kayitlar['liste'] as $kayit)

                                    <tr id="row-{{$kayit->id}}">
                                        <td>{{Date::convert($kayit->tarih, '{dayNumber0}.{monthNumber0}.{year}')}}</td>
                                        <td class="text-{{$kayit->islem=='o'?'danger':'success'}}">{{$kayit->islem=="o"?"Ödeme":"Tahsilat"}}</td>
                                        <td>{{$kayit->KasaAdi}}</td>
                                        <td>{{$kayit->hesap}}</td>
                                        <td>{{$kayit->aciklama}}</td>
                                        <td class="text-success">{{$kayit->gelir=="0.0000"?"":number_format((float)$kayit->gelir,2)}}</td>
                                        <td class="text-danger">{{$kayit->gider=="0.0000"?"":number_format((float)$kayit->gider,2)}} </td>
                                 
                                        <td>
                                           
                                            <a class="btn btn-danger btn-sm" onclick="deleteAction('{{$kayit->id}}','{{URL::site('kasa/ajax')}}','kayitSil')">
                                                        <i data-feather="trash" class="me-50"></i>
                                                    </a>
                                        </td>

                                    </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>

                        <div class="card-footer">
                            <nav aria-label="Page navigation">
                                {{$kayitlar['sayfalama']}}
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