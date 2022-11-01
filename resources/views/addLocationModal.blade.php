<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('gStore') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="location-input">Location Name</label>
                        <input type="text" name="name" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="lat">Latitude</label>
                        <input type="text" name="lat" id="lat1" class="form-control @error('lat') is-invalid @enderror "
                            placeholder="Latitude Here">
                    </div>
                    <div class="form-group">
                        <label for="lng">Longitude</label>
                        <input type="text" name="lng" id="lng1" class="form-control @error('lng') is-invalid @enderror "
                            placeholder="Longitude Here">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Save Location" class="form-control btn btn-info btn-block">
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

{{-- @push('js')
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
@endpush --}}
