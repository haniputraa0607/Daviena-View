@extends('layouts.main')

@section('page-style')
<link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .sortable-handle {
        cursor: move;
    }
    tbody tr {
        background: white;
    }
</style>
@endsection

@section('page-script')
<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
<script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<script>
    var unsaved = false;
    function recolor() {
        const children = $('tbody').children();
        for (let i = 0; i < children.length; i++) {
            const child = children[i];
            if ($(child).find('[type="checkbox"]').prop('checked')) {
                $(child).removeClass('bg-grey');
            } else {
                $(child).addClass('bg-grey');
            }
        }
    }
    $(document).ready(function() {
        $(window).bind('beforeunload', function() {
            if(unsaved){
                return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
            }
        });

        // Monitor dynamic inputs

        $('.sortable').on('switchChange.bootstrapSwitch', ':input', function(){
            unsaved = true;
            recolor();
        });

        $('.sortable').sortable({
            handle: ".sortable-handle",
        });
        $( ".sortable" ).on( "sortchange", function() {unsaved=true;} );
        recolor();

        $('#modalLogo').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var url = button.data('image') // Extract info from data-* attributes
            var id = button.data('id') // Extract info from data-* attributes
            var modal = $(this)
            if (url != "") {
                modal.find('.previewImage').attr("src", url)
            } else {
                modal.find('.previewImage').attr("src", "https://via.placeholder.com/500x500?text=No+Image")
            }
            modal.find('.id_logo').val(id)
        })

        $(".file").change(function(e) {
                var _URL = window.URL || window.webkitURL;
                var image, file;

                btnRemove = $(this).parents().siblings('.btremove');

                if ((file = this.files[0])) {
                    image = new Image();

                    image.onload = function() {
                        if (this.width != this.height) {
                            toastr.warning("Please check dimension of your image.");
                            btnRemove.trigger( "click" );
                        }
                    };
                    image.src = _URL.createObjectURL(file);
                }
            });
    })
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
                <i class=" icon-layers font-green"></i>
                <span class="caption-subject font-green bold uppercase">Setting Payment Method</span>
            </div>
        </div>
        <div class="portlet-body">
            <form class="form-horizontal" action="{{ url('transaction/setting/available-payment') }}" method="post" id="form">
                {{ csrf_field() }}
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Payment Name</th>
                            <th>Payment Gateway</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="sortable">
                        @foreach($payments as $key => $payment)
                        <tr>
                            <td class="sortable-handle"><i class="fa fa-ellipsis-h" style="transform: rotate(90deg);"></i></td>
                            <td><a href="#modalLogo" class="btn btn-info btn_add_type" data-toggle="modal" data-id="{{ $payment['id'] }}" data-image="{{ empty($payment['logo']) ? "" : env('STORAGE_URL_API').$payment['logo']}}"><i class="fa fa-file-image-o"></i></a></td>
                            <td>{{$payment['payment_name']}}</td>
                            <td>{{$payment['payment_gateway']}}</td>
                            <td>{{$payment['payment_method']}}</td>
                            <td>
                                <input type="checkbox" name="status[{{$payment['id']}}][status]" class="make-switch brand_visibility" data-size="small" data-on-color="info" data-on-text="Enable" data-off-color="default" data-off-text="Disable" value="1" {{$payment['status']?'checked':''}}>
                            </td>
                            <input type="hidden" name="ids[]" value="{{$payment['id']}}">
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-actions text-center">
                    <button onclick="unsaved=false" class="btn green">
                        <i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalLogo" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body form">
                    <div class="form-body">
                    <form role="form" id="update-featured-songwriter" action="{{ url('transaction/setting/available-payment/update/logo') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row" style="padding: 10px">
                            <div class="col-md-4">
                                <div class="input-icon right">
                                    <label class="control-label">Image<span class="required" aria-required="true">*</span>
                                    <br>
                                    <span class="required" aria-required="true">(square image)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100%;">
                                        <img class='previewImage' src="https://via.placeholder.com/500x500?text=No+Image" alt="">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" id="image_landscape"  style="width: 100%;"></div>
                                    <div class='btnImage'>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <span class="fileinput-exists"> Change </span>
                                            <input type="hidden" name="id" class="id_logo" value="">
                                            <input type="file" id="modalNew" accept="image/*" value="" class="file form-control demo featureImageForm" name="file" data-jenis="landscape" required>
                                        </span>
                                        <a href="javascript:;" id="removeImagemodalNew" class="btn red btremove" data-dismiss="fileinput" style="display: none"> Remove </a>
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
@endsection
