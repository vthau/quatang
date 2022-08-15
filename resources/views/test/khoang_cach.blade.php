<!DOCTYPE html>
<html>
<head>
    <title>Place Autocomplete Address Form</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDmhTF-eJ9aZSRQKdA_Dm0UBBFlSuOYwo&callback=initAutocomplete&libraries=places&v=weekly"
        defer
    ></script>

    <link href="{{url('public/google/custom.css')}}" rel="stylesheet">
    <script src="{{url('public/google/custom.js')}}" type="text/javascript"></script>
    <script src="{{url('public/themes/be/js/jquery.min.js')}}" type="text/javascript"></script>

</head>
<body>

<div class="locationField">
    <input
        id="autocomplete1"
        placeholder="Enter your address"
{{--        onFocus="geolocate()"--}}
        type="text"
    />
</div>

<!-- Note: The address components in this sample are typical. You might need to adjust them for
           the locations relevant to your app. For more information, see
     https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
-->

<table class="address">
    <tr>
        <td class="label">Street address</td>
        <td class="slimField">
            <input class="field" id="a1_street_number" disabled="true" />
        </td>
        <td class="wideField" colspan="2">
            <input class="field" id="a1_route" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">City</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a1_locality" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">State</td>
        <td class="slimField">
            <input
                class="field"
                id="a1_administrative_area_level_1"
                disabled="true"
            />
        </td>
        <td class="label">Zip code</td>
        <td class="wideField">
            <input class="field" id="a1_postal_code" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">Country</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a1_country" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">Latitude</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a1_latitude" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">Longitude</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a1_longitude" disabled="true" />
        </td>
    </tr>
</table>

<div class="locationField">
    <input
        id="autocomplete2"
        placeholder="Enter your address"
        {{--        onFocus="geolocate()"--}}
        type="text"
    />
</div>

<!-- Note: The address components in this sample are typical. You might need to adjust them for
           the locations relevant to your app. For more information, see
     https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
-->

<table class="address">
    <tr>
        <td class="label">Street address</td>
        <td class="slimField">
            <input class="field" id="a2_street_number" disabled="true" />
        </td>
        <td class="wideField" colspan="2">
            <input class="field" id="a2_route" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">City</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a2_locality" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">State</td>
        <td class="slimField">
            <input
                class="field"
                id="a2_administrative_area_level_1"
                disabled="true"
            />
        </td>
        <td class="label">Zip code</td>
        <td class="wideField">
            <input class="field" id="a2_postal_code" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">Country</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a2_country" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">Latitude</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a2_latitude" disabled="true" />
        </td>
    </tr>
    <tr>
        <td class="label">Longitude</td>
        <td class="wideField" colspan="3">
            <input class="field" id="a2_longitude" disabled="true" />
        </td>
    </tr>
</table>

<div class="locationField">
    <input type="hidden" id="_tks" value="{{csrf_token()}}" />
    <button type="button" onclick="tinhToan()">Calculate Distance</button>

    <span style="margin: 0 20px;">km: </span>
    <span id="kctt"></span>

</div>

<script type="text/javascript">
    var gks = {
            baseURL: '{{url('')}}',
        };
</script>

</body>
</html>
