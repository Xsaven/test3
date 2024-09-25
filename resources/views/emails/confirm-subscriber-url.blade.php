<x-mail::message>
# Hello
You can confirm your subscription by clicking the button below.
<x-mail::button :url="$url">
    Confirm
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
