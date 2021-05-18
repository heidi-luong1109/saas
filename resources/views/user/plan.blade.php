<ul class="list-group list-group-flush list my--3">
    @foreach($plans as $plan)
        <li class="list-group-item px-0">
            <div class="row align-items-center">
                <div class="col-auto">
                    <a href="#" class="avatar rounded-circle">
                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/plan')).'/'.$plan->image}}">
                    </a>
                </div>
                <div class="col ml--2">
                    <h4 class="mb-0">
                        <a href="#!">{{$plan->name}}</a>
                    </h4>
                    <small> {{\Auth::user()->priceFormat($plan->price)}} {{' / '. $plan->duration}}</small>
                </div>
                <div class="col ml--2">
                    <h4 class="mb-0">
                        <a href="#!">{{__('Employees')}}</a>
                    </h4>
                    <small> {{$plan->max_employee}}</small>
                </div>
                <div class="col ml--2">
                    <h4 class="mb-0">
                        <a href="#!">{{__('Clients')}}</a>
                    </h4>
                    <small> {{$plan->max_client}}</small>
                </div>
                <div class="col-auto">
                    @if($user->plan==$plan->id)
                        <div class="media-value"></div>
                        <div class="media-label text-success"><h5>{{__('Active')}}</h5></div>
                    @else
                        <div class="media-value">
                            <a href="{{route('plan.active',[$user->id,$plan->id])}}" class="btn btn-primary btn-sm" title="Click to Upgrade Plan"><i class="fas fa-cart-plus"></i></a>
                        </div>
                        <div class="media-label text-success"><h6></h6></div>
                    @endif

                </div>
            </div>
        </li>
    @endforeach
</ul>
