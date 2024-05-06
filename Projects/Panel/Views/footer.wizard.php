<div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <!--<footer class="footer footer-light">
        <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT &copy; {{date('Y')}}<a class="ms-25" href="https://www.bursayazilim.com" target="_blank">Bursa Yazılım</a><span class="d-none d-sm-inline-block">, Tüm Hakları Saklıdır</span></span><span class="float-md-end d-none d-md-block">Yazılım Gücü<i data-feather="heart" data-toogle="tooltip" title="Yürekten geliyor"></i></span></p>
    </footer>-->
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script src="vendors/js/forms/select/select2.full.min.js"></script>

    <!-- BEGIN: Page Vendor JS-->
    <script src="vendors/js/ui/jquery.sticky.js"></script>
    <script src="vendors/js/calendar/fullcalendar.min.js"></script>
    <!-- <script src="vendors/js/charts/apexcharts.min.js"></script> -->
    <script src="vendors/js/extensions/toastr.min.js"></script>
    <script src="vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <!-- END: Page Vendor JS-->

    <script src="js/scripts/jquery-ui.min.js"></script> <!-- jquery kütüphanelerimizi ekliyoruz -->
    <script src="js/scripts/jquery.ui.touch-punch.min.js"></script> 
    <!-- Bu JS dosyası ile Mobil cihazlar ve tabletlerde sürükle bıark özelliğini aktif ediyoruz-->

    <!-- BEGIN: Theme JS-->
    <script src="js/core/app-menu.js"></script>
    <script src="js/core/app.js"></script>
    <script src="js/scripts/forms/form-repeater.js"></script>

    <script src="js/scripts/pages/auth-login.js"></script>
    <script src="js/scripts/forms/form-select2.js"></script>
   <!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDizM76Gj0ty8oFWl96MWJ_5y4b9FLvWyw&libraries=places"></script>
    <script type='text/javascript' src='js/scripts/gmap.js'></script>-->
    <script type='text/javascript' src='vendors/js/pickers/flatpickr/flatpickr.min.js'></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>




@if(CURRENT_CONTROLLER=='Planlama')

    <script type="text/javascript">

    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        dayMaxEventRows: 3,
        height: 750,
        selectable: true,
        events: '{{URL::site("ajax/etkinlikListe")}}',
        select: function (start, end, allDay) {
            $("#add-new-sidebar").modal("show");
            $("#startDate").val(start.startStr+" 09:00");
            $("#endDate").val(start.startStr+" 19:00");
        },
        eventClick: function(info) {
            //alert('Event: ' + info.event.extendedProps.tur);
            //alert('id: ' + info.event.end);

            
            $("#eventTitle").html(info.event.title);

            var startDateObj = new Date(info.event.start);

            var formattedStartDate = ('0' + startDateObj.getDate()).slice(-2) + '.' + ('0' + (startDateObj.getMonth() + 1)).slice(-2) + '.' + startDateObj.getFullYear();


            $("#start").html(formattedStartDate+" - "+info.event.extendedProps.sTime);

            var start = info.event.start;
            var end = info.event.end || start;

            var endDateObj = new Date(end);

            var formattedendDate = ('0' + endDateObj.getDate()).slice(-2) + '.' + ('0' + (endDateObj.getMonth() + 1)).slice(-2) + '.' + endDateObj.getFullYear();

            $("#end").html(formattedendDate+" - "+info.event.extendedProps.eTime);

            $("#eventTur").html(info.event.extendedProps.tur);
            $("#location").html(info.event.extendedProps.lctn);
            $("#description").html(info.event.extendedProps.description);
            $("#users").html(info.event.extendedProps.allUsers);

            if (info.event.extendedProps.sUrl!="") {
                $("#url").attr('href',info.event.extendedProps.sUrl);
            }

            $("#eventDelete").attr('href','{{URL::site("planlama/etkinlikSil")}}/'+info.event.id);
            $("#eventEdit").attr('data-id',info.event.id);

            if (info.event.extendedProps.mailInfo=="1") {
                $("#mailInfo").html('Gönderildi');
            }else{
                $("#mailInfo").html('Gönderilmedi');
            }


            if (info.event.extendedProps.smsInfo=="1") {
                $("#smsInfo").html('Gönderildi');
            }else{
                $("#smsInfo").html('Gönderilmedi');
            }
            

            $("#eventModal").modal("show");


        }
    });

    calendar.render();
    });
    </script>

