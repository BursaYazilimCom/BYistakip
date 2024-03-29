$( "#submitForm" ).submit(function( event ) {

    var s_url = $('#submitForm').attr('action');

    $.ajax({
        url:s_url,
        dataType:"json",
        data:$('#submitForm').serialize() ,
        type:"post",
        success:function(data){
            if(data.error){

                toastr.error(data.error,data.title,
                    {
                        closeButton: true,
                        tapToDismiss: false
                    });

            }

            if(data.success){

                if(data.redirect!=""){

                    toastr.success(data.success,data.title,
                        {
                            closeButton: true,
                            tapToDismiss: false,
                            timeOut: 1000,
                            onHidden: function () {
                                window.location = data.redirect;
                            }
                        });

                }else{

                    toastr.success(data.success,data.title,
                        {
                            closeButton: true,
                            tapToDismiss: false,
                            timeOut: 1000
                        });
                        $('#addDataTable').prepend(data.addData);

                }

                if (data.modalClose!=""){

                    $('#'+data.modalClose).modal('hide');

                }

            }

        }
    });
});

$( "#clickAction" ).click(function( event ) {

    var s_url       =   $(this).attr('action');
    var dataAction  =   $(this).attr('data-action');
    var dataId      =   $(this).attr('data-id');

    $.ajax({
        url:s_url,
        dataType:"json",
        data:{"dataAction":dataAction,"dataId":dataId} ,
        type:"post",
        success:function(data){
            if(data.error){
                toastr.error(data.error,data.title,
                    {
                        closeButton: true,
                        tapToDismiss: false
                    });

            }

            if(data.success){

                toastr.success(data.success,data.title,
                    {
                        closeButton: true,
                        tapToDismiss: false,
                        timeOut: 1000,
                        onHidden: function () {
                            window.location = data.redirect;
                        }
                    });

            }

        }
    });
});

function deleteAction(id,action,dataActions) {

    var s_url       =  action;
    var dataAction  =   dataActions;
    var dataId      =   id;

    if (confirm("Silmek istediğinize emin misiniz? Bu İşlemin geri dönüşü yoktur!")) {

        $.ajax({
            url:s_url,
            dataType:"json",
            data:{"dataAction":dataAction,"dataId":dataId} ,
            type:"post",
            success:function(data){
                if(data.error){
                    toastr.error(data.error,data.title,
                        {
                            closeButton: true,
                            tapToDismiss: false
                        });

                }

                if(data.success){

                    $("#row-"+dataId).fadeOut();

                    toastr.success(data.success,data.title,
                        {
                            closeButton: true,
                            tapToDismiss: false,
                            timeOut: 1000
                        });

                }

                if(data.redirect!=""){
                    window.location = data.redirect;
                }

            }
        });

    }

}



var elems = document.getElementsByClassName('confirm');
    var confirmIt = function (e) {
        if (!confirm('Bunu yapmak istediğinize EMİN MİSİNİZ ? \nBu işlemin geri dönüşü yoktur, \nİlişkisel bir veri siliyorsanız bu veriye bağlı diğer verilerin görünmemesine sebep olabilir!')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }



