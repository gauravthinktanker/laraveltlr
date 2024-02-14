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

    #dynamicTableContainer {
        max-height: 400px;
        /* Set a maximum height for the table container */
        overflow: auto;
        /* Enable vertical scrolling if the content overflows */
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    td {
        vertical-align: top;
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

    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <div id="dynamicTableContainer"></div>

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
    var path = '<?= URL::route('tlr_checkInOut', ['id' => ':id']) ?>';

    $(document).ready(function() {
        function initializeDataTable(responseData) {
            var tableContainer = $('#dynamicTableContainer');

            if (responseData && responseData.aaData && responseData.aaData.length > 0) {
                var tableHtml = '<table style="width:100%">';

                // Group data by employee name
                var groupedData = groupBy(responseData.aaData, 'name');

                // Iterate through each employee's data
                for (var name in groupedData) {
                    if (groupedData.hasOwnProperty(name)) {
                        var employeeData = groupedData[name];

                        tableHtml += '<tr>';
                        tableHtml += '<td colspan="5"><strong>Name:</strong> ' + name + '</td>';
                        tableHtml += '</tr>';
                        tableHtml += '<tr>';
                        tableHtml += '<th>Date</th>';
                        tableHtml += '<th>Check-In Time</th>';
                        tableHtml += '<th>Check-Out Time</th>';
                        tableHtml += '<th>Daily Total Hours</th>';
                        tableHtml += '<th>Monthly Total Hours</th>';
                        tableHtml += '</tr>';

                        // Sort employeeData by start_time
                        employeeData.sort(function(a, b) {
                            return new Date(a.start_time) - new Date(b.start_time);
                        });

                        // Initialize variables to track the current date and total times
                        var currentDate = null;
                        var dailyTotalHours = 0;
                        var monthlyTotalHours = 0;

                        // Iterate through each check-in and check-out
                        for (var i = 0; i < employeeData.length; i++) {
                            var startTime = new Date(employeeData[i].start_time);
                            var endTime = employeeData[i].end_time ? new Date(employeeData[i].end_time) : null;

                            // Check if end_time is null
                            if (endTime !== null) {
                                var hours = Math.abs(endTime - startTime) / 36e5;
                            } else {
                                // Set hours to 0 if end_time is null
                                var hours = 0;
                            }

                            var formattedDate = new Date(employeeData[i].start_time).toLocaleDateString();
                            var formattedStartTime = new Date(employeeData[i].start_time).toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            var formattedEndTime = endTime ? new Date(employeeData[i].end_time).toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit'
                            }) : 'N/A';

                            // Check if the date has changed
                            if (formattedDate !== currentDate) {
                                if (currentDate !== null) {
                                    // Display daily total hours
                                    tableHtml += '<tr>';
                                    tableHtml += '<td></td>';
                                    tableHtml += '<td></td>';
                                    tableHtml += '<td></td>';
                                    tableHtml += '<td>' + dailyTotalHours.toFixed(2) + ' hours</td>';
                                    tableHtml += '<td></td>';
                                    tableHtml += '</tr>';

                                    // Update monthly total hours
                                    monthlyTotalHours += dailyTotalHours;

                                    // Reset daily total hours for the new date
                                    dailyTotalHours = 0;
                                }

                                // Display date and apply highlighting to the current date
                                var currentDateClass = (new Date(formattedDate).toDateString() === new Date().toDateString()) ? 'highlight-current-date' : '';
                                tableHtml += '<tr class="' + currentDateClass + '">';
                                tableHtml += '<td>' + formattedDate + '</td>';
                                tableHtml += '<td>' + formattedStartTime + '</td>';
                                tableHtml += '<td>' + formattedEndTime + '</td>';
                            } else {
                                // Display empty date cells
                                tableHtml += '<tr>';
                                tableHtml += '<td></td>';
                                tableHtml += '<td>' + formattedStartTime + '</td>';
                                tableHtml += '<td>' + formattedEndTime + '</td>';
                            }

                            // Update daily variables
                            dailyTotalHours += hours;

                            currentDate = formattedDate;
                        }

                        // Display the last row
                        tableHtml += '<tr>';
                        tableHtml += '<td></td>';
                        tableHtml += '<td></td>';
                        tableHtml += '<td></td>';
                        tableHtml += '<td>' + dailyTotalHours.toFixed(2) + ' hours</td>';
                        tableHtml += '<td>' + monthlyTotalHours.toFixed(2) + ' hours</td>';
                        tableHtml += '</tr>';
                    }
                }

                tableHtml += '</table>';
                tableContainer.html(tableHtml);
                var dataTable = $('#employeeDataTable').DataTable({
                    "paging": true, // Enable pagination
                    "searching": true, // Enable search bar
                    // You can add more DataTables options here
                });
            } else {
                // Display an error message or handle the case where no data is available.
                tableContainer.html('<p>No data available.</p>');
            }
        }

        function groupBy(array, key) {
            return array.reduce(function(acc, obj) {
                var property = obj[key];
                acc[property] = acc[property] || [];
                acc[property].push(obj);
                return acc;
            }, {});
        }

        function fetchData() {
            $.ajax({
                'url': path, // Make sure 'path' is defined
                'data': {
                    'user': $('#users').val(),
                    'month': $('#month').val(),
                    'year': $('#year').val(),
                },
                'success': function(json) {
                    if (json) {
                        responseData = json;
                        initializeDataTable(responseData);
                    } else {
                        console.error('Invalid response format or no data:', json);
                    }
                },
                'error': function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Display an error message or handle the error appropriately.
                }
            });
        }

        fetchData();

        $('#month, #users, #year').change(function() {
            fetchData();
        });

        $.ajaxSetup({
            'statusCode': {
                401: function() {
                    location.reload();
                }
            }
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


</script>

@endpush