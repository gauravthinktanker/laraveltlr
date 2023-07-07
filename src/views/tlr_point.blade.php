@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="vendor/laraveltlr/tlr/storage/css/dataTables.bootstrap4.min.css">
<style>
    .paginate_button.page-item.active {
        background-color: #ffb400 !important;
        border-color: #ffb400 !important;
    }

    .dataTables_filter {
        display: block !important;
        margin-top: 3%;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        height: 50% !important;

    }
</style>
@section('filter-section')
<x-filters.filter-box>
    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0" style="height: 45px; !important">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Month</p>
        <div class="select-status d-flex">
            <select id="month" name="month" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"></select>
            <input type="hidden" name="month" value="<?= date('m') ?>">
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
                    <th>Organizer</th>
                    <th>Point</th>
                    <th>Zone</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection
@push('scripts')

<script src="vendor/laraveltlr/tlr/storage/js/jquery.dataTables.min.js"></script>
<script src="vendor/laraveltlr/tlr/storage/js/dataTables.bootstrap4.min.js"></script>
<script>
    var path = '<?= URL::route('pointmaster.index', ['id' => ':id']) ?>';
    path = path.replace(':id', '<?= $id ?>');
    var user_id = path.split('/').pop();

    $(document).ready(function() {

        $(function() {
            var url_id = '<?= $id ?>';
            var logged_id = "<?= $logged_user_id ?>"

            if (logged_id != url_id && logged_id != '') {
                var path = '<?= URL::route('pointmaster.index', ['id' => ':id']) ?>';
                path = path.replace(':id', '<?= $logged_user_id ?>');
                window.location.href = path;
            }

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
                // "sAjaxSource": "{{ URL::route('pointmaster.index')}}",
                // "fnServerParams": function ( aoData ) {
                //     aoData.push({ "name": "act", "value": "fetch" });
                //     server_params = aoData;
                // },
                "aoColumns": [{
                        mData: 'name',
                        name: 'users.name',
                        sWidth: "15%",
                        bSortable: true,
                    },
                    {
                        mData: 'point',
                        name: 'point.point',
                        sWidth: "7%",
                        bSortable: true,
                    },
                    {
                        mData: null,
                        sWidth: "7%",
                        bSortable: false,
                        mRender: function(v, t, o) {

                            var user_id = o['user_id'];
                            var date = o['date'].split("/").reverse().join("-");
                            var html = new Date(date);
                            var month = html.getMonth();

                            var pathadmin = "<?= URL::route('point.user', ['month' => ':id', 'user_id' => ':user_id']) ?>";
                            pathadmin = pathadmin.replace(':id', month);
                            pathadmin = pathadmin.replace(':user_id', user_id);

                            if (o['point'] >= 70) {
                                var extra_html = '<a href="' + pathadmin + '"+ target="_blank"><div class="rounded-circle" style="background-color: green;height:24px;width:24px;"></div></a>'

                            } else if (o['point'] >= 50 && o['point'] < 70) {
                                var extra_html = '<a href="' + pathadmin + '" target="_blank"><div class="rounded-circle" style="background-color: yellow;height:24px;width:24px;"></div></a>'
                            } else if (o['point'] >= 40 && o['point'] < 50) {
                                var extra_html = '<a href="' + pathadmin + '" target="_blank"><div class="rounded-circle" style="background-color: red;height:24px;width:24px;"></div></a>'
                            } else {
                                var extra_html = '<a href="' + pathadmin + '" target="_blank"><div class="rounded-circle" style="background-color: Gray;height:24px;width:24px;"></div></a>'
                            }
                            return extra_html
                        }
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
    // $('a').click(function(){
    //     console.log("yes")
    //     var link = $(this).attr("href");
    //     window.open(link,'_blank')
    // })
    $('#month').change(function() {
        //for export logs
        var months = parseInt($('#month').val());
        months = months + 1;

        $('.dataTable').each(function() {
            dt = $(this).dataTable();
            // console.log(dt);
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