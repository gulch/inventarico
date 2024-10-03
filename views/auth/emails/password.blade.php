Нажмите на эту ссылку чтобы сменить пароль:
<a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">
    {{ $link }}
</a>
