<div id="flashContent" class="large-devices-offers">
    <div id="altContent">
        @include('partials.lower_header.offers')
    </div>
</div>

<div class="small-devices-offers">
    @include('partials.lower_header.offers')
</div>

<script src="{{ URL::asset('swfs/js/swfobject.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    var flashvars = {
    };
    var params = {
        menu: "false",
        scale: "noScale",
        allowFullscreen: "true",
        allowScriptAccess: "always",
        bgcolor: "#FFF"
    };
    var attributes = {
        id:"slider"
    };
    swfobject.embedSWF("{{ URL::asset('slider/slider.swf') }}", "altContent", "1068px", "337px", "10.0.0", "{{ URL::asset('swfs/expressInstall.swf') }}", flashvars, params, attributes);
</script>
