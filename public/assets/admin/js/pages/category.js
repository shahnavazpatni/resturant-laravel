$(document).ready(function () {
    // Javascript method's body can be found in assets/js/demos.js
    md.initDashboardPageCharts();

    var url = $("#categoryUrl").val();
    var table = $('#category_table').DataTable({
        processign: true,
        serverSide: true,
        ajax: url,
        columns: [
            {data:'id', data:'id'},
            {data:'name', data:'name'},
            {data:'slug', data:'slug'},
            {data:'action', data:'action', orderable: false, searchable: true},
        ]
    });
});