<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper ">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Planlama Yönetimi</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{URL::site()}}">Anasayfa</a></li>
                                <li class="breadcrumb-item"><a href="{{URL::site('planlama')}}">Planlama</a></li>
                                <li class="breadcrumb-item"><a href="{{URL::site('planlama/takvim')}}">Takvim</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            
                <!-- Full calendar start -->
                <section>
                    <div class="app-calendar overflow-hidden border">
                    {{ Redirect::select('bilgi',true) }}
                        <div class="row g-0">
                            <div class="col position-relative">
                                <div class="card shadow-none border-0 mb-0 rounded-0">
                                    <div class="card-header border-bottom pb-0" style="display: block">
                                        @foreach($etkinlikTurleri['liste'] as $et)
                                        <a style="background-color: {{$et->renk}}; padding:2px 8px 2px 8px; margin:0px 5px 15px 5px; border-radius: 20px; font-size: 11px; color:#fff; font-weight: bold; float: left">{{$et->tur}}</a>
                                        @endforeach
                                    </div>

                                    <div class="card-body pt-1 pb-0">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Calendar -->
                            <div class="body-content-overlay"></div>
                        </div>
                    </div>
                    <!-- Calendar Add/Update/Delete event modal-->
                    <div class="modal modal-slide-in event-sidebar fade" id="add-new-sidebar">
                        <div class="modal-dialog sidebar-lg">
                            <div class="modal-content p-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title">Etkinlik Ekle</h5>
                                </div>
                                <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                    <form class="event-form" method="post" data-ajax="false" action="{{URL::site('planlama/etkinlikEkle')}}">
                                        <div class="mb-1">
                                            <label for="title" class="form-label">Başlık</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Etkinlik Başlığı" required />
                                        </div>
                                        <div class="mb-1">
                                            <label for="tur" class="form-label">Tür</label>
                                            <select class="select2 select-label form-select w-100" required id="tur" name="tur">
                                            <option data-label="primary" value="" selected>--Seçiniz--</option>
                                                @foreach($etkinlikTurleri['liste'] as $ete)
                                                <option data-label="primary" value="{{$ete->id}}" selected>{{$ete->tur}}</option>
                                                @endforeach
                                              
                                            </select>
                                        </div>
                                        <div class="mb-1 position-relative">
                                            <label for="startDate" class="form-label">Başlangıç Tarihi</label>
                                            <input type="datetime-local" class="form-control" id="startDate" required name="startDate" placeholder="Başlangıç Tarihi" />
                                        </div>
                                        <div class="mb-1 position-relative">
                                            <label for="endDate" class="form-label">Bitiş Tarihi</label>
                                            <input type="datetime-local" class="form-control" id="endDate" required name="endDate" placeholder="Bitiş Tarihi" />
                                        </div>

                                        <div class="mb-1">
                                            <label for="event-url" class="form-label">Etkinik URL</label>
                                            <input type="url" class="form-control" id="event-url" name="url" placeholder="İnternet Adresi varsa Giriniz" />
                                        </div>
                                        <div class="mb-1 select2-primary">
                                            <label for="katilimcilar" class="form-label">Katılımcılar</label>
                                            <div class="row" id="event-emails">
                                                <div class="col-12">
                                                    <div class="input-group">
                                                        <input type="email" class="form-control" id="katilimcilar" name="katilimci[]" placeholder="Katılımcı Mail Adresi"  />
                                                        <button class="btn btn-outline-success" type="button" id="add-email">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-1">
                                            <label for="event-location" class="form-label">Etkinlik Yeri</label>
                                            <input type="text" class="form-control" name="konum" id="event-location" placeholder="Etkinlik Mekanı Yada Konum" />
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label">Açıklama</label>
                                            <textarea name="aciklama" id="event-description-editor" class="form-control"></textarea>
                                        </div>
                                        <div class="mb-1">
                                            <label for="event-location" class="form-label">Bilgilendirme</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="mailBilgilendirme" id="mailBilgilendirme" value="1" />
                                                <label class="form-label" for="mailBilgilendirme">Katılımcılara Mail Gönder</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="smsBilgilendirme" id="smsBilgilendirme" value="1" />
                                                <label class="form-label" for="smsBilgilendirme">Katılımcılara SMS Gönder</label>
                                            </div>
                                         
                                        </div>
                                        <div class="mb-1 d-flex">
                                            <button type="submit" class="btn btn-primary add-event-btn me-1">Add</button>
                                            <button type="button" class="btn btn-outline-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>

                                    <script type="text/javascript">

                                        document.addEventListener("DOMContentLoaded", function() {
                                            document.getElementById("add-email").addEventListener("click", function() {
                                                var eventEmailsContainer = document.getElementById("event-emails");

                                                
                                                // Yeni bir div oluştur
                                                var newRow = document.createElement("div");
                                                newRow.className = "row";

                                                // Yeni bir input-group divi oluştur
                                                var newInputGroup = document.createElement("div");
                                                newInputGroup.className = "input-group col-12";

                                                // Yeni bir input elementi oluştur
                                                var newInput = document.createElement("input");
                                                newInput.type = "text";
                                                newInput.className = "form-control";
                                                newInput.name = "katilimci[]";
                                                newInput.placeholder = "Katılımcı Mail Adresi";

                                                // Yeni bir buton oluştur
                                                var newButton = document.createElement("button");
                                                newButton.className = "btn btn-outline-danger";
                                                newButton.type = "button";

                                                // Buton içeriği olarak "+" simgesi ekle
                                                var buttonText = document.createElement("i");
                                                buttonText.className = "fa fa-times";

                                                // Butona tıklanınca satırı silmek için bir event listener ekle
                                                newButton.addEventListener("click", function() {
                                                    eventEmailsContainer.removeChild(newRow);
                                                });

                                                // Butonun içeriğini ekleyin
                                                newButton.appendChild(buttonText);

                                                // Buton ve input elementini input-group içine ekleyin
                                                newInputGroup.appendChild(newInput);
                                                newInputGroup.appendChild(newButton);

                                                // input-group divini satır divine ekleyin
                                                newRow.appendChild(newInputGroup);

                                                // Satırı event-emails containerına ekleyin
                                                eventEmailsContainer.appendChild(newRow);
                                            });
                                        });

                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Calendar Add/Update/Delete event modal-->
                </section>

        </div>
    </div>