@endif
@if(CURRENT_CONTROLLER=='Rapor')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script type="text/javascript">

    $(document).ready(function () {

        var elems = document.getElementsByClassName('confirm');
        var confirmIt = function (e) {
            if (!confirm('Bunu yapmak istediğinize EMİN MİSİNİZ ? \nBu işlemin geri dönüşü yoktur, \nBu işlem; Bu veriye bağlı diğer verilerin görünmemesine sebep olabilir!')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }

        var options = {
            chart: {
                height: 500,
                type: "area",
                
            },
            dataLabels: {
                enabled: false
            },
            series: [
                {
                    type: 'line',
                name: "Gelir",
                data: [{{$gelir}}],
                color: '#009E2F'
                }
                ,
                {
                type: 'line',
                name: "Gider",
                data: [{{$gider}}],
                color: '#ff0000'
                }
                ,
                {
                    type: 'column',
                name: "Kasa Toplamı",
                data: [{{$kasaToplami}}],
                color: '#356BFF'
                }
            ],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [50, 90, 100]
                },
                pattern: {
                    style: 'verticalLines',
                    width: 6,
                    height: 6,
                    strokeWidth: 2
                }
            },
            xaxis: {
                categories: [{{$kategoriler}}],
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                    return value + "₺";
                    }
                },
                },
            stroke: {
                show: true, 
                curve: 'straight', //'straight', 'smooth', 'monotoneCubic', 'stepline'
                lineCap: 'butt',
                colors: undefined,
                width: 3,
                dashArray: 0, 
            }
        };

        var chart = new ApexCharts(document.querySelector("#gelirGiderGrafigi"), options);

        chart.render();
 
    });
    
    

        
    </script>

@endif

<script type="text/javascript">
    $(document).ready(function() {

        $(document).ready(function(){
            // Ödeme seçimi her değiştiğinde tetiklenecek olan event listener'ı ekle
            $("#odeme").change(function(){
                // Seçili ödeme durumunu al
                var odemeDurumu = $(this).val();
                // MasrafGirisi div elementini seç
                var masrafGirisiDiv = $("#masrafGirisi");

                // Eğer ödeme yapıldı ise masrafGirisi div'ini görünür yap, yapılmadı ise gizle
                if (odemeDurumu === "1") {
                    masrafGirisiDiv.slideDown();
                } else {
                    masrafGirisiDiv.slideUp();
                }
            });

            // Sayfa yüklendiğinde önceki seçimin durumunu kontrol et
            $("#odeme").trigger("change");
        });

        $('#summernote').summernote({
            placeholder: 'Müşterilerinize Göstereceğiniz Detayları Girin',
            tabsize: 2,
            height: 200
        });
        $('#summernote2').summernote({
            placeholder: 'Müşterilerinize Göstereceğiniz Cevabı Girin',
            tabsize: 2,
            height: 200
        });
        let buttons = $('.note-editor button[data-toggle="dropdown"]');

        buttons.each((key, value)=>{
            $(value).on('click', function(e){
                $(this).attr('data-bs-toggle', 'dropdown')
                console.log()
                ata('id', 'dropdownMenu');
            })
        })
    });
</script>


<script src="js/by.js"></script>
    <!-- END: Page JS-->

@if(CURRENT_CONTROLLER=='BolgeselAyarlar')

<script type="text/javascript">

    $(document).ready(function (){

        $('.editButon').on('click',function () {

            var action       =   $(this).attr('data-action');

            $('#modals-add').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children('td').map(function () {
                return $(this).text();
            })

            console.log(data);

            if(action=="dilDuzenle"){

                $('#dataAction').val('dilGuncelle');
                $('#update_id').val(data[0]);
                $('#baslik').val(data[1]);
                $('#kod').val(data[2]);
                $('#image').val(data[3]);
                $('#sira').val(data[4]);
                if (data[5]=="Aktif"){
                    var durum = "1";
                }else {
                    var durum = "0";
                }
                $('#durum').val(durum);

            }
          
           
            else if(action=="sehirDuzenle")    {

                $('#dataAction').val('sehirGuncelle');
                $('#update_id').val(data[0]);
                $('#il').val(data[1]);
                $('#plaka').val(data[2]);
                $('#siralama').val(data[3]);
                $('#kod').val(data[4]);


            }
            else if(action=="paraBirimiDuzenle")    {

                $('#dataAction').val('paraBirimiGuncelle');
                $('#update_id').val(data[0]);
                $('#para').val(data[1]);
                $('#kod').val(data[2]);
                $('#sembol').val(data[3]);
                $('#guncel_kur').val(data[4]);


            }
          
             else{}

        })



    });

</script>

@endif

@if(CURRENT_CONTROLLER=='Sektorler')

<script type="text/javascript">

    $(document).ready(function (){

        $('.editButon').on('click',function () {

            var action       =   $(this).attr('data-action');

            $('#modals-add').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children('td').map(function () {
                return $(this).text();
            })

            //console.log(data);

            if(action=="sektorDuzenle")    {

                $('#dataAction').val('sektorGuncelle');
                $('#update_id').val(data[0]);
                $('#sektor_adi').val(data[1]);
                if (data[3]=="Aktif"){
                    var durum = "1";
                }else {
                    var durum = "0";
                }
                $('#durum').val(durum);


            }else{}

        })

    });

