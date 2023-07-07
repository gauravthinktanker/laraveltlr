@extends('layouts.app')
@push('styles')

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<style>
    .filter-box {
        z-index: 2;
    }
    .paginate_button.page-item.active {
        background-color: #ffb400 !important;
    }
</style>
@endpush

@section('filter-section')
<x-filters.filter-box>
    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0" style="height: 45px; !important">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Month</p>
        <div class="select-status d-flex">
            <select id="month" name="month" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"></select>
        </div>
    </div>

    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Year</p>
        <div class="select-status d-flex">
            <select id="year" name="year" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"></select>
        </div>
    </div>
</x-filters.filter-box>
@endsection
@section('content')

<div class="content-wrapper">
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <table id="employee" class="table table-hover border-0 w-100">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Subject</th>
                    <th>Obtained Point</th>
                    <th>Total Point</th>
                    <th>Time</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Total Points</th>
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
    var path = '<?= URL::route('tlr_month', ['id' => ':id']) ?>';
    path = path.replace(':id', '<?= $id ?>');
    var user_id = path.split('/').pop();

    $(document).ready(function() {

        $(function()

            {



                var url_id = '<?= $id ?>';
                var logged_id = "<?= $logged_user_id ?>"

                if (logged_id != url_id && logged_id != '') {
                    var path = '<?= URL::route('tlr_month', ['id' => ':id']) ?>';
                    path = path.replace(':id', '<?= $logged_user_id ?>');
                    window.location.href = path;

                }
                var master = $('#employee').dataTable({

                    "bProcessing": true,
                    "bServerSide": true,
                    "autoWidth": true,
                    "ajax": {
                        'url': path,
                        'data': function(d) {

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
                            mData: null,
                            sWidth: "20%",

                            mRender: function(v, t, o) {


                                if (o['subject'] == "Leaves Points") {
                                    var extra_html = "Leaves";
                                } else if (o['subject'] == "Punctuality Points") {
                                    var extra_html = "Punctuality";
                                } else {
                                    var extra_html = o['topic']
                                }
                                return extra_html;


                            }

                        },
                        {
                            mData: null,
                            sWidth: "20%",

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
                            sWidth: "12%",
                            bSortable: false,

                        },
                        {
                            mData: null,
                            sWidth: "10%",
                            bSortable: false,
                            mRender: function(v, t, o) {


                                if (o['subject'] == "DRS Points" || o['subject'] == "Leaves Points" || o['topic'] == "Learn New Technology" || (o['topic'] == "Workshop on New Technology" && o['participant'] == 0)) {
                                    var extra_html = 20;
                                } else if (o['subject'] == "Punctuality Points" || o['topic'] == "Job Referral Joining" || o['topic'] == "Job Referral (2+ Years)" || o['topic'] == "Presentation (With PPT)" || o['topic'] == "Training Fresher") {
                                    var extra_html = 10;
                                } else if (o['participant'] == 1 || o['topic'] == "Presentation" || o['topic'] == "Job Referral (0-2 Years)" || o['subject'] == "Typing Points") {
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


                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };
                        pageTotal = api
                            .column(2, {
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

                        $(api.column(2).footer()).html(pageTotal + extra_html);
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
        selectMonth.val(month);

        var session_month = "{{Session::get('month')}}";
        if (session_month == 15 || session_month.length == 0) {
            selectMonth.val(month);
        } else {
            selectMonth.val(session_month);
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
</script>
@endpush