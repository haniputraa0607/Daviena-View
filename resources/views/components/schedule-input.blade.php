@props([
    'detail' => [],
])

<div>
    <div class="form-group" id="province-selection">
        <div class="col-md-12">
            <div class="col-md-3">
                <label class="control-label">User<span class="required"
                        aria-required="true">*</span>
                    <i class="fa fa-question-circle tooltips" data-original-title="User"
                        data-container="body"></i>
                </label>
            </div>
            <div class="col-md-9">
                <div class="col-md-10">
                    <select name="user_id" id="user-input" class="form-control select2-input"
                        required data-type="users"></select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group" id="outlet-selection">
        <div class="col-md-12">
            <div class="col-md-3">
                <label class="control-label">Outlet<span class="required"
                        aria-required="true">*</span>
                    <i class="fa fa-question-circle tooltips" data-original-title="Outlet"
                        data-container="body"></i>
                </label>
            </div>
            <div class="col-md-9">
                <div class="col-md-10">
                    <select name="outlet_id" id="outlet-input" class="form-control select2-input"
                        required data-type="outlets"></select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-3">
                <label class="control-label">Schedule Month<span class="required" aria-required="true">*</span>
                    <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
                </label>
            </div>
            <div class="col-md-9">
                <div class="col-md-10">
                    <select name="schedule_month" id="schedule-month-input" class="form-control select2-input" required data-type="outlets">
                        <option value="1" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '1' ? 'selected' : '' }} @endisset>January</option>
                        <option value="2" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '2' ? 'selected' : '' }} @endisset>February</option>
                        <option value="3" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '3' ? 'selected' : '' }} @endisset>March</option>
                        <option value="4" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '4' ? 'selected' : '' }} @endisset>April</option>
                        <option value="5" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '5' ? 'selected' : '' }} @endisset>May</option>
                        <option value="6" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '6' ? 'selected' : '' }} @endisset>June</option>
                        <option value="7" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '7' ? 'selected' : '' }} @endisset>July</option>
                        <option value="8" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '8' ? 'selected' : '' }} @endisset>August</option>
                        <option value="9" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '9' ? 'selected' : '' }} @endisset>September</option>
                        <option value="10" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '10' ? 'selected' : '' }} @endisset>October</option>
                        <option value="11" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '11' ? 'selected' : '' }} @endisset>November</option>
                        <option value="12" @isset($detail['schedule_month']){{ $detail['schedule_month'] == '12' ? 'selected' : '' }} @endisset>December</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-3">
                <label class="control-label">Schedule Year<span class="required" aria-required="true">*</span>
                    <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
                </label>
            </div>
            <div class="col-md-9">
                <div class="col-md-10">
                    @isset($detail['schedule_year'])
                        <input type="text" class="form-control" name="schedule_year" placeholder="Schedule Year (Required)"
                            value="{{ $detail['schedule_year'] }}" required>
                    @else
                        <input type="text" class="form-control" name="schedule_year" placeholder="Schedule Year (Required)"
                            value="{{ old('schedule_year') ?? date('Y') }}" required>
                    @endisset
                   
                </div>
            </div>
        </div>
    </div>
    
    

    
</div>
