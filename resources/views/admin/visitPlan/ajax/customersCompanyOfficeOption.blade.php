<option value="">Select Customer</option>
@foreach ($customer_offices as $office)
<option value="{{ $office->id }}">{{ $office->title }}
</option>
@endforeach
