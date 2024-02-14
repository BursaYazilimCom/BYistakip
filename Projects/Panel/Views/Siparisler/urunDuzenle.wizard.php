<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Siparişler</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{URL::site('siparisler')}}">Siparişler</a>
                                <li class="breadcrumb-item"><a href="{{URL::site('siparisler/duzenle/')}}{{$siparisDetay->id}}">#{{$siparisDetay->id}}</a>
                                <li class="breadcrumb-item active">Sipariş Ürün Düzenle
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic Horizontal form layout section start -->
            <section id="basic-horizontal-layouts">

                <div class="row">
                    <div class="col-md-12 col-12">

                        @Form::csrf()->action("siparisler/urunGuncelle/".$urunDetay->id)->open('submitForm',['class'=>'form form-horizontal'])

                        <div class="row">
                            {{ Redirect::select('bilgi',true) }}
                            <div class="col-md-6 col-12">
                                <div class="card brdt-danger">
                                    <div class="card-header">
                                        <h4 class="card-title">{{$urunDetay->urun_adi}} Detayları (#{{$urunDetay->id}})</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="cari">Müşteri</label>
                                                </div>
                                                <div class="col-sm-12">

                                                    <strong><a href="{{URL::site()}}cari/detay/{{$cariBilgi->id}}"> {{$cariBilgi->adi}}</a></strong><br> {{$cariBilgi->gsm}} - {{$cariBilgi->email}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="odeme_periyodu">Ödeme Priyodu</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                            
                                                        <select name="odeme_periyodu" class="form-select">

                                                            <option value="0" {{$urunDetay->odeme_periyodu=="0"?"selected":"";}}>Ücretsiz</option>
                                                            {[
                                                            if ($anaUrunDetay->fiyat >0) {
                                                                $selected = $urunDetay->odeme_periyodu=="T"?"selected":"";
                                                                echo '<option value="T" '.$selected.'>Tek Seferlik ('.$anaUrunDetay->fiyat.' '.$anaUrunDetay->fiyat_birim.')</option>';
                                                            }

                                                            if ($anaUrunDetay->aylik_fiyat >0) {
                                                                $selected = $urunDetay->odeme_periyodu=="A"?"selected":"";
                                                                echo '<option value="A" '.$selected.'>Aylık ('.$anaUrunDetay->aylik_fiyat.' '.$anaUrunDetay->fiyat_birim.')</option>';
                                                            }

                                                            if ($anaUrunDetay->uc_aylik_fiyat >0) {
                                                                $selected = $urunDetay->odeme_periyodu=="3A"?"selected":"";
                                                                echo '<option value="3A" '.$selected.'>3 Aylık ('.$anaUrunDetay->uc_aylik_fiyat.' '.$anaUrunDetay->fiyat_birim.')</option>';
                                                            }

                                                            if ($anaUrunDetay->alti_aylik_fiyat >0) {
                                                                $selected = $urunDetay->odeme_periyodu=="6A"?"selected":"";
                                                                echo '<option value="6A" '.$selected.'>6 Aylık ('.$anaUrunDetay->alti_aylik_fiyat.' '.$anaUrunDetay->fiyat_birim.')</option>';
                                                            }

                                                            if ($anaUrunDetay->yillik_fiyat >0) {
                                                                $selected = $urunDetay->odeme_periyodu=="Y"?"selected":"";
                                                                echo '<option value="Y" '.$selected.'>Yıllık ('.$anaUrunDetay->yillik_fiyat.' '.$anaUrunDetay->fiyat_birim.')</option>';
                                                            }

                                                            ]}
                                                             

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="odeme_yontemi">Sipariş Tarihi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        {{Date::convert($urunDetay->siparis_tarihi, '{dayInMonth}.{monthInYear-}.{year}')}}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning">
                                                <div class="alert-body">
                                                    Dikkat ! Burada yapacağınız fiyatı etkileyen değişiklikler sadece ilk satış faturasının fiyatlarını günceller. Diğer yenileme faturalarını güncellemek için fatura düzenleme sayfasını kullanın.
                                                </div>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="baslangic_tarihi">Baslangıç Tarihi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control date-picker" name="baslangic_tarihi" value="{{AyarModel::tarihGoster($urunDetay->baslangic_tarihi)}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="odeme_yontemi">Bitiş Tarihi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control date-picker" name="bitis_tarihi" value="{{AyarModel::tarihGoster($urunDetay->bitis_tarihi)}}">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="para_birimi">Sipariş Para Birimi</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        {{$urunDetay->para_birimi}}

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <label class="col-form-label" for="gecerli_kur">Sipariş Zamanı Döviz Kuru</label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        {{number_format($urunDetay->gecerli_kur,4)}} ₺
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-12">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" name="fiyat_sabitle" id="fiyat_sabitle" value="1" {{$urunDetay->fiyat_sabitle=="1"?"checked":""}} />
                                                            <label class="form-check-label" for="fiyat_sabitle">Sonraki faturalarda bu fiyatı değiştirme <br><small>Abonelikli ürünlerde ileride kesilecek faturalarda güncel ürün fiyatını değil bu faturada geçerli olan {{number_format($urunDetay->birim_fiyat,2)}} ₺ fiyatını baz alır.</small></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="birim_fiyat">Fiyat Bilgisi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td>Birim Fiyat</td>
                                                                <td style="text-align: right">{{number_format($urunDetay->birim_fiyat,2)}} ₺</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Toplam KDV (% {{$urunDetay->kdv}})</td>
                                                                <td style="text-align: right">{{number_format($urunDetay->kdv_tutari,2)}} ₺</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Genel Toplam</td>
                                                                <td style="text-align: right">{{number_format($urunDetay->toplam_fiyat,2)}} ₺</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 col-12">
                                <div class="card brdt-danger">
                                    <div class="card-header">
                                        <h4 class="card-title">{{$urunDetay->urun_adi}}</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="tedarikci">Tedarikçi</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="tedarikci" id="tedarikci">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($tedarikciler as $tedarikci)
                                                        <option  value="{{$tedarikci->id}}" {{$tedarikci->id==$urunDetay->tedarikci?'selected':''}}>{{$tedarikci->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label" for="durum">Sipariş Durumları</label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <select class="select2 form-select" name="durum" id="durum">

                                                        <option value="">--Seçiniz--</option>
                                                        @foreach($siparisDurumlari as $sd)
                                                        <option  value="{{$sd->id}}" {{$sd->id==$urunDetay->durum?'selected':''}}>{{$sd->adi}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="siparis_notu">Sipairş Notu</label>
                                            <div class="input-group input-group-merge">
                                                @Form::id('siparis_notu')->placeholder('Sipariş Notu')->textarea('siparis_notu',$urunDetay->notu,['class'=>'form-control'])
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <button type="submit" class="dt-button create-new btn btn-success"><span><i data-feather="save"></i> KAYDET</span></button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                @if($urunDetay->islem_gerekiyor=='1')

                                <div class="card  alert alert-danger">
                                    <div class="card-header">
                                        <h4 class="card-title">Yapılması Gereken İşlem var</h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-12">
                                            <label class="form-label" for="siparis_notu">Yapılacak İşlem</label>
                                            <div class="input-group input-group-merge">
                                               {{$urunDetay->yapilacak_islem}}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="input-group input-group-merge pt-50">
                                                <a href="{{URL::site('siparisler/urunKontrolEdildi/')}}{{$urunDetay->id}}" class="btn btn-primary">Kontrol Sağlangı İşlem Yapıldı</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                @endif

                            </div>


                        </div>

                        @Form::close()

                    </div>

                </div>
            </section>



        </div>
    </div>
</div>