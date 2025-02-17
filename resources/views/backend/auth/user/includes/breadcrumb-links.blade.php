<x-utils.link
    class="c-subheader-nav-link"
    :href="route('admin.auth.user.deactivated')"
    :text="__('Deactivated Admins')"
    permission="admin.access.user.reactivate" />

@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.auth.user.deleted')" :text="__('Deleted Admins')" />
@endif
