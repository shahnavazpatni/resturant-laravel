$(document).ready(function () {
    // Javascript method's body can be found in assets/js/demos.js
    md.initDashboardPageCharts();

    $('#example').DataTable();

    // Get All Data
    function getData() {
        var url  = $("input#sliderUrl").val();
        var path = $("input#imgPath").val();
        $.ajax({
            url: url,
            method: "GET",
            dataType: "JSON",
            success: function (data) {
                var i = 1;
                var html = '';
                data.forEach(function (value) {
                    
                    if (value.status == 1) {
                        var checked = "checked";
                    } else {
                        var checked = "";
                    }
                    html += '<tr>';
                    html += '<td>' + i++ + '</td>';
                    html += '<td>' + value.title + '</td>';
                    html += '<td>' + value.sub_title + '</td>';
                    html += '<td>';
                    html += '<img src="'+path+'upload/sliders/' + value.image + '" width="50px">';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="checkbox" '+checked+' id="slider_status" class="form-control" data-id="'+value.id+'">';
                    html += '</input>';
                    html += '<td>';
                    html += '<button type="button" class="btn btn-xs btn-warning btn-edit" title="Data Edit" data-id="' + value.id + '">Edit</button>';
                    html += '<button type="button" class="btn btn-xs btn-danger btn-delete" title="Data Delete" data-id="' + value.id + '">Delete</button>';
                    html += '</td>';
                    html += '</tr>';

                });
                $("table tbody").append(html);
            }
        });
    }
    getData();

    // All Functionalities
    function reset() {
        $("#storeSlider").find('input').each(function () {
            $(this).val(null)
        })
    }
    function updateReset() {
        $("#updateSlider").find('input').each(function () {
            $(this).val(null)
        })
    }

    // Add Slider
    $(document).on("submit", "#storeSlider", function(e){
        e.preventDefault();
        var url    = $(this).attr('action');
        var method = $(this).attr('method');
        var message = $("#message");
        var show_message = $("#message span");
        $.ajax({
            url: url,
            method: method,
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $(".submit-form-loader").css('display', 'block');
            },
            success: function(data){
                reset();
                $("#sliderModal").modal("hide");
                message.css('display', 'block');
                show_message.html(data.message);
                message.addClass(data.class_name);
                $("table tbody").empty();
                getData();
            },
            complete: function(){
                $(".submit-form-loader").css('display', 'none');
            }
        });
    });

    // Change Slider Status
    $(document).on("click", "#slider_status", function(){
        var id = $(this).data("id");
        var url = $("#slide_path").val();
        $.ajax({
            url: url,
            method: "POST",
            data: {
                id: id
            },
            dataType: "JSON",
            beforeSend: function(){
                $(".submit-form-loader").css('display', 'block');
            },
            success: function(data){
                $("#slider_message").empty();
                $("#slider_message").css('display', 'block').html(data.message);
                setInterval( function(){
                    $("#slider_message").css('display', 'none');
                }, 4000);
            },
            complete: function(){
                $(".submit-form-loader").css('display', 'none');
            }
        });
    });

    // Delete Slider
    $(document).on("click", ".btn-delete", function(){
        var id = $(this).data("id");
        var url = $("#delete_path").val();
        $.ajax({
            url: url,
            method: "POST",
            data: {
                id: id
            },
            dataType: "JSON",
            beforeSend: function () {
                $(".submit-form-loader").css('display', 'block');
            },
            success: function (data) {
                $("table tbody").empty();
                getData();
                $("#slider_message").empty();
                $("#slider_message").css('display', 'block').html(data.message);
                setInterval(function () {
                    $("#slider_message").css('display', 'none');
                }, 4000);
            },
            complete: function () {
                $(".submit-form-loader").css('display', 'none');
            }
        });
    });

    // Edit Slider
    $(document).on("click", ".btn-edit", function () {
        $("#sliderEditModal").modal("show");
        var id = $(this).data("id");
        var url = $("#edit_path").val();

        $.ajax({
            url: url,
            method: "POST",
            data: {
                id: id
            },
            dataType: "JSON",
            success: function (data) {
                if ($.isEmptyObject(data) != null) {
                    $("#update_title").val(data[0].title);
                    $("#update_sub_title").val(data[0].sub_title);
                    $("#hidden_id").val(data[0].id);
                }
            }
        });
    });

    // Update Slider
    $(document).on("submit", "#updateSlider", function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var message = $("#message");
        var show_message = $("#message span");
        $.ajax({
            url: url,
            method: "POST",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $(".submit-form-loader").css('display', 'block');
            },
            success: function (data) {
                updateReset();
                $("#sliderEditModal").modal("hide");
                message.css('display', 'block');
                show_message.html(data.message);
                message.addClass(data.class_name);
                $("table tbody").empty();
                getData();
            },
            complete: function () {
                $(".submit-form-loader").css('display', 'none');
            }
        });
    });

});