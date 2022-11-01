@extends('admin.layouts.adminMaster')
@push('title')
    Products Creation
@endpush
@push('css')
    <style>
        select#backUpType {
            background: none;
            border: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('back/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Products Creation
            </div>
        </div>
        <div class="card-body bg-secondary">
            @include('alerts.alerts')
            {{-- <div class="row pb-2">
                <div class="col-12 col-md-3">
                    <fieldset>
                        <legend>Bulk Upload <a href="{{ asset('img/customer.png') }}" class="badge badge-danger" title="Follow The instruction "><i class="fas fa-info"></i></a>
                        </legend>
                        <form action="{{ route('admin.product.store') }}" enctype="multipart/form-data" method="post">
                            @csrf
                            <input type="hidden" name="bulk_upload" value="bulk_upload">
                            <div class="form-group">
                                <input type="file" name="file">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Upload" class="btn btn-info">
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div> --}}
            <form action="{{ route('admin.product.store',['service_type'=>$service_type]) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6 form-group">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Product Name" value="{{old('name')}}">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="capacity">Capacity</label>
                        <input type="text" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{old('capacity')}}">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="Brand">Brand</label>
                        <select name="brand" id="Brand"
                            class="form-control select2 @error('brand') is-invalid @enderror">
                            <option value="">Select Brand </option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="model">Model</label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{old('model')}}">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="backup_time"> Backup time</label>
                        <input type="text" name="backup_time"
                            class="form-control @error('backup_time') is-invalid @enderror" value="{{old('backup_time')}}">
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="type">Type</label>
                        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{old('type')}}">
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="warranty">Warranty</label>
                        <input type="number" name="warranty"
                            class="form-control @error('warranty') is-invalid @enderror" value="{{old('warranty')}}">
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="unit_price">Unit Price</label>
                        <input type="number" name="unit_price"
                            class="form-control @error('unit_price') is-invalid @enderror" value="{{old('unit_price')}}">
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="Origin">Country of Origin</label>
                        <select name="origin" id="Origin"
                            class="form-control select2 @error('origin') is-invalid @enderror">
                            <option value="">Select Origin </option>
                            @foreach ($countries as $country)
                                <option value="{{ ucfirst(strtolower($country->name)) }}">
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
                                <option value="{{ ucfirst(strtolower($country->name)) }}">
                                    {{ ucfirst(strtolower($country->name)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="short_description">Short Description</label>
                        <textarea name="short_description" id="short_description" cols="30" rows="3" class="form-control">{{old('short_description')}}</textarea>
                    </div>

                    {{-- <div class="col-12 col-md-12 form-group">
                        <label for="terms_and_condition">Terms And Condition</label>
                        <textarea name="terms_and_condition" class="form-control" id="terms_and_condition" cols="30" rows="3">
                            <div>1. Payment&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : 100% Cash/cheque within 15 days in favor of Orient Computers.</div><div>2. Delivery&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Within 15 days from date of work order.</div><div>3. Offer Validity&nbsp; &nbsp; &nbsp; &nbsp; : 15 days</div><div>4. VAT &amp; TAX&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : Included</div><div>5. Site Preparation&nbsp; &nbsp; : Any civil work for site preparation is excluding of this proposal.</div><div>6. Accessories&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: AC Cables, Breakers, Earthing, Grounding are out of this proposal.</div><div>7. Warranty Void&nbsp; &nbsp; &nbsp; &nbsp;: Over Charged/Discharged, Burnt, Terminal Soldering, Physical Damage/Lost/Theft etc.</div>



                        </textarea>
                    </div> --}}

                    <div class="col-12 col-md-6 form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" value="stock" class="form-control">
                    </div>

                    <div class="col-12 col-md-12 form-group">
                        <input type="submit" class="btn btn-info">
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
