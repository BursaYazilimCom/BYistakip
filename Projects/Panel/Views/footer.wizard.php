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

<script src="vendors/js/forms/select/select2.full.min.js"></script>

    <!-- BEGIN: Page Vendor JS-->
    <script src="vendors/js/ui/jquery.sticky.js"></script>
<!-- <script src="vendors/js/charts/apexcharts.min.js"></script> -->
<script src="vendors/js/extensions/toastr.min.js"></script>
<script src="vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="js/core/app-menu.js"></script>
    <script src="js/core/app.js"></script>
    <script src="js/scripts/forms/form-repeater.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="js/scripts/pages/dashboard-ecommerce.js"></script>
<script src="js/scripts/pages/auth-login.js"></script>
<script src="js/scripts/forms/form-select2.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDizM76Gj0ty8oFWl96MWJ_5y4b9FLvWyw&libraries=places"></script>
<script type='text/javascript' src='js/scripts/gmap.js'></script>
<script type='text/javascript' src='vendors/js/pickers/flatpickr/flatpickr.min.js'></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>


<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],

                ['para', ['ul','ol','paragraph']],
                ["view", ["fullscreen", "codeview"]]
            ],
        });

        $('#openModal').on('shown.bs.modal', function() {
            $('#summernote').summernote({
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],

                    ['para', ['ul','ol','paragraph']],
                    ["view", ["fullscreen", "codeview"]]
                ],
            });
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
            else if(action=="sektorDuzenle")    {

                $('#dataAction').val('sektorGuncelle');
                $('#update_id').val(data[0]);
                $('#sektor_adi').val(data[1]);
                if (data[3]=="Aktif"){
                    var durum = "1";
                }else {
                    var durum = "0";
                }
                $('#durum').val(durum);


            }
            else if(action=="ulkeDuzenle")    {

                $('#dataAction').val('ulkeGuncelle');
                $('#update_id').val(data[0]);
                $('#isim').val(data[1]);
                $('#iso_code_2').val(data[2]);
                $('#iso_code_3').val(data[3]);
                if (data[5]=="Aktif"){
                    var durum = "1";
                }else {
                    var durum = "0";
                }
                $('#durum').val(durum);

                if (data[4]=="Gerekli"){
                    var pk = "1";
                }else {
                    var pk = "0";
                }
                $('#posta_kodu_gerekliligi').val(pk);


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
            else if(action=="ilceDuzenle")    {

                $('#dataAction').val('ilceGuncelle');
                $('#update_id').val(data[0]);
                $('#ilce').val(data[1]);


            }
            else if(action=="mahalleDuzenle")    {

                $('#dataAction').val('mahalleGuncelle');
                $('#update_id').val(data[0]);
                $('#mahalle_adi').val(data[1]);
                $('#mahalle_key').val(data[2]);
                $('#ilce_key').val(data[3]);
                if (data[4]=="Veriliyor"){
                    var hizmet = "1";
                }else {
                    var hizmet = "0";
                }
                $('#hizmet').val(hizmet);


            }  else{}

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

        $('.altKalemDuzenle').on('click',function () {

            var action       =   $(this).attr('data-action');
            var parent       =   $(this).attr('data-parent');
            var color       =   $(this).attr('data-color');
            var names       =   $(this).attr('data-names');
            var id       =   $(this).attr('data-id');


            $('#altKalemEkle').modal('show');

            if(action=="altKalemDuzenle")    {
                $('#dataAction').val('altKalemGuncelle');
                $('#update_id').val(id);
                $('#ust').val(parent);
                $('#adis').val(names);
                $('#renk').val(color);


            }else{}

        });

        $("#anaKalemEkle").on('hide.bs.modal', function(){
            $('#dataAction').val('anaKalemEkle');
            $('#update_id').val('');
        });

        $('.anaKalemDuzenle').on('click',function () {

            var action       =   $(this).attr('data-action');
            var color       =   $(this).attr('data-color');
            var names       =   $(this).attr('data-names');
            var id       =   $(this).attr('data-id');


            $('#anaKalemEkle').modal('show');

            if(action=="anaKalemDuzenle")    {
                $('#dataAction').val('anaKalemGuncelle');
                $('#update_id').val(id);
                $('#adia').val(names);
                $('#renk').val(color);


            }else{}

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
