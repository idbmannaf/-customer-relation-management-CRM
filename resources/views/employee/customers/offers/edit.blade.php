@extends('employee.layouts.employeeMaster')
@push('title')
    Customer {{ $customer->customer_name }}
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div>Edit Offer for Customers: {{ $customer->customer_name }} ({{ $customer->customer_code }})
                    <a href="{{ route('employee.customerOffer', $customer) }}" class="btn btn-warning"> Back</a>
                </div>

            </div>
        </div>
        @include('alerts.alerts')
        <form action="{{ route('employee.customerOfferUpdate', $offer) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="Ref">Ref</label>
                            <input type="text" class="form-control" name="ref" id="date"
                                value="{{ old('ref') ?? $offer->ref }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" id="date"
                                value="{{ old('date') ?? $offer->date }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ $offer->email ?? $offer->customer->email }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="to">To</label>
                            <textarea name="to" id="to" class="form-control" cols="30" rows="4">
@if ($offer->to)
{{ $offer->to }}
@endif
</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject"
                        value="{{ $offer->subject ?? 'Price offer for supplying of' }}">
                </div>

                <div class="row">
                    <div class="col-12 col-md-10">
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea name="body" id="body" class="form-control" cols="30" rows="10">
@if ($offer->body)
{{ $offer->body }}
@else
<p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">Dear Sir,<o:p></o:p></span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">With pleasure we would like to submit herewith our most competitive price offer of the above-mentioned&nbsp;</span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">product for your kind acceptance. For your kind information, Orient Computers is the sole Distributor in Bangladesh of&nbsp;</span><span style="font-weight: bolder; font-family: Calibri, sans-serif; font-size: 11pt;">LONG</span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">&nbsp;brand SLAMF Battery from Taiwan.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">We are also the sole Distributor of&nbsp;<span style="font-weight: bolder;">Apollo</span>&nbsp;brand Line Interactive, Online UPS, IPS, Automatic Voltage Stabilizer and Auto Rescue Device from Taiwan since&nbsp;<span style="font-weight: bolder;">1998</span>. Besides we are the&nbsp;<span style="font-weight: bolder;">Authorized Service Provider (ASP)&nbsp;</span>of Online and Offline Smart UPS in Bangladesh<span style="font-weight: bolder;">&nbsp;</span>of&nbsp;<span style="font-weight: bolder;">APC by SCHNEIDER ELECTRIC</span>. As well as we are the sole Distributor of&nbsp;<span style="font-weight: bolder;">ViewSonic</span>&nbsp;brand Multimedia Projector,&nbsp;<span style="font-weight: bolder;">SAMSUNG &amp; MICRODIGITAL</span>&nbsp;CCTV System from Korea,&nbsp;<span style="font-weight: bolder;">CAMPRO</span>&nbsp;brand CCTV Solutions from Taiwan,&nbsp;<span style="font-weight: bolder;">VIDEOTEC</span>&nbsp;Explosion Proof CCTV from Italy and&nbsp;<span style="font-weight: bolder;">HUNDURE</span>&nbsp;Access Control &amp; Time Attendance System from Taiwan and&nbsp;<span style="font-weight: bolder;">Dr. Board</span>&nbsp;Brand Interactive Smart Board from Taiwan.&nbsp;</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">We have an organized service center with necessary equipment’s and skilled technical personnel who are capable to serve any problem anywhere in Bangladesh. We are serving number of organizations against Annual Maintenance Contract (AMC). Your positive response in this regard will be highly appreciated.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;"><br></span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">Thanking you in anticipation.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-family: Calibri, sans-serif; font-size: 11pt;"><br></span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">Thanking you,</span></p>
@endif
</textarea>
                        </div>
                    </div>
                </div>
                @php
                    $employee = auth()->user()->employee;
                @endphp
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="signature">Signature</label>
                            <textarea name="signature" id="signature" class="form-control" cols="30" rows="4">
@if ($offer->signature)
{!! $offer->signature !!}
@else
<div style="line-height:5px; font-family: Calibri, sans-serif; font-size: 11pt;">
                                <p style="font-family: Calibri, sans-serif; font-size:11pt">University of Liberal Arts Bangladesh (ULAB)</p><p style="font-family: Calibri, sans-serif; font-size:11pt">
                                House 56, Road 4/A Dhanmondi,</p><p style="font-family: Calibri, sans-serif; font-size:11pt">
                                Dhaka-1209, Bangladesh.
                                </p>
                                </div>
