<script>
    $(document).ready(function() {

        // call_status Start
        $("#call_status3").click(function() {
            var that = $(this);
            if (that.is(':checked')) {
                $("#others_call_status").attr('required', true);
            } else {
                $("#others_call_status").attr('required', false);
            }
        })
        $("#call_status1").click(function() {
            var that = $(this);
            if (that.is(':checked')) {
                $("#others_call_status").attr('required', false);
            }

        })
        $("#call_status2").click(function() {
            var that = $(this);
            if (that.is(':checked')) {
                $("#others_call_status").attr('required', false);
            }
        })
        // call_status End






    })
</script>

<script>
    $(document).ready(function() {
        $('.service_product').select2({
            theme: 'bootstrap4',
            closeOnSelect: true,
            // minimumInputLength: 1,
            ajax: {
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    // alert(data[0].s);
                    var data = $.map(data, function(obj) {
                        obj.id = obj.id || obj.id;
                        return obj;
                    });
                    var data = $.map(data, function(obj) {
                        obj.text = obj.name + "(" + obj.model + ")";
                        return obj;
                    });
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                }
            },
        });
    });

    $(document).on('click', '.addServiceProduct', function(e) {
        e.preventDefault();
        var that = $(this);
        var productId = that.closest('.row').find('#service_product').val();
        if (productId == null) {
            that.closest('.row').find('#service_productError').text('Select Product');
            return;
        }
        that.closest('.row').find('#service_productError').text('');
        var url = that.attr('href');
        $.ajax({
            url: url,
            type: "GET",
            data: {
                product_id: productId
            },
            success: function(res) {

                if (res.success) {
                    $('.sericeProductShow').append(res.html);

                }
            }
        })
    })

    function productDescriptionupdate(e, type) {
        var that = $(e);
        var url = that.closest('tr').find('.productDescriptionupdate').val();
        var value = that.val();
        $.ajax({
            url: url,
            type: "GET",
            data: {
                value: value,
                type: type
            },
            success: function(res) {
                if (res.success) {

                }
            }
        })
    }
    $(document).on('click', '.serviceProductDeleteBtn', function(e) {
        e.preventDefault();
        var that = $(this);
        var url = that.attr('href');
        $.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                if (res.success) {
                    that.closest('tr').remove();

                }
            }
        })
    })
    $(document).on('click', '.addRequre', function(e) {
        e.preventDefault();

        var that = $(this);
        var problem = that.closest('tr').find('.problem').val();
        var product_id = that.closest('tr').find('.service_product').val();
        var quantity = that.closest('tr').find('.quantity').val();
        var unit = that.closest('tr').find('.unit').val();
        // Validation Start
        if (product_id == null || product_id == '') {
            that.closest('tr').find('.desError').text("Product Required");
            return;
        } else {
            that.closest('tr').find('.desError').text("");
        }
        if (quantity == null || quantity == '') {
            that.closest('tr').find('.qtyError').text("Quantity Required");
            return;
        } else {
            that.closest('tr').find('.qtyError').text("");
        }
        if (unit == null || unit == '') {
            that.closest('tr').find('.unitError').text("Unit Required");
            return;
        } else {
            that.closest('tr').find('.unitError').text("");
        }


        // Validation End
        var url = that.attr('href');
        $.ajax({
            url: url,
            type: "GET",
            data: {
                problem: problem,
                product_id: product_id,
                quantity: quantity,
                unit: unit
            },
            success: function(res) {

                if (res.success) {
                    that.closest('.showServiceRequirements').prepend(res.html);
                    that.closest('tr').find('.problem').val('');
                    that.closest('tr').find('.spear_part_cat').val('');
                    that.closest('tr').find('.quantity').val('');
                    that.closest('tr').find('.unit').val('');
                    that.closest('tr').find('.service_product').val('').change();
                    serialMaintain();
                    $('.service_product').select2({
                        theme: 'bootstrap4',
                        closeOnSelect: true,
                        // minimumInputLength: 1,
                        ajax: {
                            data: function(params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page
                                };
                            },
                            processResults: function(data, params) {
                                params.page = params.page || 1;
                                // alert(data[0].s);
                                var data = $.map(data, function(obj) {
                                    obj.id = obj.id || obj.id;
                                    return obj;
                                });
                                var data = $.map(data, function(obj) {
                                    obj.text = obj.name + "(" + obj.model + ")";
                                    return obj;
                                });
                                return {
                                    results: data,
                                    pagination: {
                                        more: (params.page * 30) < data.total_count
                                    }
                                };
                            }
                        },
                    });

                }

            }
        })

    })
    $(document).on('click', '.DeleteServiceReq', function(e) {
        e.preventDefault();
        var that = $(this);

        var url = that.attr('href');
        $.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                if (res.success) {
                    that.closest('tr').empty();
                    serialMaintain();
                }
            }
        })

    })

    function updateServiceRequire(e, type) {

        var that = $(e);
        var url = that.closest('tr').find('.updateServiceRequirement').val();
        var value = that.val();
        $.ajax({
            url: url,
            type: "GET",
            data: {
                value: value,
                type: type
            },
            success: function(res) {

            }
        })
    }

    function serialMaintain() {
        var serial = $('.serialO');
        serial.map((index, element) => {
            $(element).text(index + 1)
        })
    }
    serialMaintain();

    $("input[id='file']").on('change', function(e) {
        e.preventDefault();
        var form = $(e.target).closest("form");
        var formElement = form.get(0)
        var formData = new FormData(formElement);
        var url = "{{ route('global.fileStore', ['type' => 'visit']) }}";
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            contentType: false,
            processData: false,
            cache: false,
            _token: $('meta[name="csrf-token"]').attr('content'),
            async: false,
            data: formData,
            success: function(res) {
                if (res.success) {
                    $('.images').append(res.html);
                    $('#file').val('')
                    serial_maintains()
                }
            }
        })
    })
    $(document).on('click', '.deleteAttachment', function(e) {
        e.preventDefault();
        var that = $(this);
        var url = that.attr('href');
        if (confirm('Are you sure? You want to delete this file?')) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(res) {
                    if (res.success) {
                        that.closest('.attachment').remove();
                        serial_maintains()
                        // location.reload();
                    }
                }
            });
        }
    })

    function serial_maintains() {
        var seralArray = $('.serial');
        seralArray.map((value, index) => {
            $(index).text(value + 1 + ". ");
        })
    }
    serial_maintains();


    // $("input[id='photGallery']").on('change', function(e) {
    //             e.preventDefault();
    //             var form = $(e.target).closest("form");
    //             var formElement = form.get(0)
    //             console.log(formElement);
    //             var formData = new FormData(formElement);
    //             console.log(formData);
    //             $.ajax({
    //                     url: url,
    //                     type: 'POST',
    //                     dataType: 'JSON',
    //                     contentType: false,
    //                     processData: false,
    //                     cache: false,
    //                     _token: $('meta[name="csrf-token"]').attr('content'),
    //                     async: false,
    //                     data: formData,
    //                 })
    //                 .done(function(data) {
    //                         console.log(data.view);
    //                         $("#imagePreview").empty().html(data.view);
    //                         $('.imageDelete').on('click', function() {

    //                         })
    //                     }
    // )}

    $(document).on('change', '#category', function() {
        var that = $(this);
        var category = that.val();
        var url = that.attr('data-url')
        $.ajax({
            url: url,
            method: "GET",
            data: {
                category_id: category
            },
            success: function(res) {
                if (res.success) {
                    $('.showProduct').html(res.html)
                    $('.service_product').select2({
                        theme: 'bootstrap4',
                        closeOnSelect: true,
                        // minimumInputLength: 1,
                        ajax: {
                            data: function(params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page
                                };
                            },
                            processResults: function(data, params) {
                                params.page = params.page || 1;
                                // alert(data[0].s);
                                var data = $.map(data, function(obj) {
                                    obj.id = obj.id || obj.id;
                                    return obj;
                                });
                                var data = $.map(data, function(obj) {
                                    obj.text = obj.name + "(" + obj.model + ")";
                                    return obj;
                                });
                                return {
                                    results: data,
                                    pagination: {
                                        more: (params.page * 30) < data.total_count
                                    }
                                };
                            }
                        },
                    });

                }
            }
        })
    })
</script>
