<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Site Ayarları</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Site Ayarları
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                  
                </div>
            </div>
        </div>
        <div class="content-body">

            <!-- Hoverable rows start -->
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <form action="{{URL::site('ayarlar/guncelle')}}" method="post" enctype="multipart/form-data">
                    <div class="card brdt-navy">
                        <div class="card-header">
                            <h4 class="card-title">Ayarlar</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Tüm sitenin ayarlarının kontrolü
                            </p>
                            {{ Redirect::select('bilgi',true) }}
                            
                               
                            <ul class="nav nav-tabs" role="tablist">
                            {[ $g=0; ]}
                                @foreach($ayarArray as $key=>$value)
                                    <li class="nav-item">
                                        <a class="nav-link {{$g==0?'active':''}}" id="{{$key}}-tab" data-bs-toggle="tab" href="#{{$key}}" aria-controls="{{$key}}" role="tab" {{$g==0?'aria-selected="true"':'aria-selected="false"'}}>{{$key}}</a>
                                    </li>
                                {[ $g++; ]}
                            @endforeach
                             
                            </ul>
                           
                            <div class="tab-content table-responsive-sm table-responsive-md table-responsive-xl">
                                {[$a=0;]}
                                @foreach($ayarArray as $key=>$value)
                                <div class="tab-pane {{$a==0?'active':''}}" id="{{$key}}" aria-labelledby="{{$key}}-tab" role="tabpanel">

                                    <table class="table table-hover  table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanımlama</th>
                                                <th>Değer</th>
                                                <th>Açıklama</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($ayarArray[$key] as $ayar)
                                            <tr>
                                                <td>{{$ayar->baslik}}<small></small>
                                                    <input type="hidden" name="anahtar[]" class="form-control" value="{{$ayar->anahtar}}">
                                                    <input type="hidden" name="tur[]" class="form-control" value="{{$ayar->tur}}"></td>
                                            
                                                <td>
                                                    @if($ayar->tur=="file")
                                                    <table>
                                                        <tr>
                                                            <td><img src="{{URL::site()}}../Uploads/site-img/{{$ayar->deger}}" style="max-height:50px"></td>
                                                            <td>
                                                                <input type="file" name="{{$ayar->anahtar}}" class="form-control">
                                                                <input type="hidden" name="deger[{{$ayar->anahtar}}]" class="form-control" value="{{$ayar->anahtar}}">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                

                                                    @elseif($ayar->tur=="enum")
                                                    <select name="deger[{{$ayar->anahtar}}]" class="form-select">
                                                        <option>--Seçiniz--</option>
                                                        @foreach(Json::decode($ayar->tum_degerler) as $deger)
                                                        <option value="{{$deger->value}}" {{$ayar->deger==$deger->value?"selected":""}}>{{$deger->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    @elseif($ayar->tur=="select")
                                                    <select name="deger[{{$ayar->anahtar}}]"  class="form-select">
                                                        <option>--Seçiniz--</option>
                                                        @foreach(Json::decode($ayar->tum_degerler) as $deger)
                                                        <option value="{{$deger->value}}" {{$ayar->deger==$deger->value?"selected":""}}>{{$deger->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    @elseif($ayar->tur=="sql")

                                                    <select name="deger[{{$ayar->anahtar}}]"  class="form-select">
                                                        <option>--Seçiniz--</option>
                                                        @foreach(AyarModel::sqlAyarGetir($ayar->tum_degerler) as $deger)

                                                        <option value="{{$deger->id}}" {{$ayar->deger==$deger->id?"selected":""}}>{{$deger->baslik}}</option>

                                                        @endforeach

                                                    </select>
                                                    @elseif($ayar->tur=="textarea")
                                                        <textarea name="deger[{{$ayar->anahtar}}]" class="form-control">{{$ayar->deger}}</textarea>
                                                    @else
                                                    <input type="text" name="deger[{{$ayar->anahtar}}]" class="form-control" value="{{$ayar->deger}}">
                                                    @endif


                                                </td>
                                                <td>

                                                    {{$ayar->aciklama}}

                                                </td>
                                            </tr>
                                            @endforeach 
                
                                        </tbody>
                                    </table>

                                </div>
                                {[ $a++; ]}
                                @endforeach
                                                            
                            </div>
                            
                        </div>
                        
                        <div class="card-footer"><button type="submit" class="btn btn-info">Kaydet</button></div>
                      
                    </div>
                </form>
                </div>
            </div>
            <!-- Hoverable rows end -->



        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>