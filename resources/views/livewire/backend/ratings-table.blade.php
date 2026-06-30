<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{__("Ratings Management")}}</h6>
            <div class="d-flex gap-2">
                <input type="text" wire:model="search" class="form-control form-control-sm" placeholder="{{__('Search...')}}" style="width: 200px;">
                <select wire:model="perPage" class="form-control form-control-sm" style="width: 120px;">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__("ID")}}</th>
                        <th>{{__("Merchant")}}</th>
                        <th>{{__("Captain")}}</th>
                        <th>{{__("Rating")}}</th>
                        <th>{{__("Notes")}}</th>
                        <th>{{__("Created At")}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($ratings as $rating)
                        <tr>
                            <td>#{{$rating->id}}</td>
                            <td>
                                @if($rating->merchant)
                                    <a href="{{route('admin.merchant.show', $rating->merchant_id)}}">
                                        {{$rating->merchant->name}}
                                    </a>
                                @else
                                    --
                                @endif
                            </td>
                            <td>
                                @if($rating->captain)
                                    <a href="{{route('admin.captain.show', $rating->captain_id)}}">
                                        {{$rating->captain->name}}
                                    </a>
                                @else
                                    --
                                @endif
                            </td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->rate)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                ({{$rating->rate}})
                            </td>
                            <td>{{$rating->notes ?? '--'}}</td>
                            <td>{{$rating->created_at ? \Carbon\Carbon::parse($rating->created_at)->format('Y-m-d H:i:s') : 'N/A'}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{__("No ratings found")}}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <div>
                    @if ($ratings->total() > 0)
                        <p class="text-muted mb-0">
                            {{ __("Showing") }} {{ $ratings->firstItem() }} - {{ $ratings->lastItem() }}
                            {{ __("of") }} {{ $ratings->total() }} {{ __("results") }}
                        </p>
                    @endif
                </div>
                <div>
                    <nav>
                        {{ $ratings->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
