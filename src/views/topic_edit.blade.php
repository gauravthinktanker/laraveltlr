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
</style>
@endpush
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12">
                <?= Form::model($topic,['route'=>['topic.update','id'=>$topic->id], 'class' => 'form-horizontal','method'=>'put']) ?>
                <div class="add-client bg-white rounded">
        <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
          Edit Header</h4>
        <div class="row p-20">
                  <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputDate" class ="col-sm-2 form-label">Headers</label>
                        <div class="col-sm-9">
                            <?= Form::text('topic',$topic->topic,['class' => 'form-control height-35 f-14' ,'id' => 'topic']) ?>
                <?= $errors->first('topic',"<span class='text-danger'>:message</span>"); ?>
                        </div>
                    </div>

                    <div class="form-group" id='subject_div'>
                      <label for="exampleInputLeaveType" class ="col-sm-2 form-label">Points</label>
                      <div class="col-sm-9">
                            <?= Form::text('point',$topic->point, ['class' => 'form-control height-35 f-14', 'id'=>'point','placeholder' => 'point' , 'autocomplete'=>'off']);?>
                          <?= $errors->first('point',"<span class='text-danger'>:message</span>"); ?>
                          </div>
            </div>
          </div>
        </div>
                    
        <div class="w-100 border-top-grey d-block d-lg-flex d-md-flex justify-content-start px-4 py-3">
        <button type="submit" class="btn-primary rounded f-14 p-2 mr-3" id="submit" name="save_button" value="save_new"><svg class="svg-inline--fa fa-check fa-w-16 mr-1" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
              <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
            </svg>Save</button>
            <a href="<?= URL::route('topic.index') ?>" class="btn-cancel rounded f-14 p-2 border-0"> Cancel</a>
                  </div>
              {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@stop
