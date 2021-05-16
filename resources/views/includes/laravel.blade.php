<script type="text/javascript">
    window.laravel = {useMetricSystem: {{ config('thehotmeal.useMetricSystem') ? 'true' : 'false' }}, appVersion: '{{ env('APP_VERSION') }}', location: '{{ config('app.location', 'UK') }}', currencySm: '{{ config('locations.'.config('app.location', 'UK').'.currency_symbol', '£') }}'};
</script>
