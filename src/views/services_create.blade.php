@extends('layouts.app')
@push('styles')

<style>
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

    <?= Form::open(array('url' => route('services.store') ,'class' => 'form-horizontal')) ?>
      <div class="add-client bg-white rounded">
        <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
          Add Service</h4>
        <div class="row p-20">
          <div class="card-body">
            <div class="form-group">
              <label for="person_name" class="col-sm-2 form-label">Person Name</label>
              <div class="col-sm-9">
              <?= Form::text('person_name' ,old('person_name') ,['class' => 'form-control height-35 f-14','placeholder' => 'Person Name']); ?>
                    <?= $errors->first('person_name',"<span class='text-danger'>:message</span>");?>
            </div>
            </div>

            <div class="form-group">
              <label for="category" class="col-sm-2 form-label">Category</label>
              <div class="col-sm-9">
              <?= Form::select('category',$category,old('category') ,['class' => 'form-control height-35 f-14 select2','id'=>'category']); ?>
                    <?= $errors->first('category',"<span class='text-danger'>:message</span>");?> 
            </div>
            </div>
            <div class="form-group">
              <label for="contact_name" class="col-sm-2 form-label">Contact No</label>
              <div class="col-sm-9">
              <select class="select2_2 form-control height-35 f-14" id="phone_num" multiple="multiple" name="phone_num[]" placeholder="Phone Number">
                    </select>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100 border-top-grey d-block d-lg-flex d-md-flex justify-content-start px-4 py-3">
        <button type="submit" class="btn-primary rounded f-14 p-2 mr-3" id="submit" name="save_button" value="save_new"><svg class="svg-inline--fa fa-check fa-w-16 mr-1" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
              <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
            </svg>Save</button>
          <a href="<?= URL::route('services.index') ?>" class="btn-cancel rounded f-14 p-2 border-0"> Cancel</a>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

