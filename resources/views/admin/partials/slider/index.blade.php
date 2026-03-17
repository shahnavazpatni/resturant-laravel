@extends('layouts.app')

@section('title')
    <title>Slider</title>
@endsection

@push('css')
  <link type="text/css" href="{{asset('/')}}assets/admin/css/datatable.bootstrap.min.css">
  <style>
    .submit-form-loader{
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999999;
      background: #171515b8;
      transition: 1s;
      display: none;
    }
    .submit-form-loader .loader{
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
    }
  </style>
@endpush

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div id="message" style="display: none">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span></span>
            </div>
            <div id="slider_message" class="alert alert-success" style="display:none">
              
            </div>
          </div>
          <div class="col-md-12">
              <div class="card">
              <div class="card-header card-header-primary d-flex" style="justify-content: space-between;">
                  <h4 class="card-title ">All Slider</h4>
                  <button type="button" class="btn btn-dark" id="addSlider" data-toggle="modal" data-target="#sliderModal">
                    Add Slider
                  </button>
              </div>
              <div class="card-body">
                  <input type="hidden" value="{{route('slider.read')}}" id="sliderUrl">
                  <input type="hidden" value="{{asset('/')}}" id="imgPath">
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th>SL</th>
                              <th>Title</th>
                              <th>Sub Title</th>
                              <th>Image</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                          
                      </tbody>
                  </table>
              </div>
              </div>
          </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="sliderModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Slider</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{route('slider.store')}}" method="POST" id="storeSlider" enctype="multipart/form-data">
      @csrf
        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group bmd-form-group">
              <label class="bmd-label-floating">Title</label>
              <input type="text" class="form-control" name="title" id="title">
          </div>
          <div class="form-group bmd-form-group">
              <label class="bmd-label-floating">Sub Title</label>
              <input type="text" class="form-control" name="sub_title" id="sub_title">
          </div>
          <div class="form-group bmd-form-group">
              <label>Image</label><br>
              <input type="file" name="image" id="image">
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="sliderEditModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Update Slider</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="" method="POST" id="updateSlider" enctype="multipart/form-data">
      @csrf
      @method('PUT')
        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group bmd-form-group">
              <label class="bmd-label-floating">Title</label>
              <input type="text" class="form-control" name="update_title" id="update_title">
          </div>
          <div class="form-group bmd-form-group">
              <label class="bmd-label-floating">Sub Title</label>
              <input type="text" class="form-control" name="update_sub_title" id="update_sub_title">
          </div>
          <div class="form-group bmd-form-group">
              <label>Image</label><br>
              <input type="file" name="update_image" id="update_image">
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
          <input type="hidden" name="hidden_id" id="hidden_id">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="submit-form-loader">
  <div class="loader">
    <img src="{{asset('/')}}assets/admin/img/loader.gif" alt="">
  </div>
</div>
@endsection


@push('js')
  {{-- DataTable --}}
  <script type="text/javascript" src="{{asset('/')}}assets/admin/js/datatable/datatable.min.js"></script>
  <script type="text/javascript" src="{{asset('/')}}assets/admin/js/datatable/datatable.bootstrap4.min.js"></script>

  <script src="{{asset('/')}}assets/admin/demo/demo.js"></script>
  <script src="{{asset('/')}}assets/admin/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
  {{-- <script src="{{asset('/')}}assets/admin/js/pages/slider.js"></script> --}}
  <script>
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
                $("table tbody").html(html);
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
          var url     = $(this).attr('action');
          var method  = $(this).attr('method');
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
        var url = "{{url('admin/slider/status')}}";

        $.ajax({
          url: url+'/'+id,
          method: "GET",
          success: function(data){
            swal(data.message, {
              icon: data.alert_type,
            });
          }
        });
      });

      // Delete Slider
      $(document).on("click", ".btn-delete", function(){
        var id = $(this).data("id");
        var csrf_token = $('meta[name="csrf_token"]').attr('content');
        var url = "{{url('admin/slider')}}";
        swal({
            title: "Are you sure?",
            text: "Once Delete Slider",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              url: url+'/'+id+'/destroy',
              method: "POST",
              data: {
                '_method' : 'DELETE', '_token' : csrf_token
              },
              success: function (data) {
                swal(data.message, {
                    icon: data.alert_type,
                });
                getData();
              }
            }); 
          }else{
            swal("Your imaginary file is safe!");
          }
        });
      });
      // Edit Slider
      $(document).on("click", ".btn-edit", function () {
        $("#sliderEditModal").modal("show");
        var id = $(this).data("id");
        var url = "{{url('admin/slider')}}";
        $.ajax({
            url: url+'/'+id+'/edit',
            method: "GET",
            success: function (data) {
              if ($.isEmptyObject(data) != null) {
                $("#update_title").val(data.title);
                $("#update_sub_title").val(data.sub_title);
                $("#hidden_id").val(data.id);
              }
            }
        });
      });

      // Update Slider
      $(document).on("submit", "#updateSlider", function (e) {
          e.preventDefault();
          var id = $("#hidden_id").val();
          var url = "{{route('slider.update',"+id+")}}";
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
                getData();
              },
              complete: function () {
                $(".submit-form-loader").css('display', 'none');
              }
          });
      });

    });
  </script>
@endpush