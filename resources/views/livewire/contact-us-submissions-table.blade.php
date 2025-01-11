
<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="{{ __('Search') }}" wire:model.live="search">
        </div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Subject') }}</th>
            <th>{{ __('Message') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contactUsSubmissions as $contactUsSubmission)
            <tr>
                <td>
                    <a href="mailto:{{ $contactUsSubmission->email }}">{{ $contactUsSubmission->email }}</a>
                </td>
                <td>{{ $contactUsSubmission->subject }}</td>
                <td>{{ $contactUsSubmission->message }}</td>
                <td>
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.contactUsSubmission.delete'))
                        <x-utils.delete-button :href="route('admin.contactUsSubmission.delete', $contactUsSubmission)" />
                    @endif

                </td>

        </tr>
    @endforeach
    </tbody>
</table>

<div>
    {{ $contactUsSubmissions->links() }}
</div>
</div>
