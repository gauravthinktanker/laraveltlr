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
@if($logged_user_id == "")
    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0" style="height: 45px; !important">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">User</p>
        <div class="select-status d-flex">
        <?= Form::select('user_id', ['' => 'select'] + $all_users, old('user_id'), ['class' => 'position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey', 'id' => 'users']) ?>
        </div>
    </div>
    @endif

    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Year</p>
        <div class="select-status d-flex">
        <select id="year" name="year" class="position-relative text-dark form-control border-0 p-2 text-left f-14 f-w-500 border-additional-grey"></select>
        </div>
    </div>

    <div class="select-box d-flex pr-2 border-right-grey border-right-grey-sm-0">
        <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Date</p>
        <div class="select-status d-flex">
        <input type="text" name="from_date" id="from_date" class="form-control  date-picker height-45 f-14 select_year" placeholder="From Date" />
        <input type="text" name="to_date" id="to_date" class="form-control date-picker height-45 f-14 select_year" placeholder="To Date" />
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
                  <th>Month</th>
                  <th>Point</th>
                  <th>Zone</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Total Points</th>
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
  var path = '<?= URL::route('tlr_year') ?>';
  var user_id = path.split('/').pop();

  $(document).ready(function() {
    var path = '<?= URL::route('tlr_year') ?>';
    var user_id = path.split('/').pop();

    //  $('.input-daterange').datepicker({
    //             todayBtn:'linked',
    //             dateFormat:'yy-mm-dd',
    //             autoclose:true
    //            });
    load_data();
    const datepickerConfig = {
      formatter: (input, date, instance) => {
        input.value = moment(date).format('YYYY-MM-DD')
      },
      showAllDates: true,
      customDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      customMonths: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      customOverlayMonths: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      overlayButton: "Submit",
      overlayPlaceholder: "4-digit year",
      startDay: parseInt("1")
    };
    datepicker('#from_date', {
      position: 'bl',
      ...datepickerConfig,
      onSelect: function(){
        $('.dataTable').each(function() {
          dt = $(this).dataTable();
          dt.fnDraw();
        });
      }
    });

    datepicker('#to_date', {
      position: 'bl',
      ...datepickerConfig,
      onSelect: function(){
        $('.dataTable').each(function() {
          dt = $(this).dataTable();
          dt.fnDraw();
        });
      }
    });

    function load_data(from_date = '', to_date = '') {

      var url_id = '<?= $id ?>';
      var logged_id = "<?= $logged_user_id ?>"

      if (logged_id != url_id && logged_id != '') {
        var path = '<?= URL::route('tlr_year') ?>';
        window.location.href = path;

      }
      var master = $('#employee').dataTable({
        "bProcessing": false,
        "bServerSide": true,
        "searching": false,
        "bPaginate": false,
        "autoWidth": true,
        "ajax": {
          'url': path,
          'data': function(d) {
            d.user = $('#users').val();

            d.from_date = $('#from_date').val();
            d.to_date = $('#to_date').val();

            if ($('#year').val() == null) {
              var a = new Date();
              d.year = a.getFullYear();
            } else {

              d.year = $('#year').val();
            }
          }
        },

        "aoColumns": [

          {
            mData: null,
            sWidth: "20%",
            bSortable: false,
            mRender: function(v, t, o) {

              var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
              var date = o['date'].split("/").reverse().join("-");;
              var extra_html = new Date(date);
              var month = monthNames[extra_html.getMonth()]
              return month;


            }

          },

          {
            mData: 'point',
            sWidth: "15%",
            bSortable: true,
          },

          {
            mData: null,
            sWidth: "7%",
            bSortable: false,
            mRender: function(v, t, o) {

              var date = o['date'].split("/").reverse().join("-");
              var html = new Date(date);
              var month = html.getMonth();

              var pathadmin = "<?= URL::route('monthset', ['month' => ':id']) ?>";
              pathadmin = pathadmin.replace(':id', month);

              var pathuser = "<?= URL::route('monthsetuser', ['month' => ':id']) ?>";
              pathuser = pathuser.replace(':id', month);

              @if($logged_user_id == "")
              if (o['point'] >= 70) {
                var extra_html = '<a href="' + pathadmin + '"+><div class="rounded-circle" style="background-color: green;height:24px;width:24px;"></div></a>'

              } else if (o['point'] >= 50 && o['point'] < 70) {
                var extra_html = '<a href="' + pathadmin + '"><div class="rounded-circle" style="background-color: yellow;height:24px;width:24px;"></div></a>'
              } else if (o['point'] >= 40 && o['point'] < 50) {
                var extra_html = '<a href="' + pathadmin + '"><div class="rounded-circle" style="background-color: red;height:24px;width:24px;"></div></a>'
              } else {
                var extra_html = '<a href="' + pathadmin + '"><div class="rounded-circle" style="background-color: Gray;height:24px;width:24px;"></div></a>'
              }
              return extra_html;

              @else

              if (o['point'] >= 70) {
                var extra_html = '<a href="' + pathuser + '"+><div class="rounded-circle" style="background-color: green;height:24px;width:24px;"></div>'

              } else if (o['point'] >= 50 && o['point'] < 70) {
                var extra_html = '<a href="' + pathuser + '"+><div class="rounded-circle" style="background-color: yellow;height:24px;width:24px;"></div>'
              } else if (o['point'] >= 40 && o['point'] < 50) {
                var extra_html = '<a href="' + pathuser + '"+><div class="rounded-circle" style="background-color: red;height:24px;width:24px;"></div>'
              } else {
                var extra_html = '<a href="' + pathuser + '"+><div class="rounded-circle" style="background-color: Gray;height:24px;width:24px;"></div>'
              }
              return extra_html;

              @endif
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
            .column(1, {
              page: 'current'
            })
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          $(api.column(1).footer()).html(pageTotal);
        },

      });


      $.ajaxSetup({
        statusCode: {
          401: function() {
            location.reload();
          }
        }
      });
    }


    $('#year').change(function() {
      //for export logs

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

    $('#from_date').change(function() {
alert("Sdsd");
      $('.dataTable').each(function() {
        dt = $(this).dataTable();
        dt.fnDraw();
      });
    });

    $('#to_date').change(function() {

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
      var month = d.getMonth();
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
  });
</script>
@endpush