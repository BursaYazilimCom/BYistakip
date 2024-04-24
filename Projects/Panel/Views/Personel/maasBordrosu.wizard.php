<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-10 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Personel Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active">Maaş Bordrosu
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-2 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">

                    <a data-bs-toggle="modal" data-bs-target="#openModal" data-id="{{$personelDetay->id}}"
                        data-action="mesaiSaatiEkle" class="btn btn-success mb-2">
                        <i data-feather="plus"></i>
                        <span class="fw-bold">Mesai Ekle</span>
                    </a>

                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- title row -->

            {{ Redirect::select('bilgi',true) }}

            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card brdt-primary">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <h2 class="page-header">
                                        <i class="fa fa-money"></i> <strong>{{$personelDetay->isim}}</strong> {{$buYil}}
                                        {{AyarModel::ay((int)$buAy)}} Ayı Bordrosu
                                    </h2>
                                </div>

                                <div class="col-md-6">
                                    <form class="form-horizontal" method="post"
                                        action="{{URL::site('personel/maasBordrosu/')}}{{$personelDetay->id}}">
                                        <div class="row">
                                            <label for="username" class="col-sm-1 control-label">Yıl</label>
                                            <div class="col-sm-3">
                                                <select name="yil" class="form-control">
                                                    <option value="2023" {{$buYil=="2023" ?"selected":""}}>2023</option>
                                                    <option value="2024" {{$buYil=="2024" ?"selected":""}}>2024</option>
                                                    <option value="2025" {{$buYil=="2025" ?"selected":""}}>2025</option>
                                                    <option value="2026" {{$buYil=="2026" ?"selected":""}}>2026</option>
                                                    <option value="2027" {{$buYil=="2027" ?"selected":""}}>2027</option>
                                                    <option value="2028" {{$buYil=="2028" ?"selected":""}}>2028</option>
                                                </select>
                                            </div>

                                            <label for="username" class="col-sm-1 control-label">Ay</label>
                                            <div class="col-sm-2">
                                                <select name="ay" class="form-control">
                                                    <option value="01" {{$buAy=="01" ?"selected":""}}>Ocak</option>
                                                    <option value="02" {{$buAy=="02" ?"selected":""}}>Şubat</option>
                                                    <option value="03" {{$buAy=="03" ?"selected":""}}>Mart</option>
                                                    <option value="04" {{$buAy=="04" ?"selected":""}}>Nisan</option>
                                                    <option value="05" {{$buAy=="05" ?"selected":""}}>Mayıs</option>
                                                    <option value="06" {{$buAy=="06" ?"selected":""}}>Haziran</option>
                                                    <option value="07" {{$buAy=="07" ?"selected":""}}>Temmuz</option>
                                                    <option value="08" {{$buAy=="08" ?"selected":""}}>Ağustos</option>
                                                    <option value="09" {{$buAy=="09" ?"selected":""}}>Eylül</option>
                                                    <option value="10" {{$buAy=="10" ?"selected":""}}>Ekim</option>
                                                    <option value="11" {{$buAy=="11" ?"selected":""}}>Kasım</option>
                                                    <option value="12" {{$buAy=="12" ?"selected":""}}>Aralık</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-1  ">
                                                <button type="submit" class="btn btn-primary">Göster</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-8 invoice-col">
                                    İşveren
                                    <address>
                                        <strong>{{AyarModel::defaultAyarlar('firmaAdi')}}</strong><br>
                                        {{AyarModel::defaultAyarlar('firmaAdresi')}}<br>
                                        Email: {{AyarModel::defaultAyarlar('iletisimEposta')}}
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">

                                    <b>Personel:</b> {{$personelDetay->isim}}<br>
                                    <b>Toplam Çalışma:</b> {{$toplamcalisma}} Saat<br>
                                    <b>Maaş::</b> {{$toplamMaas}} ₺<br>
                                </div>
                            </div>


                            <!-- info row -->

            
                            <style>
                                .bordro>thead>tr>th,
                                .bordro>tbody>tr>td {
                                    border: 1px solid #dbdbdb;
                                }
                                .table thead tr th{
                                    padding: 10px 3px !important;
                                }
                                .table tbody tr td{
                                    padding: 10px 3px !important;
                                }
                            </style>

                            <div class="row">
                                <div class="col-sm-12 table-responsive">
                                    <table class="bordro table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th colspan="14">{{AyarModel::ay((int)$buAy)}} {{$buYil}}</th>
                                            </tr>
                                            <tr>
                                                <th>Adı Soyadı<br>TC Kimlik</th>
                                                <th>Ücret Şekli</th>
                                                <th>İşe Giriş Tarihi</th>
                                                <th>Çalışma Günü</th>
                                                <th>Hafta Tatili</th>
                                                <th>Genel Tatil</th>
                                                <th>Ücretli İzin</th>
                                                <th>Ücretsiz İzin</th>
                                                <th>Raporlu</th>
                                                <th>Yıllık İzin</th>
                                                <th>Ücretli Gün. S.</th>
                                                <th>Aylık <br>Gün/Saat</th>
                                                <th>Günlük Ücret</th>
                                                <th>Fazla Mesai Saat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$personelDetay->isim}}<br>{{$personelDetay->tc_kimlik}}</td>
                                                <td>Aylık</td>
                                                <td>{{Date::convert($personelDetay->ise_giris, 'd.m.Y')}}</td>
                                                <td>{{$kayitTuruSayi['N']}}</td>
                                                <td>{{$kayitTuruSayi['HT']}}</td>
                                                <td>{{$kayitTuruSayi['T']}}</td>
                                                <td>{{$kayitTuruSayi['I']}}</td>
                                                <td>{{$kayitTuruSayi['UI']}}</td>
                                                <td>{{$kayitTuruSayi['R']}}</td>
                                                <td>{{$kayitTuruSayi['YI']}}</td>
                                                <td>{{$ayKacGunCekiyor}}</td>
                                                <td>30<br>225</td>
                                                <td>{{number_format($personelDetay->maas/30,2)}}</td>
                                                <td>{{$fazlaMesai}}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="bordro table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th colspan="31">{{AyarModel::ay((int)$buAy)}} {{$buYil}}</th>
                                            </tr>
                                            <tr>
                                                @for($gun=1;$gun<=count($gunler);$gun++) 
                                                <th>{{$gun}}</th>
                                                    @endfor
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @for($gun=1;$gun<=count($gunler);$gun++) 
                                                    @if($gun<10) 
                                                    {[ $gun="0" .$gun; ]} 
                                                    @endif 

                                                    <td>{{$gunler[$gun]['kayitTuru']}}</td>

                                                @endfor
                                            </tr>
                                            <tr>
                                                @for($gun=1;$gun<=count($gunler);$gun++) 
                                                    @if($gun<10) 
                                                    {[ $gun="0" .$gun; ]} 
                                                    @endif 
                                                    {[
                                                        $toplamSaat=$gunler[$gun]['normalCalismaSaati']+$gunler[$gun]['fazlaMesaiSaati']; 
                                                        ]}
                                                        <td>
                                                        @if($toplamSaat>9)
                                                        <strong>{{$toplamSaat}}</strong>
                                                        @elseif($toplamSaat<9) 
                                                        <span class="text-danger">{{$toplamSaat}}</span>
                                                            @else
                                                            {{$toplamSaat}}
                                                            @endif
                                                        </td>
                                                @endfor



                                            </tr>

                                        </tbody>



                                    </table>

                                </div>

                                <!-- /.col -->

                            </div>

                            <div class="row mt-1">

                                    <div class="col-sm-6">
                                        <div class="alert alert-dark" role="alert">
                                            <h4 class="alert-heading">Tanımlamalar</h4>
                                            <div class="alert-body">
                                            <strong>N</strong>: normal çalışma, <strong>HT</strong>: Hafta Tatili, <strong>R</strong>:
                                            Raporlu, <strong>I</strong>: Ücretli izinli,<br> <strong>UI</strong>: Ücretsiz izin,
                                            <strong>T</strong>: Resmi Tatil
                                            , <strong>SI</strong>: Saatlik izinli, <strong>YI</strong>: Yıllık izin
                                            </div>
                                        </div>

                                        <div class="alert alert-warning" role="alert">
                                            <h4 class="alert-heading">Bilgilendirme</h4>
                                            <div class="alert-body">
                                                Maaş hesaplaması yaparken yasa gereği firmalar, çalışanların haftasonu tatillerinin de ödemesini
                                                yapmaktadır.<br>
                                                Eğer hafta içi çalışma saatleriniz; 45 saatten az ise yasal olarak haftasonu tatilini haketmiş
                                                olmayacağınız için ilgili haftanin haftasonu hakedişi olan 7,5 saat'lik ücretiniz ödenmez!
                                            </div>
                                        </div>
                                      
                                    </div>

                                    <div class="col-sm-6">
                                        <p class="lead">Toplamlar</p>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th style="width:50%">Normal Çalışma Saati:</th>
                                                    <td>{{$normalCalisma}} saat</td>
                                                </tr>
                                                <tr>
                                                    <th>Fazla Mesai Saati</th>
                                                    <td>{{$fazlaMesai}} Saat</td>
                                                </tr>
                                                <tr>
                                                    <th>Eksik Çalışma Saati:</th>
                                                    <td>{{$eksikCalismaSuresi}} Saat</td>
                                                </tr>
                                                <tr>
                                                    <th>Haftasonu Hakediş saati:</th>
                                                    <td>{{$haftaSonuHakedis}} Saat</td>
                                                </tr>
                                                <tr>
                                                    <th>Toplam Maaş:</th>
                                                    <td>{{$toplamMaas}} ₺</td>
                                                </tr>
                                                <tr>
                                                    <th>Aylık Yemek Ücreti:</th>
                                                    <td>{{$aylikYolYemekHakedisi->yemekAdet*AyarModel::defaultAyarlar('gunluk_yemek_ucreti')}}
                                                        ₺</td>
                                                </tr>
                                                <tr>
                                                    <th>Aylık Yol Ücreti:</th>
                                                    <td>{{($aylikYolYemekHakedisi->iseGelisYol+$aylikYolYemekHakedisi->istenCikisYol)*AyarModel::defaultAyarlar('yol_ucreti')}}
                                                        ₺</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>




                        </div>
                    </div>
                </div>
            </div>

            

            

            <!-- /.row -->



            

            <!-- /.row -->



            <!-- this row will not appear when printing -->

            <div class="row no-print">

                <div class="col-xs-12">

                    <a href="javascript:;"
                        onclick="$('.yazdirilacak').printThis({ignoreElements: '.exclude-from-print'})"
                        class="btn btn-default"><i class="fa fa-print"></i> Print</a>



                </div>

            </div>



        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<div class="modal fade" id="openModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1 class="text-center mb-1" id="modalTitle">Mesai İşlemleri</h1>

                <div class="fetched-data"></div>

            </div>
        </div>
    </div>
</div>