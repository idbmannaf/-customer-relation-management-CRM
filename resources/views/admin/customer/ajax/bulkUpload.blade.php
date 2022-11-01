<div class="modal fade" id="bulkUpload">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               <h3>Bulk Upload</h3>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.importCustomer')}}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group">
                    <input type="file" name="file" >
                </div>
                <div class="form-group">
                    <input type="submit" value="Upload" class="btn btn-info">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>Please Folow this Format</p>
<img src="{{asset('img/employeeDemo.png')}}" alt="">
            </div>
        </div>
    </div>
</div>
