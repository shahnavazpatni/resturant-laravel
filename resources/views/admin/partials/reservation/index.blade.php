@extends('layouts.app')

@section('title')
    <title>Reservation</title>
@endsection

@push('css')
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/bootstrap4.min.css">
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/dataTables.bootstrap.min.css"> --}}
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/jquery.dataTables.min.css">
  <style>
    .status_change_loader {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 300px;
        height: 31px;
        background: #333333c7;
        text-align: center;
        color: #fff;
        border-radius: 3px;
        z-index: 9999;
        display: none;
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
                  <h4 class="card-title ">All Reservation</h4>
              </div>
              <div class="card-body">
                <table id="reservation_table" class="table table-bordered text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Delivery Date</th>
                      <th>Message</th>
                      <th>Status</th>
                      <th>Order Date</th>
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

<div class="status_change_loader">
    <p class="lead">Please Wait...</p>
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
    $(document).ready(function(){
      md.initDashboardPageCharts();

      // Get Data
      var table = $('#reservation_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('reservation.index')}}",
        columns: [
            {data:'id', name:'id'},
            {data:'name', name:'name'},
            {data:'phone', name:'phone'},
            {data:'email', name:'email'},
            {data:'date_and_time', name:'date_and_time'},
            {data:'message', name:'message'},
            {data:'status', name:'status'},
            {data:'order', name:'order'},
            {
              data:'action',
              name:'action',
              orderable: true,
              searchable: true
            },
        ]
      });

      // Reservation Status Change
      $(document).on('click', '.btn-confirmed', function(){
        var id = $(this).data('id');
        var confirmed_id = $(this).parent().parent().find('td').eq(6).attr('id', 'confirmed_'+id+'');

        var path = "{{url('admin/reservation')}}";
        var url  = path+'/'+id+'/'+'status';

        swal({
            title: "Are you sure?",
            text: "Change This Reservation Status",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
                url: url,
                method: 'GET',
                beforeSend: function(){
                  $('.status_change_loader').css('display', 'block');
                },
                success: function(data){
                    swal(data.message, {
                        icon: data.alert_type,
                    });
                    confirmed_id.html(data.html);
                },
                complete: function(){
                    $('.status_change_loader').css('display', 'none');
                }
            });
          }else{
            swal("Your imaginary status is safe!");
          }
        });

      });
      // Delete Reservation
      $(document).on("click", ".btn-delete", function(){
        var delete_tr = $(this).parent().parent().addClass('parent_delete');
        var id = $(this).data("id");
        var csrf_token = $('meta[name="csrf_token"]').attr('content');
        var url = "{{url('admin/reservation')}}";
        swal({
            title: "Are you sure?",
            text: "Once Delete Reservation",
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
                delete_tr.hide();
                swal(data.message, {
                    icon: data.alert_type,
                });
              }
            }); 
          }else{
            swal("Your imaginary file is safe!");
          }
        });
      });

    });
  </script>
@endpush