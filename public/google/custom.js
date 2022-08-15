// This sample uses the Autocomplete widget to help the user select a
// place, then it retrieves the address components associated with that
// place, and then it populates the form fields with those details.
// This sample requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
let placeSearch;
let autocomplete1, autocomplete2;
const componentForm = {
    street_number: "short_name",
    route: "long_name",
    locality: "long_name",
    administrative_area_level_1: "short_name",
    country: "long_name",
    postal_code: "short_name",
};

function initAutocomplete() {
    // Create the autocomplete object, restricting the search predictions to
    // geographical location types.
    autocomplete1 = new google.maps.places.Autocomplete(
        document.getElementById("autocomplete1"),
        // { types: ["geocode"] }
    );
    // Avoid paying for data that you don't need by restricting the set of
    // place fields that are returned to just the address components.
    autocomplete1.setFields(["address_component", "geometry"]);
    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete1.addListener("place_changed", fillInAddress1);


    autocomplete2 = new google.maps.places.Autocomplete(
        document.getElementById("autocomplete2"),
        // { types: ["geocode"] }
    );
    // Avoid paying for data that you don't need by restricting the set of
    // place fields that are returned to just the address components.
    autocomplete2.setFields(["address_component", "geometry"]);
    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete2.addListener("place_changed", fillInAddress2);
}

function fillInAddress1() {
    // Get the place details from the autocomplete object.
    const place = autocomplete1.getPlace();

    // console.log('ENTER 1: ');
    // console.log(place);
    // console.log('latitude: ' + place.geometry.location.lat());
    // console.log('longitude: ' + place.geometry.location.lng());

    document.getElementById('a1_latitude').value = place.geometry.location.lat();
    document.getElementById('a1_longitude').value = place.geometry.location.lng();

    lat1 = place.geometry.location.lat();
    lng1 = place.geometry.location.lng();

    for (const component in componentForm) {
        document.getElementById('a1_' + component).value = "";
        document.getElementById('a1_' + component).disabled = false;
    }

    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    for (const component of place.address_components) {
        const addressType = component.types[0];

        if (componentForm[addressType]) {
            const val = component[componentForm[addressType]];
            document.getElementById('a1_' + addressType).value = val;
        }
    }
}

function fillInAddress2() {
    // Get the place details from the autocomplete object.
    const place = autocomplete2.getPlace();

    // console.log('ENTER 2: ');
    // console.log(place);
    // console.log('latitude: ' + place.geometry.location.lat());
    // console.log('longitude: ' + place.geometry.location.lng());

    document.getElementById('a2_latitude').value = place.geometry.location.lat();
    document.getElementById('a2_longitude').value = place.geometry.location.lng();

    lat2 = place.geometry.location.lat();
    lng2 = place.geometry.location.lng();

    for (const component in componentForm) {
        document.getElementById('a2_' + component).value = "";
        document.getElementById('a2_' + component).disabled = false;
    }

    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    for (const component of place.address_components) {
        const addressType = component.types[0];

        if (componentForm[addressType]) {
            const val = component[componentForm[addressType]];
            document.getElementById('a2_' + addressType).value = val;
        }
    }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
// function geolocate() {
//     if (navigator.geolocation) {
//         navigator.geolocation.getCurrentPosition((position) => {
//
//             console.log('CURRENT: ');
//             console.log(position.coords.latitude);
//             console.log(position.coords.longitude);
//
//             const geolocation = {
//                 lat: position.coords.latitude,
//                 lng: position.coords.longitude,
//             };
//             const circle = new google.maps.Circle({
//                 center: geolocation,
//                 radius: position.coords.accuracy,
//             });
//             autocomplete.setBounds(circle.getBounds());
//         });
//     }
// }

var lat1 = 0, lat2 = 0, lng1 = 0, lng2 = 0;
function tinhToan() {
    jQuery.ajax({
        url: gks.baseURL + '/tinh-khoang-cach',
        type: 'post',
        data: {
            lat1: lat1,
            lng1: lng1,
            lat2: lat2,
            lng2: lng2,
            _token: gks.tempTK,
        },
        success: function (response) {
            jQuery('#kctt').text(response.DATA);
        }
    });
}