<script type="text/javascript">
  
      var tag = $('#phone_num').select2({
        placeholder : "select tag",
       
      });
      @if(old('phone_num'))
            var old_tag = {!! json_encode(old('phone_num')) !!};
            $.each(old_tag,function(k,v){
                $("#phone_num").append($('<option>', {value: v, text: v}));
            })
            tag.val(old_tag).trigger('change');
        @endif
      //category
      $('#category').prepend('<option value="">Select Category</option>');
        $("#category").val($(".select2 option:first").val());
        $("#category")
        .select2({
            placeholder: 'Select type',
            width: '100%',
            minimumResultsForSearch: Infinity
          })
        .on('select2:close', function() {
            var el = $(this);
            if(el.val()==="NEW") {
              var newval = prompt("Enter New Category: ");
              if(newval !== null) {
                $.ajax({
                    url     : "{{URL::route('category.store')}}",
                    type    : 'post',
                    dataType: 'html',
                    data    :{
                                'newval': newval,
                                '_token':'{{csrf_token()}}'
                            },
                    success    : function(resp) 
                    {
                        el.append('<option value='+resp+'>'+newval+'</option>')
                          .val(newval);
                    }
                });
              }
            }
        });
    </script>   
    <script>
        !function(e){"use strict";e(".flexdatalist").length&&e(".flexdatalist").flexdatalist(),e("#popover-1").length&&e("#popover-1").popSelect({showTitle:!1,maxAllowed:2}),e("#popover-2").length&&e("#popover-2").popSelect({showTitle:!1,placeholderText:"Click to Add More",position:"bottom"}),e(".select2_1").length&&e(".select2_1").select2(),e(".select2_2").length&&e(".select2_2").select2({tags:!0}),e(".multiselect").length&&e(".multiselect").multiselect(),e("#search").length&&e("#search").multiselect({search:{left:'<input type="text" name="q" class="form-control" placeholder="Search..." />',right:'<input type="text" name="q" class="form-control" placeholder="Search..." />'}}),e("input[name='demo1']").length&&e("input[name='demo1']").TouchSpin({min:0,max:100,step:.1,decimals:2,boostat:5,maxboostedstep:10,postfix:"%"}),e("input[name='demo2']").length&&e("input[name='demo2']").TouchSpin({min:-1e9,max:1e9,stepinterval:50,maxboostedstep:1e7,prefix:"$"}),e("input[name='demo_vertical']").length&&e("input[name='demo_vertical']").TouchSpin({verticalbuttons:!0}),e("input[name='demo_vertical2']").length&&e("input[name='demo_vertical2']").TouchSpin({verticalbuttons:!0,verticalupclass:"glyphicon glyphicon-plus",verticaldownclass:"glyphicon glyphicon-minus"}),e("input[name='demo5']").length&&e("input[name='demo5']").TouchSpin({prefix:"pre",postfix:"post"}),e("#timepicker").length&&e("#timepicker").timepicker({defaultTIme:!1}),e("#timepicker2").length&&e("#timepicker2").timepicker({showMeridian:!1}),e("#timepicker3").length&&e("#timepicker3").timepicker({minuteStep:15}),e(".colorpicker-default").length&&e(".colorpicker-default").colorpicker({format:"hex"}),e(".colorpicker-rgba").length&&e(".colorpicker-rgba").colorpicker(),e("#datepicker").length&&e("#datepicker").datepicker(),e("#datepicker-autoclose").length&&e("#datepicker-autoclose").datepicker({autoclose:!0,todayHighlight:!0}),e("#datepicker-inline").length&&e("#datepicker-inline").datepicker(),e("#datepicker-multiple-date").length&&e("#datepicker-multiple-date").datepicker({format:"mm/dd/yyyy",clearBtn:!0,multidate:!0,multidateSeparator:","}),e("#date-range").length&&e("#date-range").datepicker({toggleActive:!0}),e(".input-daterange-datepicker").length&&e(".input-daterange-datepicker").daterangepicker({buttonClasses:["btn","btn-sm"],applyClass:"btn-default",cancelClass:"btn-primary"}),e(".input-daterange-timepicker").length&&e(".input-daterange-timepicker").daterangepicker({timePicker:!0,format:"MM/DD/YYYY h:mm A",timePickerIncrement:30,timePicker12Hour:!0,timePickerSeconds:!1,buttonClasses:["btn","btn-sm"],applyClass:"btn-default",cancelClass:"btn-primary"}),e(".input-limit-datepicker").length&&e(".input-limit-datepicker").daterangepicker({format:"MM/DD/YYYY",minDate:"06/01/2016",maxDate:"06/30/2016",buttonClasses:["btn","btn-sm"],applyClass:"btn-default",cancelClass:"btn-primary",dateLimit:{days:6}}),e("#reportrange").length&&(e("#reportrange span").html(moment().subtract(29,"days").format("MMMM D, YYYY")+" - "+moment().format("MMMM D, YYYY")),e("#reportrange").daterangepicker({format:"MM/DD/YYYY",startDate:moment().subtract(29,"days"),endDate:moment(),minDate:"01/01/2016",maxDate:"12/31/2016",dateLimit:{days:60},showDropdowns:!0,showWeekNumbers:!0,timePicker:!1,timePickerIncrement:1,timePicker12Hour:!0,ranges:{Today:[moment(),moment()],Yesterday:[moment().subtract(1,"days"),moment().subtract(1,"days")],"Last 7 Days":[moment().subtract(6,"days"),moment()],"Last 30 Days":[moment().subtract(29,"days"),moment()],"This Month":[moment().startOf("month"),moment().endOf("month")],"Last Month":[moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")]},opens:"left",drops:"down",buttonClasses:["btn","btn-sm"],applyClass:"btn-success",cancelClass:"btn-default",separator:" to ",locale:{applyLabel:"Submit",cancelLabel:"Cancel",fromLabel:"From",toLabel:"To",customRangeLabel:"Custom",daysOfWeek:["Su","Mo","Tu","We","Th","Fr","Sa"],monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],firstDay:1}},function(a,t,r){console.log(a.toISOString(),t.toISOString(),r),e("#reportrange span").html(a.format("MMMM D, YYYY")+" - "+t.format("MMMM D, YYYY"))})),e("input#defaultconfig").length&&e("input#defaultconfig").maxlength(),e("input#thresholdconfig").length&&e("input#thresholdconfig").maxlength({threshold:20}),e("input#moreoptions").length&&e("input#moreoptions").maxlength({alwaysShow:!0,warningClass:"label label-success",limitReachedClass:"label label-danger"}),e("input#alloptions").length&&e("input#alloptions").maxlength({alwaysShow:!0,warningClass:"label label-success",limitReachedClass:"label label-danger",separator:" out of ",preText:"You typed ",postText:" chars available.",validate:!0}),e("textarea#textarea").length&&e("textarea#textarea").maxlength({alwaysShow:!0}),e("input#placement").length&&e("input#placement").maxlength({alwaysShow:!0,placement:"top-left"})}(jQuery);
    </script>
@endpush