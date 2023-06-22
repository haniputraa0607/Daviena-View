@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
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
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>
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
    })
</script>
@endsection

@section('content')
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Order</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Delivery Settings</span>
            <i class="fa fa-circle"></i>
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
            <span class="caption-subject font-green bold uppercase">Available Delivery</span>
        </div>
    </div>
    <div class="portlet-body">
        <form class="form-horizontal" action="{{ url('transaction/setting/available-delivery') }}" method="post" id="form">
            {{ csrf_field() }}
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">Default <span class="required" aria-required="true"> * </span>
                        <i class="fa fa-question-circle tooltips" data-html="true" data-original-title="- By price: default yang terpilih adalah yang memiliki biaya termurah
                            <br>- Selected: delivery yang terpilih akan berdasarkan susunan dari urutan" data-container="body"></i>
                    </label>
                    <div class="col-md-4">
                        <select  class="form-control select2" name="default_delivery" data-placeholder="Select Default">
                            <option></option>
                            <option value="price" @if($default_delivery == 'price') selected @endif>By Price</option>
                            <option value="selected" @if($default_delivery == 'selected') selected @endif>Selected</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-info">Drag [<i class="fa fa-ellipsis-h" style="transform: rotate(90deg);"></i>] handle button to reorder delivery</div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Delivery Code</th>
                        <th>Delivery Method</th>
                        <th>Delivery Name</th>
                        <th>Description</th>
                        <th>Show Status</th>
                        <th>Available Status</th>
                    </tr>
                    </thead>
                    <tbody class="sortable">
                    @foreach($delivery as $key => $val)
                        <tr>
                            <td class="sortable-handle"><i class="fa fa-ellipsis-h" style="transform: rotate(90deg);"></i></td>
                            <td>{{$val['code']}}</td>
                            <td>{{ucfirst($val['delivery_method'])}}</td>
                            <td>
                            <textarea name="delivery[{{$val['code']}}][delivery_name]" class="form-control">{{$val['delivery_name']}}</textarea>
                            <td>
                                <textarea name="delivery[{{$val['code']}}][description]" class="form-control">{{$val['description']}}</textarea>
                            </td>
                            <td>
                                <input type="hidden" name="delivery[{{$val['code']}}][show_status]" value="0">
                                <input type="checkbox" name="delivery[{{$val['code']}}][show_status]" class="make-switch" data-size="small" data-on-color="info" data-on-text="Show" data-off-color="default" data-off-text="Hide" value="1" @if($val['show_status'] == 1) checked @endif>
                            </td>
                            <td>
                                <input type="checkbox" name="delivery[{{$val['code']}}][available_status]" class="make-switch brand_visibility" data-size="small" data-on-color="info" data-on-text="Enable" data-off-color="default" data-off-text="Disable" value="1" @if($val['available_status'] == 1) checked @endif>
                            </td>
                            <input type="hidden" name="delivery[{{$val['code']}}][dummy]">
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="form-actions text-center">
                <button onclick="unsaved=false" class="btn green">
                    <i class="fa fa-check"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
