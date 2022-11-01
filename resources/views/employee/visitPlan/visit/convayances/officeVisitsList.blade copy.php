@extends('admin.layouts.adminMaster')
@push('title')
Employee Office Visit
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-body shadow">
            <strong> Office Visit of {{ $employee->name }} ({{ $employee->employee_id }})  </strong>
        </div>
    </div>

    @if (count($office_visit_locations) > 0)
        <div class="row">
            <div class="col-md-12">
                <!-- The time line -->
                <div class="timeline">
                    <!-- timeline time label -->
                    <div class="time-label">
                        <span class="bg-red">{{ \Carbon\Carbon::parse(request()->date)->format('d M. Y') }}</span>
                    </div>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    @php $previous_office_id = null;  @endphp

                    @foreach ($office_visit_locations as $location)
                        @if($previous_office_id != $location->office_location_id)
                            <div>
                                <i
                                    class="fas fa-map-marker-alt {{ $location->Office->type == 'customer' ? 'bg-primary' : 'bg-warning' }}"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i>
                                        {{ $location->created_at->toTimeString() }}</span>
                                    <h3 class="timeline-header">
                                        @if ($loop->first)
                                            <a href="#">Start</a>
                                        @elseif ($loop->last)
                                            <a href="#">End</a>
                                            @else
                                            <a href="#">&nbsp;</a>
                                        @endif
                                    </h3>

                                    <div class="timeline-body">
                                        <strong>Office:</strong> {{ $location->Office->title }} <br>

                                        <strong>Company:</strong>
                                        @if ($location->Office->type == 'customer')
                                            {{ $location->Office->customer_company->name }}) (Customer Company)
                                        @else
                                            {{ $location->Office->company->name }})
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        @php $previous_office_id = $location->office_location_id;  @endphp

                        <!--<div>-->
                        <!--        <i-->
                        <!--            class="fas fa-map-marker-alt {{ $location->Office->type == 'customer' ? 'bg-primary' : 'bg-warning' }}"></i>-->
                        <!--        <div class="timeline-item">-->
                        <!--            <span class="time"><i class="fas fa-clock"></i>-->
                        <!--                {{ $location->created_at->toTimeString() }}</span>-->
                        <!--            <h3 class="timeline-header">-->
                        <!--                @if ($loop->first)-->
                        <!--                    <a href="#">Start</a>-->
                        <!--                @elseif ($loop->last)-->
                        <!--                    <a href="#">End</a>-->
                        <!--                    @else-->
                        <!--                    <a href="#">&nbsp;</a>-->
                        <!--                @endif-->
                        <!--            </h3>-->

                        <!--            <div class="timeline-body">-->
                        <!--                <strong>Office:</strong> {{ $location->Office->title }} <br>-->

                        <!--                <strong>Company:</strong>-->
                        <!--                @if ($location->Office->type == 'customer')-->
                        <!--                    {{ $location->Office->customer_company->name }}) (Customer Company)-->
                        <!--                @else-->
                        <!--                    {{ $location->Office->company->name }})-->
                        <!--                @endif-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                    @endforeach

                    <!-- END timeline item -->
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
    @endif

@endsection



@push('js')
@endpush
