@extends('layouts.app')

@section('title')
    <title>Category</title>
@endsection

@push('css')
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/bootstrap4.min.css">
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/dataTables.bootstrap.min.css"> --}}
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/jquery.dataTables.min.css">
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
    .form-control:focus {
      color: #333;
      background-color: #fff;
      border-color: #333;
      outline: 0;
      box-shadow: 0 0 0 0 rgba(0,123,255,.25);
    }
    .form-control {
      border: none;
    }
  </style>
@endpush

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header card-header-primary d-flex" style="justify-content: space-between;">
                  <h4 class="card-title ">All Category</h4>
                  <button type="button" class="btn btn-dark" id="addCategory">
                    Add Category
                  </button>
              </div>
              <div class="card-body">
                <table id="category_table" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
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
<div class="modal fade" id="CategoryModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{route('category.store')}}" method="POST" id="storeCategory">
      @csrf
        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group bmd-form-group">
              <label class="bmd-label-floating">Name</label>
              <input type="text" class="form-control" name="name" id="name">
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
<div class="modal fade" id="categoryEditModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Update Slider</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="" method="POST" id="updateCategory">
      @csrf
      @method('PUT')
        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group bmd-form-group">
            <input type="text" class="form-control" name="update_name" id="update_name">
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
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
  <script type="text/javascript" src="{{asset('/')}}assets/admin/datatable/js/jquery.min.js"></script>
  <script type="text/javascript" src="{{asset('/')}}assets/admin/datatable/js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="{{asset('/')}}assets/admin/datatable/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="{{asset('/')}}assets/admin/datatable/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{asset('/')}}assets/admin/datatable/js/jqeury.dataTables.min.js"></script>

  <script src="{{asset('/')}}assets/admin/demo/demo.js"></script>
  <script src="{{asset('/')}}assets/admin/js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
  
  <script>
    $(document).ready(function (){
        // Javascript method's body can be found in assets/js/demos.js
        md.initDashboardPageCharts();

        // Get Data
        var table = $('#category_table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('category.index')}}",
          columns: [
              {data:'id', name:'id'},
              {data:'name', name:'name'},
              {
                data:'action',
                name:'action',
                orderable: true,
                searchable: true
              },
          ]
        });
        
        // Delete Category
        $(document).on("click", ".btn-delete", function(){
          var delete_tr = $(this).parent().parent().addClass('parent_delete');
          var csrf_token = $('meta[name="csrf_token"]').attr('content');
            swal({
                title: "Are you sure?",
                text: "Once Delete Category",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                  var id = $(this).data("id");
                  var url = "{{url('admin/category')}}";
                  $.ajax({
                    url: url+'/'+id+'/delete',
                    method: "POST",
                    data: {
                      '_method' : 'DELETE', '_token' : csrf_token
                    },
                    success: function (data) {
                      if ($.isEmptyObject(data.error)) {
                        delete_tr.hide();
                        swal(data.message, {
                            icon: data.alert_type,
                        });
                      }else{
                        
                      }
                    }
                  });
                }else{
                  swal("Your imaginary file is safe!");
                }
            });
        });

        $("#addCategory").click(function(){
          $("#CategoryModal").modal("show");
        });

        // Store Category
        var t = $('#category_table').DataTable();
        var counter = 1;
        $(document).on("submit", "#storeCategory", function(e){
          e.preventDefault();
          var url    = $(this).attr('action');
          var method = $(this).attr('method');
          $.ajax({
              url: url,
              method: method,
              data: $(this).serialize(),
              dataType: "JSON",
              success: function(data){
                swal(data.message, {
                  icon: data.alert_type,
                });
                $("#CategoryModal").modal("hide");
                $("form #name").val("");
                t.row.add([
                  counter +'.1',
                  counter +'.2',
                  counter +'.4',
                ]).draw( false );
                counter++;
              },
              error: function(data){
                swal(data.message, {
                  icon: data.alert_type,
                });
              }
          });
        });

        // Edit Slider
        $(document).on("click", ".btn-edit", function (){

          $("#categoryEditModal").modal("show");
          var id = $(this).data("id");
          var url = "{{url('admin/category')}}";
          $(this).parent().parent().find('td').eq(1).addClass(''+id+'');
          if(id){
            $.ajax({
              url: url+'/'+id+'/edit',
              method: "GET",
              success: function (data) {
                if($.isEmptyObject(data) != null) {
                  $("#update_name").val(data.name);
                  $("#hidden_id").val(data.id);
                }
              }
            });
          }
        });

      //Update Slider
      $(document).on("submit", "#updateCategory", function (e) {
          e.preventDefault();
          $("#update_name").val();
          var id  = $("#hidden_id").val();
          var url = "{{route('category.update',"+id+")}}";
          var tbody = $('table tbody tr td.'+id+'');
          $.ajax({
              url: url,
              method: "POST",
              data: $(this).serialize(),
              dataType: "JSON",
              beforeSend: function () {
                $(".submit-form-loader").css('display', 'block');
              },
              success: function (data) {
                if (data.message){
                  swal(data.message, {
                    icon: data.alert_type,
                  });
                  $("#update_name").val('');
                  $("#hidden_id").val('');
                  $("#categoryEditModal").modal("hide");
                  $(tbody).text(data.name);
                }else if(data.require){
                  alert(data.require);
                }
                
              },
              complete: function () {
                $(".submit-form-loader").css('display', 'none');
              }
          });
      });
    });
  </script>
@endpush