<div class="modal modal-fullscreen-xl" id="modal-fullscreen-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.customerOfferFinalSave2', ['customer' => $customer, 'offer' => $offer]) }}"
                    method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="Ref">Ref</label>
                                    <input type="text" class="form-control" name="ref" id="date"
                                        value="{{ old('ref') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" name="date" id="date"
                                        value="{{ old('date') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="to">To</label>
                                    <textarea name="to" id="to" class="form-control" cols="30" rows="4">
@if ($offer->to)
{!! $offer->to !!}
@else
<div style="line-height:5px; font-family: Calibri, sans-serif; font-size: 11pt;">
                                    <p>University of Liberal Arts Bangladesh (ULAB)</p><p>
                                    House 56, Road 4/A Dhanmondi,</p><p>
                                    Dhaka-1209, Bangladesh.
                                    </p>
                                    </div>
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
{!! $offer->body !!}
@else
<p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">Dear Sir,<o:p></o:p></span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">With pleasure we would like to submit herewith our most competitive price offer of the above-mentioned&nbsp;</span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">product for your kind acceptance. For your kind information, Orient Computers is the sole Distributor in Bangladesh of&nbsp;</span><span style="font-weight: bolder; font-family: Calibri, sans-serif; font-size: 11pt;">LONG</span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">&nbsp;brand SLAMF Battery from Taiwan.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">We are also the sole Distributor of&nbsp;<span style="font-weight: bolder;">Apollo</span>&nbsp;brand Line Interactive, Online UPS, IPS, Automatic Voltage Stabilizer and Auto Rescue Device from Taiwan since&nbsp;<span style="font-weight: bolder;">1998</span>. Besides we are the&nbsp;<span style="font-weight: bolder;">Authorized Service Provider (ASP)&nbsp;</span>of Online and Offline Smart UPS in Bangladesh<span style="font-weight: bolder;">&nbsp;</span>of&nbsp;<span style="font-weight: bolder;">APC by SCHNEIDER ELECTRIC</span>. As well as we are the sole Distributor of&nbsp;<span style="font-weight: bolder;">ViewSonic</span>&nbsp;brand Multimedia Projector,&nbsp;<span style="font-weight: bolder;">SAMSUNG &amp; MICRODIGITAL</span>&nbsp;CCTV System from Korea,&nbsp;<span style="font-weight: bolder;">CAMPRO</span>&nbsp;brand CCTV Solutions from Taiwan,&nbsp;<span style="font-weight: bolder;">VIDEOTEC</span>&nbsp;Explosion Proof CCTV from Italy and&nbsp;<span style="font-weight: bolder;">HUNDURE</span>&nbsp;Access Control &amp; Time Attendance System from Taiwan and&nbsp;<span style="font-weight: bolder;">Dr. Board</span>&nbsp;Brand Interactive Smart Board from Taiwan.&nbsp;</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;">We have an organized service center with necessary equipment’s and skilled technical personnel who are capable to serve any problem anywhere in Bangladesh. We are serving number of organizations against Annual Maintenance Contract (AMC). Your positive response in this regard will be highly appreciated.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-size: 11pt; font-family: Calibri, sans-serif;"><br></span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">Thanking you in anticipation.</span></p><p class="MsoNormal" style="text-align: justify;"><span style="font-family: Calibri, sans-serif; font-size: 11pt;"><br></span><span style="font-family: Calibri, sans-serif; font-size: 11pt;">Thanking you,</span></p>
@endif
</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="signature">Signature</label>
                                    <textarea name="signature" id="signature" class="form-control" cols="30" rows="4">
@if ($offer->signature)
{!! $offer->signature !!}
@else
<div style="line-height:5px; font-family: Calibri, sans-serif; font-size: 11pt;">

                                        @if (auth()->user()->employee)
<p>{{ auth()->user()->employee->name }}</p><p>{{ auth()->user()->employee->designation->title }}</p>
@endif
                                    </div>
@endif
</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-10">
                                <div class="form-group">
                                    <label for="search">Select Product For Financial Proposal</label>
                                    <select id="product" name="product"
                                        class="form-control user-select select2-container employee-select select2"
                                        data-placeholder="Product Model/ Name"
                                        data-ajax-url="{{ route('global.productAllAjax') }}" data-ajax-cache="true"
                                        data-ajax-dataType="json" data-ajax-delay="200" style="">
                                    </select>
                                    <span class="text-danger productError"></span>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 pt-2 d-flex justify-content-start align-items-center">
                                <a href=""
                                    data-url={{ route('employee.customerOfferItemAjax', ['customer' => $customer, 'offer' => $offer->id]) }}
                                    class="btn btn-info addBtn"><i class="fas fa-plus"></i></a>
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
<div style="font-size: 11pt; font-family: Calibri, sans-serif;">1. Payment&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : 100% Cash/cheque within 15 days in favor of Orient Computers.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">2. Delivery&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Within 15 days from date of work order.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">3. Offer Validity&nbsp; &nbsp; &nbsp; &nbsp; :<span style="font-family: Arial;"> 15 da</span>ys</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">4. VAT &amp; TAX&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: Included</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">5. Site Preparation&nbsp; &nbsp;: Any civil work for site preparation is excluding of this proposal.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">6. Accessories&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: AC Cables, Breakers, Earthing, Grounding are out of this proposal.</div><div style="font-size: 11pt; font-family: Calibri, sans-serif;">7. Warranty Void&nbsp; &nbsp; &nbsp; : Over Charged/Discharged, Burnt, Terminal Soldering, Physical Damage/Lost/Theft etc.</div>
@endif
</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->employee)
                        @if (auth()->user()->employee->team_admin)
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="float-right">
                        <input type="submit" class="btn btn-success">
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