@endif
</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12 col-md-4">
                        <label for="category">Select Category</label>
                        <select name="category" id="category"
                            data-url="{{ route('employee.categoryToProductAjax', ['customer' => $customer->id, 'offer' => $offer->id]) }}"
                            class="form-control select2">
                            <option value="" selected disabled>Select Category</option>
                            <option value="0">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-8 showProduct">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Item Description</th>
                                <th>QTY</th>
                                <th>Unit Price (BDT)</th>
                                <th>Total Price (BDT)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="showItem">
                            @if (count($customer_offer_items))
                                @foreach ($customer_offer_items as $customer_offer_item)
                                    @include('employee.customers.offers.ajax.customerOfferItem')
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="border: none; text-align:right;" colspan="3">Total Price:</th>
                                <td class="subtotal" colspan="2">{{ $offer->total_price() }}</td>
                            </tr>
                            <tr>
                                <th style="border: none; text-align:right;" colspan="3">Service Charge:</th>
                                <th colspan="1"><input type="number" id="service_charge" name="service_charge"
                                        class="form-control" colspan="2" value="{{$offer->service_charge }}"
                                        oninput="changeOfferItem(this,'service_charge')">
                                </th>
                            </tr>
                            <tr>
                                <th style="border: none; text-align:right;" colspan="3">Sub Total:</th>
                                <td class="subsubtotal" colspan="2">{{ $offer->subtotal_price }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="terms_and_condition">Terms And Condition</label>
                            <textarea name="terms_and_condition" id="terms_and_condition" class="form-control" cols="30" rows="4">
@if ($offer->terms_and_condition)
{!! $offer->terms_and_condition !!}
@else
<div style="font-size: 11pt; font-family: Calibri, sans-serif;">1. Payment&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : 100% Cash/cheque within 15 days in favor of Orient Computers.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">2. Delivery&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Within 15 days from date of work order.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">3. Offer Validity&nbsp; &nbsp; &nbsp; &nbsp; :<span style="font-family: Arial;"> 15 da</span>ys</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">4. VAT &amp; TAX&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Included</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">5. Site Preparation&nbsp; &nbsp;: Any civil work for site preparation is excluding of this proposal.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">6. Accessories&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: AC Cables, Breakers, Earthing, Grounding are out of this proposal.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">7. Warranty Void&nbsp; &nbsp; &nbsp; : Over Charged/Discharged, Burnt, Terminal Soldering, Physical&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Damage/Lost/Theft etc.</div>
@endif
</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 py-3">
                <label for="offer_attachments">Offer Attachments</label>
                <div class="">
                    <input type="file" multiple id="file" name="file[]"
                        data-url="{{ route('global.fileStore', ['type' => 'offer', 'offer_id' => $offer->id]) }}">
                </div>

                <div class="images">
                    @foreach ($attachments as $attachment)
                        <div class="attachment">
                            <span class="serial"></span>
                            <a class="badge badge-success"
                                href="{{ route('imagecache', ['template' => 'original', 'filename' => $attachment->name]) }}">
                                {{ $attachment->name }}</a> <a
                                href="{{ route('global.fileDelete', ['file' => $attachment->id]) }}"
                                class="badge badge-danger deleteAttachment"><i class="fas fa-times"></i></a>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="col-12">
                @if (auth()->user()->employee->team_admin && $offer->status == 'pending')
                    <div class="row">
                        <div class="col-12 col-md-6 m-auto">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <input type="submit" name="status" value="rejected"
                                        class="btn btn-danger form-control">
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="submit" name="status" value="approved"
                                        class="btn btn-success form-control">

                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option {{ $offer->status == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                            <option {{ $offer->status == 'approved' ? 'selected' : '' }} value="approved">Approved
                            </option>
                            <option {{ $offer->status == 'rejected' ? 'selected' : '' }} value="rejected">Rejected
                            </option>
                        </select>
                    </div> --}}
                @elseif ($offer->status == 'temp')
                    <div class="row">
                        <div class="col-12 col-md-5 m-auto">
                            <input type="submit" name="status" value="pending" class="btn btn-success form-control">
                        </div>
                    </div>
                @elseif ($offer->status == 'pending')
                    <div class="row">
                        <div class="col-12 col-md-5 m-auto">
                            <input type="submit" name="status" value="approved" class="btn btn-success form-control">
                        </div>
                    </div>
                @endif
            </div>

            {{-- <div class="float-right">
                <input type="submit" class="btn btn-success">
            </div> --}}
        </form>
    </div>
    {{-- {{dd($offer)}} --}}
@endsection



@push('js')
    <!-- summernote css/js -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $("input[id='file']").on('change', function(e) {
            e.preventDefault();
            var form = $(e.target).closest("form");
            var formElement = form.get(0)
            var formData = new FormData(formElement);
            var url = $(e.target).attr('data-url');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                _token: $('meta[name="csrf-token"]').attr('content'),
                async: false,
                data: formData,
                success: function(res) {
                    if (res.success) {
                        $('.images').append(res.html);
                        $('#file').val('')
                        serial_maintains()
                    }
                }
            })
        })
        $(document).on('click', '.deleteAttachment', function(e) {
            e.preventDefault();
            var that = $(this);
            var url = that.attr('href');
            if (confirm('Are you sure? You want to delete this file?')) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        if (res.success) {
                            that.closest('.attachment').remove();
                            serial_maintains()
                            // location.reload();
                        }
                    }
                });
            }
        })

        function serial_maintains() {
            var seralArray = $('.serial');
            seralArray.map((value, index) => {
                $(index).text(value + 1 + ". ");
            })
        }
        serial_maintains();

        $(document).on('change', '#category', function() {
            var that = $(this);
            var category = that.val();
            var url = that.attr('data-url')
            $.ajax({
                url: url,
                method: "GET",
                data: {
                    category_id: category
                },
                success: function(res) {
                    if (res.success) {
                        $('.showProduct').html(res.html)
                        $('.employee-select').select2({
                            theme: 'bootstrap4',
                            // minimumInputLength: 1,
                            ajax: {
                                data: function(params) {
                                    return {
                                        q: params.term, // search term
                                        page: params.page
                                    };
                                },
                                processResults: function(data, params) {
                                    params.page = params.page || 1;
                                    // alert(data[0].s);
                                    var data = $.map(data, function(obj) {
                                        obj.id = obj.id || obj.id;
                                        return obj;
                                    });
                                    var data = $.map(data, function(obj) {
                                        obj.text = obj.name + "(" + obj.model + ")";
                                        return obj;
                                    });
                                    return {
                                        results: data,
                                        pagination: {
                                            more: (params.page * 30) < data.total_count
                                        }
                                    };
                                }
                            },
                        });

                    }
                }
            })
        })

        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

        });
    </script>
    <script type="text/javascript">
        $('#body').summernote({
            height: 400
        });
        $('#terms_and_condition').summernote({
            height: 300
        });
        $('#to').summernote({
            height: 200
        });
        $('#signature').summernote({
            height: 100
        });
    </script>
    <script>
        $(document).on('input', '#search', function() {
            var that = $(this);
            var q = that.val();
            var url = that.attr('data-url');
            var finalUrl = url + "?q=" + q;
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    $('.showCustomer').html(res)
                }
            })
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('.showCustomer').html(res)
                }
            })
        })
    </script>
    <script>
        $(document).on('click', '.addBtn', function(e) {
            e.preventDefault();
            var product_id = $(this).closest('.row').find('#product').val();
            if (!product_id) {
                $('.productError').text('Select A product');
                return;
            }
            $('.productError').text('');

            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    product_id: product_id,
                },
                success: function(res) {
                    if (res.success) {
                        $('.showItem').append(res.html);
                    }
                }
            })
        })

        function changeOfferItem(e, type) {
            var that = $(e);
            if (type == 'service_charge') {
                var url = $('.mother').attr('data-url');
            } else {
                var url = that.closest('.mother').attr('data-url');

            }

            if (type == 'quantity') {
                if (that.val() < 1) {
                    alert('Quantity Must be at last 1');
                    return;
                }
                that.closest('.motherCard').find('.qtyError').text('');
            }

            var value = that.val();
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    value: value,
                    type: type
                },
                success: function(res) {
                    if (res.success) {
                        that.closest('.mother').find('.total_price').text(res.item_total_price);
                        $('.subtotal').text(res.total_price);
                        $('.subsubtotal').text(res.subtotal_price);
                    }
                }
            })
        }
        $(document).on('input', '.service_charge', function() {

        })
        $(document).on('click', '.deleteItem', function(e) {
            e.preventDefault();
            var that = $(this);
            var url = that.attr('href');
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    if (res.success) {
                        that.closest('.mother').remove();
                        $('.subtotal').text(res.total_price);
                        $('.subsubtotal').text(res.subtotal_price);
                    }
                }
            })
        })
    </script>
@endpush
