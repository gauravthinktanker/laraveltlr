@extends('layouts.app')
@push('styles')

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<style>
    .filter-box {
        z-index: 2;
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
</style>

@endpush
@section('content')
<div class="content-wrapper">
    <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar">
        <div id="table-actions" class="flex-grow-1 align-items-center">
            <a href="<?= URL::route('services.create') ?>" class="btn-primary rounded f-14 p-2 mr-3" style="float: right !important;">Add Service</a>

        </div>
    </div>
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <table id="employee" class="table table-hover border-0 w-100">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Person Name</th>
                    <th>Contact</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
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
    var delete_path = "{{ route('services.delete') }}";

    $(document).ready(function() {

        $(function() {
            var master = $('#employee').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "autoWidth": true,
                "aaSorting": [
                    [1, "asc"]
                ],
                "sAjaxSource": "{{ URL::route('services.index')}}",
                "fnServerParams": function(aoData) {
                    aoData.push({
                        "name": "act",
                        "value": "fetch"
                    });
                    server_params = aoData;
                },
                "aoColumns": [{
                        mData: 'id',
                        sWidth: "20%",
                        bSortable: true,
                        bVisible: false
                    },
                    {
                        mData: 'person_name',
                        sWidth: "30%",
                        bSortable: true,
                    },
                    {
                        mData: 'phone_num',
                        sWidth: "30%",
                        bSortable: true,
                        mRender: function(v, t, o) {
                          //  values = v.split(',');
                           // return values[0];
                           return v;
                        }
                    },
                    {
                        mData: 'category',
                        sWidth: "20%",
                        bSortable: true,
                    },
                    {
                        mData: null,
                        sWidth: "10%",
                        bSortable: false,
                        mRender: function(v, t, o) {

                            var path = "<?= URL::route('services.edit', array(':id')) ?>";

                            path = path.replace(':id', o['id']);

                            var extra_html  =   "<div class=task_view><div class=dropdown>"
                                    +       "<a class='align-items-center d-flex dropdown-toggle justify-content-center task_view_more'id=dropdownMenuLink-41 aria-expanded=false aria-haspopup=true data-toggle=dropdown type=link><i class='icon-options-vertical icons'></i></a><div class='dropdown-menu dropdown-menu-right'aria-labelledby=dropdownMenuLink-41 style=position:absolute;will-change:transform;top:0;left:0;transform:translate3d(23px,26px,0) tabindex=0 x-placement=bottom-end><a class='dropdown-item'href="+path+"> <svg aria-hidden=true class='mr-2 svg-inline--fa fa-edit fa-w-18'data-fa-i2svg=''data-icon=edit data-prefix=fa focusable=false role=img viewBox='0 0 576 512'xmlns=http://www.w3.org/2000/svg><path d='M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z'fill=currentColor></path></svg> Edit </a>"
                                    +   "<a id='delete' class='dropdown-item delete-table-row' href='javascript:void(0)' data-duration=single data-leave-id=3124 data-unique-id=GZ668RQZU8QPFbWG id=delete onclick=\"deleteRecord('"+delete_path+"','"+title+"','"+text+"','"+token+"','"+type+"',"+o['id']+")\"><svg class='svg-inline--fa fa-trash fa-w-14 mr-2' aria-hidden='true' focusable='false' data-prefix='fa' data-icon='trash role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' data-fa-i2svg=''><path fill='currentColor' d='M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z'></path></svg>Delete</a>  "
                                    +   "</div></div>";
                    return extra_html;


                        }

                    }
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
    if (status_message != '')
        window.onload = massge;
</script>
@endpush