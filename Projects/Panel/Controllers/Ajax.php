<?php namespace Project\Controllers;

Use Http,Post,Get,Cookie,User,Date,URL,Json,Encode,Security,Form,Validation,Cart;
Use AjaxModel,KasaModel,AyarModel, InternalFaturaModel as FaturaModel,InternalPlanlamaModel as PlanlamaModel;
Use PersonelModel,InternalProjeModel as ProjeModel, InternalUrunModel as UrunModel,InternalCariModel as CariModel,SiparisModel;
use MasrafModel,InternalDestekModel as DestekModel;




class Ajax extends Controller
{

    public function main(){

        if(!Http::isajax()){
            redirect("Login");
            exit;
        }

    }

    public function sort() {
        
        if(!Http::isajax()){
            redirect("Login");
            exit;
        }

        $veri = Post::row();

        if(Post::action()=="yolHaritasi"){

            foreach ($veri as $key => $value) {
                
              
                $degistir = ProjeModel::yolharitasiSiraDegistir(['id'=>$value,'sira'=>$key+1]);
                
            }
            if($degistir){
                echo "success";
            }

        }

        if(Post::action()=="siparisDurumlari"){

            foreach ($veri as $key => $value) {
                
              
                $degistir = AyarModel::siparisDurumSiraGuncelle(['id'=>$value,'sira'=>$key+1]);
                
            }
            if($degistir){
                echo "success";
            }

        }
        if(Post::action()=="urunGuruplari"){

            foreach ($veri as $key => $value) {
                
              
                $degistir = UrunModel::urunGrupSiraGuncelle(['id'=>$value,'sira'=>$key+1]);
                
            }
            if($degistir){
                echo "success";
            }

        }
        

        



    }

    public function etkinlikListe(){

        $start = Get::start();
        $end = Get::end();

        /*if(!Http::isajax()){
            redirect("Login");
            exit;
        }*/

        $etkinlikListe = PlanlamaModel::etkinlikListe();
        $etkinlikler = [];
        foreach ($etkinlikListe['liste'] as $etkinlik) {
            $turDetay = PlanlamaModel::etkinlikTurDetay($etkinlik->tur);
            $katilimcilar = json_decode($etkinlik->katilimcilar);
            $users ="";
            if(count($katilimcilar)>0){
                for($k=0;$k<count($katilimcilar);$k++){
                    $users = $users."<br>".$katilimcilar[$k];
                }
            }else{
                $users ="Katılımcı Kaydı Yapılmadı";
            }
            

            $etkinlikDetay = [
                'id'            => $etkinlik->id,
                'title'         => $etkinlik->baslik,//
                
                'start'         => $etkinlik->baslangic_tarihi,//
                'end'           => $etkinlik->bitis_tarihi,//
                'color'         => $turDetay->renk,
                'extendedProps' => [
                    'description'   => $etkinlik->aciklama,//
                    'tur'           => $turDetay->tur,//
                    'sTime'         => $etkinlik->baslangic_saati,//
                    'eTime'         => $etkinlik->bitis_saat,//
                    'sUrl'          => $etkinlik->url,//
                    'allUsers'      => $users,//
                    'lctn'          => $etkinlik->konum,//
                    'mailInfo'      => $etkinlik->mail_bilgilendirme,
                    'smsInfo'       => $etkinlik->sms_bilgilendirme
                ]
                
            ];

            array_push($etkinlikler, $etkinlikDetay);
            $etkinlikDetay = "";

        }

        echo json_encode($etkinlikler);

        exit(); 

        

    }


