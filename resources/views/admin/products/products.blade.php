@extends('admin.layouts.adminMaster')
@push('title')
    Products
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">{{ ucfirst(request()->service_type) ?? 'Products' }}
                @can('product-add')
                <a href="{{ route('admin.product.create', ['service_type' => request()->service_type]) }}"
                    class="btn btn-danger"> New {{ request()->type == 'spare_parts' ? 'Spare Parts' : 'Product' }}
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @include('alerts.alerts')
            <div class="row">
                <div class="col-12 col-md-4 ml-auto pb-2">
                    <input type="text" id="search" class="form-control"
                        data-search-url="{{ route('admin.product.index', ['service_type' => request()->service_type]) }}"
                        placeholder="Search Here">
                </div>
            </div>

            <div class="productShow">
                @include('admin.products.part.productPart')
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();
        $(document).on('input', '#search', function() {
            var that = $(this);
            var q = that.val();
            var url = that.attr('data-search-url');
            var finalUrl = url + "&q=" + q;
            delay(function() {
            $.ajax({
                url: finalUrl,
                method: 'GET',
                success: function(res) {
                    if (res.success) {
                        $(".productShow").html(res.html);
                    }
                }
            })
        }, 500);

        })
        $(document).on('click', 'a.page-link', function(e) {
            e.preventDefault();
            var that = $(this);
            var url = that.attr('href');

            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    $(".productShow").html(res.html);
                }
            })
        })
    </script>
@endpush
