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

        <?php
        $parameter = str_random(30);
        ?>
        <div class="col-sm-12">
            <div class="d-flex justify-content-end ">
                <div class="d-flex justify-content-end ">
                    <button type="button" class="btn btn-primary btn-md btn-warning ml-2 mr-2 text-white" data-toggle="modal" data-target="#exampleModal">Import Questions</button>
                    <!-- <button type="submit"  class="btn btn-primary btn-md  btn-warning ml-2 mr-2 text-white" id="delete_token">Delete Link</button> -->
                    <button type="button" class="btn btn-primary btn-md btn-warning ml-2 mr-2 text-white" data-toggle="modal" data-target="#generate-link">Generate Interview</button>
                </div>
                <a href="<?= URL::route('aptitude.create') ?>" id="leaves_create" class="btn-primary rounded f-14 p-2 mr-3" style="float: right !important;">Add Questions
                </a>
            </div>
        </div>
        <!-- Generate link Modal -->
        <div class="modal fade" id="generate-link" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Generate Link</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="custom-file ">
                            <select class="form-control height-35 f-14" name="tech" id="testtype">
                                @foreach($technology as $key => $value)
                                <option value="{{$value['id']}}">{{$value['name']}}</option>
                                @endforeach
                            </select>

                            <select class="form-control height-35 f-14" name="level" id="leveltype" style="margin-top:2%">
                                <option value="none">Please Select</option>
                                <option value="fresher">Fresher</option>
                                <option value="junior">Junior</option>
                                <option value="senior">Senior</option>
                            </select>
                        </div>
                        <div class="custom-file " style="margin-top:2%">
                            <label class="mr-1" for="exampleInputFile">Link</label>
                            <input type="text" id="copy-text" value="{{url('/aptitude/startpage',$parameter)}}" class="form-control height-35 f-14 copy-text" id="link" name="link" placeholder="link">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save_token" onclick="copytext()">Copy</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Choose File</h5>
                    <button type="button" class="close closed" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="file" id="file">
                </div>
                <div class="modal-footer">
                    <button type="button" class="closed btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-dismiss="modal" id="save_file" class="btn btn-primary closed">Upload</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
        <table id="employee" class="table table-hover border-0 w-100">
            <thead>
                <tr>
                    <th>Question</th>
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
    var title = "Are you sure to delete selected record(s)?";
    var text = "You will not be able to recover this record";
    var type = "warning";
    var token = "{{ csrf_token() }}";

    function copytext() {
        // Get the element with ID "copy-text"
        var copyText = document.getElementById("copy-text");

        // Select the text in the input field
        copyText.select();

        try {
            // Copy the selected text to the clipboard using the execCommand method
            var successful = document.execCommand('copy');
            var message = successful ? 'Text copied to clipboard' : 'Copying text failed';
            console.log(message);
        } catch (err) {
            console.error('Unable to copy text: ', err);
        }

        // Assuming you have jQuery loaded, hide the modal with ID "generate-link"
        $('#generate-link').modal('hide');
    }



    $(document).ready(function() {
        $('#leveltype').hide();
        $('#testtype').change(function() {
            var value = $(this).val()
            if (value != 1) {
                $('#leveltype').show();
            } else {
                $('#leveltype').hide();
            }
        })
        $(document).on('click', '.save_token', function(e) {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            var hidden = $('#testtype').val()
            var level = $('#leveltype').val()

            var token = <?= json_encode($parameter); ?>;
            $.ajax({
                type: "POST",
                url: "aptitude/savetoken",
                data: {
                    "token": token,
                    "_token": "{{csrf_token()}}",
                    "hidden": hidden,
                    "level": level
                },
                success: function(response) {
                    if (response.status == true) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 5000);

                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                }
            });

        });

        $(document).on('click', '.code', function(e) {
            var token = <?= json_encode($parameter); ?>;
            $.ajax({
                type: "POST",
                url: "aptitude/savetoken",
                data: {
                    "token": token,
                    "_token": "{{csrf_token()}}"
                },
                success: function(response) {

                }
            });

        });

        $('.closemodal').click(function() {
            location.reload();
        })


        $(document).on('click', '#delete_token', function(e) {

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $.ajax({
                type: "GET",
                url: "aptitude/deletetoken",
                data: {
                    "_token": "{{csrf_token()}}"
                },
                success: function(response) {
                    if (response.status == true) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 2000);

                    }
                }
            });

        });



        $('#employee').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "ajax": "<?= URL::route('aptitude') ?>",
            "aaSorting": false,
            "aoColumns": [

                {
                    mData: 'question',
                    sWidth: "90%",
                    bSortable: false
                },

                {
                    mData: null,
                    sWidth: "10%",
                    bSortable: false,
                    mRender: function(v, t, o) {
                        var extra_html = '';

                        var path_del = "<?= route('aptitude.delete', [':id']) ?>";
                        path_del = path_del.replace(':id', o['id']);

                        // var path_edit = "<?= route('aptitude.edit', [':id']) ?>";
                        // path_edit = path_edit.replace(':id',o['id']);

                        //     extra_html += '<a href="'+path_edit+'" title="edit" class="fa fa-fw fa-edit"></a>  ';
                        //     extra_html += '<a href="'+path_del+'" title="delete" class="fa fa-fw fa-trash"></a>  ';

                        var path_edit = "<?= URL::route('aptitude.edit', array(':id')) ?>";
                        path_edit = path_edit.replace(':id', o['id']);

                        var extra_html = "<div class=task_view><div class=dropdown>" +
                            "<a class='align-items-center d-flex dropdown-toggle justify-content-center task_view_more'id=dropdownMenuLink-41 aria-expanded=false aria-haspopup=true data-toggle=dropdown type=link><i class='icon-options-vertical icons'></i></a><div class='dropdown-menu dropdown-menu-right'aria-labelledby=dropdownMenuLink-41 style=position:absolute;will-change:transform;top:0;left:0;transform:translate3d(23px,26px,0) tabindex=0 x-placement=bottom-end><a class='dropdown-item'href=" + path_edit + "> <svg aria-hidden=true class='mr-2 svg-inline--fa fa-edit fa-w-18'data-fa-i2svg=''data-icon=edit data-prefix=fa focusable=false role=img viewBox='0 0 576 512'xmlns=http://www.w3.org/2000/svg><path d='M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z'fill=currentColor></path></svg> Edit </a>" +
                            "<a id='delete' class='dropdown-item delete-table-row' href='javascript:void(0)' data-duration=single data-leave-id=3124 data-unique-id=GZ668RQZU8QPFbWG id=delete onclick=\"deleteRecord('" + path_del + "','" + title + "','" + text + "','" + token + "','" + type + "'," + o['id'] + ")\"><svg class='svg-inline--fa fa-trash fa-w-14 mr-2' aria-hidden='true' focusable='false' data-prefix='fa' data-icon='trash role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' data-fa-i2svg=''><path fill='currentColor' d='M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z'></path></svg>Delete</a>  " +
                            "</div></div>";

                        return extra_html;
                    }
                },
            ],
        });
        $('#save_file').click(function() {
            var data = $('#file').prop('files')[0];
            var token = "{{csrf_token()}}";
            var form_data = new FormData();
            form_data.append('excel', data);
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $.ajax({
                url: "<?= URL::route('aptitude.import.store') ?>",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == true) {

                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {

                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        })

                    }
                },
            });
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
@endpush