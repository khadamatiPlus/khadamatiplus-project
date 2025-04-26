<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="{{ __('Search by service title or merchant') }}" wire:model.live="search">
        </div>
        <div class="col-md-3">
            <select class="form-control" wire:model.live="merchantFilter">
                <option value="">{{ __('All Merchants') }}</option>
                @foreach($merchants as $merchant)
                    <option value="{{ $merchant->id }}">{{ $merchant->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{ __('Main Image') }}</th>
            <th>{{ __('Title') }}</th>
            <th>{{ __('Merchant') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($services as $service)
            <tr>
                <td>
                    @php
                        $serviceMainImage = \App\Domains\Service\Models\ServiceImage::query()
                            ->where('service_id', $service->id)
                            ->where('is_main', 1)
                            ->first();
                    @endphp
                    @if(isset($serviceMainImage->image))
                        <img src="{{ $serviceMainImage->image }}" width="100" loading="lazy" />
                    @else
                        ----------------
                    @endif
                </td>
                <td>{{ $service->title }}</td>
                <td>
                    {{ $service->merchant->name }}
                    @if(!empty($service->merchant->profile->mobile_number))
                        <br>
                        <small>
                            <a href="tel:{{ $service->merchant->profile->mobile_number }}">
                                {{ $service->merchant->profile->mobile_number }}
                            </a>
                        </small>
                    @endif
                </td>
                <td>
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.service.update'))
                        <x-utils.edit-button :href="route('admin.service.edit', $service)" />
                    @endif
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.service.delete'))
                        <x-utils.delete-button :href="route('admin.service.delete', $service)" />
                    @endif
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.service.show'))
                        <x-utils.view-button :href="route('admin.service.show', $service)" />
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div>
        {{ $services->links() }}
    </div>
</div>
