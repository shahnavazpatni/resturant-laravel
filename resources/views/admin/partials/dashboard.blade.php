@extends('layouts.app')

@section('title')
    <title>Dashboard</title>
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
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                        <i class="material-icons">content_copy</i>
                        </div>
                        <p class="card-category">Category/Item</p>
                        <h3 class="card-title">{{$categoryCount}}/{{$itemCount}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                        <i class="material-icons text-success">info</i>
                        <a href="javascript:;">Total Categories & Items</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                        <i class="material-icons">slideshow</i>
                        </div>
                        <p class="card-category">Total Slider</p>
                        <h3 class="card-title">{{$sliderCount}}</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                        <i class="material-icons">date_range</i>
                        <a href="{{route('slider.index')}}">Get More Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                    <i class="material-icons">info_outline</i>
                    </div>
                    <p class="card-category">Reservations</p>
                    <h3 class="card-title">{{$reservations->count()}}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                    <i class="material-icons">local_offer</i> Not Confirmed Reservations
                    </div>
                </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="fa fa-twitter"></i>
                        </div>
                        <p class="card-category">Conatct</p>
                        <h3 class="card-title">{{$contactCount}}</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">update</i> Just Updated
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header card-header-primary d-flex" style="justify-content: space-between;">
                  <h4 class="card-title ">All Reservation</h4>
              </div>
              <div class="card-body">
                <table id="reservation_dashboard_table" class="table table-bordered text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Email</th>
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
    $(document).ready(function() {
    // Javascript method's body can be found in assets/js/demos.js
        md.initDashboardPageCharts();

            // Get Data
        var table = $('#reservation_dashboard_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('reservation.dashboard')}}",
        columns: [
            {data:'id', name:'id'},
            {data:'name', name:'name'},
            {data:'phone', name:'phone'},
            {data:'email', name:'email'},
            {data:'status', name:'status'},
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
        var confirmed_tr = $(this).parent().parent().attr('id', 'confirmed_'+id+'');

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
                    confirmed_tr.hide();
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

    });
    </script>
@endpush