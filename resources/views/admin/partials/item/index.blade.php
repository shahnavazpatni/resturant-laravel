@extends('layouts.app')

@section('title')
    <title>Item</title>
@endsection

@push('css')
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/bootstrap4.min.css">
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/dataTables.bootstrap.min.css">
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
      padding: 0;
    }
    .wait-loading {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #171515b8;
      z-index: 999999;
      display: none;
    }
    .wait-loading .load {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      color: #fff;

    }
    .wait-loading .load h3 {
      font-size: 23px;
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
                  <h4 class="card-title ">All Items</h4>
                  <button type="button" class="btn btn-dark" id="addItem">
                    Add Item
                  </button>
              </div>
              <div class="card-body">
                <table id="item_table" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Image</th>
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

<!-- Item Modal -->
<div class="modal fade" id="itemModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form method="POST" enctype="multipart/form-data">
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group">
            <label class="bmd-label-floating">Category</label>
            <select name="category" id="category" class="form-control">
              
            </select>
          </div>
          <div class="form-group">
            <label class="bmd-label-floating">Name</label>
            <input type="text" class="form-control" name="name" id="name">
          </div>
          <div class="form-group">
            <label class="bmd-label-floating">Description</label>
            <textarea class="form-control" name="description" id="description" rows="10">

            </textarea>
          </div>
          <div class="form-group">
            <label class="bmd-label-floating">Price</label>
            <input type="text" class="form-control" name="price" id="price">
          </div>
          <div class="form-group">
            <label class="bmd-label-floating">Image</label>
            <input type="file" class="form-control" name="image" id="image">
            <input type="hidden" name="hidden_id" id="hidden_id">
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary add-item">Submit</button>
          <button type="submit" class="btn btn-primary update-item">Update</button>
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
<div class="wait-loading">
  <div class="load">
    <h3>Please Wait...</h3>
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

        // All Function

        // Get Data
        var table = $('#item_table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('item.index')}}",
          columns: [
            {data:'id', name:'id'},
            {data:'category', name:'category'},
            {data:'name', name:'name'},
            {data:'description', name:'description'},
            {data:'price', name:'price'},
            {data:'item_image', name:'item_image'},
            {
              data:'action',
              name:'action',
              orderable: true,
              searchable: true
            },
          ]
        });
        
        // Delete Item
        $(document).on("click", ".btn-delete", function(){
          var delete_tr = $(this).parent().parent().addClass('parent_delete');
          var csrf_token = $('meta[name="csrf_token"]').attr('content');
          var id = $(this).data("id");
          var url = "{{url('admin/item')}}";
          
            swal({
                title: "Are you sure?",
                text: "Once Delete Category",
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

        $("#addItem").click(function(){
          $("#itemModal").modal("show");
          $(".modal-title").text('Add Item');
          $(".add-item").css('display', 'block');
          $(".update-item").css('display', 'none');
          $("form").addClass("storeItem");
        });
        // Get All Category
        function getCategory(){
          var url = "{{url('admin/item/category')}}";
          $.get(url, function(data){
            var html = '';
            html += '<option value="">Select Category</option>';
            data.forEach( function(row){
              html += '<option value="'+row.id+'">'+row.name+'</option>';
              
            });
            $("#category").html(html);
          });
        }
        getCategory();

      // Store Category
        var t = $('#item_table').DataTable();
        var counter = 1;
        
        $(document).on("click", ".add-item", function(e){
          e.preventDefault();
          var url         = "{{ route('item.store') }}";

          var category    = $('select[name="category"]').val();
          var _token      = $('meta[name="csrf_token"]').attr('content');
          var name        = $('input[name="name"]').val();
          var description = $('textarea[name="description"]').val();
          var price       = $('input[name="price"]').val();
          var image       = $('input[name="image"]').prop("files")[0];
          var form_data   = new FormData();
          
          form_data.append('category',category);
          form_data.append('_token',_token);
          form_data.append('name',name);
          form_data.append('description',description);
          form_data.append('price',price);
          form_data.append('image',image);

          $.ajax({
            url: url,
            method: "POST",
            data: form_data,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
              $(".submit-form-loader").css('display', 'block');
            },
            success: function(data){
              $('.form-control').val('');
              $("#sliderModal").modal("hide");
              console.log(data);
              swal(data.message, {
                icon: data.alert_type,
              });
              t.row.add([
                counter +'.1',
                counter +'.2',
                counter +'.3',
                counter +'.4',
                counter +'.5',
                counter +'.6',
              ]).draw( false );
              counter++;
            },
            complete: function(){
                $(".submit-form-loader").css('display', 'none');
            }
          });
        });

        // Edit Item
        $(document).on("click", ".btn-edit", function (){

          $("#itemModal").modal("show");
          $(".modal-title").text('Edit Item');
          $(".add-item").css('display', 'none');
          $(".update-item").css('display', 'block');
          $("form").addClass("updateItem");
          $("#category").html('');
          $(".form-control").val('');
          var id  = $(this).data("id");
          var url = "{{url('admin/item')}}";
          $(this).parent().parent().find('td').eq(1).addClass('category_'+id);
          $(this).parent().parent().find('td').eq(2).addClass('name_'+id);
          $(this).parent().parent().find('td').eq(3).addClass('des_'+id);
          $(this).parent().parent().find('td').eq(4).addClass('price_'+id);
          $(this).parent().parent().find('td').eq(5).addClass('image_'+id);
          if(id){
            $.ajax({
              url: url+'/'+id+'/edit',
              method: "GET",
              beforeSend: function(){
                $('.wait-loading').css('display', 'block');
              },
              success: function (data) {
                if($.isEmptyObject(data) != null) {
                  
                  var category_id = data[0].category_id;
                  var html = '';
                  html += '<option value="">Select Category</option>';
                  data[1].forEach(function(row){
                    if (category_id == row.id) {
                      var selected = "selected";
                    }else {
                      var selected = "";
                    }
                    
                    html += '<option '+selected+' value="'+row.id+'">'+row.name+'</option>';
                  });
                  $("#category").html(html);

                  $('select[name="category"]').val(data[0].category);
                  $('input[name="name"]').val(data[0].name);
                  $('textarea[name="description"]').val(data[0].description);
                  $('input[name="price"]').val(data[0].price);
                  $('input[name="hidden_id"]').val(data[0].id);
                }
              },
              complete: function(){
                $('.wait-loading').css('display', 'none');
              }
            });
          }
        });

      // //Update Item
      $(document).on("click", ".update-item", function (e) {
          e.preventDefault();

          var id    = $("#hidden_id").val();

          var path = "{{url('admin/item')}}";
          var url = path+'/'+id+'/update';
          
          var category_id = $('table tbody tr td.category_'+id+'');
          var name_id     = $('table tbody tr td.name_'+id+'');
          var des_id      = $('table tbody tr td.des_'+id+'');
          var price_id    = $('table tbody tr td.price_'+id+'');
          var image_id    = $('table tbody tr td.image_'+id+' img');

          var category    = $('select[name="category"]').val();
          var _token      = $('meta[name="csrf_token"]').attr('content');
          var name        = $('input[name="name"]').val();
          var description = $('textarea[name="description"]').val();
          var price       = $('input[name="price"]').val();
          var image       = $('input[name="image"]').prop("files")[0];
          
          var form_data   = new FormData();
          
          form_data.append('category',category);
          form_data.append('_token',_token);
          form_data.append('name',name);
          form_data.append('description',description);
          form_data.append('price',price);
          form_data.append('image',image);
          form_data.append('_method','PUT');
          var asset = "{{asset('upload/items/')}}";
          $.ajax({
            url: url,
            method: "POST",
            data: form_data,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
              $(".submit-form-loader").css('display', 'block');
            },
            success: function (data) {
              swal(data.message, {
                icon: data.alert_type,
              });
              $(".form-control").val('');
              $("#hidden_id").val('');
              $("#itemModal").modal("hide");

              category_id.text(data.category);
              name_id.text(name);
              des_id.text(description);
              price_id.text(price);
              image_id.attr('src',asset+'/'+data.image);
            },
            complete: function () {
              $(".submit-form-loader").css('display', 'none');
            }
          });
      });
      
    });
  </script>
@endpush