@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table=$('#table_data').DataTable({
				bProcessing: true,
				bServerSide: true,
				ajax: {
                    url: "{{ url('api/be/banner_clinic') }}",
                    headers: {
                            "Authorization": "{{ session('access_token') }}"
                        },
				},
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'image', name: 'image' },
                    { data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    aria: {
                        sortAscending: ": activate to sort column ascending",
                        sortDescending: ": activate to sort column descending"
                    },
                    emptyTable: "No data available in table",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries found",
                    infoFiltered: "(filtered1 from _MAX_ total entries)",
                    lengthMenu: "_MENU_ entries",
                    search: "Search:",
                    zeroRecords: "No matching records found"
                },
                responsive: {
                    details: {
                        type: "column",
                        target: "tr"
                    }
                },
                order: [],
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                pageLength: 10
            });
        });

        $('body').on('click', '#btn-delete', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let token = $("meta[name='csrf-token']").attr("content");

            swal({
                    title: "Are you sure want to delete " + name + "?",
                    text: "Your will not be able to recover this data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function() {
                    $.ajax({
                        type: "DELETE",
                        url: `/landing_page/banner_clinic/delete/${id}`,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                swal({
                                    title: 'Deleted!',
                                    text: 'product has been deleted.',
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false
                                })
                                window.location.reload(true);
                            } else if (response.status == "fail") {
                                swal("Error!", response.messages[0], "error")
                            } else {
                                swal("Error!",
                                    "Something went wrong. Failed to delete vehicle brand.",
                                    "error")
                            }
                        }
                    });
                });
        });
    </script>
    <script>
        const main = {
            add: function(){
                this.null();
                this.show();
                $('#modal-form').attr('action', `{{ url('landing_page/banner_clinic/store') }}`);
            },
            null: function(){
                $('#id').val(0);
                $('#blah_image').attr('src', '{{ asset("images/logo.svg") }}');
            },
            show: function(){
                $('#modal-main').modal('show');
            },
            submit: function(){
                $('#modal-form').submit();
            },
            edit: function(that){
                this.null();
                let data = $(that).data('data');
                $('#blah_image').attr('src', `{{ env('API_URL') }}${data.image}`);
                $('#modal-form').attr('action', `{{ url('landing_page/banner_clinic/update') }}/${data.id}`);
                this.show();
            }
        }
        
        function readURL(input, level) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var fileimport = $('#' + input.id).val();
                var allowedExtensions = /(\.png)$/i;
                if (!allowedExtensions.exec(fileimport)) {
                alert('Gambar harus bertipe gambar');
                $('#' + input.id).val('');
                return false;
                }
                reader.onload = function(e) {
                $('#blah_' + level).attr('src', e.target.result).width(200);
                // .height();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function imgError(data) {
            console.log('error_img');
            data.setAttribute('src', '{{ asset("images/logo.svg") }}');
        }

    </script>
@endsection

@section('content')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>{{ $title }}</span>
                @if (!empty($sub_title))
                    <i class="fa fa-circle"></i>
                @endif
            </li>
            @if (!empty($sub_title))
            <li>
                <span>{{ $sub_title }}</span>
            </li>
            @endif
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue sbold uppercase">CMS Banner Clinic List</span>
            </div>
        </div>
        <div class="portlet-body">
            <a onclick="main.add()" class="btn btn-success btn_add_user" style="margin-bottom: 15px;">
                <i class="fa fa-plus"></i> Add Banner Clinic
            </a>
            <table class="table trace trace-as-text table-striped table-bordered table-hover dt-responsive" id="table_data">
                <thead class="trace-head">
                    <tr>
                        <th>No</th>				
                        <th>Name</th>				
                        <th style="width: 90px;"></th>				
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    
    <div class="modal fade" id="modal-main" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Banner Clinic Form</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" id="modal-form" action="" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <small class="text-danger"><i>Click the image to change new image</i></small>
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success text-center col-sm-12">
                                    <img id="blah_image" src="{{ @$detail['image'] ? $detail['image'] : asset('images/logo.svg') }}" style="width:200px;" onerror="imgError(this)" alt="..." loading="lazy" onclick="$('#image').click();">
                                </div>
                                <input class="form-control" name="image" style="display:none;" id="image" type="file" onchange="readURL(this, 'image');">
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <center>
                                <button type="button" class="btn green" onclick="main.submit()"><i class="fa fa-check"></i> Submit</button>
                            </center>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
