@extends('layouts.app')
@push('styles')
<style>
  .btnCreate-primary {

    background-color: #FFB400 !important;
    border: 1px solid #FFB400 !important;
    color: #fff !important;
    padding: 9px 11px;
    position: relative;
    text-transform: capitalize;
  }

  .btnCancel-secondary {
    background-color: #fff !important;
    border: 1px solid #616e80;
    color: #616e80 !important;
    padding: 9px 11px;
    position: relative;
    text-transform: capitalize;
  }

  .btnCreate-primary:hover {
    background-color: #000 !important;
    border: 1px solid #000 !important;
    color: #fff !important;
  }

  .btnCancel-secondary:hover {
    background-color: #000 !important;
    border: 1px solid #000 !important;
    color: #fff !important;
  }
  .swal2-cancel{
        padding: 10px 10px 10px 10px;
        margin: 10px 10px 10px 10px;
    }
    .swal2-confirm{
        padding: 10px 10px 10px 10px;
        margin: 10px 10px 10px 10px;
    }
</style>
@endpush
@section('content')


<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12">

      <?= Form::open(array('url' => route('point.store'), 'class' => 'form-horizontal')) ?>
      <div class="add-client bg-white rounded">
        <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
          Allow Points</h4>
        <div class="row p-20">
          <div class="card-body">
            <div class="form-group">
              <label for="exampleInputDate" class="col-sm-2 form-label">Topic</label>
              <div class="col-sm-9">
                <?= Form::select('topic', ['' => 'Select Topic'] + $topic, old('topic'), ['class' => 'form-control height-35 f-14', 'id' => 'topic']) ?>
                <?= $errors->first('topic', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>

            <div class="form-group" id='subject_div'>
              <label for="exampleInputLeaveType" class="col-sm-2 form-label">Subject</label>
              <div class="col-sm-9">
                <?= Form::text('subject', old('subject'), ['class' => 'form-control height-35 f-14', 'id' => 'subject']) ?>
                <?= $errors->first('subject', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>
            <div class="form-group" id="skills_div">
              <label for="exampleInputLeaveTime" class="col-sm-2 form-label">Skills</label>
              <div class="col-sm-9">
                <?= Form::select('skill', ['' => 'Select Skill'] + $skills, old('skill'), ['class' => 'form-control height-35 f-14', 'id' => 'skill']) ?>
                <?= $errors->first('skill', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>
            <div class="form-group">
              <label for="exampleInputLeavePurpose" class="col-sm-2 form-label" id="organizer">Organizer</label>
              <div class="col-sm-9">
                <?= Form::select('organizer', ['' => 'Select User'] + $users, old('organizer'), ['class' => 'form-control height-35 f-14', 'id' => 'organizer']) ?>
                <?= $errors->first('organizer', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>
            <div class="form-group" id='subject_fresher_div'>
              <label for="exampleInputLeaveType" class="col-sm-2 form-label">Trainee Name</label>
              <div class="col-sm-9">
                <?= Form::select('trainee_name', ['' => 'Select User'] + $users, old('trainee_name'), ['class' => 'form-control height-35 f-14', 'id' => 'trainee_name']) ?>
                <?= $errors->first('trainee_name', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>

            <div class="form-group" id='participant_div'>
              <label for="exampleInputLeavePurpose" class="col-sm-2 form-label">Participants</label>
              <div class="col-sm-9">
                <!--   <button type="button" class="btn btn-primary btn-sm mb-1" onclick="selectAll()">Select All</button>
                         <button type="button" class="btn btn-primary btn-sm mb-1" onclick="deselectAll()">Deselect All</button> -->

                <select class="form-control height-35 f-14  duallistbox" id="participant" name="participant[]" multiple="multiple">
                </select>

                <?= $errors->first('participant', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>


            <div class="form-group">
              <label for="exampleInputLeavePurpose" class="col-sm-2 form-label">Date</label>
              <div class="col-sm-9">
                <?= Form::text('date', old('date'), ['class' => 'form-control height-35 f-14', 'id' => 'start_date', 'placeholder' => 'Date', 'autocomplete' => 'off']); ?>
                <?= $errors->first('date', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>

            <div class="form-group">
              <label for="exampleInputLeavePurpose" class="col-sm-2 form-label">Show Time</label>
              <div class="col-sm-9">

                <input class="time" type="checkbox" name="time" value="1" />
                <?= $errors->first('time', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>

            <div class="form-group" id="from_hour_div">
              <label for="exampleInputLeavePurpose" class="col-sm-2 form-label">From Hours</label>
              <div class="col-sm-9">
                <?= Form::text('from_hour', '10:00 AM', ['class' => 'form-control height-35 f-14 timepicker1', 'placeholder' => 'From Hours']); ?>
                <?= $errors->first('from_hour', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>
            <div class="form-group" id="to_hour_div">
              <label for="exampleInputLeavePurpose" class="col-sm-2 form-label">To Hours</label>
              <div class="col-sm-9">
                <?= Form::text('to_hour', '07:00 PM', ['class' => 'form-control height-35 f-14 timepicker2', 'placeholder' => 'To Hours']); ?>
                <?= $errors->first('to_hour', "<span class='text-danger'>:message</span>"); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100 border-top-grey d-block d-lg-flex d-md-flex justify-content-start px-4 py-3">
          <button type="submit" class="btnCreate-primary f-14 p-2 mr-3 submit" id="submit" name="save_button" value="save_new">Save</button>
          <a href="<?= URL::route('tlr_month_admin') ?>" class="btnCancel-secondary"> Cancel</a>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="vendor/laraveltlr/tlr/storage/js/jquery.bootstrap-duallistbox.min.js"></script>

<script type="text/javascript">
  //  function selectAll() {
  //  $("#participant > option").prop("selected", true);
  //  $("#participant").trigger("change");
  // }

  // function deselectAll() {
  //     $("#participant > option").prop("selected", false);
  //     $("#participant").trigger("change");
  // }
  jQuery(document).ready(function() {

    $('#participant').bootstrapDualListbox()


    $('#from_hour_div').hide();
    $('#to_hour_div').hide();

    function valueChanged() {
      if ($('.time').is(":checked")) {

        $('#from_hour_div').show();
        $('#to_hour_div').show();
      } else {
        $('#from_hour_div').hide();
        $('#to_hour_div').hide();

      }
    }

    $(document).on('change', '#organizer', function() {
      $('#participant').empty("");
      var id = $("#organizer").val();
      $.ajax({
        type: "GET",
        url: "{{ route('point.create') }}",
        data: {
          'id': id
        },
        success: function(res) {
          $("#participant").children().remove();
          $.each(res.participant, function(index, value) {
            $('#participant').append($('<option/>', {
              value: index,
              text: value
            }));
          });
          $("#participant").bootstrapDualListbox('refresh', true);
        }
      });
    });

    $(".time").on("change", valueChanged);


    // $('#participant').select2({
    //        placeholder: "Please select Organizer First",             
    // });

    $('#user').select2({
      placeholder: "Please select organizer",
    });

    var currentDate = new Date();
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
    datepicker('#start_date', {
      position: 'bl',
      ...datepickerConfig,
      onSelect: function() {
        $('.dataTable').each(function() {
          dt = $(this).dataTable();
          dt.fnDraw();
        });
      }
    });

    // $('#start_date').datepicker({
    //     dateFormat: "dd-mm-yy",
    //     autoclose: true,
    //     todayHighlight: true,
    //     endDate:currentDate,
    // });


    var topic = $('#topic');


    SkillsShowHide();
    ParticipantShowHide();
    subjectshowhide();

    topic.on('change', function() {
      SkillsShowHide();
      ParticipantShowHide();
      subjectshowhide();

    });


    function SkillsShowHide() {

      TopicVal = topic.val();

      if (TopicVal == '5') {
        $('#skills_div').show();
      } else {
        $('#skills_div').hide();
      }
    }

    function ParticipantShowHide() {



      TopicVal = topic.val();

      if (TopicVal == '1' || TopicVal == '4') {
        $('#participant_div').show();
        $('#subject_div').show();
      } else {
        $('#participant_div').hide();
        $('#subject_div').hide();
      }
    }

    function subjectshowhide() {
      TopicVal = topic.val();

      if (TopicVal == '5') {
        $('#subject_div').hide();
      } else {
        $('#subject_div').show();
      }

      if (TopicVal == '15') {
        $('#organizer').text("Senior Name");
        $('#subject_fresher_div').show();
        $('#subject_div').hide();
      } else {
        $('#organizer').text("Organizer");
        $('#subject_fresher_div').hide();
      }

    }



  });
</script>
@endpush