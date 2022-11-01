@auth
    @if (auth()->user()->track)
        <script type="text/javascript">
            var geo = navigator
                .geolocation;
        </script>
        <script>
            $(function() {
                (function latest() {
                    var latApp = getCookieData('lat');
                    var longApp = getCookieData('long');

                    $latElm = $('#lat1');
                    $longElm = $('#lng1');

                    if (latApp == false || longApp == false) {
                        if (geo) {
                            geo.watchPosition(updateLocation);
                        } else {
                            alert("Oops, Geolocation API is not supported");
                        }
                    } else {
                        $("#lati").val(latApp);
                        $("#long").val(longApp);
                        $latElm.val(latApp);
                        $longElm.val(longApp);
                    }

                    function updateLocation(position) {
                        $latElm.val(position.coords.latitude);
                        $longElm.val(position.coords.longitude);

                        $("#lati").val(position.coords.latitude);
                        $("#long").val(position.coords.longitude);
                    }


                    var lat = $("#lati").val();
                    var long = $("#long").val();
                    var url = $(".user-location-set").attr('data-url');
                    var urls = url + '?lat=' + lat + '&lng=' + long;

                    $.ajax({
                            url: urls,
                            type: 'GET',
                            dataType: 'json',
                            cache: false,
                        })
                        .done(function(data) {
                            // $(".wall-right-suggested").empty().append(data);
                        })
                        .fail(function() {
                            // console.log("error");
                        })
                        .then(function() {
                            setTimeout(latest, 8000);
                        });
                })
                ();

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
