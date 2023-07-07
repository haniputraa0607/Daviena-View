@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('#modalUpdateCustomPrice').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var id = button.data('id') // Extract info from data-* attributes
                var name = button.data('name') // Extract info from data-* attributes
                var price = button.data('price') // Extract info from data-* attributes
                var modal = $(this)

                //clear modal first
                modal.find('#update-custom-price').attr("action", null)
                modal.find('#custom_price_update_id').val(null)
                modal.find('#custom_price_update_name').val(null)
                modal.find('#custom_price_update_price').val(null)

                var url = "{{url('transaction/setting/price/custom-price/update')}}/"+id

                modal.find('#update-custom-price').attr("action", url)
                modal.find('#custom_price_update_id').val(id)
                modal.find('#custom_price_update_name').val(name)
                modal.find('#custom_price_update_price').val(price)
            })
        });
    </script>
    <script type="text/javascript">
        var SweetAlert = function() {
                return {
                    init: function() {
                        $(".sweetalert-delete").each(function() {
                            var token  	= "{{ csrf_token() }}";
                            let id     	= $(this).data('id');
                            let name    = $(this).data('name');
                            $(this).click(function() {
                                swal({
                                        title: "Are you sure want to delete "+name+"?",
                                        text: "Your will not be able to recover this data!",
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonClass: "btn-danger",
                                        confirmButtonText: "Yes, delete it!",
                                        closeOnConfirm: false
                                    },
                                    function(){
                                        $.ajax({
                                            type : "GET",
                                            url : "{{ url('transaction/setting/price/custom-price/delete') }}"+'/'+id,
                                            success : function(result) {
                                                if (result.status == "success") {
                                                    swal({
                                                        title: 'Deleted!',
                                                        text: 'Custom Price has been deleted.',
                                                        type: 'success',
                                                        showCancelButton: false,
                                                        showConfirmButton: false
                                                    })
                                                    SweetAlert.init()
                                                    window.location.reload(true);
                                                } else if(result.status == "fail"){
                                                    swal("Error!", result.messages[0], "error")
                                                } else {
                                                    swal("Error!", "Something went wrong. Failed to delete Custom Price.", "error")
                                                }
                                            }
                                        });
                                    });
                            })
                        })
                    }
                }
            }();

        jQuery(document).ready(function() {
            SweetAlert.init()
        });
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
                <span class="caption-subject font-blue sbold uppercase">Price Setting</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    @if (in_array(32, session('granted_features')) || session('user_role') == 'super_admin')
                        <li class="active">
                            <a data-toggle="tab" href="#global_price">Global Price</a>
                        </li>
                    @endif
                    @if (in_array(34, session('granted_features')) || session('user_role') == 'super_admin')
                        <li class="{{ !in_array(32, session('granted_features')) && session('user_role') != 'super_admin' ? "active" : ""}}">
                            <a data-toggle="tab" href="#mdr_fee">MDR Fee</a>
                        </li>
                    @endif
                    @if (in_array(37, session('granted_features')) || session('user_role') == 'super_admin')
                        <li class="{{ !in_array(32, session('granted_features')) && !in_array(34, session('granted_features')) && session('user_role') != 'super_admin' ? "active" : ""}}">
                            <a data-toggle="tab" href="#custom_price">Custom Price</a>
                        </li>
                    @endif
                </ul>
            </div>
            <br>
            <div class="tab-content">
                @if (in_array(32, session('granted_features')) || session('user_role') == 'super_admin')
                    <div id="global_price" class="tab-pane active">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject font-yellow sbold uppercase">Global Price</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <form class="form-horizontal" role="form" action="{{url('transaction/setting/price/global')}}" method="post">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Price<span class="required" aria-required="true"> * </span>
                                                <i class="fa fa-question-circle tooltips" data-original-title="Harga default pengisian daya (per menit) dalam rupiah" data-container="body"></i></label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <input type="number" step="100" min="100" class="form-control" name="price" required value="{{ $global_price['price'] }}"><span class="input-group-addon">/minutes</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Tax<span class="required" aria-required="true"> * </span>
                                                <i class="fa fa-question-circle tooltips" data-original-title="Pajak dalam persen" data-container="body"></i></label></label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <input type="number" step=".01" min="0" max="100" class="form-control" name="tax" required value="{{ $global_price['tax'] }}"><span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if (in_array(33, session('granted_features')) || session('user_role') == 'super_admin')
                                        <div class="form-actions" style="text-align: center">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                                        </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                @if (in_array(34, session('granted_features')) || session('user_role') == 'super_admin')
                    <div id="mdr_fee" class="tab-pane {{ !in_array(32, session('granted_features')) && session('user_role') != 'super_admin' ? "active" : ""}}">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject font-yellow sbold uppercase">MDR Fee</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <form class="form-horizontal" role="form" action="{{url('transaction/setting/price/mdr-fee')}}" method="post">
                                    <div class="form-body">
                                        @foreach ($mdr_fee as $mdr)
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">{{ $mdr['name'] }}<span class="required" aria-required="true">*</span>
                                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Payment" data-container="body"></i>
                                                </label>
                                                <div class="col-md-6">
                                                    <div class="input-icon right">
                                                        <input type="text" placeholder="Formula" class="form-control" name="formula[{{ $mdr['key'] }}]" value="{{ $mdr['formula'] }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if (in_array(35, session('granted_features')) || session('user_role') == 'super_admin')
                                        <div class="form-actions" style="text-align: center">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                                        </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                @if (in_array(37, session('granted_features')) || session('user_role') == 'super_admin')
                    <div id="custom_price" class="tab-pane {{ !in_array(32, session('granted_features')) && !in_array(34, session('granted_features')) && session('user_role') != 'super_admin' ? "active" : ""}}">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption col-md-2">
                                    <span class="caption-subject font-yellow sbold uppercase">Custom Price</span>
                                </div>
                                @if (in_array(36, session('granted_features')) || session('user_role') == 'super_admin')
                                <div class="col-md-10" style="text-align: right;">
                                    <a href="#modalNewCustomPrice" class="btn btn-success btn_add_custom_price" data-toggle="modal" style="margin-bottom: 15px;">
                                        <i class="fa fa-plus"></i> Add New Custom Price
                                    </a>
                                </div>
                                @endif
                            </div>
                            <div class="portlet-body form">
                                <form class="form-horizontal" role="form" method="post">
                                    <div class="form-body">
                                        @if (!empty($custom_price))
                                            @foreach ($custom_price as $price)
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">{{ $price['name'] }}
                                                        <i class="fa fa-question-circle tooltips" data-original-title="Custom Price Group" data-container="body"></i>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <input type="text" placeholder="Price" class="form-control" value="{{ $price['price'] }}" readonly><span class="input-group-addon">/minutes</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        @if (in_array(38, session('granted_features')) || session('user_role') == 'super_admin')
                                                        <a href="#modalUpdateCustomPrice" class="btn btn-sm blue btn-primary" data-toggle="modal" data-id="{{ $price['id'] }}" data-name="{{ $price['name'] }}" data-price="{{ $price['price'] }}"><i class="fa fa-edit"></i></a>
                                                        @endif
                                                        @if (in_array(39, session('granted_features')) || session('user_role') == 'super_admin')
                                                        <a class="btn btn-sm red sweetalert-delete btn-primary" data-id="{{ $price['id'] }}" data-name="{{ $price['name'] }}"><i class="fa fa-trash-o"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="form-group">
                                                <label class="col-md-12 control-label" style="text-align: center;">No Custom Price Data.</label>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNewCustomPrice" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">New Custom Price</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" id="new-custom-price" action="{{url('transaction/setting/price/custom-price/add')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Custom Price" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="text" id="name_new" placeholder="Custom Price Name" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Price<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Harga per menit" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="number" id="price_new" placeholder="Price" class="form-control" name="price" step="100" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <center>
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </center>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateCustomPrice" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update Custom Price</h4>
                </div>
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" id="update-custom-price" action="" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Nama Custom Price" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="hidden" name="id" id="custom_price_update_id" value="">
                                        <input type="text" id="custom_price_update_name" placeholder="Custom Price Name" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Price<span class="required" aria-required="true">*</span>
                                    <i class="fa fa-question-circle tooltips" data-original-title="Harga per menit" data-container="body"></i>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-icon right">
                                        <input type="number" id="custom_price_update_price" placeholder="Price" class="form-control" name="price" step="100" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding: 10px">
                            <center>
                                <button type="submit" class="btn yellow"><i class="fa fa-save"></i> Update</button>
                            </center>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
