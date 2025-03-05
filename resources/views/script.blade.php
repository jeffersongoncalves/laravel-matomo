@if(!empty(config('matomo.domains')))
    <script defer data-domain="{{ config('matomo.domains') }}"
            src="{{ config('matomo.host_analytics') }}/js/script.js"></script>
    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//{{ config('matomo.host_analytics') }}/";
            _paq.push(['setTrackerUrl', u+'{{ config('matomo.file') }}']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'{{ config('matomo.script') }}'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
@endif