    public function modal()
    {

        if(!Http::isajax()){
            redirect("Login");
            exit;
        }

        $kasaHesaplari      = KasaModel::turHesaplari(1);
        $bankaHesaplari     = KasaModel::turHesaplari(2);
        $posHesaplari       = KasaModel::turHesaplari(3);
        $kkartiHesaplari    = KasaModel::turHesaplari(4);
        $veresiyeHesaplari  = KasaModel::turHesaplari(5);
        $digerHesaplar      = KasaModel::turHesaplari(6);
        $musteriler         = CariModel::tumListe();

        $user = User::data();

        if(Post::action()=="kasaHesapBilgi"){

            $hesap = KasaModel::hesapBilgi(Post::rowid());

            ?>

                <form action="<?=URL::site('kasa/kasaHesapGuncelle/'.$hesap->id)?>" method="POST">

                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Hesap Adı:</strong></td>
                            <td><input type="text" class="form-control" name="adi" required id="adi" placeholder="Adı" value="<?=$hesap->adi?>"></td>
                        </tr>
                        <tr>
                            <td><strong>Hesap No:</strong></td>
                            <td>
                                <input type="text" class="form-control" name="hesapNo" id="hesapNo" placeholder="Adı" value="<?=$hesap->hesap_no?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Hesap Türü:</strong></td>
                            <td>
                                <select class="form-control" name="tur" required >
                                    <option value="" <?=($hesap->tur=="")?"selected":""?>>--Seçiniz--</option>
                                    <option value="1" <?=($hesap->tur=="1")?"selected":""?>>Kasa Hesabı</option>
                                    <option value="2" <?=($hesap->tur=="2")?"selected":""?>>Banka Hesabı</option>
                                    <option value="3" <?=($hesap->tur=="3")?"selected":""?>>Pos Hesabı</option>
                                    <option value="4" <?=($hesap->tur=="4")?"selected":""?>>Kredi Kartı Hesabı</option>
                                    <option value="5" <?=($hesap->tur=="5")?"selected":""?>>Veresiye Hesabı</option>
                                    <option value="6" <?=($hesap->tur=="6")?"selected":""?>>Diğer Hesaplar</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Açıklama:</strong></td>
                            <td>
                                <textarea name="aciklama" class="form-control" placeholder="Açıklama"><?=$hesap->aciklama?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Güncel Tutar:</strong></td>
                            <td><input type="text" class="form-control" name="tutar" required id="tutar" value="<?=$hesap->tutar?>"></td>
                        </tr>
                        <tr>
                            <td><strong>Durum:</strong></td>
                            <td>

                                <select name="durum" class="form-control">
                                    <option value="1" <?=($hesap->durum=="1")?"selected":""?>>Aktif</option>
                                    <option value="0" <?=($hesap->durum=="0")?"selected":""?>>Pasif</option>
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" class="btn btn-primary" value="Güncelle"></td>
                        </tr>
                    </table>
                </form>

            <?php

        }

        if(Post::action()=="tahsilatEkle") {

            ?>

            <form action="<?=URL::site('kasa/odemeEkle/tahsilat/')?>0" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Tahsilat Ekle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Ödeme Yapan:</label>
                                <div class="input-group input-group-merge">
                                    <select name="cari" required class="form-control">
                                        <option value="0">--Seçiniz--</option>

                                            <?php foreach($musteriler as $m){?>
                                                <option value="<?=$m->id?>"><?=$m->adi?>(<?=$m->firma_adi?>)</option>
                                            <?php }?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Ödeme Hesabı:</label>
                                <div class="input-group input-group-merge">
                                    <select name="kasa" required class="form-control">
                                        <option value="0">--Seçiniz--</option>
                                        <optgroup label="Kasa Hesapları">
                                            <?php foreach($kasaHesaplari as $kh){?>
                                                <option value="<?=$kh->id?>"><?=$kh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Banka Hesapları">
                                            <?php foreach($bankaHesaplari as $bh){?>
                                                <option value="<?=$bh->id?>"><?=$bh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="POS Hesapları">
                                            <?php foreach($posHesaplari as $ph){?>
                                                <option value="<?=$ph->id?>"><?=$ph->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Kredi Kartı Hesapları">
                                            <?php foreach($kkartiHesaplari as $kkh){?>
                                                <option value="<?=$kkh->id?>"><?=$kkh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Veresiye Hesapları">
                                            <?php foreach($veresiyeHesaplari as $vh){?>
                                                <option value="<?=$vh->id?>"><?=$vh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Diğer Hesaplar">
                                            <?php foreach($digerHesaplar as $dh){?>
                                                <option value="<?=$dh->id?>"><?=$dh->adi?></option>
                                            <?php }?>
                                        </optgroup>

                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="odeme_tarihi">Ödeme Tarihi:</label>
                                <div class="input-group input-group-merge">
                                    <input type="date" name="odeme_tarihi" id="odeme_tarihi" class="form-control" placeholder="24.10.2023" maxlength="10" value="{{Date::current()}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="tutar">Tutar (TL):</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" onkeyup="$(this).val($(this).val().replace(/,/g, '.'));" name="tutar" id="tutar" placeholder="Ödenen tutar" value="">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Açıklama:</label>
                                <div class="input-group input-group-merge">
                                    <textarea class="form-control" name="aciklama" placeholder="Açıklama"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="bildirim">Bildirim</label>
                                    </div>
                                    <div class="col-sm-12">
                                  
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="bildirim" id="bildirim" value="1" />
                                            <label class="form-check-label" for="bildirim">Müşteriye E-Posta ile bildir</label>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

            <?php



        }

        if(Post::action()=="odemeYontemiDuzenle"){

            $odemeYontemi = AyarModel::odemeYontemiDetay(Post::rowid());
            $kasaHesaplari = KasaModel::kasaHesaplari();



            echo Form::csrf()->action('ayarlar/odemeYontemleriGuncelle/'.$odemeYontemi->id)->open('oyForm',['class'=>'row gy-1 gx-2 mt-75']); ?>
            <div class="col-12">
                <label class="form-label" for="modalAddCardNumber">Başlık</label>
                <div class="input-group input-group-merge">
                    <?php echo Form::vRequired()->id('baslik')->placeholder('Başlık Giriniz')->text('baslik',$odemeYontemi->baslik,['class'=>'form-control']); ?>
                </div>
            </div>

            <div class="col-md-12">
                <label class="form-label" for="modalAddCardName">Kasa Hesabı</label>
                <select name="kasa_hesabi" required id="kasa_hesabi" class="form-control">
                    <option value="">--Seçiniz--</option>
                    <?php
                    foreach($kasaHesaplari as $kasa){ ?>
                    <option value="<?=$kasa->id?>" <?=$odemeYontemi->kasa_hesabi==$kasa->id?"selected":""?>><?=$kasa->adi?></option>
                     <?php } ?>
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label" for="durum">Durum</label>
                <select name="durum" id="durum" class="form-control">
                    <option value="1" <?=$odemeYontemi->durum==1?"selected":""?>>Aktif</option>
                    <option value="0" <?=$odemeYontemi->durum==0?"selected":""?>>Pasif</option>
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label" for="modalAddCardName"><strong>Entegrasyon Bilgileri</strong></label><br>
                <small>Banka Hesapları, Bilgilendirme mesajı vs yazabilirsiniz. Ödeme sayfasında görünecektir</small>

                <div class="input-group input-group-merge">

                    <textarea class="form-control" id="summernote" name="bilgiler"><?=Security::htmlDecode($odemeYontemi->bilgiler)?></textarea>

                </div>

            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary me-1 mt-1">Kaydet</button>
                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                    Vazgeç
                </button>
            </div>
            <?php echo Form::close(); ?>

            <script>
                function satirEkle() {
                    var tablo = document.getElementById("satirEklemeTablosu");
                    var yeniSatir = tablo.insertRow(tablo.rows.length);
                    var hucre1 = yeniSatir.insertCell(0);
                    var hucre2 = yeniSatir.insertCell(1);
                    var hucre3 = yeniSatir.insertCell(2);

                    hucre1.innerHTML = '<input type="text" name="key[]" class="form-control" placeholder="Key">';
                    hucre2.innerHTML = '<input type="text" name="value[]" class="form-control" placeholder="value">';
                    hucre3.innerHTML = '<button type="button" class="btn btn-danger" value="Sil" onclick="silSatir(this)">x</button>';
                }

                function silSatir(button) {
                    var satir = button.parentNode.parentNode;
                    satir.parentNode.removeChild(satir);
                }
            </script>

                <?php

        }

        if(Post::action()=="siparisDurumDuzenle"){

            $siparisDurum = AyarModel::siparisDurumDetay(Post::rowid());

            echo Form::csrf()->action('ayarlar/siparisDurumGuncelle/'.$siparisDurum->id)->open('oyForm',['class'=>'row gy-1 gx-2 mt-75']); ?>
            <div class="col-12">
                <label class="form-label" for="modalAddCardNumber">Başlık</label>
                <div class="input-group input-group-merge">
                    <?php echo Form::vRequired()->id('adi')->placeholder('Başlık Giriniz')->text('adi',$siparisDurum->adi,['class'=>'form-control']); ?>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label" for="sira">Sıra</label>
                <div class="input-group input-group-merge">
                    <?php echo Form::vRequired()->id('sira')->placeholder('Sıra')->text('sira',$siparisDurum->sira,['class'=>'form-control']); ?>
                </div>
            </div>


            <div class="col-md-12">
                <label class="form-label" for="uyari">Durum</label>
                <select name="uyari" id="uyari" class="form-control">
                    <option value="info" >--Seçiniz--</option>
                    <option value="primary" <?=$siparisDurum->uyari=="primary"?"selected":""?>>Sarı</option>
                    <option value="warning" <?=$siparisDurum->uyari=="warning"?"selected":""?>>Sarı</option>
                    <option value="success" <?=$siparisDurum->uyari=="success"?"selected":""?>>Yeşil</option>
                    <option value="info" <?=$siparisDurum->uyari=="info"?"selected":""?>>Mavi</option>
                    <option value="secondary" <?=$siparisDurum->uyari=="secondary"?"selected":""?>>Gri</option>
                    <option value="danger" <?=$siparisDurum->uyari=="danger"?"selected":""?>>Kırmızı</option>
                    <option value="dark" <?=$siparisDurum->uyari=="dark"?"selected":""?>>Siyah</option>
                </select>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary me-1 mt-1">Kaydet</button>
                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">
                    Vazgeç
                </button>
            </div>
            <?php echo Form::close(); ?>

            <?php

        }

        if(Post::action()=="siparisUrunleri"){

            $urunler = SiparisModel::siparisUrunleri(Post::rowid());

            $yetkiler               = \Json::decode($user->yetkiler);

            if(in_array('Siparisler/duzenle',$yetkiler)){

                $duzenlemeYetkisi = "1";

            }

                ?>

                <table class="table table-bordered table-responsive">
                    <tr>
                        <th>Ürün Adı</th>
                        <th colspan="2">Ölçüler</th>
                        <th>Adet</th>
                        <th>Not</th>
                        <th>Acil</th>
                        <?php
                        if($user->fiyat_yetkisi=="1"){
                        ?>
                        <th>Birim Fiyat</th>
                        <th>Toplam Fiyat</th>
                        <?php } ?>
                        <th>Durum</th>
                        <th>#</th>
                    </tr>
                    <?php
            foreach ($urunler as $urun) {
                $urunDetay = UrunModel::detay($urun->urun);
                    ?>
                <tr>
                    <td><?=$urun->urun_adi?></td>
                    <?php
                    if($urunDetay->birim=="m2"){
                    ?>
                    <td>En: <?=$urun->en?></td>
                    <td>Boy: <?=$urun->boy?></td>
                    <?php }else{ ?>
                        <td colspan="2"></td>
                    <?php } ?>
                    <td><?=$urun->adet?></td>
                    <td><?=$urun->notu?></td>
                    <td><?=$urun->aciliyet=="0"?"<a class='btn btn-default btn-xs degistir' id='degistir-".$urun->id."' data-id='".$urun->id."' data-name='siparisUrunuAciliyet' ><i class='glyphicon glyphicon-warning-sign'></i> </a>":"<a class='btn btn-danger btn-xs degistir' id='degistir-".$urun->id."' data-id='".$urun->id."' data-name='siparisUrunuAciliyet' ><i class='glyphicon glyphicon-warning-sign'></i> </a>"?></td>
                    <?php
                    if($user->fiyat_yetkisi=="1"){
                        ?>
                    <td><?=$urun->birim_fiyat?></td>
                    <td><?=$urun->toplam_fiyat?></td>
                    <?php } ?>
                    <td><span class="label label-<?=$urun->durum_uyari?>"><?=$urun->durum_adi?></span> </td>
                    <td>
                        <?php
                        if($duzenlemeYetkisi=="1"){
                            ?>
                            <a href="<?=URL::site('siparisler/siparisUrunKaldir/')?><?=$urun->id?>/<?=$urun->siparis?>" class="btn btn-danger btn-xs confirm"><i class="fa fa-times"></i></a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php

            }
            ?>
            </table>
            <?php

        }

        if(Post::action()=="siparisUrunBilgileri"){

            $user = User::data();

            $yetkiler               = \Json::decode($user->yetkiler);

            $urunId = Post::rowid();

            $siparisUrunBilgileri   = SiparisModel::siparisUrunDetay($urunId);
            $urun                   = UrunModel::detay($siparisUrunBilgileri->urun);
            $siparisBilgileri       = SiparisModel::detay($siparisUrunBilgileri->siparis);
            $uyeBilgi               = UyeModel::detay($siparisBilgileri->uye);

            ?>

            <div class="form-group">
                <label class="col-md-11 text-left text-primary text-bold"><h4><?=$urun->adi?> (<?=$urun->fiyat?> <?=$urun->fiyat_birim?>)</h4></label>
                <input type="hidden" name="urun" value="<?=$urun->id?>">
                <input type="hidden" name="id" value="<?=$urunId?>">
                <input type="hidden" name="uye" value="<?=$uyeBilgi->adi?>">
            </div>

            <div class="form-group">
                <?php
                if($user->fiyat_yetkisi=="1"){
                    ?>
                    <label for="adi" class="col-md-2 control-label">Fiyat:</label>
                    <div class="col-md-4">
                        <div class="input-group">

                            <input type="text" data-toggle="tooltip" class="form-control" <?php if(!in_array("Siparisler/fiyatDegisim",$yetkiler)){ echo "readonly title='Fiyat değiştirmeye yetkili değilsiniz!'"; } else{ } ?>  name="fiyat" id="fiyat" value="<?=AyarModel::dovizeCevir($siparisUrunBilgileri->birim_fiyat,$urun->fiyat_birim)?>">
                            <span class="input-group-addon"><?=$urun->fiyat_birim?></span>
                        </div>
                    </div>
                <?php }?>

                <label for="adi" class="col-md-2 control-label">Adet:</label>
                <div class="col-md-4">
                    <input name="adet" id="adet" type="number" onchange="hesapla()" onkeypress="return isNumberKey(event)" class="form-control" value="<?=$siparisUrunBilgileri->adet?>">
                </div>
            </div>

            <?php
            if($urun->birim=="m2"){
                ?>
                <div class="form-group">
                    <label for="adi" class="col-md-2 control-label">En:</label>
                    <div class="col-md-4">
                        <input name="en" id="en" type="text" onkeypress="return isNumberKey(event)" onkeyup="hesapla()" placeholder="En"  class="form-control" value="<?=$siparisUrunBilgileri->en?>">
                    </div>

                    <label for="adi" class="col-md-2 control-label">Boy:</label>
                    <div class="col-md-4">
                        <input name="boy" id="boy" type="text" onkeypress="return isNumberKey(event)" onkeyup="hesapla()" placeholder="Boy" class="form-control" value="<?=$siparisUrunBilgileri->boy?>">
                    </div>
                </div>

                <?php
            }
            ?>
            <div class="form-group">
                <label for="adi" class="col-md-2 control-label">KDV:</label>
                <div class="col-md-2">
                    <input name="kdv" id="kdv" type="number" readonly class="form-control" value="<?=$urun->kdv?>">
                </div>

                <label for="adi" class="col-md-2 control-label">İskonto:</label>
                <div class="col-md-2">
                    <input name="iskonto" id="iskonto" type="text" readonly class="form-control" value="<?=$uyeBilgi->iskonto?>">
                </div>
                <?php
                if($user->fiyat_yetkisi=="1"){
                    ?>
                    <label for="adi" class="col-md-2 control-label">Toplam:</label>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" class="form-control" readonly name="toplam" id="toplam" value="<?=AyarModel::dovizeCevir($siparisUrunBilgileri->toplam_fiyat,$urun->fiyat_birim)?>">
                            <span class="input-group-addon"><?=$urun->fiyat_birim?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="adi" class="col-md-2 control-label">Ek  Not:</label>
                <div class="col-md-10">
                    <textarea class="form-control" name="eknot" placeholder="İsteğe bağlı ek not"></textarea>
                </div>
            </div>

            <script type="text/javascript">

                hesapla = function(i){
                    <?php
                    if($urun->birim=="m2"){
                    ?>
                    var en      = document.getElementById('en').value;
                    var boy     = document.getElementById('boy').value;
                    <?php } ?>
                    var adet    = document.getElementById('adet').value;
                    var fiyati  = document.getElementById('fiyat').value;
                    var kdvsi   = document.getElementById('kdv').value;

                    var musteriIskonto = document.getElementById('musteriIskonto').value;

                    <?php
                    if($urun->birim=="m2"){
                    ?>
                    var m2          = (en*boy)/10000;
                    var guncelFiyat = (m2*fiyati)*adet;

                    <?php
                    }else{ ?>

                    var guncelFiyat = fiyati*adet;

                    <?php } ?>


                    if (musteriIskonto>0){

                        var indirimTutari   = (guncelFiyat/100)*musteriIskonto;
                        var yeniGuncelFiyat = guncelFiyat-indirimTutari;

                        guncelFiyat = yeniGuncelFiyat;

                        document.getElementById('iskonto').value = musteriIskonto;

                    }

                    var kdvFiyat    = (guncelFiyat/100)*kdvsi;
                    var kdvsizToplamFiyat = guncelFiyat.toFixed(2);
                    var kdvliFiyat  = kdvFiyat+guncelFiyat;
                    var toplamFiyat = kdvliFiyat.toFixed(2);

                    $('#altToplamlar').fadeIn('slow');

                    document.getElementById('toplam').value = kdvsizToplamFiyat;

                }

            </script>

            <?php

        }

        if(Post::action()=="siparisUrunDurumlari"){

            $urun               = Post::rowid();
            $siparisUrunDetay   = SiparisModel::siparisUrunDetay($urun);

            $durumlar = SiparisModel::siparisDurumlari();
            $siparisDurumUyarilari = UyariModel::siparisUyarilari($siparisUrunDetay->durum);

            ?>
                <input type="hidden" name="pk" value="<?=$urun?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="adi" class="col-sm-2 control-label">Sipariş Durumu</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="durumlar" name="value">
                                    <?php
                                    foreach ($durumlar as $durum) {
                                        ?>
                                        <option value="<?=$durum->id?>" <?php if ($siparisUrunDetay->durum==$durum->id){ echo"selected='selected'"; } ?>><?=$durum->adi?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="adi" class="col-sm-2 control-label">Uyarılar</label>
                            <div class="col-sm-10">

                                <?php
                                foreach ($siparisDurumUyarilari as $sdu) {
                                    ?>
                                    <div class="checkbox">
                                        <label>
                                            <input value="<?=$sdu->uyari?>" name="uyarilar[]" required type="checkbox"><?=$sdu->uyari?>
                                            <span class="label label-<?=$sdu->onem?>">
                                                <?php
                                                if($sdu->onem=="danger"){ echo "Çok Önemli" ;}
                                                if($sdu->onem=="warning"){ echo "Önemli" ;}
                                                if($sdu->onem=="info"){ echo "Dikkat gerektirir"; ;}
                                                ?>
                                            </span>
                                        </label>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>


                    </div>
                </div>

            <?php

        }

        if(Post::action()=="siparisFisi"){

            $siparisId      =   Post::rowid();
            $siparisDetay   =   SiparisModel::detay($siparisId);
            $uyeBlgileri    =   UyeModel::detay($siparisDetay->uye);
            $siparisurunleri=   SiparisModel::siparisUrunleri($siparisDetay->siparisId);

            ?>

            <table class="table table-bordered yazdirilacak" style="border: solid 2px #4e4e4e">
                <tr style="border-bottom: solid 2px #4e4e4e">
                    <td>
                        <img src="<?=URL::site()?>../Uploads/site/logo.png" class="img-responsive" style="max-height: 50px">
                    </td>
                    <td class="text-center">
                        <p><strong>SİPARİŞ FİŞİ</strong></p>
                    </td>
                </tr>
                <?php
                if($uyeBlgileri->fatura_adresi=="" or $uyeBlgileri->fatura_adresi=="null"){
                ?>
                <tr>
                    <td colspan="2"><h1>MÜŞTERİNİ FATURA BİLGİLERİ YOK <br>
                        LÜTFEN FATURA BİLGİLERİNİ GİRİNİZ</h1><br>
                        <a href="<?=URL::site('uye/form/').$siparisDetay->uye?>" target="_blank" class="btn btn-danger">FATURA BİLGİLERİNİ DÜZENLE</a>
                    </td>
                </tr>
                <?php
                exit();
                }?>
                <tr style="border-bottom: solid 2px #403f3f">
                    <td colspan="2">
                        <strong>Firma Bilgileri</strong><br>
                        <?=AyarModel::defaultAyarlar('firmaAdi')?><br>
                        <?=AyarModel::defaultAyarlar('faturaAdresi')?><br>
                    </td>
                </tr>
                <tr style="border-bottom: solid 2px #403f3f">
                    <td colspan="2">
                        <strong>Müşteri Bilgileri</strong><br>
                        <?=$uyeBlgileri->firma_adi?><br>
                        <?=$uyeBlgileri->fatura_adresi?><br>
                    </td>
                </tr>
                <tr style="border-bottom: solid 2px #403f3f">
                    <td colspan="2">
                        <strong>Sipariş Bilgileri</strong><br>
                        Sipariş no: <?=$siparisDetay->siparis_kodu==""?"TRM20".$siparisId:$siparisDetay->siparis_kodu?><br>
                        <table class="table table-condensed">
                            <tr>
                                <td>
                                    <?php
                                    foreach ($siparisurunleri as $su) {
                                        echo $su->adet." x ".$su->urun_adi."<br>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="border-bottom: solid 2px #403f3f">
                    <td colspan="2">
                        Not: Faturanız firmamız tarafından kayıtlı E-posta adresinize elektronik fatura olarak iletilecektir.<br>
                        Faturanız tarafınıza ulaşmazsa lütfen muhasebe@termofom.com adresine mail gönderiniz.
                    </td>

                </tr>
            </table>

            <?php

        }

        if(Post::action()=="siparisSil") {

            $id = Post::rowid();
            $siparisDetay = SiparisModel::detay($id);

            ?>

            <form action="<?=URL::site('siparisler/sil/')?><?=$id?>" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Sipariş Sil</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="tumFaturalar">Tüm Faturalar</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="tumFaturalar" id="tumFaturalar" value="1" />
                                            <label class="form-check-label" for="tumFaturalar">Siparişe Ait Tüm Faturaları Sil !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="odenmemisFaturalar">Ödenmemiş Faturalar</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="odenmemisFaturalar" id="odenmemisFaturalar" value="1" checked />
                                            <label class="form-check-label" for="odenmemisFaturalar">Sadece Ödenmemiş Faturaları Sil !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="kasaDefterindenKaldir">Kasa Defteri</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="kasaDefterindenKaldir" id="kasaDefterindenKaldir" value="1" />
                                            <label class="form-check-label" for="kasaDefterindenKaldir">Sipariş ve Faturalar ile ilişkili tüm ödeme kayıtlarını kasa defterinden kaldır. <br> <br><div class="alert alert-warning p-1"><i>Not: Bu kasa defteri işlemi hesaplarınızda hataya sebep olabilir, Kasa hesaplarından silmek yerine geri iade ettiğiniz ödeme varsa bunları gider olarak tekrar kayıt altına almanız ileride yaşanabilecek bir olumsuzluk durumunda size bilgi sağlayacağı için, daha doğru bir yaklaşım olacaktır.</i></div></label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

            <?php



        }

        if(Post::action()=="faturaUrunleri"){

            $urunler = InternalFaturaModel::faturaUrunleri(Post::rowid());

            ?>

            <table class="table table-bordered table-responsive">
                <tr>
                    <th>Stok Adı</th>
                    <th>F. Ürün Adı</th>
                    <th>Birim Fiyat</th>
                    <th>Adet</th>
                    <th>KDV</th>
                    <th>Toplam</th>
                </tr>
                <?php
                foreach ($urunler as $urun) {
                    $urunDetay = MalzemeModel::detay($urun->urun);
                    ?>
                    <tr>
                        <td><?=$urunDetay->adi?></td>
                        <td><?=$urun->urun_adi?></td>
                        <td><?=$urun->fiyat?></td>
                        <td><?=$urun->miktar?></td>
                        <td><?=$urun->kdv?></td>
                        <td><?=$urun->tutar+(($urun->tutar/100)*$urun->kdv)?></td>
                        <td>
                            <a href="<?=URL::site('tedarikci/alimSil/')?><?=$urun->id?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>

                        </td>
                    </tr>
                    <?php

                }
                ?>
            </table>
            <?php

        }

        if(Post::action()=="faturaUrunDuzenle"){

            $id = Post::rowid();

            $urunDetay = InternalFaturaModel::faturaUrunDetay($id);

            $faturaDetay = InternalFaturaModel::detay($urunDetay->fatura);

            ?>

            <div class="container-fluid">
                <form class="form-horizontal" action="<?=URL::site('fatura/urunGuncelle/')?><?=$id?>" method="post">
                    <input type="hidden" name="gorevId" value="<?=$id?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box">
                                        <div class="box-header"><h3>Fatura Ürün Detayları</h3></div>
                                        <div class="box-body">
                                            <table class="table table-hover">
                                                <tbody>

                                                    <tr>
                                                        <th scope="row">Ürün</th><td><?=MalzemeModel::malzemeBilgi($urunDetay->urun)['adi']?></td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Fatura Adı</th>
                                                        <td><input type="text" name="urunAdi" class="form-control" value="<?=$urunDetay->urun_adi?>"></td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Miktar</th><td><input type="text" id="miktar-1" name="miktar" onkeyup="hesapla(1)" class="form-control" value="<?=$urunDetay->miktar?>"></td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Fiyat</th><td><input type="text" id="fiyat-1" name="fiyat" class="form-control" value="<?=$urunDetay->fiyat?>"></td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">KDV</th>
                                                        <td>
                                                            <select class="form-control"  id="kdv-1" name="kdv"  onchange="hesapla(1)">
                                                                <option value="18" <?=$urunDetay->fiyat==18?'selected':''?> >18</option>
                                                                <option value="8" <?=$urunDetay->fiyat==8?'selected':''?>>8</option>
                                                                <option value="0" <?=$urunDetay->fiyat==0?'selected':''?>>0</option>
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Tutar</th><td><input type="text" id="tutar-1" placeholder="tutar" readonly class="form-control" name="tutar" value="<?=$urunDetay->tutar?>"></td>
                                                    </tr>

                                                </tbody>
                                            </table>

                                            <script type="text/javascript">
                                                hesapla = function(i){
                                                    var miktar = document.getElementById('miktar-'+i).value;
                                                    var fiyat = document.getElementById('fiyat-'+i).value;
                                                    var kdv = document.getElementById('kdv-'+i).value;
                                                    document.getElementById('tutar-'+i).value = ((((miktar*fiyat)/100)*kdv)+(miktar*fiyat)).toFixed(2);
                                                }
                                            </script>

                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-success pull-right">Kaydet</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>


            <?php

        }

        if(Post::action()=="faturaOdendiYap") {

            $id = Post::rowid();
            $faturaDetay = FaturaModel::detay($id);

            ?>

            <form action="<?=URL::site('kasa/odemeEkle/fatura/')?><?=$id?>" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Fatura Ödeme Ekle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">
                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label" for="modalAddCardNumber">Fatura Tutarı:</label>
                                    <div class="input-group input-group-merge">
                                        <?=number_format($faturaDetay->genel_toplam,2)?> ₺
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label" for="modalAddCardNumber">Daha Önce Alınan Ödeme:</label>
                                    <div class="input-group input-group-merge">
                                        <?=number_format($faturaDetay->alinan_odeme,2)?> ₺
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label" for="modalAddCardNumber">Kalan Ödeme:</label>
                                    <div class="input-group input-group-merge">
                                        <?=number_format($faturaDetay->genel_toplam-$faturaDetay->alinan_odeme,2)?> ₺
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Ödeme Hesabı:</label>
                                <div class="input-group input-group-merge">
                                    <select name="kasa" required class="form-control">
                                        <option value="0">--Seçiniz--</option>
                                        <optgroup label="Kasa Hesapları">
                                            <?php foreach($kasaHesaplari as $kh){?>
                                            <option value="<?=$kh->id?>"><?=$kh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Banka Hesapları">
                                            <?php foreach($bankaHesaplari as $bh){?>
                                            <option value="<?=$bh->id?>"><?=$bh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="POS Hesapları">
                                            <?php foreach($posHesaplari as $ph){?>
                                            <option value="<?=$ph->id?>"><?=$ph->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Kredi Kartı Hesapları">
                                            <?php foreach($kkartiHesaplari as $kkh){?>
                                            <option value="<?=$kkh->id?>"><?=$kkh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Veresiye Hesapları">
                                            <?php foreach($veresiyeHesaplari as $vh){?>
                                            <option value="<?=$vh->id?>"><?=$vh->adi?></option>
                                            <?php }?>
                                        </optgroup>
                                        <optgroup label="Diğer Hesaplar">
                                            <?php foreach($digerHesaplar as $dh){?>
                                            <option value="<?=$dh->id?>"><?=$dh->adi?></option>
                                            <?php }?>
                                        </optgroup>

                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="odeme_tarihi">Ödeme Tarihi:</label>
                                <div class="input-group input-group-merge">
                                    <input type="date" name="odeme_tarihi" id="odeme_tarihi" class="form-control" placeholder="24.10.2023" maxlength="10" value="{{Date::current()}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="tutar">Tutar (TL):</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" onkeyup="$(this).val($(this).val().replace(/,/g, '.'));" name="tutar" id="tutar" placeholder="Ödenen tutar" value="">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalAddCardNumber">Açıklama:</label>
                                <div class="input-group input-group-merge">
                                    <textarea class="form-control" name="aciklama" placeholder="Açıklama"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="odendi">Ödendi Yap</label>
                                    </div>
                                    <div class="col-sm-12">

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="odendi" id="odendi" value="1" />
                                            <label class="form-check-label" for="odendi">Faturayı <strong>Ödendi</strong> olarak işaretle ! <br><small>(Fatura tam oalrak ödenmese bile faturayı ödendi yapmak istemeniz durumunda kullanabilirsiniz)</small></label>
                                        </div>

                                        <!--<div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="siparisOdendi" id="siparisOdendi" value="1" />
                                            <label class="form-check-label" for="siparisOdendi">İlgili Siparişi <strong>Ödendi</strong> olarak işaretle !</label>
                                        </div>-->

                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="bildirim">Bildirim</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="bildirim" id="bildirim" value="1" />
                                            <label class="form-check-label" for="bildirim">Müşteriye E-Posta ile bildir</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="uzat" id="uzat" value="1" />
                                            <label class="form-check-label" for="uzat">Fatura Ürününün Süresini Uzat</label>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

        <?php



        }

        if(Post::action()=="faturayiOdendiYap") {

            $id = Post::rowid();
            $faturaDetay = FaturaModel::detay($id);

            ?>

            <form action="<?=URL::site('faturalar/odendiYap/')?><?=$id?>" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Faturayı Ödendi Yap</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-danger">
                            DİKKAT: <strong>Bu işlem sadece fatura ve siparişin durumunu değiştirir, Kasa defterine herhangi bir veri işlemez.</strong>  <br>Eğer bu fatura ile iglili gelir gider kaydı daha önceden yapmadıysanız bu fatura ile ilgili tahsilatı daha sonra manuel eklemeniz gerekir.<br> Eğer kasa defterine işlensin istiyorsanız "ÖDEME EKLE" seçeneğini kullanın
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="odendi">Ödendi Yap</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="odendi" id="odendi" value="1" />
                                            <label class="form-check-label" for="odendi">Faturayı <strong>Ödendi</strong> olarak işaretle !</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="siparisOdendi" id="siparisOdendi" value="1" />
                                            <label class="form-check-label" for="siparisOdendi">İlgili Siparişi <strong>Ödendi</strong> olarak işaretle !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

            <?php



        }

        if(Post::action()=="faturaOdenmediYap") {

            $id = Post::rowid();
            $faturaDetay = FaturaModel::detay($id);

            ?>

            <form action="<?=URL::site('kasa/odemeKaldir/fatura/')?><?=$id?>" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Fatura Ödeme Kaldır</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="odenmediYap">Fatura</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="odenmediYap" id="odenmediYap" value="1" checked />
                                            <label class="form-check-label" for="odenmediYap">Faturayı Ödenmemiş Olarak İşaretle !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="siparisOdenmediYap">Sipariş</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="siparisOdenmediYap" id="siparisOdenmediYap" value="1" checked />
                                            <label class="form-check-label" for="siparisOdenmediYap">Faturaya bağlı siparişin ödeme durumunu da Ödenmemiş Olarak İşaretle !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="kasaDefterindenKaldir">Kasa Defteri</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="kasaDefterindenKaldir" id="kasaDefterindenKaldir" value="1" checked />
                                            <label class="form-check-label" for="kasaDefterindenKaldir">Faturaya ait tüm ödemeleri Kasa defterinden de kaldır !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="bildirim">Bildirim</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="bildirim" id="bildirim" value="1" />
                                            <label class="form-check-label" for="bildirim">Müşteriye E-Posta ile bildir !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

            <?php



        }

        if(Post::action()=="faturaResmilestir"){

            $id = Post::rowid();
            $faturaDetay = FaturaModel::detay($id);

            ?>

            <?php echo  Form::csrf()->enctype('multipart/form-data')->method('post')->action('faturalar/resmilestir/'.$id)->open('faturaResmilestir'); ?>

                <div class="modal-header">
                    <h4 class="modal-title">Fatura Resmileştir</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="col-12">
                                <label class="form-label" for="fatura_no">Resmi Fatura No:</label>
                                <div class="input-group input-group-merge">
                                    <?php echo Form::vRequired()->id('fatura_no')->placeholder('Fatura No')->text('fatura_no','',['class'=>'form-control']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">

                                    <div class="col-lg-12 col-md-12 mb-1 mb-sm-0">
                                        <label for="formFile" class="form-label">Resmi Fatura Yükle</label>
                                        <?php echo Form::id('fatura_dosya')->file('fatura_dosya', '',['accept' => 'application/pdf', 'class' => 'form-control']); ?>
                                        <small>Sadece PDF Dosyası</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-12">
                                        <label class="col-form-label" for="bildirim">Bildirim</label>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="bildirim" id="bildirim" checked value="1" />
                                            <label class="form-check-label" for="bildirim">Müşteriye E-Posta ile bildir !</label>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="faturayaUrunEkle") {
            $id = Post::rowid();
            ?>

            <?php echo Form::csrf()->method('post')->action('faturalar/kalemEkle/'.$id)->open('urunEkle'); ?>

                <div class="modal-header">
                    <h4 class="modal-title">Fatura Kalemi Ekle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <div class="col-12">
                                <label class="form-label" for="urun_adi">Fatura Kalem Adı:</label>
                                <div class="input-group input-group-merge">
                                    <?php echo Form::vRequired()->id('urun_adi')->placeholder('Ürün adı')->text('urun_adi','',['class'=>'form-control']); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="aciklama">Açıklama:</label>
                                <div class="input-group input-group-merge">
                                    <?php echo Form::vRequired()->id('aciklama')->placeholder('Açıklama')->textarea('aciklama','',['class'=>'form-control']); ?>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-6">
                                    <label class="form-label" for="miktar">Adet:</label>
                                    <div class="input-group input-group-merge">
                                        <?php echo Form::vRequired()->id('miktar')->placeholder('Adet')->number('miktar',1,['class'=>'form-control']); ?>
                                    </div>
                                </div>
                                <div class="col-6">

                                    <label class="form-label" for="kdv">Kdv:</label>

                                    <div class="input-group input-group-merge">

                                        <?php

                                        $options = [ '' => '--Seçiniz--', '0' => '%0', '10' => '%10', '20' => '%20' ];
                                        echo Form::vRequired()->select('kdv', $options, '',['class'=>'form-control']);

                                        ?>
                                    </div>

                                </div>

                            </div>


                            <div class="col-12">
                                <label class="form-label" for="fiyat">Birim Fiyat (TL):</label>
                                <div class="input-group input-group-merge">
                                    <?php echo Form::vRequired()->id('fiyat')->placeholder('Fiyat')->text('fiyat','',['class'=>'form-control']); ?>
                                   <!-- <input type="text" class="form-control" required onkeyup="$(this).val($(this).val().replace(/,/g, '.'));" name="fiyat" id="fiyat" placeholder="Birim Fiyat" value="">-->
                                </div>
                            </div>

                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
            <?php echo Form::close(); ?>


        <?php

        }

        if(Post::action()=="faturaSil") {
            $id = Post::rowid();

            $sil = FaturaModel::sil($id);

            if($sil){

            }


        }

        if(Post::action()=="bildirimGonder"){

            $id = Post::rowid();
            $faturaDetay = FaturaModel::detay($id);

            ?>

            <?php echo  Form::csrf()->action('Faturalar/bildirimGonder/'.$id)->open('submitForm',['id'=>'submitForm']); ?>

            <div class="modal-header">
                <h4 class="modal-title">Bildirim Gönder</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="ekNot">Ek Not:</label><br>
                            <small>Ödeme hatırlatma bildirimine not eklemek isterseniz aşağıdaki alanı kullanabilirsiniz.</small>
                            <div class="input-group input-group-merge">
                                <?php echo Form::id('ekNot')->placeholder('Ek not')->textarea('ekNot','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Gönder</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="projeGeriBildirimDuzenle"){

            $id = Post::rowid();
            $detay = ProjeModel::geriBildirimDetay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('projeler/geriBildirimGuncelle/'.$id)->open('geriBildirimGuncelle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Geri Bildirim Düzenle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="aciklama"><strong>Bildirim:</strong></label>
                            <div class="input-group input-group-merge text-danger">
                                <?=$detay->detay?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="tur">Tür:</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="durum" id="durum">
                                    <option value="1" <?php if($detay->durum=="0"){ echo "selected"; } ?> >İşlem Bekliyor</option>
                                    <option value="2" <?php if($detay->durum=="1"){ echo "selected"; } ?> >İşlem Planına Alındı</option>
                                    <option value="3" <?php if($detay->durum=="2"){ echo "selected"; } ?> >Gerekli İşlem Yapıldı</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="cevap">Cevap:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('cevap')->placeholder('Cevap')->textarea('cevap',$detay->cevap,['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php


        }

        if(Post::action()=="yolHaritasiEkle"){

            $id = Post::rowid();
            $projeDetay = ProjeModel::detay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('projeler/yolHaritasiEkle/'.$id)->open('yolHaritasiEkle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Yol Haritası Ekle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="baslik">Baslik:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('baslik')->placeholder('Başlık')->text('baslik','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="aciklama">Açıklama:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('aciklama')->placeholder('Açıklama')->textarea('aciklama','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="sira">Sıra:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('sira')->placeholder('Sıra')->text('sira','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="aciklama">Durum:</label>
                            <div class="input-group input-group-merge">
                                <select name="durum" id="durum" class="form-control">
                                    <option value="0">Bekliyor</option>
                                    <option value="1">Gerçekleştirildi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="yolHaritasiDuzenle"){

            $id = Post::rowid();
            $detay = ProjeModel::yolHaritasiDetay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('projeler/yolHaritasiGuncelle/'.$id)->open('yolHaritasiGuncelle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Yol Haritası Ekle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="baslik">Baslik:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('baslik')->placeholder('Başlık')->text('baslik',$detay->baslik,['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="aciklama">Açıklama:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('aciklama')->placeholder('Açıklama')->textarea('aciklama',$detay->aciklama,['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="sira">Sıra:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('sira')->placeholder('Sıra')->text('sira',$detay->sira,['class'=>'form-control']); ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="aciklama">Durum:</label>
                            <div class="input-group input-group-merge">
                                <select name="durum" id="durum" class="form-control">
                                    <option value="0" <?php if($detay->durum=="0"){ echo "selected"; } ?>>Bekliyor</option>
                                    <option value="1" <?php if($detay->durum=="1"){ echo "selected"; } ?>>Gerçekleştirildi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="projePersonelEkle"){

            $id = Post::rowid();
            $projeDetay = ProjeModel::detay($id);
            $personeller = PersonelModel::tumListe();

            ?>

            <?php echo  Form::csrf()->method('post')->action('projeler/personelEkle/'.$id)->open('personelEkle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Projeye Çalışan Ekle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="personel_id">Dahil Olacak Personel:</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="personel_id" id="personel_id">
                                    <option value="">--Seçiniz--</option>
                                    <?php foreach($personeller as $personel){ ?>
                                    <option value="<?=$personel->id?>"><?=$personel->isim?></option>
                                    <?php }?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="gorevi">Projedeki Görevi:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('gorevi')->placeholder('Görevi')->textarea('gorevi','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="projePersonelDuzenle"){

            $id = Post::rowid();
            $projeDetay = ProjeModel::detay($id);
            $personelDetay = ProjeModel::personelDetay($id);
            $personeller = PersonelModel::tumListe();

            ?>

            <?php echo  Form::csrf()->method('post')->action('projeler/personelGuncelle/'.$id)->open('projePersonelDuzenle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Proje Çalışanı Düzenle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="personel_id">Dahil Olacak Personel:</label>
                            <div class="input-group input-group-merge">
                                <?=PersonelModel::isim($personelDetay->personel_id)?>

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="gorevi">Projedeki Görevi:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('gorevi')->placeholder('Görevi')->textarea('gorevi',$personelDetay->gorevi,['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="yapilanIslemEkle"){

            $id = Post::rowid();
            $projeDetay = ProjeModel::detay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('projeler/yapilanIslemEkle/'.$id)->open('yapilanIslemEkle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Yapilan İşlem Ekle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="baslik">Tür:</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="tur" id="tur">
                                    <option value="1" selected>Değişiklik yapıldı</option>
                                    <option value="2">Hata Çözümü Gerçekleştirildi</option>
                                    <option value="3">Yenilik Getirildi</option>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="islem">Yapılan İşlem:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('islem')->placeholder('işlem')->textarea('islem','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="link">Link:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::id('link')->placeholder('Göstermek istediğiniz birşey varsa URL ekleyin')->text('link','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="yapilanIslemDuzenle"){

            $id = Post::rowid();
            $detay = ProjeModel::yapilanIslemDetay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('projeler/yapilanIslemGuncelle/'.$id)->open('yapilanIslemGuncelle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Yol Haritası Ekle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="tur">Tür:</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="tur" id="tur">
                                    <option value="1" <?php if($detay->tur=="1"){ echo "selected"; } ?> >Değişiklik yapıldı</option>
                                    <option value="2" <?php if($detay->tur=="2"){ echo "selected"; } ?> >Hata Çözümü Gerçekleştirildi</option>
                                    <option value="3" <?php if($detay->tur=="3"){ echo "selected"; } ?> >Yenilik Getirildi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="aciklama">Yapılan İşlem:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('islem')->placeholder('İşlem')->textarea('islem',$detay->islem,['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="urunFiyatlariniGoster"){

            $id = Post::rowid();
            
            $urunBilgi = UrunModel::detay($id);

            ?>
                

                <table class="table table-bordered">
                        <tr>
                            <td><strong>KDV:</strong></td>
                            <td>
                                %<?php echo $urunBilgi->kdv; ?>
                            </td>
                        </tr>

                    <?php if($urunBilgi->odeme_turu=="U"){ ?>

                        <tr>
                            <td><strong>Ücretsiz:</strong></td>
                            <td>
                                0.00 <?php echo $urunBilgi->fiyat_birim; ?>
                            </td>
                        </tr>

                    <?php }elseif($urunBilgi->odeme_turu=="T") { ?>

                        <tr>
                            <td><strong>Tek Seferlik:</strong></td>
                            <td>
                               <?php echo $urunBilgi->fiyat; ?> <?php echo $urunBilgi->fiyat_birim; ?>
                            </td>
                        </tr>

                    <?php }else{ ?>

                        <tr>
                            <td><strong>Aylık:</strong></td>
                            <td>
                                <?php echo $urunBilgi->aylik_fiyat; ?> <?php echo $urunBilgi->fiyat_birim; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3 Aylık:</strong></td>
                            <td>
                                <?php echo $urunBilgi->uc_aylik_fiyat; ?> <?php echo $urunBilgi->fiyat_birim; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>6 Aylık:</strong></td>
                            <td>
                                <?php echo $urunBilgi->alti_aylik_fiyat; ?> <?php echo $urunBilgi->fiyat_birim; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Yıllık:</strong></td>
                            <td>
                                <?php echo $urunBilgi->yillik_fiyat; ?> <?php echo $urunBilgi->fiyat_birim; ?>
                            </td>
                        </tr>

                    <?php } ?>

                </table>

            <?php

        }

        if(Post::action()=="hatirlatmaEkle"){

           // $id = Post::rowid();
            //$hatirlatmaDetay = PlanlamaModel::hatirlatmaDetay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('planlama/hatirlatmaEkle')->open('hatirlatmaEkle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Hatırlatma Ekle</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                        <div class="mb-1 row">
                            <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Hatırlatma Notu</label>
                            <div class="col-sm-9">
                                <?php echo Form::vRequired()->id('aciklama')->placeholder('Hatırlatma Notu')->textarea('aciklama','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                        <div class="mb-1 row">
                            <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Periyod</label>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline  odeme_turuT">
                                    <input class="form-check-input" type="radio" name="periyod" id="periyod1" value="0" checked />
                                    <label class="form-check-label" for="periyod1">Tek Sefer</label>
                                </div>
                                <div class="form-check form-check-inline odeme_turuY">
                                    <input class="form-check-input" type="radio" name="periyod" id="periyod2" value="1" />
                                    <label class="form-check-label" for="periyod2">Yenilenen</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1 row">
                            <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Durum</label>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="durum" id="durum1" value="1" checked />
                                    <label class="form-check-label" for="durum1">Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="durum" id="durum2" value="0" />
                                    <label class="form-check-label" for="durum2">Pasif</label>
                                </div>
                            </div>
                        </div>


                    <div class="mb-1 row" id="yenilenen" style="display: none">
                        <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Zaman</label>
                        <div class="col-sm-3">
                            <select name="ay" class="form-select">
                                <option value="0">Her Ay</option>
                                <option value="01">Ocak</option>
                                <option value="02">Şubat</option>
                                <option value="03">Mart</option>
                                <option value="04">Nisan</option>
                                <option value="05">Mayıs</option>
                                <option value="06">Haziran</option>
                                <option value="07">Temmuz</option>
                                <option value="08">Agustos</option>
                                <option value="09">Eylül</option>
                                <option value="10">Ekim</option>
                                <option value="11">Kasım</option>
                                <option value="12">Aralık</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="gun" class="form-select">
                                <option value="0">Her Gün</option>
                                <?php
                                for($g=1;$g<=31;$g++){
                                    if($g<10) {
                                        $g = "0" . $g;
                                    } ?>
                                <option value="<?=$g?>"><?=$g?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="time" name="saat" class="form-control time-mask" placeholder="sa:dk" id="time" />
                        </div>

                    </div>

                    <div class="mb-1 row" id="tek" style="display: block">
                        <div class="mb-1 row">
                            <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Zaman</label>
                            <div class="col-sm-9">
                                <input type="datetime-local" name="zaman"  style="width: 100%" class="form-control time-mask" placeholder="sa:dk" id="time" />
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
            <script type="text/javascript">

                $(document).ready(function () {
                    $(".odeme_turuT").click(function(){
                        $("#tek").show();
                        $("#yenilenen").hide();
                    });
                    $(".odeme_turuY").click(function(){
                        $("#tek").hide();
                        $("#yenilenen").show();
                    });
                });
            </script>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="hatirlatmaDuzenle"){

            $id = Post::rowid();
            $detay = PlanlamaModel::hatirlatmaDetay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('planlama/hatirlatmaGuncelle/'.$id)->open('hatirlatmaGuncelle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Hatırlatma Düzenle</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="mb-1 row">
                        <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Hatırlatma Notu</label>
                        <div class="col-sm-9">
                            <?php echo Form::vRequired()->id('aciklama')->placeholder('Hatırlatma Notu')->textarea('aciklama',$detay->aciklama,['class'=>'form-control']); ?>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Periyod</label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline  odeme_turuT">
                                <input class="form-check-input" type="radio" name="periyod" id="periyod1" value="0" <?=$detay->periyod=="0"?"checked":""?> checked />
                                <label class="form-check-label" for="periyod1">Tek Sefer</label>
                            </div>
                            <div class="form-check form-check-inline odeme_turuY">
                                <input class="form-check-input" type="radio" name="periyod" id="periyod2" <?=$detay->periyod=="1"?"checked":""?> value="1" />
                                <label class="form-check-label" for="periyod2">Yenilenen</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Durum</label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="durum" id="durum1" value="1" <?=$detay->durum=="1"?"checked":""?> />
                                <label class="form-check-label" for="durum1">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="durum" id="durum2" value="0" <?=$detay->durum=="0"?"checked":""?> />
                                <label class="form-check-label" for="durum2">Pasif</label>
                            </div>
                        </div>
                    </div>


                    <div class="mb-1 row" id="yenilenen" style="display: <?=$detay->periyod=="0"?"none":"block"?>">
                        <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Zaman</label>
                        <div class="col-sm-3">
                            <select name="ay" class="form-select">
                                <option value="0" <?=$detay->ay=="0"?"selected":""?>>Her Ay</option>
                                <option value="01" <?=$detay->ay=="01"?"selected":""?>>Ocak</option>
                                <option value="02" <?=$detay->ay=="02"?"selected":""?>>Şubat</option>
                                <option value="03" <?=$detay->ay=="03"?"selected":""?>>Mart</option>
                                <option value="04" <?=$detay->ay=="04"?"selected":""?>>Nisan</option>
                                <option value="05" <?=$detay->ay=="05"?"selected":""?>>Mayıs</option>
                                <option value="06" <?=$detay->ay=="06"?"selected":""?>>Haziran</option>
                                <option value="07" <?=$detay->ay=="07"?"selected":""?>>Temmuz</option>
                                <option value="08" <?=$detay->ay=="08"?"selected":""?>>Agustos</option>
                                <option value="09" <?=$detay->ay=="09"?"selected":""?>>Eylül</option>
                                <option value="10" <?=$detay->ay=="10"?"selected":""?>>Ekim</option>
                                <option value="11" <?=$detay->ay=="11"?"selected":""?>>Kasım</option>
                                <option value="12" <?=$detay->ay=="12"?"selected":""?>>Aralık</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="gun" class="form-select">
                                <option value="0">Her Gün</option>
                                <?php
                                for($g=1;$g<=31;$g++){
                                    if($g<10) {
                                        $g = "0" . $g;
                                    } ?>
                                    <option value="<?=$g?>" <?=$detay->ay==$g?"selected":""?>><?=$g?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="time" name="saat" class="form-control time-mask" value="$detay->saat" placeholder="sa:dk" id="time" />
                        </div>

                    </div>

                    <div class="mb-1 row" id="tek" style="display: <?=$detay->periyod=="1"?"none":"block"?>">
                        <div class="mb-1 row">
                            <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Zaman</label>
                            <div class="col-sm-9">
                                <input type="datetime-local" name="zaman" value="<?=$detay->yil."-".$detay->ay."-".$detay->gun." ".$detay->saat?>"  style="width: 100%" class="form-control time-mask" placeholder="sa:dk" id="time" />
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
            <script type="text/javascript">

                $(document).ready(function () {
                    $(".odeme_turuT").click(function(){
                        $("#tek").show();
                        $("#yenilenen").hide();
                    });
                    $(".odeme_turuY").click(function(){
                        $("#tek").hide();
                        $("#yenilenen").show();
                    });
                });
            </script>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="etkinlikTurEkle"){

            // $id = Post::rowid();
             //$hatirlatmaDetay = PlanlamaModel::hatirlatmaDetay($id);
 
             ?>
 
             <?php echo  Form::csrf()->method('post')->action('planlama/etkinlikTurEkle')->open('etkinlikTurEkle'); ?>
 
             <div class="modal-header">
                 <h4 class="modal-title">Etkinlik Tür Ekle</h4>
             </div>
             <div class="modal-body">
                 <div class="row">
 
                         <div class="mb-1 row">
                             <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Hatırlatma Notu</label>
                             <div class="col-sm-9">
                                 <?php echo Form::vRequired()->id('tur')->placeholder('Tür Adı')->text('tur','',['class'=>'form-control']); ?>
                             </div>
                         </div>

                         <div class="mb-1 row">
                             <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Uyari Rengi</label>
                             <div class="col-sm-9">
                                 <?php echo Form::vRequired()->id('renk')->placeholder('Görüntülenme Rengi')->color('renk','',['class'=>'form-control']); ?>
                             </div>
                         </div>
                 </div>
 
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                 <button type="submit" class="btn btn-primary">Kaydet</button>
             </div>

 
             <?php echo Form::close(); ?>
 
             <?php
 
 
 
         }
 
         if(Post::action()=="etkinlikTurDuzenle"){
 
             $id = Post::rowid();
             $detay = PlanlamaModel::etkinlikTurDetay($id);
 
             ?>
 
             <?php echo  Form::csrf()->method('post')->action('planlama/etkinlikTurGuncelle/'.$id)->open('etkinlikTurGuncelle'); ?>
 
             <div class="modal-header">
                 <h4 class="modal-title">Etkinlik Türü Düzenle</h4>
             </div>
             <div class="modal-body">
                 <div class="row">
 
                     <div class="mb-1 row">
                         <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Başlık</label>
                         <div class="col-sm-9">
                             <?php echo Form::vRequired()->id('tur')->placeholder('Başlık')->text('tur',$detay->tur,['class'=>'form-control']); ?>
                         </div>
                     </div>

   
                     <div class="mb-1 row">
                             <label for="colFormLabelLg" class="col-sm-3 col-form-label-lg">Uyari Rengi</label>
                             <div class="col-sm-9">
                                 <?php echo Form::vRequired()->id('renk')->placeholder('Görüntülenme Rengi')->color('renk',$detay->renk,['class'=>'form-control']); ?>
                             </div>
                         </div>
 
                 </div>
 
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                 <button type="submit" class="btn btn-primary">Kaydet</button>
             </div>

 
             <?php echo Form::close(); ?>
 
             <?php
 
 
 
         }

        if(Post::action()=="altMasrafKalemDuzenle"){

            $id                 = Post::rowid();
            $detay              = MasrafModel::bilgi($id);
            $masrafKalemleri    = MasrafModel::masrafKalemleri();


            echo  Form::csrf()->method('post')->action('masraf/altKalemGuncelle/'.$id)->open('altKalemGuncelle'); ?>

                <input type="hidden" name="id" id="id" value="<?=$id?>">
                <div class="modal-header">
                    <h4 class="modal-title">Alt Masraf Grubu Düzenle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="input-group input-group-merge">
                        <label class="form-label">Ana Kalemler</label>
                        <div class="input-group input-group-merge">
                            <select class="form-control" id="ust" name="ust" required >
                                <option value="">--Seçiniz--</option>
                                <?php
                                foreach($masrafKalemleri['anaKalemler'] as $ust){ ?>
                                    <option value="<?=$ust->id?>" <?=$ust->id==$detay->ust?"selected":""?>><?=$ust->adi?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- /.input group -->
                    </div>

                    <div class="col-12">
                        <label class="form-label">Adı:</label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" value="<?=$detay->adi?>" id="adis" name="adi" placeholder="Adı" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Renk</label>
                        <div class="input-group input-group-merge">
                            <select class="form-control" name="renk" id="renk" required >
                                <option value="success" <?=$detay->renk=="success"?"selected":""?>>Yeşil</option>
                                <option value="info" <?=$detay->renk=="info"?"selected":""?>>Açık Mavi</option>
                                <option value="primary" <?=$detay->renk=="primary"?"selected":""?>>Koyu Mavi</option>
                                <option value="warning" <?=$detay->renk=="warning"?"selected":""?>>Sarı</option>
                                <option value="dark" <?=$detay->renk=="dark"?"selected":""?>>Siyah</option>
                                <option value="danger" <?=$detay->renk=="danger"?"selected":""?>>Kırmızı</option>
                            </select>
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

    
            <?php echo Form::close(); ?>

            <?php
        }

        if(Post::action()=="anaMasrafKalemDuzenle"){

            $id                 = Post::rowid();
            $detay              = MasrafModel::bilgi($id);


            echo  Form::csrf()->method('post')->action('masraf/anaKalemGuncelle/'.$id)->open('anaKalemGuncelle'); ?>

                <input type="hidden" name="id" id="id" value="<?=$id?>">
                <div class="modal-header">
                    <h4 class="modal-title">Alt Masraf Grubu Düzenle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                    <div class="col-12">
                        <label class="form-label">Adı:</label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" value="<?=$detay->adi?>" id="adis" name="adi" placeholder="Adı" required>
                        </div>
                    </div>

                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>

    
            <?php echo Form::close(); ?>

            <?php
        }

        if(Post::action()=="masrafEkle") {

            $masrafKalemleri    = MasrafModel::masrafKalemleri();

            $kasaHesaplari      = KasaModel::turHesaplari(1);
            $bankaHesaplari     = KasaModel::turHesaplari(2);
            $posHesaplari       = KasaModel::turHesaplari(3);
            $kkartiHesaplari    = KasaModel::turHesaplari(4);
            $veresiyeHesaplari  = KasaModel::turHesaplari(5);
            $digerHesaplar      = KasaModel::turHesaplari(6);


            ?>
                            <form action="<?=URL::site('masraf/masrafEkle')?>" method="post">
                                <div class="modal-header">
                                    <h4 class="modal-title">Masraf Ekle</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="col-6">
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Masraf K.</label>
                                                <div class="input-group input-group-merge">
                                                    <select class="form-control" name="kalem" required >
                                                        <option value="">--Seçiniz--</option>
                                                        <?php
                                                        foreach($masrafKalemleri['anaKalemler'] as $ustList){ ?>
                                                        <optgroup label="<?=$ustList->adi?>">
                                                            <?php
                                                            foreach($masrafKalemleri['altKalemler'] as $altKalemList){
                                                            

                                                                if($altKalemList->ust==$ustList->id){

                                                                ?>

                                                                <option value="<?=$altKalemList->id?>"><?=$altKalemList->adi?></option>
                                                                <?php
                                                                }

                                                            } ?>
                                                        </optgroup>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Belge No:</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" name="belge_no" id="belge_no" placeholder="Fiş / Fatura No" value="">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Açıklama:</label>
                                                <div class="input-group input-group-merge">
                                                    <textarea class="form-control" name="aciklama" placeholder="Açıklama"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Dosya:</label>
                                                <div class="input-group input-group-merge">
                                                    <label class="input-group-btn">
                                                                    <span class="btn btn-primary">
                                                                        <i class="fa fa-upload"></i> Masraf Belgesi Seç <input type="file" name="belge_dosya" style="display: none;">
                                                                    </span>
                                                    </label>
                                                    <input type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Ödeme:</label>
                                                <div class="input-group input-group-merge">
                                                    <select name="odeme_durumu" id="gizleGoster" data-name="kasalar" required class="form-control">
                                                        <option value="1">Ödendi</option>
                                                        <option value="0">Ödenmedi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Ödeme Hesabı:</label>
                                                <div class="input-group input-group-merge">
                                                    <select name="kasa" required class="form-control">
                                                        <option value="0">--Seçiniz--</option>
                                                        <optgroup label="Kasa Hesapları">
                                                            <?php foreach($kasaHesaplari as $kh){ ?>
                                                            <option value="<?=$kh->id?>"><?=$kh->adi?></option>
                                                            <?php }?>
                                                        </optgroup>
                                                        <optgroup label="Banka Hesapları">
                                                            <?php foreach($bankaHesaplari as $bh){ ?>
                                                            <option value="<?=$bh->id?>"><?=$bh->adi?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <optgroup label="POS Hesapları">
                                                            <?php foreach($posHesaplari as $ph){ ?>
                                                            <option value="<?=$ph->id?>"><?=$ph->adi?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <optgroup label="Kredi Kartı Hesapları">
                                                            <?php foreach($kkartiHesaplari as $kkh) { ?>
                                                            <option value="<?=$kkh->id?>"><?=$kkh->adi?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <optgroup label="Veresiye Hesapları">
                                                            <?php foreach($veresiyeHesaplari as $vh){ ?>
                                                            <option value="<?=$vh->id?>"><?=$vh->adi?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <optgroup label="Diğer Hesaplar">
                                                            <?php foreach($digerHesaplar as $dh){ ?>
                                                            <option value="<?=$dh->id?>"><?=$dh->adi?></option>
                                                            <?php } ?>
                                                        </optgroup>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Ödeme Tarihi:Güm.Ay.Yıl</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" name="odeme_tarihi" class="form-control" placeholder="24.10.2023"onkeyup="
                                                    var v = this.value;
                                                    if (v.match(/^\d{2}$/) !== null) {
                                                        this.value = v + '.';
                                                    } else if (v.match(/^\d{2}\.\d{2}$/) !== null) {
                                                        this.value = v + '.';
                                                    }" maxlength="10" value="<?=Date::current()?>">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="modalAddCardNumber">Tutar (TL):</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="text" class="form-control" onkeyup="$(this).val($(this).val().replace(/,/g, '.'));" name="tutar" id="belge_no" placeholder="Ödenen tutar" value="">
                                                </div>
                                            </div>

                                        </div>

                                    </div>




                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </div>
                            </form>

        <?php
        }

        if(Post::action()=="teklifeUrunEkle") {
            $id = Post::rowid();
            ?>

            <?php echo Form::csrf()->method('post')->action('teklifler/kalemEkle/'.$id)->open('urunEkle'); ?>

                <div class="modal-header">
                    <h4 class="modal-title">Teklif Kalemi Ekle</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <div class="col-12">
                                <label class="form-label" for="urun_adi">Fatura Kalem Adı:</label>
                                <div class="input-group input-group-merge">
                                    <?php echo Form::vRequired()->id('urun_adi')->placeholder('Ürün adı')->text('urun_adi','',['class'=>'form-control']); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="aciklama">Açıklama:</label>
                                <div class="input-group input-group-merge">
                                    <?php echo Form::vRequired()->id('aciklama')->placeholder('Açıklama')->textarea('aciklama','',['class'=>'form-control']); ?>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-6">
                                    <label class="form-label" for="miktar">Adet:</label>
                                    <div class="input-group input-group-merge">
                                        <?php echo Form::vRequired()->id('miktar')->placeholder('Adet')->number('miktar',1,['class'=>'form-control']); ?>
                                    </div>
                                </div>
                                <div class="col-6">

                                    <label class="form-label" for="kdv">Kdv:</label>

                                    <div class="input-group input-group-merge">

                                        <?php

                                        $options = [ '' => '--Seçiniz--', '0' => '%0', '10' => '%10', '20' => '%20' ];
                                        echo Form::vRequired()->select('kdv', $options, '',['class'=>'form-control']);

                                        ?>
                                    </div>

                                </div>

                            </div>


                            <div class="col-12">
                                <label class="form-label" for="fiyat">Birim Fiyat (TL):</label>
                                <div class="input-group input-group-merge">
                                    <?php echo Form::vRequired()->id('fiyat')->placeholder('Fiyat')->text('fiyat','',['class'=>'form-control']); ?>
                                   <!-- <input type="text" class="form-control" required onkeyup="$(this).val($(this).val().replace(/,/g, '.'));" name="fiyat" id="fiyat" placeholder="Birim Fiyat" value="">-->
                                </div>
                            </div>

                        </div>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
            <?php echo Form::close(); ?>


        <?php

        }

        if(Post::action()=="destekDepartmanEkle"){

            $id = Post::rowid();
            $personeller = PersonelModel::tumListe();

            ?>

            <?php echo  Form::csrf()->method('post')->action('destek/departmanEkle/')->open('departmanEkle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Destek Departmanı Ekle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="yetkili_personel">Yetkili Personel</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="yetkili_personel" id="yetkili_personel">
                                    <option value="">--Seçiniz--</option>
                                    <?php foreach($personeller as $personel){ ?>
                                    <option value="<?=$personel->id?>"><?=$personel->isim?></option>
                                    <?php }?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="adi">Departman Adı:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('adi')->placeholder('Departman Adı')->text('adi','',['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="durum">Durum</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="durum" id="durum">
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>

                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }
        if(Post::action()=="destekDepartmanDuzenle"){

            $id = Post::rowid();
            $personeller = PersonelModel::tumListe();
            $detay = DestekModel::deparmanDetay($id);

            ?>

            <?php echo  Form::csrf()->method('post')->action('destek/departmanGuncelle/'.$id)->open('departmanGuncelle'); ?>

            <div class="modal-header">
                <h4 class="modal-title">Destek Departmanı Düzenle</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="yetkili_personel">Yetkili Personel</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="yetkili_personel" id="yetkili_personel">
                                    <option value="">--Seçiniz--</option>
                                    <?php foreach($personeller as $personel){ ?>
                                    <option value="<?=$personel->id?>" <?=$detay->yetkili_personel == $personel->id ? 'selected' : ''?>><?=$personel->isim?></option>
                                    <?php }?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="adi">Departman Adı:</label>
                            <div class="input-group input-group-merge">
                                <?php echo Form::vRequired()->id('adi')->placeholder('Departman Adı')->text('adi',$detay->adi,['class'=>'form-control']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <label class="form-label" for="durum">Durum</label>
                            <div class="input-group input-group-merge">
                                <select class="form-control" required name="durum" id="durum">
                                    <option value="1" <?=$detay->durum == '1' ? 'selected' : ''?>>Aktif</option>
                                    <option value="0" <?=$detay->durum == '0' ? 'selected' : ''?>>Pasif</option>
                                </select>

                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>

            <?php echo Form::close(); ?>

            <?php



        }

        if(Post::action()=="mesaiSaatiEkle"){

            $personelDetay = PersonelModel::detay(Post::rowid());
            $personelListe          = PersonelModel::calisanlar();
            ?>

                <form class="form-horizontal" action="<?=URL::site('personel/mesaiEkle')?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title"><?=$personelDetay->isim?> Mesai Ekle<Masraf Ekle</h4>
                    </div>

                    <div class="modal-body">

                    
                        <div class="col-12">
                            
                            <label class="form-label" for="modalAddCardNumber">Personel</label>
                            <div class="input-group input-group-merge">
                                <select name="personel" required class="form-control select2"  style="width: 100%;">
                                <?php 
                                    foreach($personelListe as $prsnl){
                                    ?>
                                            <option value="<?=$prsnl->id?>" <?=$prsnl->id==$personelDetay->id?'selected':''?> >
                                            <?=$prsnl->isim?>
                                        </option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label" for="modalAddCardNumber">Giriş Tarihi</label>
                                <div class="input-group input-group-merge">
                                    <input type="date" class="form-control" required name="giris_tarihi" value="<?=date("Y-m-d",strtotime('-1 day',strtotime(date("Y-m-d"))))?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="modalAddCardNumber">Giriş Saati</label>
                                <div class="input-group input-group-merge">
                                <input type="time" class="form-control" value="<?=AyarModel::defaultAyarlar('gunlukCalismaBaslangicSaati')?>" required name="giris_saati">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="modalAddCardNumber">Çıkış Tarihi</label>
                                <div class="input-group input-group-merge">
                                    <input type="date" class="form-control" required name="cikis_tarihi" value="<?=date("Y-m-d",strtotime('-1 day',strtotime(date("Y-m-d"))))?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="modalAddCardNumber">Çıkış Saati</label>
                                <div class="input-group input-group-merge">
                                <input type="time" class="form-control" value="<?=AyarModel::defaultAyarlar('gunlukCalismaBitisSaati')?>" required name="cikis_saati">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            
                            <label class="form-label" for="modalAddCardNumber">Fazla Mesai Sebebi</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" name="fazla_mesai_sebebi">
                            </div>
                        </div>

                        <div class="row custom-options-checkable g-1 mb-1 mt-1">

                            <div class="col-md-4">
                                <input class="custom-option-item-check" type="radio" name="izin_durumu" id="izin_durumu1" value="1" checked />
                                <label class="custom-option-item p-1" for="izin_durumu1">
                                <span class="d-flex justify-content-between flex-wrap mb-50">
                                    <span class="fw-bolder">Çalışıyor</span>
                            </div>

                            <div class="col-md-4">
                                <input class="custom-option-item-check" type="radio" value="1" name="izin_durumu" id="izin_durumu2" />
                                <label class="custom-option-item p-1" for="izin_durumu2">
                                <span class="d-flex justify-content-between flex-wrap mb-50">
                                    <span class="fw-bolder">Ücretsiz İzinli</span>
                            </div>

                            <div class="col-md-4">
                                <input class="custom-option-item-check" type="radio" value="2" name="izin_durumu" id="izin_durumu3" />
                                <label class="custom-option-item p-1" for="izin_durumu3">
                                <span class="d-flex justify-content-between flex-wrap mb-50">
                                    <span class="fw-bolder">Ücretli İzinli</span>
                            </div>

                            
                        </div>

                        <div class="col-12">
                            
                            <label class="form-label" for="modalAddCardNumber">Kayıt Türü</label>
                            <div class="input-group input-group-merge">
                                    <select class="form-control select2" style="width: 100%" name="kayit_turu">
                                        <option value="N">Normal Çalışma</option>
                                        <option value="HT">Hafta Tatili</option>
                                        <option value="R">Raporlu</option>
                                        <option value="I">Ücretli İzinli</option>
                                        <option value="UI">Ücretsiz İzinli</option>
                                        <option value="T">Resmi Tatil</option>
                                        <option value="SI">Saatlik İzin</option>
                                        <option value="YI">Yıllık İzinli</option>
                                    </select>
                            </div>
                        </div>

                        <div class="row custom-options-checkable g-1 mb-1 mt-1">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <label class="form-label" for="ise_gelis_yol_ucreti1">İşe Geliş Yol Ücreti</label>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="ise_gelis_yol_ucreti" id="ise_gelis_yol_ucreti1" value="1" checked />
                                                <label class="custom-option-item p-1" for="ise_gelis_yol_ucreti1">
                                                <span class="d-flex justify-content-between flex-wrap mb-50">
                                                    <span class="fw-bolder">Verilecek</span>
                                                </span></label>
                                            </div>

                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" value="0" name="ise_gelis_yol_ucreti" id="ise_gelis_yol_ucreti2" />
                                                <label class="custom-option-item p-1" for="ise_gelis_yol_ucreti2">
                                                <span class="d-flex justify-content-between flex-wrap mb-50">
                                                    <span class="fw-bolder">Verilmeyecek</span>
                                                    </span></label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                        <label class="form-label" for="isten_cikis_yol_ucreti1">İşten Çıkış Yol Ücreti</label>
                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" name="isten_cikis_yol_ucreti" id="isten_cikis_yol_ucreti1" value="1" checked />
                                                <label class="custom-option-item p-1" for="isten_cikis_yol_ucreti1">
                                                <span class="d-flex justify-content-between flex-wrap mb-50">
                                                    <span class="fw-bolder">Verilecek</span>
                                                    </span></label>
                                            </div>

                                            <div class="col-md-6">
                                                <input class="custom-option-item-check" type="radio" value="0" name="isten_cikis_yol_ucreti" id="isten_cikis_yol_ucreti2" />
                                                <label class="custom-option-item p-1" for="isten_cikis_yol_ucreti2">
                                                <span class="d-flex justify-content-between flex-wrap mb-50">
                                                    <span class="fw-bolder">Verilmeyecek</span>
                                                    </span></label>
                                            </div>
                                        </div>

                                        
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            
                            <label class="form-label" for="modalAddCardNumber">Yemek Hakediş Adedi</label>
                            <div class="input-group input-group-merge">
                                <input type="number" class="form-control" value="1" name="yemek_hakedis">
                            </div>
                        </div>

                        <div class="col-12">
                            
                            <label class="form-label" for="modalAddCardNumber">Günlük Not</label>
                            <div class="input-group input-group-merge">
                                <input type="number" class="form-control" name="gunluk_not">
                            </div>
                        </div>
            
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>



        
                </form>
            <?php

        }


    }


    public function s404(){

    }
}