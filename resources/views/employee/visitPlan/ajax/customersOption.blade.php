<option value="">Select Customer</option>
@foreach ($customers as $customer)
<option value="{{ $customer->id }}">{{ $customer->customer_name }}
    ({{ $customer->customer_code }})
</option>
@endforeach
