
<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="{{ __('Search') }}" wire:model.live="search">
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Phone Number') }}</th>

            <th>{{ __('Image') }}</th>
            <th>{{ __('Approve') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>
                    @if(!empty($customer->profile->mobile_number))
                        <a href = "tel: {{ $customer->profile->mobile_number }}">{{ $customer->profile->mobile_number }}</a>
                    @else
                        @lang('Empty')
                    @endif
                </td>
                <td>
                    @if(isset($customer->image))
                        <img class="zoom" src="{{storageBaseLink(\App\Enums\Core\StoragePaths::CAPTAIN_PROFILE_PIC.$customer->image??'')}}" width="100"  loading="lazy" />
                    @else
                        ----------------
                    @endif
                </td>
                <td>
                    <input type="checkbox" name="is_verified" id="is_verified" data-toggle="toggle" data-size="sm" data-onstyle="primary" onchange="changeCheckBox({{$customer->id}})" value="{{ old($customer->is_verified) ?? ($customer->is_verified == 0)?'no':'yes'}}" {{$customer->is_verified == 1 ? 'checked' : ''}} >
                </td>

                <td>
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.customer.show'))
                        <x-utils.view-button :href="route('admin.customer.show', $customer)" />
                    @endif
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.customer.update'))
                        <x-utils.edit-button :href="route('admin.customer.edit', $customer)" />
                    @endif


                            <script>
                                function changeCheckBox(id){
                                    debugger
                                    $.ajax({
                                        url : '{{route('admin.customer.updateStatusByCustomerId')}}?'+'customerId='+id,
                                        type:'GET',
                                        async : false,
                                        success:function(data){
                                            if(data){
                                            }
                                        },
                                        error:function (xhr){
                                            alert(xhr.message);
                                        }
                                    })
                                }
                            </script>

                </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div>
    {{ $customers->links() }}
</div>
</div>
