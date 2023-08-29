@extends('layouts.app')
@push('styles')

<style>
    .swal2-cancel {
        padding: 10px 10px 10px 10px;
        margin: 10px 10px 10px 10px;
    }

    .swal2-confirm {
        padding: 10px 10px 10px 10px;
        margin: 10px 10px 10px 10px;
    }
</style>
@endpush
@section('content')


<div class="content-wrapper">
<div class="d-block d-lg-flex d-md-flex justify-content-between action-bar">
        <div id="table-actions" class="flex-grow-1 align-items-center">
            <button class="btn-primary rounded f-14 p-2 mb-3" id="add_new_tech" style="float: right !important;">Add New Technology</button>
        </div>
    </div>
  <div class="row">
    <div class="col-sm-12">

    <?= Form::open(array('url' => route('aptitude.store') ,'class' => 'form-horizontal','files' => true)) ?>
      <div class="add-client bg-white rounded">
        <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
          Add Aptitude</h4>
        <div class="row p-20">
          <div class="card-body">
          <input type="hidden"  class="hiddentech" name="tech_id">
                        <div class="form-group">
                        <label for="companyname" class="col-sm-2 mt-2 form-label">Technology</label>
                        <div class="col-sm-9">
                            <select class="form-control height-35 f-14" id="techdropdown">
                                <option value="">Select</option>
                                 @foreach($technology as $key => $value)
                              <option value="{{$value['id']}}">{{$value['name']}}</option>
                              @endforeach
                            </select>
                        </div>
                        </div>
            <div class="form-group">
            <label for="companyname" class="col-sm-2 mt-2 form-label">Questions</label>
              <div class="col-sm-9">
              <?= Form::text('question', old('question'), ['class' => 'form-control height-35 f-14', 'placeholder' => 'Aptitude Question']); ?>
                        <?= $errors->first('question',"<span class='text-danger'>:message</span>");?>
            </div>
            </div>

            <div class="form-group">
            <label class="col-sm-2 mt-2 form-label">Options</label>
              <div class="col-sm-9">
              <div class="form-inline" id="dynamic_form">
                        <input type="radio" name="is_true" value="1" id="is_true" />&nbsp&nbsp
                        <input type="text" style="margin-bottom:10px;width:52.5%;" class="form-control height-35 f-14" name="options" id="options" placeholder="enter options">
                        <a href="javascript:void(0)" style="vertical-align:3px;margin-left:5px;" class="btn btn-primary height-35 f-14" id="plus5">Add More</a>
                        <a href="javascript:void(0)" style="vertical-align:3px;margin-left:5px;" class="btn btn-danger height-35 f-14" id="minus5">Remove</a>
                    </div>
                </div>
            </div>
          </div>
        </div>
        
        <div class="w-100 border-top-grey d-block d-lg-flex d-md-flex justify-content-start px-4 py-3">
        <button type="submit" class="btn-primary rounded f-14 p-2 mr-3" id="submit" name="save_button" value="save_new"><svg class="svg-inline--fa fa-check fa-w-16 mr-1" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
              <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
            </svg>Save</button>
          <a href="<?= route('aptitude') ?>" class="btn-cancel rounded f-14 p-2 border-0"> Cancel</a>
        </div>
        {!! Form::close() !!}
      </div>
      <div class="modal" id="tech" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Add New Technology</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="custom-file ">
                            <label class="form-label">Technology</label>
                            <input type="text" class="form-control height-35 f-14 technology" placeholder="Enter Technology Name" name="technology">
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save_tech">Save</button>
                      <button type="button" class="btn btn-secondary closemodal" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>           
              </div>
    </div>
  </div>
</div>
@endsection


