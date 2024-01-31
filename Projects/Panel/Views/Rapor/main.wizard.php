<div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Rapor Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Raporlar</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                {{ Redirect::select('bilgi',true) }}
                <section id="dashboard-ecommerce">
                   

                    <div class="row match-height">
                        <!-- Company Table Card -->

                        <div class="col-lg-2 col-md-6 col-12">
                        

                                    <div class="list-group">
                                        <a href="{{URL::site('rapor')}}" class="list-group-item list-group-item-action active"><i data-feather="eye"></i> Genel Bakış </a>
                                    </div>

                            
                        </div>

                        <div class="col-lg-10 col-12">
                            <div class="row">
                              
                                <div class="col-xl-12 col-sm-12 pb-2">
                                <form action="{{URL::site('rapor/tarih')}}" method="post">
                                    <div class="row">
                                    
                                        <div class="col-xl-3 col-sm-6 col-xs-6">
                                            <small>Yıl Bazlı</small>
                                            <select class="form-control select2" name="yil" onchange="javascript:location.href = this.value;">
                                                <option value="{{URL::site('rapor/2024')}}" {{$yil=="2024"?"selected":""}}>2024</option>
                                                <option value="{{URL::site('rapor/2023')}}" {{$yil=="2023"?"selected":""}}>2023</option>
                                                <option value="{{URL::site('rapor/2022')}}" {{$yil=="2022"?"selected":""}}>2022</option>
                                               
                                            </select>
                                        </div>
                                        @if($yil!="")
                                            <div class="col-xl-2 col-sm-3 col-xs-3">
                                                <small>Ay</small>
                                                <select class="form-control select2" name="ay" onchange="javascript:location.href = this.value;">
                                                    <option value="{{URL::site('rapor')}}" {{$ay==""?"selected":""}}>--Seçiniz--</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/1" {{$ay=="1"?"selected":""}}>Ocak</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/2" {{$ay=="2"?"selected":""}}>Şubat</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/3" {{$ay=="3"?"selected":""}}>Mart</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/4" {{$ay=="4"?"selected":""}}>Nisan</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/5" {{$ay=="5"?"selected":""}}>Mayıs</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/6" {{$ay=="6"?"selected":""}}>Haziran</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/7" {{$ay=="7"?"selected":""}}>Temmuz</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/8" {{$ay=="8"?"selected":""}}>Ağustos</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/9" {{$ay=="9"?"selected":""}}>Eylül</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/10" {{$ay=="10"?"selected":""}}>Ekim</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/11" {{$ay=="11"?"selected":""}}>Kasım</option>
                                                    <option value="{{URL::site('rapor/')}}{{$yil}}/12" {{$ay=="12"?"selected":""}}>Aralık</option>
                                                
                                                </select>
                                            </div>
                                        @endif
                                        
                                            <div class="col-xl-3 col-sm-3 col-xs-3">
                                                <small>Başlangıç</small>
                                                <input type="date" name="baslangic" class="form-control">
                                            </div>
                                            <div class="col-xl-3 col-sm-3 col-xs-3">
                                                <small>Bitiş</small>
                                                <input type="date" name="bitis" class="form-control">
                                            </div>
                                            <div class="col-xl-1 col-sm-12 col-xs-12 ">
                                                
                                                <button type="submit" class="btn btn-primary mt-2">Filtrele</button>
                                            </div>
                                        
                                        
                                      
                                    </div>
                                    </form>


                                </div>
                     
                            </div>

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3>Gelir Gider Grafiği - 
                                            {{$yil!=""?$yil:""}} 
                                            @if($ay!="")
                                            {{AyarModel::ay($ay)}} Ay'ı
                                            @endif
                                             Grafiği</h3>

                                    </div>
                                    <div class="card-body">
                                            <div id="gelirGiderGrafigi"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        </div>

                    </div>
                </section>
                <!-- Dashboard Ecommerce ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    
    