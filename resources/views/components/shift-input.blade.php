@props([
    'detail' => [],
])

<div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-3">
                <label class="control-label">Day<span class="required" aria-required="true">*</span>
                    <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
                </label>
            </div>
            <div class="col-md-9">
                <div class="col-md-10">
                    <select name="day" id="day" class="form-control select2-input" required data-type="day">
                        <option value="Sunday" @isset($detail['day']) {{ $detail['day'] == 'Sunday' ? 'selected' : '' }} @endisset>Sunday</option>
                        <option value="Monday" @isset($detail['day']) {{ $detail['day'] == 'Monday' ? 'selected' : '' }} @endisset>Monday</option>
                        <!-- Add options for other days here -->
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <div class="col-md-3">
                <label class="control-label">Number Of Shift<span class="required" aria-required="true">*</span>
                    <i class="fa fa-question-circle tooltips" data-original-title="Nama" data-container="body"></i>
                </label>
            </div>
            <div class="col-md-9">
                <div class="col-md-10">
                    <input type="number" class="form-control" name="number_of_shift" id="number_of_shift" placeholder="Shift Name is Required"
                        value="{{ old('number_of_shift') }}" onkeyup="addDetail()" oninput="addDetail()">
                </div>
            </div>
        </div>
    </div>
    
    <div id="detail-input">
        <!-- Include your Blade component here if needed -->
        {{-- <x-shift-input-detail/> --}}
    </div>
</div>

   <script>
        function addDetail() {
            removeDetail()

            var number_of_shift = document.getElementById("number_of_shift").value
            for (let index = 0; index < number_of_shift; index++) {
                
                document.getElementById("detail-input")
                .innerHTML += `<div class="detail-added" id="detail-${index}">  <hr> <x-shift-input-detail/> </div>`;
            }
        }
        function removeDetail() {
            var number_of_shift = document.getElementById("number_of_shift").value
            console.log(number_of_shift);
            var detailInput = document.getElementById("detail-input");
            while (detailInput.firstChild) {
                detailInput.removeChild(detailInput.firstChild);
        }
    }
</script>