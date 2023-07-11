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
    .swal2-cancel{
        padding: 10px 10px 10px 10px;
        margin: 10px 10px 10px 10px;
    }
    .swal2-confirm{
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
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Year</p>
        <div class="select-status">
            <select id="year" name="year" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"></select>
        </div>
    </div>

    <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Month</p>
        <div class="select-status">
            <?= Form::selectMonth('month', Session::get('month'), ['id' => 'month', 'class' => 'position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey']); ?>
            <input type="hidden" name="month" value="<?= date('m') ?>">
        </div>
    </div>
</div>
</form>
</div>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar">
        <div id="table-actions" class="flex-grow-1 align-items-center">
            <a href="<?= URL::route('point.create') ?>" class="btn-primary rounded f-14 p-2 mr-3" style="float: right !important;">Add Point</a>

        </div>
    </div>
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <table id="employee" class="table table-hover border-0 w-100">
            <thead>
                <tr>
                    <th>Organizer</th>
                    <th>Topic</th>
                    <th>Subject</th>
                    <th>Obtained Point</th>
                    <th>Total Point</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Total Points</th>
                    <th></th>
                    <th></th>
                    <th style="display:flex;justify-content: space-between;"></th>
                    <th></th>
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
    var token = "<?= csrf_token() ?>";
    var title = "Are you sure to delete selected record(s)?";
    var text = "You will not be able to recover this record";
    var type = "warning";
    var token = "{{ csrf_token() }}";
    var delete_path = "{{ route('point.delete') }}";
    var path = '<?= URL::route('tlr_month_admin', ['id' => ':id']) ?>';

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

                        if ($('#month').val() == null) {
                            var a = new Date();
                            d.month = a.getMonth();
                            d.year = a.getFullYear();
                        } else {

                            d.month = $('#month').val();
                            d.year = $('#year').val();
                        }
                    }
                },

                "aoColumns": [{
                        mData: 'name',
                        sWidth: "15%",
                        bSortable: true,
                    },
                    {
                        mData: null,
                        sWidth: "20%",
                        bSortable: true,

                        mRender: function(v, t, o) {


                            if (o['subject'] == "Leaves Points") {
                                var extra_html = "Leaves";
                            } else if (o['subject'] == "Punctuality Points") {
                                var extra_html = "Punctuality";
                            } else {
                                var extra_html = o['topic_id']
                            }
                            return extra_html;


                        }

                    },
                    {
                        mData: null,
                        sWidth: "15%",
                        bSortable: false,
                        mRender: function(v, t, o) {


                            if (o['participant'] == 1) {
                                var extra_html = o['subject'] + " - " + "participant";
                            } else {
                                var extra_html = o['subject'];
                            }
                            return extra_html;


                        }

                    },
                    {
                        mData: 'point',
                        sWidth: "10%",
                        bSortable: false,
                    },
                    {
                        mData: null,
                        sWidth: "5%",
                        bSortable: false,
                        mRender: function(v, t, o) {


                            if (o['subject'] == "DRS Points" || o['subject'] == "Leaves Points" || o['topic_id'] == "Learn New Technology" || (o['topic_id'] == "Workshop on New Technology" && o['participant'] == 0)) {
                                var extra_html = 20;
                            } else if (o['subject'] == "Punctuality Points" || o['topic_id'] == "Job Referral Joining" || o['topic_id'] == "Job Referral (2+ Years)" || o['topic_id'] == "Presentation (With PPT)" || o['topic_id'] == "Training Fresher") {
                                var extra_html = 10;
                            } else if (o['participant'] == 1 || o['topic_id'] == "Presentation" || o['topic_id'] == "Job Referral (0-2 Years)" || o['subject'] == "Typing Points") {
                                var extra_html = 5;
                            }
                            return extra_html;
                        }

                    },
                    {
                        mData: null,
                        sWidth: "20%",
                        bSortable: false,
                        mRender: function(v, t, o) {


                            if (o['from_hour'] == null) {
                                var extra_html = null;
                            } else {
                                var extra_html = o['from_hour'] + " To " + o['to_hour'];
                            }
                            return extra_html;
                        }

                    },

                    {
                        mData: null,
                        sWidth: "20%",
                        bSortable: false,
                        mRender: function(v, t, o) {

                            var date = o['date'].split("/").join("-");

                            return date;


                        }
                    },
                    {
                        mData: null,
                        sWidth: "10%",
                        bSortable: false,
                        mRender: function(v, t, o) {


                            var extra_html = "<div class='btn-group pr5'>"

                                +
                                "<a id='delete' href='javascript:void(0)' onclick=\"deleteRecord('" + delete_path + "','" + title + "','" + text + "','" + token + "','" + type + "'," + o['id'] + ")\" class='fa fa-trash'></a>  " +
                                "</div>";
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
                    pageTotal = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    if (pageTotal >= 70) {
                        var extra_html = '<div class="rounded-circle" style="background-color: green;height:24px;width:24px;"></div>'

                    } else if (pageTotal >= 50 && pageTotal <= 70) {
                        var extra_html = '<div class="rounded-circle" style="background-color: yellow;height:24px;width:24px;"></div>'
                    } else if (pageTotal >= 40 && pageTotal < 50) {
                        var extra_html = '<div class="rounded-circle" style="background-color: red;height:24px;width:24px;"></div>'
                    } else {
                        var extra_html = '<div class="rounded-circle" style="background-color: Gray;height:24px;width:24px;"></div>'
                    }

                    $(api.column(3).footer()).html(pageTotal + extra_html);
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





    $('#month').change(function() {
        //for export logs

        var months = parseInt($('#month').val());
        months = months + 1;
        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });

    });

    $('#users').change(function() {

        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
    });

    $('#year').change(function() {
        //for export logs

        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            dt.fnDraw();
        });
    });

    $(document).ready(function() {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        let qntYears = 9;
        let selectYear = $("#year");
        let selectMonth = $("#month");
        let currentYear = new Date().getFullYear();
        for (var y = 0; y < qntYears; y++) {
            let date = new Date(currentYear);
            let yearElem = document.createElement("option");
            yearElem.value = currentYear
            yearElem.textContent = currentYear;
            selectYear.append(yearElem);
            currentYear--;
        }

        var d = new Date();
        var getyears = d.getFullYear();
        var getmonth = d.getMonth();
        getmonth = getmonth + 1;

        var d = new Date();
        var month = d.getMonth() - 1;
        var year = d.getFullYear();

        AdjustMonth();
        selectMonth.val(year);

        selectYear.val(year);
        selectYear.on("change", AdjustMonth);

        var session_month = "{{Session::get('month')}}";
        var session_user = "{{Session::get('user_id')}}";

        if (session_month == 15 || session_month.length == 0) {
            selectMonth.val(month);
        } else {
            $('#users').val(session_user)
            selectMonth.val(session_month);
            <?= Session::forget('user_name') ?>;

        }
        <?= Session::put("month", 15); ?>;


        function AdjustMonth() {
            var setmonth = $(".month").val();
            var year = selectYear.val();
            var month = parseInt(selectMonth.val()) + 1;
            selectMonth.empty();
            if (year == getyears) {
                for (var m = 0; m < getmonth; m++) {
                    let month1 = monthNames[m];
                    let monthElem1 = document.createElement("option");
                    monthElem1.value = m;
                    monthElem1.textContent = month1;
                    selectMonth.append(monthElem1);
                    selectMonth.val(setmonth);
                }
            } else {
                for (var m = 0; m < 12; m++) {
                    let month1 = monthNames[m];
                    let monthElem1 = document.createElement("option");
                    monthElem1.value = m;
                    monthElem1.textContent = month1;
                    selectMonth.append(monthElem1);
                    selectMonth.val(setmonth);
                }
            }
        }
    });

    function deleteRecord(delete_path, title, text, token, type, id) {
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: title,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                deleteRequest(delete_path, id, token);
            }
        })
    }

    function deleteRequest(delete_path, id, token) {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $.ajax({
            url: delete_path,
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
                _token: token
            },
            beforeSend: function() {
                $('#spin').show();
            },
            complete: function() {
                $('#spin').hide();
                var redrawtable = $('#employee').dataTable();
                $("#employee").DataTable().ajax.reload();
                var is_checked = $('.select_check_box').is(':checked');
                if (is_checked == true) {
                    $('.select_check_box').prop('checked', false);
                }
                Toast.fire({
                    icon: 'success',
                    title: 'Data Deleted Successfully.',
                })
            }
        });
    }
</script>
<script type="text/javascript">
    var status_message = '{{session()->get("message")}}';
  function massge() {
  Swal.fire(
           
            '{{ session()->get('message') }}',
            '',
            '{{ session()->get('message_type') }}'
        );
  }
if(status_message !='')
  window.onload = massge;
 </script>
@endpush