</script>

@endif

@if(CURRENT_CONTROLLER=='Masraf')

<script type="text/javascript">



    $(document).ready(function (){

        $("#altKalemEkle").on('hide.bs.modal', function(){
            $('#dataAction').val('altKalemEkle');
            $('#update_id').val('');
        });

        $("#anaKalemEkle").on('hide.bs.modal', function(){
            $('#dataAction').val('anaKalemEkle');
            $('#update_id').val('');
        });

    });

</script>

@endif

@if(CURRENT_CONTROLLER=='Urun')

<script type="text/javascript">

    $(document).ready(function (){

        $('.editButon').on('click',function () {

            var action       =   $(this).attr('data-action');

            $('#modals-add').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children('td').map(function () {
                return $(this).text();
            })

            console.log(data);

            if(action=="grupDuzenle")    {

                $('#dataAction').val('grupGuncelle');
                $('#update_id').val(data[0]);
                $('#sira').val(data[1]);
                $('#adi').val(data[2]);

                if (data[3]=="Aktif"){
                    var durum = "1";
                }else {
                    var durum = "0";
                }
                $('#durum').val(durum);


            }else{}

        })

    });

</script>

@endif

<script type="text/javascript">

    $(document).ready(function(){

        $('#openModal').on('show.bs.modal', function (e) {
            $('.fetched-data').html('<div class="col-md-12 text-center"><img src="{{URL::site()}}../Uploads/gif/loading.gif" class="img-responsive"> </div>');
            var rowid = $(e.relatedTarget).data('id');
            var action = $(e.relatedTarget).data('action');
            $.ajax({
                type : "post",
                url : "{{URL::site('Ajax/modal')}}", //Here you will fetch records
                data :  "rowid="+ rowid+"&action="+action, //Pass $id
                success : function(data){
                    $('.fetched-data').html(data);//Show fetched data from database
                }
            });
        });

    });



</script>

<script type="text/javascript">
    $(document).ready(function () {
    $("#sort").sortable({ 
        axis: 'y', 
        revert: true, 
        stop: function (event, ui) {
            var data = $(this).sortable('serialize');
            $.ajax({
                type: "POST", 
                data: data+"&action={{$siralamaYeri}}",
                url: "{{URL::site('Ajax/sort')}}",
                success: function (data) { 
                    if (data == "success") {
                        $('#resultJS').html("<strong class='text-success'> Sonuç: Sıra Değişikliği Başarılı</strong>"); 
                      
                        window.location.reload();
                    }
                    else {
                        $('#resultJS').html("<strong class='text-danger'>  Sonuç: Sıra Değişikliği  Başarısız</strong>");
                   
                    }
                }
            });
        }
    });
});

$('#widget').draggable();  

