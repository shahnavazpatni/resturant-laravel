@extends('layouts.app')

@section('title')
    <title>Contact Messages</title>
@endsection

@push('css')
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/bootstrap4.min.css">
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/dataTables.bootstrap.min.css"> --}}
  <link rel="stylesheet" href="{{asset('/')}}assets/admin/datatable/css/jquery.dataTables.min.css">
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
                      <th>Email</th>
                      <th>Subject</th>
                      <th>Message</th>
                      <th>Time</th>
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

<div class="modal fade modal-xl" id="messageModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Message Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="show-name"></div><br>
        <div class="show-email"></div><br>
        <div class="show-subject"></div><br>
        <div class="show-message"></div><br>
      </div>
    </div>
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
    $(document).ready(function(){
      md.initDashboardPageCharts();

      // Get Data
      var table = $('#reservation_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('contact.index')}}",
        columns: [
            {data:'id', name:'id'},
            {data:'name', name:'name'},
            {data:'email', name:'email'},
            {data:'subject', name:'subject'},
            {data:'message', name:'message'},
            {data:'order', name:'order'},
            {
              data:'action',
              name:'action',
              orderable: true,
              searchable: true
            },
        ]
      });

      // Delete Reservation
      $(document).on("click", ".btn-delete", function(){
        var delete_tr = $(this).parent().parent().addClass('parent_delete');
        var id = $(this).data("id");
        var csrf_token = $('meta[name="csrf_token"]').attr('content');
        var url = "{{url('admin/contact')}}";
        swal({
            title: "Are you sure?",
            text: "Once Delete This Message",
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

      // View Message
      $(document).on('click', '.btn-details', function(){
        $('#messageModal').modal('show');
        var id = $(this).data('id');
        var path = "{{url('admin/contact')}}";
        var url  = path+'/'+id+'/show';
        $.get(url, function(data){
          $('.show-name').html('<strong>Name:</strong>'+' '+data.name);
          $('.show-email').html('<strong>Email:</strong>'+' '+data.email);
          $('.show-subject').html('<strong>Subject:</strong>'+' '+data.subject);
          $('.show-message').html('<strong>Message:</strong>'+' '+data.message);
        });
      })

    });
  </script>
@endpush