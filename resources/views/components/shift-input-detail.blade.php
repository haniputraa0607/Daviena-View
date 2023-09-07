<div class="form-group">
    <div class="col-md-12">
        <div class="col-md-3">
            <label class="control-label">Name<span class="required" aria-required="true">*</span>
                <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
            </label>
        </div>
        <div class="col-md-9">
            <div class="col-md-10">
                @isset($detail['name'])
                    <input type="text" class="form-control" name="name[]" placeholder="Shift Name is Required"
                        value="{{ $detail['name'] }}" required>
                @else
                    <input type="text" class="form-control" name="name[]" placeholder="Shift Name is Required"
                        value="{{ old('name') }}" required>
                @endisset
               
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-12">
        <div class="col-md-3">
            <label class="control-label">Start Time<span class="required" aria-required="true">*</span>
                <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
            </label>
        </div>
        <div class="col-md-9">
            <div class="col-md-10">
                @isset($detail['start'])
                    <input type="time" class="form-control" name="start[]" placeholder="Start (Required)"
                        value="{{ $detail['start'] }}" required>
                @else
                    <input type="time" class="form-control" name="start[]" placeholder="Start (Required)"
                        value="{{ old('start') }}" required>
                @endisset
                
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-12">
        <div class="col-md-3">
            <label class="control-label">End Time<span class="required" aria-required="true">*</span>
                <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
            </label>
        </div>
        <div class="col-md-9">
            <div class="col-md-10">
                @isset($detail['end'])
                    <input type="time" class="form-control" name="end[]" placeholder="End (Required)"
                        value="{{ $detail['end'] }}" required>
                @else
                    <input type="time" class="form-control" name="end[]" placeholder="End (Required)"
                        value="{{ old('end') }}" required>
                @endisset
               
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-12">
        <div class="col-md-3">
            <label class="control-label">Price<span class="required" aria-required="true">*</span>
                <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
            </label>
        </div>
        <div class="col-md-9">
            <div class="col-md-10">
                @isset($detail['price'])
                <input type="text" class="form-control" name="price" id="price[]"placeholder="Price (Required)"
                    value="{{ $detail['price'] }}" required>
            @else
                <input type="text" class="form-control" name="price" id="price[]" placeholder="Price (Required)"
                    value="{{ old('price') }}" required>
            @endisset
            </div>
        </div>
    </div>
</div>