</div>
<!-- END: Content-->

<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h3 class="text-center mb-1" id="modalTitle">Etkinlik Detayları</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            <div class="card card-developer-meetup">
                            <div class="card-body">
                                <div class="meetup-header d-flex align-items-center">
                                    <div class="my-auto">
                                        <h4 class="card-title mb-25" id="eventTitle"></h4>
                                        <p class="card-text mb-0" id="eventTur"></p>
                                    </div>
                                </div>
                                <div class="row  mb-1">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="d-flex flex-row meetings">
                                            <div class="avatar bg-light-primary rounded me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="calendar" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="content-body">
                                                <h6 class="mb-0" id="start"></h6>
                                                <small>Başlangıç</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="d-flex flex-row meetings">
                                            <div class="avatar bg-light-primary rounded me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="calendar" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="content-body">
                                                <h6 class="mb-0" id="end"></h6>
                                                <small>Bitiş</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mb-1">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="d-flex flex-row meetings">
                                            <div class="avatar bg-light-primary rounded me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="map-pin" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="content-body">
                                                <h6 class="mb-0" id="location"></h6>
                                                <small>Etkinlik Yeri</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <a href="javascript:void(0)" target="_blank" id="url" class="btn btn-primary">Etkinlik İnternet Adresi</a>
                                    </div>
                                </div>

                                <div class="row  mb-1">
                                    <div class="col-12 d-flex alert alert-default">
                                    <div class="alert-body" id="description">
                                    </div>

                                    </div>
                                </div>

                                <div class="row  mb-1">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="d-flex flex-row meetings">
                                            <div class="avatar bg-light-primary rounded me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="info" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="content-body">
                                                <h6 class="mb-0" id="start">E-Posta Bilgilendirme</h6>
                                                <small id="mailInfo"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="d-flex flex-row meetings">
                                            <div class="avatar bg-light-primary rounded me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="info" class="avatar-icon font-medium-3"></i>
                                                </div>
                                            </div>
                                            <div class="content-body">
                                                <h6 class="mb-0" id="end">SMS Bilgilendirme</h6>
                                                <small id="smsInfo"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="avatar-group">
                                    <h6 class="cursor-pointer ms-50 mb-0">Katılımcılar</h6><hr>

                                    <h6 class="cursor-pointer ms-50 mb-0" id="users"></h6>
                                </div>
                                
                            </div>
                            <div class="card-footer">
                                <a href="javascript:void(0)" id="eventDelete" class="btn btn-danger btn-sm confirm">Sil</a>
                                <a href="javascript:void(0)" id="eventEdit" class="btn btn-warning btn-sm">Düzenle</a>
                            </div>
                        </div>

            </div>
        </div>
    </div>
</div>

