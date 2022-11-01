<label for="head_office">Office Locations</label>
<select name="head_office" id="head_office" class="form-control @error('head_office') is-invalid @enderror">
    <option value="">Select Office Location</option>
    @foreach ($office_locations as $office_location)
        <option value="{{ $office_location->id }}">{{ $office_location->title }} ({{ $office_location->company->name }})
        </option>
    @endforeach
</select>

@error('head_office')
    <span class="text-danger">{{ $message }}</span>
@enderror
