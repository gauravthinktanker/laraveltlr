@extends('layouts.app')
@push('styles')

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<style>
    .filter-box {
        z-index: 2;
    }

    .paginate_button.page-item.active {
        background-color: #ffb400 !important;
        border-color: #ffb400 !important;
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
</style>

@endpush
@section('content')
<div class="content-wrapper">
    <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar">
        <div id="table-actions" class="flex-grow-1 align-items-center">
            <a href="<?= URL::route('topic.create') ?>" class="btnCreate-primary rounded f-14 p-2" style="float:right">Add Header</a>
        </div>
    </div>
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <table id="employee" class="table table-hover border-0 w-100">
            <thead>
            <tr>
                            
                            <th>TLR Headers</th>
                            <th>Points</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                     <tfoot>
                        <tr>
                          <th>Total Points</th>
                          <th>{{$totalpoint[0]}}</th>
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
var delete_path = "<?=URL::route('topic.delete') ?>";
$(document).ready(function(){

    $('#employee').DataTable({

        "bProcessing" : true,
        "bServerSide" : true,
        "ajax"        :"<?= URL::route('topic.index') ?>",
        "aaSorting": [
                       [0, "asc"]
                    ],
        "bPaginate": false,
        "aoColumns" : [
            { mData: 'topic' ,bSortable:true},
            { mData: 'point' ,bSortable:false},
            
            {   
                mData: null,
                sWidth: "10%",
                bSortable : false,
                mRender:function(v,t,o) {

                    var path = "<?= URL::route('topic.edit',['id'=>':id']) ?>";
                    var path_del = "<?= URL::route('topic.delete',['id'=>':id']) ?>";
                    var deletrecord  = "deleteRecord('"+delete_path+"','"+title+"','"+text+"','"+token+"','"+type+"','"+o["id"]+"')";

                    path     = path.replace(':id',o['id']);
                    path_del = path_del.replace(':id',o['id']);

                    // var extra_html  = "<div class=task_view><div class=dropdown><a class='align-items-center d-flex dropdown-toggle justify-content-center task_view_more'id=dropdownMenuLink-41 aria-expanded=false aria-haspopup=true data-toggle=dropdown type=link><i class='icon-options-vertical icons'></i></a><div class='dropdown-menu dropdown-menu-right'aria-labelledby=dropdownMenuLink-41 style=position:absolute;will-change:transform;top:0;left:0;transform:translate3d(23px,26px,0) tabindex=0 x-placement=bottom-end><a class='dropdown-item'href="+path+"> <svg aria-hidden=true class='mr-2 svg-inline--fa fa-edit fa-w-18'data-fa-i2svg=''data-icon=edit data-prefix=fa focusable=false role=img viewBox='0 0 576 512'xmlns=http://www.w3.org/2000/svg><path d='M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z'fill=currentColor></path></svg> Edit </a>";
                    //     extra_html  += "<a class='dropdown-item delete-table-row'class='fa-trash fa fa-fw'href=javascript:void(0) data-duration=single data-leave-id=3124 data-unique-id=GZ668RQZU8QPFbWG id=delete onclick=\'"+deletrecord+"'\> Delete</a></div></div></div>";
                    // return extra_html;
                    var extra_html  =   "<div class=task_view><div class=dropdown>"
                                    +       "<a class='align-items-center d-flex dropdown-toggle justify-content-center task_view_more'id=dropdownMenuLink-41 aria-expanded=false aria-haspopup=true data-toggle=dropdown type=link><i class='icon-options-vertical icons'></i></a><div class='dropdown-menu dropdown-menu-right'aria-labelledby=dropdownMenuLink-41 style=position:absolute;will-change:transform;top:0;left:0;transform:translate3d(23px,26px,0) tabindex=0 x-placement=bottom-end><a class='dropdown-item'href="+path+"> <svg aria-hidden=true class='mr-2 svg-inline--fa fa-edit fa-w-18'data-fa-i2svg=''data-icon=edit data-prefix=fa focusable=false role=img viewBox='0 0 576 512'xmlns=http://www.w3.org/2000/svg><path d='M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z'fill=currentColor></path></svg> Edit </a>"
                                    +   "<a id='delete' class='dropdown-item delete-table-row' href='javascript:void(0)' data-duration=single data-leave-id=3124 data-unique-id=GZ668RQZU8QPFbWG id=delete onclick=\"deleteRecord('"+delete_path+"','"+title+"','"+text+"','"+token+"','"+type+"',"+o['id']+")\"><svg class='svg-inline--fa fa-trash fa-w-14 mr-2' aria-hidden='true' focusable='false' data-prefix='fa' data-icon='trash role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' data-fa-i2svg=''><path fill='currentColor' d='M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z'></path></svg>Delete</a>  "
                                    +   "</div></div>";
                    return extra_html;


                }

            }
        ],
    });

    $.ajaxSetup({
        statusCode: {
          401: function() {
            location.reload();
          }
        }
    });

});
function deleteRecord(delete_path,title,text,token,type,id)
{
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
        deleteRequest(delete_path,id,token);
      } 
    })
}
function deleteRequest(delete_path,id,token)
{
     var Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
    $.ajax({
        url: delete_path,
        type:'post',
        dataType:'json',
        data:{id:id,_token: token},
        beforeSend:function(){
            $('#spin').show();
        },
        complete:function(){
            $('#spin').hide();
            var redrawtable = $('#employee').dataTable();
            $("#employee").DataTable().ajax.reload();
            var is_checked = $('.select_check_box').is(':checked');
            if(is_checked == true)
            {
                $('.select_check_box').prop('checked',false);
            }
            Toast.fire({
                    icon: 'success',
                    title: 'Data Deleted Successfully.',
                  })
        }
    });
}
</script>
</script>   
    @if(Session::has('message'))
  <script type="text/javascript">

  function massge() {
  Swal.fire(
           
            '{{ session()->get('message') }}',
            '',
            '{{ session()->get('message_type') }}'
        );
  }

  window.onload = massge;
 </script>
 @endif

@endpush
