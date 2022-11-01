<div class="card-body">
    <div class="col-12">
        <div class="row">

            <div class="col-12 col-md-6">
                <label for="date">Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror"
                    name="date"
                    value="{{ \Carbon\Carbon::parse($visit_plan->date_time)->format('Y-m-d') }}">
            </div>
            <div class="col-12 col-md-6">
                <label for="time">Time</label>
                <input type="time" class="form-control @error('time') is-invalid @enderror"
                    name="time"
                    value="{{ \Carbon\Carbon::parse($visit_plan->date_time)->format('H:i') }}">
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-6">
                <label for="payment_collection_date">Payment Collection Date</label>
                <input type="date"
                    class="form-control @error('payment_collection_date') is-invalid @enderror "
                    name="payment_collection_date"
                    value="{{ old('payment_collection_date') ?? $visit_plan->payment_collection_date }}">
            </div>
            <div class="col-12 col-md-6">
                <label for="payment_maturity_date">Payment Maturity Date</label>
                <input type="date"
                    class="form-control @error('payment_maturity_date') is-invalid @enderror "
                    name="payment_maturity_date"
                    value="{{ old('payment_maturity_date') ?? $visit_plan->payment_maturity_date }}">
            </div>

        </div>
    </div>
    <div class="col-12">
        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
            class="form-control @error('purpose_of_visit') is-invalid @enderror"> {{ $visit_plan->purpose_of_visit }}</textarea>
    </div>
    <div class="col-12">
        <label for="achievement" class="form-label">Achievement</label>
        <textarea name="achievement" id="achievement" cols="30" rows="2"
            class="form-control @error('achievement') is-invalid @enderror"></textarea>
    </div>
</div>