@push('scripts')
<script>
    !function(t){t.fn.dynamicForm=function(e,n,i){var r,o,a,c,l=t(this),u="input, checkbox, select, textarea",h=[],f=[];function s(t){var e,n;return e=a.cloneWithAttribut(!0),"function"==typeof i.afterClone&&(n=i.afterClone(e)),(n||void 0===n)&&e.insertAfter(h[h.length-1]||l),e.getSource=function(){return l},e.attr("id")&&e.attr("id",e.attr("id")+h.length),e.effect&&i.createColor&&!t&&e.effect("highlight",{color:i.createColor},i.duration),e}function d(e){t(f).each(function(){var n=this.getPlusSelector(),i=this.getMinusSelector(),r=this.getOptions(),o=this.selector;e.find(this.selector).each(function(){r=t.extend({internalSubDynamicForm:!0,internalContainer:e,isInAClone:!0,outerCloneIndex:h.length,selector:o},r),t(this).dynamicForm(n,i,r)})})}function g(n){n.preventDefault(),this.removableClone.effect&&i.removeColor?(that=this,this.removableClone.effect("highlight",{color:i.removeColor},i.duration,function(){that.removableClone.remove()})):this.removableClone.remove(),h.splice(t.inArray(this.removableClone,h),1),0===h.length?l.find(e).show():h[h.length-1].find(e).show()}function m(e,n){var i=/(.+\[[^\[\]]+\]\[)(\d+)(\]\[[^\[\]]+\])$/;e.find(u).each(function(){var r=t(this),o=r.attr("name"),a=(r.attr("origname"),r.attr("id")),c=a.slice(0,-1)+n,l=i.exec(o);r.attr("name",l[1]+n+l[3]),a&&(r.attr("origid",a),e.find("label[for='"+a+"']").each(function(){t(this).attr("for",c)}),r.attr("id",c))})}i.internalSubDynamicForm?(r=t(i.internalContainer).find(n),o=t(i.internalContainer).find(e)):(r=t(n),o=t(e)),i=t.extend({duration:1e3,normalizeFullForm:!0,isSubDynamicForm:!1},i),l.each(function(){t.extend(this,{addSubDynamicForm:function(t){f.push(t)},getFormPrefix:function(){return c},getSource:function(){return l}})});var v=!0;return t(this).parentsUntil("body").each(function(){if(t.isFunction(this.addSubDynamicForm)&&!i.isSubDynamicForm)return v=!1,i.isSubDynamicForm=!0,t.extend({internalSubDynamicForm:!0,internalContainer:this},i),this.addSubDynamicForm(l),c=this.getFormPrefix()+"[0]["+c+"]",!1}),v&&!i.isInAClone&&(c=c+"["+c+"]"),i.isInAClone?function e(n,i,r){var o=/(.+)\[([^\[\]]+)\]$/;n.find(u).each(function(){var e=t(this),a=e.attr("name"),c=e.attr("id"),l=c+r,u=o.exec(a);e.attr("name",u[1]+"["+i+"]["+r+"]["+u[2]+"]"),c&&(e.attr("origid",c),n.find("label[for='"+c+"']").each(function(){t(this).attr("for",l)}),e.attr("id",l))})}(l,c=c||i.selector.replace(/\W/g,""),0):function e(n,i,r){n.find(u).each(function(){var e=t(this),n=e.attr("name"),o=e.attr("origname"),a=e.attr("id");e.attr("origid"),o?e.attr("name",i+"["+r+"]["+o+"]"):(e.attr("origname",n),e.attr("name",i+"["+r+"]["+n+"]")),a&&(e.attr("origid",a),t("label[for='"+a+"']").each(function(){t(this).attr("origfor",a),t(this).attr("for",a+r)}),e.attr("id",a+r))})}(l,c,0),v&&i.normalizeFullForm&&!i.isInAClone&&t(this).parentsUntil("form").each(function(){var e=t(this).parent().get(0);t(e).find(u).filter("[type!=submit]").each(function(){var e=t(this),n=e.attr("name"),i=e.attr("origname");e.attr("id"),e.attr("origid"),i||(e.attr("origname",n),e.attr("name",c+"["+n+"]"))})}),isPlusDescendentOfTemplate=(isPlusDescendentOfTemplate=l.find("*").filter(function(){return this==o.get(0)})).length>0,r.hide(),isPlusDescendentOfTemplate?o.click(function t(a,c){var u,f=h[h.length-1]||l;a.preventDefault(),f.find(n).show(),f.find(e).hide(),0===h.length&&l.find(n).hide(),o=(u=s(c)).find(e),(r=u.find(n)).get(0).removableClone=u,r.click(g),i.limit&&i.limit-2>h.length?(o.show(),r.show()):(o.hide(),r.show()),h.push(u),m(u,h.length),d(u)}):(o.click(function t(e,n){var a;e.preventDefault(),0===h.length&&r.show(),a=s(n),i.limit&&i.limit-3<h.length&&o.hide(),h.push(a),m(a,h.length),d(a)}),r.click(function t(e){e.preventDefault();var n=h.pop();h.length>=0&&(n.effect&&i.removeColor?(that=this,n.effect("highlight",{color:i.removeColor,mode:"hide"},i.duration,function(){n.remove()})):n.remove()),0===h.length&&r.hide(),o.show()})),t.extend(l,{getPlus:function(){return o},getPlusSelector:function(){return e},getMinus:function(){return r},getMinusSelector:function(){return n},getOptions:function(){return i},getClones:function(){return[l].concat(h)},getSource:function(){return l},inject:function(e){function n(e,i){var r=this;e>0&&r.getSource().getPlus().trigger("click",["disableEffect"]);var o=r.get(0).getSource().getClones()[e];t.each(i,function(i,a){if(t.isArray(a))"function"==typeof(r=o.find("#"+i)).get(0).getSource&&t.each(a,t.proxy(n,r.get(0).getSource()));else{var c=r.getSource().getClones()[e].find("[origname='"+i+"']");c&&("input"==c.get(0).tagName.toLowerCase()?"radio"==c.attr("type")?c.filter("[value='"+a+"']").attr("checked","checked"):"checkbox"==c.attr("type")?c.attr("checked","checked"):c.attr("value",a):"textarea"==c.get(0).tagName.toLowerCase()?c.text(a):"select"==c.get(0).tagName.toLowerCase()&&t(c.get(0)).find("option").each(function(){(t(this).text()==a||t(this).attr("value")==a)&&t(this).attr("selected","selected")}))}})}t.each(e,t.proxy(n,l))}}),a=l.cloneWithAttribut(!0),i.data&&l.inject(i.data),l},jQuery.fn.cloneWithAttribut=function(e){if(jQuery.support.noCloneEvent)return t(this).clone(e);t(this).find("*").each(function(){t(this).data("name",t(this).attr("name"))});var n=t(this).clone(e);return n.find("*").each(function(){t(this).attr("name",t(this).data("name"))}),n}}(jQuery);
