@auth
    @if (auth()->user()->track)
        <script type="text/javascript">
            $(document).ready(function() {
                var geo = navigator.geolocation;

                $latElm = $('#lat1');
                $longElm = $('#lng1');

                var options = {
                    enableHighAccuracy: false,
                    timeout: 5000,
                    maximumAge: 0
                }

                (function runOnReady(updateLocation) {
                    var lat = getCookieData('lat')
                    var long = getCookieData('long')
                    if (lat == false || long == false) {
                        if (geo) {
                            geo.watchPosition(updateLocation, showErrors, options);
                        } else {
                            alert("Oops, Geolocation API is not supported");
                        }
                    }

                }(updateLocation));

                function updateLocation(position) {
                    $latElm.val(position.coords.latitude);
                    $longElm.val(position.coords.longitude);
                    $("#lati").val(position.coords.latitude);
                    $("#long").val(position.coords.longitude);


                    //Ajax request


                    // (function latest() {

                    //     var lat = $("#lati").val();
                    //     var long = $("#long").val();
                    //     var dist = 0;
                    //     var url = $(".user-location-set").attr('data-url');
                    //     var urls = url + '?lat=' + lat + '&lng=' + lng;

                    //     $.ajax({
                    //             urls: urls,
                    //             type: 'GET',
                    //             dataType: 'json',
                    //             cache: false,
                    //         })
                    //         .done(function(data) {

                    //         })
                    //         .fail(function() {

                    //         })
                    //         .then(function() {
                    //             setTimeout(latest, 8000);
                    //         });
                    // })();

                    setInterval(function() {
                        var lat = $("#lati").val();
                        var long = $("#long").val();
                        var dist = 0;
                        var url = $(".user-location-set").attr('data-url');
                        var urls = url + '?lat=' + lat + '&lng=' + lng;
                        $.ajax({
                            url: urls,
                            type: "get",
                            success: function(response) {
                                if (response.success) {
                                    // $("#lati").val(response.lat);
                                    // $("#long").val(response.lng);
                                }
                            }
                        });

                    }, 6000);


                }

                function showErrors(err) {
                    //    alert('ERROR(' + err.code + '): ' + err.message+'Please Allow Location and reload this page');
                }

                function getCookieData(name) {
                    var patrn = new RegExp("^" + name + "=(.*?);"),
                        patr2 = new RegExp(" " + name + "=(.*?);");
                    if (match = (document.cookie.match(patrn) || document.cookie.match(patr2))) {
                        return match[1];
                    } else {
                        return false;
                    }
                }


            });
        </script>
    @endif
@endauth
