@extends('admin.layouts.adminMaster')
@push('title')
    Edit Products
@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('back/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Edit Product
            </div>
        </div>
        <div class="card-body">
            @include('alerts.alerts')
            <form action="{{ route('admin.product.update',['product'=>$product,'service_type'=>$service_type]) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12 col-md-6 form-group">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Product Name" value="{{old('name') ?? $product->name}}">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="capacity">Capacity</label>
                        <input type="text" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{old('capacity')  ?? $product->capacity}}">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="Brand">Brand</label>
                        <select name="brand" id="Brand"
                            class="form-control select2 @error('brand') is-invalid @enderror">
                            <option value="">Select Brand </option>
                            @foreach ($brands as $brand)
                                <option {{ $product->brand_id == $brand->id ? 'selected' : ''}} value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="model">Model</label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{old('model') ?? $product->model}}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="backup_time"> Backup time</label>
                        <input type="text" name="backup_time"
                            class="form-control @error('backup_time') is-invalid @enderror" value="{{old('backup_time') ?? $product->backup_time}}">
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="type">Type</label>
                        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{old('type') ?? $product->type}}">
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="unit_price">Unit Price</label>
                        <input type="number" name="unit_price"
                            class="form-control @error('unit_price') is-invalid @enderror" value="{{old('unit_price') ?? $product->unit_price}}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="warranty">Warranty</label>
                        <input type="text" name="warranty"
                            class="form-control @error('warranty') is-invalid @enderror" value="{{old('warranty') ?? $product->warranty}}">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="Origin">Country of Origin</label>
                        <select name="origin" id="Origin"
                            class="form-control select2 @error('origin') is-invalid @enderror">
                            <option value="">Select Origin </option>
                            @foreach ($countries as $country)
                                <option {{$product->origin == $country->nicename ? 'selected' : ''}} value="{{ $country->nicename }}">
                                    {{ ucfirst(strtolower($country->name)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="Made_in">Made In</label>
                        <select name="made_in" id="Made_in"
                            class="form-control select2 @error('made_in') is-invalid @enderror">
                            <option value="">Select Made_in </option>
                            @foreach ($countries as $country)
                                <option {{$product->made_in == $country->nicename ? 'selected' : ''}} value="{{ $country->nicename }}">
                                    {{ ucfirst(strtolower($country->name)) }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-12">
                        <label for="short_description">Short Description</label>
                        <textarea name="short_description" id="short_description" cols="30" rows="3" class="form-control">{{old('short_description') ?? $product->short_description}}</textarea>
                    </div>


                    {{-- <div class="col-12 col-md-12 form-group">
                        <label for="terms_and_condition">Terms And Condition</label>
                        <textarea name="terms_and_condition" class="form-control" id="terms_and_condition" cols="30" rows="3">@if ($product->terms_and_condition)
                            {!! $product->terms_and_condition !!}
                            @else
                            <div>1. Payment&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : 100% Cash/cheque within 15 days in favor of Orient Computers.</div><div>2. Delivery&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Within 15 days from date of work order.</div><div>3. Offer Validity&nbsp; &nbsp; &nbsp; &nbsp; : 15 days</div><div>4. VAT &amp; TAX&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : Included</div><div>5. Site Preparation&nbsp; &nbsp; : Any civil work for site preparation is excluding of this proposal.</div><div>6. Accessories&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: AC Cables, Breakers, Earthing, Grounding are out of this proposal.</div><div>7. Warranty Void&nbsp; &nbsp; &nbsp; &nbsp;: Over Charged/Discharged, Burnt, Terminal Soldering, Physical Damage/Lost/Theft etc.</div>
                        @endif

                        </textarea>
                    </div> --}}
                    <div class="col-12 col-md-6 form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control select2">
                            @foreach ($categories as $category)
                            <option {{$product->category_id == $category->id ? 'selected' : ''}} value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="stock">Stock</label>
                        <input type="number" value="{{$product->stock}}" name="stock" value="stock" class="form-control">
                    </div>

                    <div class="col-12 col-md-12 form-group">
                        <input type="submit" value="Update" class="btn btn-info">
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ asset('back/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#terms_and_condition').summernote();
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endpush
