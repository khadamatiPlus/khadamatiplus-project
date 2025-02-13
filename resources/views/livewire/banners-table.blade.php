<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="{{ __('Search') }}" wire:model.live="search">
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{ __('Image') }}</th>
            <th>{{ __('Title') }}</th>
            <th>{{ __('Link') }}</th>
            <th>{{ __('Type') }}</th> <!-- New column for type -->
            <th>{{ __('Category') }}</th>
            <th>{{ __('Service') }}</th>
            <th>{{ __('Merchant') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($banners as $banner)
            <tr>
                <td>
                    @if(isset($banner->image))
                        <img src="{{storageBaseLink(\App\Enums\Core\StoragePaths::BANNER_IMAGE.$banner->image)}}" width="100" loading="lazy" />
                    @else
                        ----------------
                    @endif
                </td>
                <td>{{ $banner->title }}</td>
                <td>{{ $banner->link }}</td>

                <!-- Display the type of the banner -->
                <td>{{ ucfirst($banner->type) }}</td> <!-- Capitalize the first letter of the type -->

                <!-- Display category, service, or merchant based on type -->
                <td>
                    @if($banner->type == 'category' && isset($banner->category_id))
                        {{ $banner->category->name ?? __('N/A') }}
                    @else
                        ----------------
                    @endif
                </td>
                <td>
                    @if($banner->type == 'service' && isset($banner->service_id))
                        {{ $banner->service->name ?? __('N/A') }}
                    @else
                        ----------------
                    @endif
                </td>
                <td>
                    @if($banner->type == 'merchant' && isset($banner->merchant))
                        {{ $banner->merchant->name ?? __('N/A') }}
                    @else
                        ----------------
                    @endif
                </td>

                <td>
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.banner.update'))
                        <x-utils.edit-button :href="route('admin.banner.edit', $banner)" />
                    @endif
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.banner.delete'))
                        <x-utils.delete-button :href="route('admin.banner.delete', $banner)" />
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div>
        {{ $banners->links() }}
    </div>
</div>
