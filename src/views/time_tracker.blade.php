@extends('layouts.app')
@push('styles')

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<style>
    .filter-box {
        z-index: 2;
    }


    .btnCreate-primary {
        background-color: #FFB400 !important;
        ;
        border-color: #FFB400 !important;
        color: white;
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
@section('filter-section')
<div class="filter-box">
    <form action="" id="filter-form">
        <div class="d-lg-flex d-md-flex d-block flex-wrap filter-box bg-white client-list-filter">

            <div class="select-box py-2 d-flex pr-2 border-right-grey border-right-grey-sm-0">
                <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">User</p>
                <div class="select-status">
                    <?= Form::select('user_id', ['' => 'Select'] + $all_users, Session::get("user_name"), ['class' => 'position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey', 'id' => 'users']) ?>
                </div>
            </div>

            <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
                <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Date</p>
                <div class="select-status d-flex">
                    <input type="text" name="from_date" id="from_date" class="form-control  date-picker height-45 f-14" placeholder="From Date" />
                    <input type="text" name="to_date" id="to_date" class="form-control date-picker height-45 f-14" placeholder="To Date" />
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
@section('content')
<div class="content-wrapper">

    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <table id="employee" class="table table-hover border-0 w-100">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>KeyBoard Count</th>
                    <th>Mouse Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


@endsection
@push('scripts')

<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script>
    var token = "{{ csrf_token() }}";
    var path = '<?= URL::route('time.tracker', ['id' => ':id']) ?>';

    $(document).ready(function() {



        $(function() {

            var master = $('#employee').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "autoWidth": true,
                "aaSorting": [
                    [1, "asc"]
                ],
                "ajax": {
                    'url': path,
                    'data': function(d) {
                        d.user = $('#users').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();

                    }
                },

                "aoColumns": [{
                        mData: 'name',
                        sWidth: "15%",
                        bSortable: true,
                    },
                    {
                        mData: 'keyboard_count',
                        sWidth: "10%",
                        bSortable: false,
                    },
                    {
                        mData: 'mouse_count',
                        sWidth: "10%",
                        bSortable: false,
                    },
                    {
                        mData: null,
                        sWidth: "10%",
                        bSortable: false,
                        mRender: function(v, t, o) {
                            var extra_html = "<a target='_blank' href='/showImage/" + o['user_id'] + "/" + ($('#from_date').val() ? $('#from_date').val() : '{{date('Y-m-d')}}') + "/" + ($('#to_date').val() ? $('#to_date').val() : '{{date('Y-m-d')}}') + "'>View</a>";
                            return extra_html;
                        }

                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    var pageTotalColumn1 = api
                        .column(1, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Calculate sum for the second column (assuming it is column 2, change it if necessary)
                    var pageTotalColumn2 = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // if (pageTotal >= 70) {
                    //     var extra_html = '<div class="rounded-circle" style="background-color: green;height:24px;width:24px;"></div>'

                    // } else if (pageTotal >= 50 && pageTotal <= 70) {
                    //     var extra_html = '<div class="rounded-circle" style="background-color: yellow;height:24px;width:24px;"></div>'
                    // } else if (pageTotal >= 40 && pageTotal < 50) {
                    //     var extra_html = '<div class="rounded-circle" style="background-color: red;height:24px;width:24px;"></div>'
                    // } else {
                    //     var extra_html = '<div class="rounded-circle" style="background-color: Gray;height:24px;width:24px;"></div>'
                    // }

                    $(api.column(1).footer()).html(pageTotalColumn1);
                    $(api.column(2).footer()).html(pageTotalColumn2);
                },


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



    $('#users').change(function() {

        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
    });
</script>
<script type="text/javascript">
    //    const datepickerConfig = {
    //       formatter: (input, date, instance) => {
    //         input.value = moment(date).format('YYYY-MM-DD')
    //       },
    //       showAllDates: true,
    //       customDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    //       customMonths: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    //       customOverlayMonths: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    //       overlayButton: "Submit",
    //       overlayPlaceholder: "4-digit year",
    //       startDay: parseInt("1")
    //     };
    datepicker('#from_date', {
        position: 'bl',
        ...datepickerConfig,
        onSelect: function() {
            $('.dataTable').each(function() {
                dt = $(this).dataTable();
                dt.fnDraw();
            });
        }
    });

    datepicker('#to_date', {
        position: 'bl',
        ...datepickerConfig,
        onSelect: function() {
            $('.dataTable').each(function() {
                dt = $(this).dataTable();
                dt.fnDraw();
            });
        }
    });
</script>
@endpush