<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="{{ __('Search') }}" wire:model.live="search">
        </div>
        <div class="col-md-3">
            <select class="form-control" wire:model.live="notificationTypeFilter">
                <option value="">{{ __('All Notification Types') }}</option>
                <option value="category">{{ __('Category') }}</option>
                <option value="service">{{ __('Service') }}</option>
                <option value="informative">{{ __('Informative') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" wire:model.live="recipientTypeFilter">
                <option value="">{{ __('All Recipient Types') }}</option>
                <option value="merchant">{{ __('Merchants') }}</option>
                <option value="user">{{ __('Customers') }}</option>
            </select>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{ __('Image') }}</th>
            <th>{{ __('Title') }}</th>
            <th>{{ __('Description') }}</th>
            <th>{{ __('Notification Type') }}</th>
            <th>{{ __('Recipient Type') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($notifications as $notification)
            <tr>
                <td>
                    @if(isset($notification->notification_icon))
                        <img src="{{storageBaseLink(\App\Enums\Core\StoragePaths::NOTIFICATION_ICON.$notification->notification_icon)}}" width="100"  loading="lazy" />
                    @else
                        ----------------
                    @endif
                </td>
                <td>{{ $notification->title }}</td>
                <td> {!!$notification->description !!}</td>
                <td>
                    @if($notification->notification_type === 'category')
                        <span class="badge badge-info">{{ __('Category') }}</span>
                        @if($notification->category)
                            <br><small>{{ $notification->category->name }}</small>
                        @endif
                    @elseif($notification->notification_type === 'service')
                        <span class="badge badge-primary">{{ __('Service') }}</span>
                        @if($notification->service)
                            <br><small>{{ $notification->service->title }}</small>
                        @endif
                    @else
                        <span class="badge badge-secondary">{{ __('Informative') }}</span>
                    @endif
                </td>
                <td>
                    @if($notification->type === 'merchant')
                        <span class="badge badge-warning">{{ __('Merchants') }}</span>
                    @else
                        <span class="badge badge-success">{{ __('Customers') }}</span>
                    @endif
                </td>
                <td>
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.notification.update'))
                        <x-utils.edit-button :href="route('admin.notification.edit', $notification)" />
                    @endif
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.notification.delete'))
                        <x-utils.delete-button :href="route('admin.notification.delete', $notification)" />
                    @endif

                    <button onclick="send({{$notification->id}})" class="btn btn-success btn-sm"><i class="fas fa-paper-plane"></i> {{__("Send")}}</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div>
        {{ $notifications->links() }}
    </div>
</div>

@push('after-scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function send(id) {
            $.ajax({
                type: 'GET',
                url: '{{route('admin.notification.sendNotification')}}?'+'notificationId='+id,
                success: function (data) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: '{{__('The Notification was successfully Sent.')}}',
                    })
                }
            });
        }
    </script>
@endpush
