@props([
    'detail' =>[]
])

@php
    use App\Lib\MyHelper;
    use Carbon\Carbon;
@endphp
<div>
    
    <div class="form-group">
        <div class="input-icon right">
            <label class="col-md-4 control-label">
                Day
            </label>
        </div>
        <div class="col-md-8">
            <label class="control-label">{{ $detail['day'] }}</label>
        </div>
    </div>
    <div class="form-group">
        <div class="input-icon right">
            <label class="col-md-4 control-label">
                Name
            </label>
        </div>
        <div class="col-md-8">
            <label class="control-label">{{ $detail['name'] }}</label>
        </div>
    </div>
    <div class="form-group">
        <div class="input-icon right">
            <label class="col-md-4 control-label">
                Shift Time
            </label>
        </div>
        <div class="col-md-8">
            <label class="control-label">{{ Carbon::parse($detail['start'])->format('H:i') .' - '. Carbon::parse($detail['end'])->format('H:i')}}</label>
        </div>
    </div>
    <div class="form-group">
        <div class="input-icon right">
            <label class="col-md-4 control-label">
                Price
            </label>
        </div>
        <div class="col-md-8">
            <label class="control-label">{{ MyHelper::rupiah($detail['price']) }}</label>
        </div>
    </div>
</div>