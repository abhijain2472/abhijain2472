@extends('layout')

@section('page-title', "List Customers")
@section('page-heading', "List Customers")
@section('page-description', "Here you can view/delete customers.")

@section('active-main-customer', 'active')
@section('active-main-customer-div', 'show')
@section('active-main-customer-list', 'active')

@section('admin-card-header')
    <div class="row justify-content-between w-100 align-items-center">
        <div class="col-auto">
            <span>All Customers</span>
        </div>

        <div class="col-auto p-0">
            <button class="btn btn-success navigate" data-src="/add-customer">Add Customers</button>
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
        <table class="table table-bordered ajax" id="dataTable" data-load="/get-customer-list-ajax">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Customer Details</th>
                    <th data-sort="false">Image</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Status</th>
                    <th data-sort="false"></th>
                </tr>
            </thead>
            {{-- <tbody>
                @php
                    $count = 1;
                @endphp
                @foreach ($categories as $key => $item)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <img src="{{ $item->icon }}" alt="{{ $item->name }}" width="70" height="70">
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            @php
                                $checked = "";
                            @endphp
                            @if ($item->status == "1")
                                @php
                                    $checked = "checked";
                                @endphp
                            @endif
                            <div class='custom-control custom-switch'>
                                <input type='checkbox' class='custom-control-input ajax-status' id='status_{{ $item->category_id }}' value="on" {{ $checked }}>
                                <label class='custom-control-label' for='status_{{ $item->category_id }}'></label>
                            </div>
                        </td>
                        <td>
                            <a href='add-category/{{ $item->category_id }}' title='Edit Category'>
                                <i class='far fa-edit'></i>
                            </a>
                            <a href='javascript:return false;' onclick='getWarning({{ $item->category_id }})' data-toggle='modal' data-target='#deleteModal' class='text-danger' title='Delete Category'>
                                <i class='far fa-trash-alt'></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody> --}}
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
                        <input type="hidden" id="delUserId">
                        Are you sure to want to delete customer <strong id="delUserName"></strong> ?
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
            url: '/get-customer/'+id,
            type: 'GET',
            beforeSend: function() {
                $(".loader").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
                $(".content").hide();
            },
            success: function(data) {
                data = JSON.parse(data);
                $("#delUserId").val(data['id']);
                $("#delUserName").text(data['first_name'] + " " + data['last_name'] + "( "+data['email']+" )");
            },
            complete: function () {
                $(".loader").html('');
                $(".content").show();
            }
        });
    }

    $(document).ready(function() {
        $("#deleteButton").click(function() {
            window.location.href = "/delete-customer/"+$("#delUserId").val()
        });

        $(document).on("change", ".ajax-status", function() {
            var status = $(this).prop("checked");
            var id = $(this).attr("id").split("_")[1];
            status = (status) ? "1" : "0";
            $.ajax({
                url: '/customer/change-status/'+id,
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