</script>
<script type="text/javascript">
    $('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
}); 
</script>
<script type="text/javascript">
                $(document).ready(function(){
                var i=1;
                $('#addRow').click(function(){

                    formHtml = '<tr id="row'+i+'">';
                    formHtml += '<td><input style="min-width: 300px" type="text" name="urun[]" id="urun" class="form-control">';
      
                    formHtml += '<td style="min-width: 400px"><input type="text" name="aciklama[]" id="aciklama" class="form-control"></td>';
             
                    formHtml += '<td><input type="number" name="miktar[]" id="miktar_'+i+'" value="1" class="miktar form-control"></td>';

                    formHtml += '<td><div class="input-group"><input type="text" name="fiyat[]" id="fiyat_'+i+'" class="fiyat form-control"><span class="input-group-text">₺</span></div></td>';
                    formHtml += '<td><select name="kdv[]" id="kdv_'+i+'" class="kdv form-control"><option value="">--Seçiniz--</option><option value="0">%0</option><option value="10">%10</option><option value="20" selected>%20</option></select></td>';
                    formHtml += '<td><div class="input-group"><input type="text" name="tutar[]" readonly id="tutar_'+i+'" class="tutar form-control"><span class="input-group-text">₺</span></div></td>';
                    formHtml += '<td><a name="remove" id="'+i+'" class="text-danger btn_remove"><i data-feather="trash" class="me-50"></i></a></td>';

                    formHtml += '</tr>';

                    i++;
                    $('#addDataTable').append(formHtml);
                    feather.replace();

                    // Tüm tr satırlarını seç
                    var satirlar = document.querySelectorAll("#addDataTable tr");

                    // Her satırdaki input alanlarını bul
                    satirlar.forEach(function(satir, index) {
                        var miktarInput = satir.querySelector(".miktar");
                        var fiyatInput = satir.querySelector(".fiyat");
                        var tutarInput = satir.querySelector(".tutar");

                        // Her miktar veya fiyat değiştiğinde hesaplama yap
                        miktarInput.addEventListener("input", hesaplaTutar);
                        fiyatInput.addEventListener("input", hesaplaTutar);

                        function hesaplaTutar() {
                            var miktar = miktarInput.value;
                            var fiyat = fiyatInput.value;
                            var kdv = satir.querySelector(".kdv").value;

                            // Hesaplama yap
                            var tutar = (miktar * fiyat) * (1 + kdv / 100);
                            
                            // Tutar alanını güncelle
                            tutarInput.value = tutar.toFixed(2);

                            // Genel toplamı hesapla
                            genelToplamHesapla();
                        }
                    });

                });

                $(document).on('click', '.btn_remove', function(){
                    var button_id = $(this).attr("id");
                    $('#row'+button_id+'').remove();
                });
                $(document).on('click', '.btn_addText', function(){
                    var button_id = $(this).attr("id");
                    $('#uyariText'+button_id+'').html('<input type="text" name="uyari[]" class="form-control">');
                });
                
            });


                $(document).ready(function(){
                    var i=1;
                    $('#addRowSupplierProduct').click(function(){

                        formHtml = '<tr id="row'+i+'">';
                        formHtml += '<td><input style="min-width: 200px" type="text" required  name="urun[]" id="urun" class="form-control"></td>';
                        formHtml += '<td><select style="min-width: 200px" class="select2 form-select" name="ilgili_urun[]" id="ilgili_urun">';
                        formHtml += '<option value="">--Seçiniz--</option>';
                        @foreach($tumUrunler as $furun)
                            formHtml += '<option value="{{ $furun->id }}">{{ $furun->adi }}</option>';
                        @endforeach
                        formHtml += '</select></td>';

                        formHtml += '<td><input type="text" name="aciklama[]" id="aciklama" class="form-control"></td>';

                        formHtml += '<td><input type="number" name="miktar[]" id="miktar_'+i+'" value="1" class="miktar form-control"></td>';

                        formHtml += '<td><div class="input-group"><input type="text" name="fiyat[]" id="fiyat_'+i+'" value="0" class="fiyat form-control"><span class="input-group-text">₺</span></div></td>';
                        formHtml += '<td><select name="kdv[]" id="kdv_'+i+'" class="kdv form-control"><option value="">--Seçiniz--</option><option value="0">%0</option><option value="10">%10</option><option value="20" selected>%20</option></select></td>';
                        formHtml += '<td><div class="input-group"><input type="text" name="tutar[]" value="0" readonly id="tutar_'+i+'" class="tutar form-control"><span class="input-group-text">₺</span></div></td>';
                        formHtml += '<td><a name="remove" id="'+i+'" class="text-danger btn_remove"><i data-feather="trash" class="me-50"></i></a></td>';

                        formHtml += '</tr>';

                        i++;
                        $('#addDataTableSupplierProduct').append(formHtml);
                        feather.replace();

                        // Tüm tr satırlarını seç
                        var satirlar = document.querySelectorAll("#addDataTableSupplierProduct tr");

                        // Her satırdaki input alanlarını bul
                        satirlar.forEach(function(satir, index) {
                            var miktarInput = satir.querySelector(".miktar");
                            var fiyatInput = satir.querySelector(".fiyat");
                            var tutarInput = satir.querySelector(".tutar");

                            // Her miktar veya fiyat değiştiğinde hesaplama yap
                            miktarInput.addEventListener("input", hesaplaTutar);
                            fiyatInput.addEventListener("input", hesaplaTutar);

                            function hesaplaTutar() {
                                var miktar = miktarInput.value;
                                var fiyat = fiyatInput.value;
                                var kdv = satir.querySelector(".kdv").value;

                                // Hesaplama yap
                                var tutar = (miktar * fiyat) * (1 + kdv / 100);

                                // Tutar alanını güncelle
                                tutarInput.value = tutar.toFixed(2);

                                // Genel toplamı hesapla
                                genelToplamHesapla();
                            }
                        });

                    });

                    $(document).on('click', '.btn_remove', function(){
                        var button_id = $(this).attr("id");
                        $('#row'+button_id+'').remove();
                    });
                    $(document).on('click', '.btn_addText', function(){
                        var button_id = $(this).attr("id");
                        $('#uyariText'+button_id+'').html('<input type="text" name="uyari[]" class="form-control">');
                    });

                });


            
                        

                        // Genel toplamı hesapla
                        function genelToplamHesapla() {
                            var tutarlar = document.querySelectorAll(".tutar");
                            var genelToplam = 0;
                            tutarlar.forEach(function(tutarInput) {
                                genelToplam += parseFloat(tutarInput.value);
                            });

                            // Genel toplam alanını güncelle
                            document.querySelector(".genel_toplam").textContent = genelToplam.toFixed(2) + " ₺";
                        }
            
            </script>