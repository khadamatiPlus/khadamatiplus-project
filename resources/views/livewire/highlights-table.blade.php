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
            <th>{{ __('Type') }}</th>
            <th>{{ __('Category') }}</th>
            <th>{{ __('Service') }}</th>
            <th>{{ __('Merchant') }}</th>
            <th>{{ __('Status') }}</th> <!-- New column for status -->
            <th>{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($highlights as $highlight)
            <tr>
                <td>
                    @if(isset($highlight->image))
                        <img src="{{ storageBaseLink(\App\Enums\Core\StoragePaths::HIGHLIGHT_IMAGE . $highlight->image) }}" width="100" loading="lazy" />
                    @else
                        ----------------
                    @endif
                </td>
                <td>{{ $highlight->title }}</td>
                <td>{{ $highlight->link }}</td>
                <td>{{ ucfirst($highlight->type) }}</td>
                <td>
                    @if($highlight->type == 'category' && isset($highlight->category_id))
                        {{ $highlight->category->name ?? __('N/A') }}
                    @else
                        ----------------
                    @endif
                </td>
                <td>
                    @if($highlight->type == 'service' && isset($highlight->service_id))
                        {{ $highlight->service->name ?? __('N/A') }}
                    @else
                        ----------------
                    @endif
                </td>
                <td>
                    @if($highlight->type == 'merchant' && isset($highlight->merchant))
                        {{ $highlight->merchant->name ?? __('N/A') }}
                    @else
                        ----------------
                    @endif
                </td>
                <td>
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.highlight.update'))
                        <div class="form-check form-switch">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="status-{{ $highlight->id }}"
                                wire:model.live="highlight.{{ $highlight->id }}.status"
                                wire:change="toggleStatus({{ $highlight->id }})"
                                {{ $highlight->status ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="status-{{ $highlight->id }}">
                                {{ $highlight->status ? __('Active') : __('Inactive') }}
                            </label>
                        </div>
                    @else
                        {{ $highlight->status ? __('Active') : __('Inactive') }}
                    @endif
                </td>
                <td>
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.highlight.update'))
                        <x-utils.edit-button :href="route('admin.highlight.edit', $highlight)" />
                    @endif
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.highlight.delete'))
                        <x-utils.delete-button :href="route('admin.highlight.delete', $highlight)" />
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div>
        {{ $highlights->links() }}
    </div>
</div>
