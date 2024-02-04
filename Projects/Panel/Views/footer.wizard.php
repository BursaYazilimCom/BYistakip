<div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-light">
        <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2022<a class="ms-25" href="https://www.bursayazilim.com" target="_blank">Bursa Yazılım</a><span class="d-none d-sm-inline-block">, Tüm Hakları Saklıdır</span></span><span class="float-md-end d-none d-md-block">Yazılım Gücü<i data-feather="heart" data-toogle="tooltip" title="Yürekten geliyor"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script src="vendors/js/forms/select/select2.full.min.js"></script>

    <!-- BEGIN: Page Vendor JS-->
    <script src="vendors/js/ui/jquery.sticky.js"></script>
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
    <!-- END: Theme JS-->
    <!-- BEGIN: Page JS-->

<script src="js/scripts/pages/auth-login.js"></script>
<script src="js/scripts/forms/form-select2.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDizM76Gj0ty8oFWl96MWJ_5y4b9FLvWyw&libraries=places"></script>
<script type='text/javascript' src='js/scripts/gmap.js'></script>
<script type='text/javascript' src='vendors/js/pickers/flatpickr/flatpickr.min.js'></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>


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



<script type="text/javascript">
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Müşterilerinize Göstereceğiniz Detayları Girin',
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

            console.log(data);

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
    $("#sort").sortable({  // sıralamanın yapılacağı ul nin id si
        axis: 'y',   // sadece dikine sıralama yapmak için y eksenini seçiyoruz
        revert: true, // sürükle bırak yaparken yavaş ve estetik olması için
        stop: function (event, ui) {
            var data = $(this).sortable('serialize'); // sıralama verisini oluşturuyoruz
            $.ajax({
                type: "POST", // post metodunu kullanıyoruz
                data: data+"&action={{$siralamaYeri}}", // data verisini yolluyoruz
                url: "{{URL::site('Ajax/sort')}}",  // post edeceğimiz sayfamızın yolu
                success: function (data) { // veri işlendikten sonra sonucu alıyoruz.
                    if (data == "success") {
                        $('#resultJS').html("<strong class='text-success'> Sonuç: Sıra Değişikliği Başarılı</strong>"); 
                        // span da işlem sonucu başarılı ise belirtiyoruz
                        window.location.reload();
                    }
                    else {
                        $('#resultJS').html("<strong class='text-danger'>  Sonuç: Sıra Değişikliği  Başarısız</strong>");
                        // span da işlem sonucu başarısız ise belirtiyoruz
                    }
                }
            });
        }
    });
});

$('#widget').draggable();  
//Bu kod ile mobil cihazlarda ve tabletlerde sürükle bırak özelliği çalışacaktır.
</script>
