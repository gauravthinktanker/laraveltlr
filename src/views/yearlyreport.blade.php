@extends('layouts.app')
@push('styles')

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<style>
    .filter-box {
        z-index: 2;
    }

    .swal2-cancel {
        padding: 10px 10px 10px 10px;
        margin: 10px 10px 10px 10px;
    }

    .swal2-confirm {
        padding: 10px 10px 10px 10px;
        margin: 10px 10px 10px 10px;
    }

    .dataTables_filter {
        display: block !important;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        padding: 5px 5px 5px 5px !important;
        margin: 10px 10px 10px 10px !important;

    }
</style>

@endpush
@section('content')
<div class="content-wrapper">
    
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <table id="employee" class="table table-hover border-0 w-100">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Missing Task</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


@endsection
@push('scripts')

<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script>
   
    var token = "{{ csrf_token() }}";

    $(document).ready(function() {

        $(function() {
            var master = $('#employee').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "autoWidth": true,
                "aaSorting": [
                    [1, "asc"]
                ],
                "sAjaxSource": "{{ URL::route('yearly.report')}}",
                "fnServerParams": function(aoData) {
                    aoData.push({
                        "name": "act",
                        "value": "fetch"
                    });
                    server_params = aoData;
                },
                "aoColumns": [
                    {
                        mData: 'name',
                        sWidth: "30%",
                        bSortable: true,
                    },
                    {
                        mData: 'missing_count',
                        sWidth: "30%",
                        bSortable: true,
                        
                    },
                    
                ],

            });


        });
        $.ajaxSetup({
            statusCode: {
                401: function() {
                    location.reload();
                }
            }
        });

    });
    
</script>

@endpush