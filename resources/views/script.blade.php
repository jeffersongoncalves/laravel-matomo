@if(!empty($settings->host_analytics))
    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        @if(!empty($settings->domains))
        _paq.push(['setDomains', {!! json_encode(array_values(array_filter(array_map('trim', explode(',', $settings->domains))))) !!}]);
        @endif
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//{{ $settings->host_analytics }}/";
            _paq.push(['setTrackerUrl', u+'{{ $settings->file }}']);
            _paq.push(['setSiteId', '{{ $settings->site_id }}']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'{{ $settings->script }}'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
@endif
