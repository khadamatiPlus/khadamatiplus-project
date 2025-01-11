
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
        </tr>
        </thead>
        <tbody>
        @foreach($subscribers as $subscriber)
            <tr>
                <td>
                    <a href="mailto:{{ $subscriber->email }}">{{ $subscriber->email }}</a>
                </td>

        </tr>
    @endforeach
    </tbody>
</table>

<div>
    {{ $subscribers->links() }}
</div>
</div>
