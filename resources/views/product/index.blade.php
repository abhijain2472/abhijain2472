@extends('layout')

@section('page-title', "List Products")
@section('page-heading', "List Products")
@section('page-description', "Here you can view/delete products.")

@section('active-main-product', 'active')
@section('active-main-product-div', 'show')
@section('active-main-product-list', 'active')

@section('admin-card-header')
    <div class="row justify-content-between w-100 align-items-center">
        <div class="col-auto">
            <span>All Products</span>
        </div>

        <div class="col-auto p-0">
            <button class="btn btn-success navigate" data-src="/add-product">Add Product</button>
            <button class="btn btn-secondary navigate" data-src="/import-product">Import Product</button>
        </div>
    </div>
@endsection

@section('admin-card-body')
<input type="hidden" id="csrf" value="{{ csrf_token() }}">
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif

    @if (Session::get('fail'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{Session::get('fail')}}
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Product Name</th>
                    <th data-sort="false">Image</th>
                    <th>Category Name</th>
                    <th>Product Price</th>
                    <th>Product Discount</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Status</th>
                    <th data-sort="false"></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 1;
                @endphp
                @foreach ($products as $key => $item)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{!! $item['product_image'] !!}</td>
                        <td>{{ $item['category'][0]['name'] }}</td>
                        <td>{{ $item['product_price'] }}</td>
                        <td>{{ $item['discount'] }}</td>
                        <td>{{ $item['created_at'] }}</td>
                        <td>{{ $item['updated_at'] }}</td>
                        <td>
                            @php
                                $checked = "";
                            @endphp
                            @if ($item['status'] == "1")
                                @php
                                    $checked = "checked";
                                @endphp
                            @endif
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input ajax-status' id='status_{{ $item['product_id'] }}' value="on" {{ $checked }}>
                                <label class='custom-control-label' for='status_{{ $item['product_id'] }}'></label>
                            </div>
                        </td>
                        <td>
                            <a href='/add-product/{{ $item['product_id'] }}' title='Edit Product'>
                                <i class='far fa-edit'></i>
                            </a>
                            <a href='javascript:return false;' onclick='getWarning({{ $item['product_id'] }})' data-toggle='modal' data-target='#deleteModal' class='text-danger' title='Delete Product'>
                                <i class='far fa-trash-alt'></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Warning</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="loader text-center"></div>
                    <div class="content">
                        <input type="hidden" id="delProductId">
                        Are you sure to want to delete product <strong id="delProductName"></strong> ?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="deleteButton">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('internal-js')
<script>
    function getWarning(id) {
        $.ajax({
            url: '/get-product/'+id,
            type: 'GET',
            beforeSend: function() {
                $(".loader").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
                $(".content").hide();
            },
            success: function(data) {
                data = JSON.parse(data);
                console.log(data)
                $("#delProductId").val(data['product_id']);
                $("#delProductName").text(data['product_name'] + " ( "+data['category'][0]['name']+" )");
            },
            complete: function () {
                $(".loader").html('');
                $(".content").show();
            }
        });
    }

    $(document).ready(function() {
        $("#deleteButton").click(function() {
            window.location.href = "/delete-product/"+$("#delProductId").val()
        });

        $(document).on("change", ".ajax-status", function() {
            var status = $(this).prop("checked");
            var id = $(this).attr("id").split("_")[1]
            status = (status) ? "1" : "0";
            $.ajax({
                url: '/product/change-status/'+id,
                type: 'POST',
                data: {status: status, _token: $("#csrf").val()},
                beforeSend: function() {
                    $(".card-body .alert").remove();
                },
                success: function(data) {
                    if(data == "success") {
                        var id = successMessage("Status is updated successfully.");
                        setTimeout(() => {
                            $("#"+id+" .close").click();
                        }, 3000);
                    } else {
                        var id = failMessage("Status is updated successfully.");
                        setTimeout(() => {
                            $("#"+id+" .close").click();
                        }, 8000);
                    }
                }
            });
        });
    });
</script>
@endsection
