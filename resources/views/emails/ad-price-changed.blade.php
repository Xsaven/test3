<x-mail::message>
# Hello
Price of the ad ({{ $subscription->ad_url }}) has been changed to {{ number_format($subscription->last_price, 2, '.', ' ') }}. You can open the ad by clicking the button below.
<x-mail::button :url="$subscription->ad_url">
    Open ad
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
