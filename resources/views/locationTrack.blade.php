@auth
    @if (auth()->user()->track)
        {{-- <button type="button" class="btn btn-primary local">
            Launch demo modal
        </button> --}}

        <table class="table table-dark" id="cookie_display">


        </table>

        <script>
            $(".local").click(function() {
                let c = document.cookie.split(";").reduce((ac, cv, i) => Object.assign(ac, {
                    [cv.split('=')[0]]: cv.split('=')[1]
                }), {});
                let cd = $('#cookie_display');

                cd.html("");

                for (let cookieKey in c) {
                    let newRow = `
                          <tr>
                          <td>
                            ${cookieKey}
                          </td>
                          <td>
                          ${c[cookieKey]}
                          </td>
                          </tr>
                  `
                    cd.append(newRow);
                    // cd.append(cookieKey+" : " + c[cookieKey]);
                    // c[cookieKey]
                }
            })
        </script>


        <script type="text/javascript">
            var geo = navigator
                .geolocation;
        </script>


        <script>
            $(function() {
                (function latest() {
                    var latApp = getCookieData('latitude');
                    var longApp = getCookieData('longitude');
                    var isApp = 0;

                    // alert(longApp);

                    $latElm = $('#lat1');
                    $longElm = $('#lng1');

                    if (latApp == false || longApp == false) {
                        if (geo) {
                            geo.watchPosition(updateLocation);
                        } else {
                            alert("Oops, Geolocation API is not supported");
                        }
                    } else {

                        isApp = 1;

                        $("#lati").val(latApp);
                        $("#long").val(longApp);
                        $latElm.val(latApp);
                        $longElm.val(longApp);
                    }

                    function updateLocation(position) {

                        isApp = 0;
                        $latElm.val(position.coords.latitude);
                        $longElm.val(position.coords.longitude);

                        $("#lati").val(position.coords.latitude);
                        $("#long").val(position.coords.longitude);
                    }


                    var lat = $("#lati").val();
                    var long = $("#long").val();


                    var url = $(".user-location-set").attr('data-url');
                    var urls = url + '?lat=' + lat + '&lng=' + long + '&isApp=' + isApp;

                    // alert(urls);

                    $.ajax({
                            url: urls,
                            type: 'GET',
                            dataType: 'json',
                            cache: false,
                        })
                        .done(function(data) {
                            // $(".wall-right-suggested").empty().append(data);
                            // alert(data.isApp);

                        })
                        .fail(function() {
                            // console.log("error");
                        })
                        .then(function() {
                            setTimeout(latest, 4000);
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