</script>
<script>
   $(document).ready(function() {
    
    $('.save_tech').click(function(){

        var techname = $('.technology').val();
        $.ajax({
                type:"POST",
                url:"aptitude/savetechnology",
                data: {"_token":"{{csrf_token()}}","hidden":techname},
                success : function(response){
                    // setTimeout(function(){
                    //     location.reload();
                    // }, 1000);
                    alert('Technology Successfully Added');
                    location.reload();
                    
               }
            });
    })
    
     
    
    $('.closemodal,.close').click(function(){
        $('#tech').hide();
    })
    
    $("#add_new_tech").click(function(){
      $('#tech').show();
    });
    // $('#techdropdown').change(function(){
    //     $('.hiddentech').val($(this).val())
    //     if($('#techdropdown option:selected').val() == "add" )
    //     {
    //         $('#tech').show();
    //     }
    // })
    $("#user_ask").select2();

    $("#selectall").click(function(){
        if($("#selectall").is(':checked') ){
            $("#user_ask > option").prop("selected","selected");
            $("#user_ask").trigger("change");
        }else{
            $("#user_ask > option").removeAttr("selected");
             $("#user_ask").trigger("change");
         }
    });

    $('input[type="radio"]').click(function(){
        $('input[type="radio"]').not($(this)).prop('checked',false);
    });

    var dynamic_form =  $("#dynamic_form").dynamicForm("#plus5", "#minus5", {
        limit:100,
        normalizeFullForm : false,
    });

    @if(!count($errors))
        dynamic_form.inject([{"options":""},{"options":""}]);
    @endif

    @if(count($errors) >= 0)
        dynamic_form.inject( <?= json_encode(old('undefined.undefined')) ?> );

        var detail_Errors = <?= json_encode($errors->toArray()) ?>;

        $.each(detail_Errors, function(id, msg){
            var id_arr = id.split('.');
            $('#options' + id_arr[id_arr.length-2]).parent().append('</br><span class="help-inline text-danger">'+ msg[0] +'</span></br>');
        });
    @endif
    
});
</script>

@endpush