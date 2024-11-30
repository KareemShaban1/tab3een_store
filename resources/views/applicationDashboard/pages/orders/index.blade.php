@extends('layouts.app')
@section('title', 'Order')

@section('content')

@php
    $statuses = ['all', 'pending', 'processing'];
@endphp

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.orders')
        <small>@lang('lang_v1.manage_your_orders')</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary'])
    @can('lang_v1.create')
        @slot('tool')
        <div class="box-tools">
        </div>
        @component('components.filters', ['title' => __('report.filters')])
        <div class="row">
            <div class="col-md-3">
                <input type="date" id="start_date" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" id="end_date" class="form-control" placeholder="End Date">
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <!-- <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span> -->
                        {!! Form::select('status', [
            'all' => __('All'),
            'pending' => __('Pending'),
            'processing' => __('Processing'),
            'shipped' => __('Shipped'),
            'completed' => __('Completed'),
            'cancelled' => __('Cancelled')
        ], 'all', [
            'class' => 'form-control',
            'id' => 'status',
            'placeholder' => __('messages.please_select'),
            'required'
        ]) !!}

                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" id="filter_date">Filter</button>
                <button class="btn btn-primary" id="clear_date">Clear</button>
            </div>
        </div>
        @endcomponent

        @endslot
    @endcan
    @can('lang_v1.view')
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="orders_table">
                <thead>
                    <tr>
                        <th>@lang('lang_v1.id')</th>
                        <th>@lang('lang_v1.order_type')</th>
                        <th>@lang('lang_v1.number')</th>
                        <th>@lang('lang_v1.client')</th>
                        <th>@lang('lang_v1.payment_method')</th>
                        <th>@lang('lang_v1.order_status')</th>
                        <th>@lang('lang_v1.payment_status')</th>
                        <th>@lang('lang_v1.shipping_cost')</th>
                        <th>@lang('lang_v1.sub_total')</th>
                        <th>@lang('lang_v1.total')</th>
                        <th>@lang('lang_v1.order_date_time')</th>
                        <th>@lang('lang_v1.assign_delivery')</th>
                        <th>@lang('lang_v1.actions')</th>

                    </tr>
                </thead>
            </table>
        </div>
    @endcan
    @endcomponent

    <div class="modal fade orders_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <!-- Delivery Assignment Modal -->
    @include('applicationDashboard.pages.orders.assignDeliveryModal')

    <!-- Order Information Modal -->
    @include('applicationDashboard.pages.orders.orderInformationModal')

    @include('applicationDashboard.pages.orders.refundOrderModal')


</section>
<!-- /.content -->

@stop
@section('javascript')
<script>
    $('#filter_date').click(function () {
        orders_table.ajax.reload(); // Reload DataTable with the new date filters
    });

    $('#clear_date').click(function () {
        $('#start_date').val('');
        $('#end_date').val('');
        orders_table.ajax.reload();
    });


    //Orders table
    var orders_table = $('#orders_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ action("ApplicationDashboard\OrderController@index") }}',
            data: function (d) {
                d.status = $('#status').val();
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        columnDefs: [
            {
                targets: 2,
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'order_type', name: 'order_type' },

            { data: 'number', name: 'number' },
            { data: 'client_contact_name', name: 'client_contact_name' }, // Ensure this matches the added column name
            { data: 'payment_method', name: 'payment_method' },
            {
                data: 'order_status', name: 'order_status', render: function (data, type, row) {
                    let badgeClass;
                    switch (data) {
                        case 'pending': badgeClass = 'badge btn-warning'; break;
                        case 'processing': badgeClass = 'badge btn-info'; break;
                        case 'shipped': badgeClass = 'badge btn-primary'; break;
                        case 'completed': badgeClass = 'badge btn-success'; break;
                        case 'cancelled': badgeClass = 'badge btn-danger'; break;
                        default: badgeClass = 'badge badge-secondary'; // For any other statuses
                    }

                    return `
                    <span class="${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>
                    
            <select class="form-control change-order-status" data-order-id="${row.id}">
                <option value="pending" ${data === 'pending' ? 'selected' : ''}>Pending</option>
                <option value="processing" ${data === 'processing' ? 'selected' : ''}>Processing</option>
                <option value="shipped" ${data === 'shipped' ? 'selected' : ''}>Shipped</option>
                <option value="completed" ${data === 'completed' ? 'selected' : ''}>Completed</option>
                <option value="cancelled" ${data === 'cancelled' ? 'selected' : ''}>Canceled</option>
            </select>`;
                }
            },
            {
                data: 'payment_status', name: 'payment_status', render: function (data, type, row) {
                    return `
            <select class="form-control change-payment-status" data-order-id="${row.id}">
                <option value="pending" ${data === 'pending' ? 'selected' : ''}>Pending</option>
                <option value="paid" ${data === 'paid' ? 'selected' : ''}>Paid</option>
                <option value="failed" ${data === 'failed' ? 'selected' : ''}>Failed</option>
            </select>`;
                }
            },
            { data: 'shipping_cost', name: 'shipping_cost' },
            { data: 'sub_total', name: 'sub_total' },
            { data: 'total', name: 'total' },
            {
                data: 'created_at',
                name: 'created_at',
                render: function (data) {
                    // Format the date using JavaScript
                    if (data) {
                        const date = new Date(data);
                        return date.toLocaleString(); // Adjust format as needed
                    }
                    return '';
                }
            },
            {
                data: 'order_status',
                name: 'order_status',
                render: function (data, type, row) {
                    // Case 1: If the order status is 'processing' and has no delivery assigned
                    if (data === 'processing' && row.has_delivery === false) {
                        return `<button class="btn btn-primary assign-delivery-btn" 
                    data-order-id="${row.id}" 
                    data-contact-name="${row.client_contact_name
                            } ">
                    @lang('lang_v1.assign_delivery')
                </button > `;
                    }
                    if (row.has_delivery === true) {
                        return `<span class="badge badge-success">
                        @lang('lang_v1.delivery_assigned')
                    </span>`;
                    }

                    return '';
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'id',
                name: 'id',
                render: function (data, type, row) {
                    // Generate the "View Order Info" button
                    let buttons = `<button class="btn btn-info view-order-info-btn" data-order-id="${row.id}">
                          @lang('lang_v1.view_order_info')
                       </button>`;

                    // Conditionally add the "Refund Order" button
                    if (row.order_status === 'completed') {
                        buttons += `<button class="btn btn-warning refund-order-btn" data-order-id="${data}">
                            @lang('lang_v1.refund_order')
                        </button>`;
                    }

                    return buttons;
                },
                orderable: false,
                searchable: false
            }



        ],

        fnDrawCallback: function (oSettings) {
            __currency_convert_recursively($('#orders_table'));
        },
    });


    $(document).on('change', '.change-order-status', function () {
        var orderId = $(this).data('order-id');
        var status = $(this).val();

        $.ajax({
            url: `{{ action("ApplicationDashboard\OrderController@changeOrderStatus", ['orderId' => ':orderId']) }}`.replace(':orderId', orderId), // Replacing the placeholder with the actual orderId
            type: 'POST',
            data: {
                order_status: status,
                _token: '{{ csrf_token() }}' // CSRF token for security
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    orders_table.ajax.reload(); // Reload DataTable to reflect the updated status
                } else {
                    alert('Failed to update order status.');
                }
            },
            error: function (xhr) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });

    $(document).on('change', '.change-payment-status', function () {
        var orderId = $(this).data('order-id');
        var status = $(this).val();

        $.ajax({
            url: `{{ action("ApplicationDashboard\OrderController@changePaymentStatus", ['orderId' => ':orderId']) }}`.replace(':orderId', orderId), // Replacing the placeholder with the actual orderId
            type: 'POST',
            data: {
                payment_status: status,
                _token: '{{ csrf_token() }}' // CSRF token for security
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    orders_table.ajax.reload(); // Reload DataTable to reflect the updated status
                } else {
                    alert('Failed to update payment status.');
                }
            },
            error: function (xhr) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });


    $(document).on('click', '.assign-delivery-btn', function () {
        var orderId = $(this).data('order-id');
        var contactName = $(this).data('contact-name'); // Get the contact name

        $('#order_id').val(orderId);

        // Set the contact name in the modal
        $('#contact_name_display').text(contactName); // Assume #contact_name_display is the placeholder for contact name

        // Fetch available deliveries
        $.ajax({
            url: `{{ action("ApplicationDashboard\DeliveryController@getAvailableDeliveries" , ['orderId' => ':orderId']) }}`.replace(':orderId', orderId),
            type: 'GET',
            success: function (response) {
                if (response.success) {
                    var deliveryOptions = response.deliveries.map(delivery => {
                        return `<option value="${delivery.id}">${delivery.name}</option>`;
                    }).join('');
                    $('#delivery_id').html(deliveryOptions);
                    $('#assignDeliveryModal').modal('show');
                } else {
                    alert('Failed to fetch deliveries.');
                }
            },
            error: function () {
                alert('An error occurred while fetching deliveries.');
            }
        });
    });


    // Event listener for saving the delivery assignment
    $('#saveDeliveryAssignment').click(function () {
        var formData = $('#assignDeliveryForm').serialize();

        $.ajax({
            url: '{{ action("ApplicationDashboard\DeliveryController@assignDelivery") }}',
            type: 'POST',
            data: formData + '&_token={{ csrf_token() }}',
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    orders_table.ajax.reload();
                    $('#assignDeliveryModal').modal('hide');
                } else {
                    alert('Failed to assign delivery.');
                }
            },
            error: function () {
                alert('An error occurred while assigning delivery.');
            }
        });
    });

    // Event listener for the 'View Order Info' button
    $(document).on('click', '.view-order-info-btn', function () {
        var orderId = $(this).data('order-id'); // Get the order ID

        // Fetch the order details
        $.ajax({
            url: `{{ action("ApplicationDashboard\OrderController@getOrderDetails", ['orderId' => ':orderId']) }}`.replace(':orderId', orderId),
            type: 'GET',
            success: function (response) {
                if (response.success) {
                    // Populate the modal with the order details
                    $('#view_order_id').val(response.order.id);
                    $('#order_number').text(response.order.number);
                    $('#business_location').text(response.order.business_location.name);
                    $('#client_name').text(response.order.client.contact.name);
                    $('#payment_method').text(response.order.payment_method);
                    $('#shipping_cost').text(response.order.shipping_cost);
                    $('#sub_total').text(response.order.sub_total);
                    $('#total').text(response.order.total);
                    $('#order_status').text(response.order.order_status);
                    $('#payment_status').text(response.order.payment_status);

                    // Populate the order items
                    const itemsTable = $('#order_items_table tbody');
                    itemsTable.empty(); // Clear existing rows

                    response.order.order_items.forEach(item => {
                        const row = `
                        <tr>
                            <td><img src="${item.product.image_url}" alt="${item.product.name}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                            <td>${item.product.name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.price}</td>
                            <td>${item.sub_total}</td>
                        </tr>
                    `;
                        itemsTable.append(row);
                    });

                    // Show the modal
                    $('#viewOrderInfoModal').modal('show');
                } else {
                    alert('Failed to fetch order details.');
                }
            },
            error: function () {
                alert('An error occurred while fetching the order details.');
            }
        });
    });

    // Show refund modal
    $(document).on('click', '.refund-order-btn', function () {
    var orderId = $(this).data('order-id');

    // Populate hidden order ID field in the refund modal
    $('#refund_order_id').val(orderId);

    // Fetch the order details
    $.ajax({
        url: `{{ action("ApplicationDashboard\OrderController@getOrderDetails", ['orderId' => ':orderId']) }}`.replace(':orderId', orderId),
        type: 'GET',
        success: function (response) {
            if (response.success) {
                const itemsTable = $('#order_items_table tbody');
                itemsTable.empty(); // Clear existing rows

                response.order.order_items.forEach(item => {
                    const row = `
                    <tr>
                        <td>
                            <img src="${item.product.image_url}" alt="${item.product.name}" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>Name: ${item.product.name} / quantity: ${item.quantity}</td>
                        <td>
                            <textarea name="refund_reason_${item.id}" class="form-control refund-reason" rows="2" data-item-id="${item.id}"></textarea>
                        </td>
                        <td>
                            <div class="input-group">
                                <button type="button" class="btn btn-secondary decrement-btn" data-item-id="${item.id}">-</button>
                                <input type="text" name="refund_amount_${item.id}" class="form-control refund-amount" data-item-id="${item.id}" value="0" readonly>
                                <button type="button" class="btn btn-secondary increment-btn" data-item-id="${item.id}">+</button>
                            </div>
                        </td>
                        <td>
                            <select name="refund_status_${item.id}" class="form-control refund-status" data-item-id="${item.id}">
                                <option value="requested">@lang('lang_v1.Requested')</option>
                                <option value="processed">@lang('lang_v1.Processed')</option>
                                <option value="approved">@lang('lang_v1.Approved')</option>
                                <option value="rejected">@lang('lang_v1.Rejected')</option>
                            </select>
                        </td>
                        <td>
                            <textarea name="admin_response_${item.id}" class="form-control refund-admin-response" rows="2" data-item-id="${item.id}"></textarea>
                        </td>
                    </tr>
                    `;
                    // Append the row to the table
                    $('#order_items_table tbody').append(row);

                    // Dynamically set the max value for refund amount based on remaining quantity
                    $(`input[name="refund_amount_${item.id}"]`).attr('max', item.remaining_quantity);
                });

                // Show the modal
                $('#refundOrderModal').modal('show');
            } else {
                alert('Failed to fetch order details.');
            }
        },
        error: function () {
            alert('An error occurred while fetching the order details.');
        }
    });
});

// Event handler for incrementing refund amount
$(document).on('click', '.increment-btn', function () {
    var itemId = $(this).data('item-id');
    var $input = $(`input[name="refund_amount_${itemId}"]`);
    var currentValue = parseInt($input.val(), 10) || 0;
    var maxQuantity = parseInt($input.attr('max'), 10);

    if (currentValue < maxQuantity) {
        $input.val(currentValue + 1);
    }
});

// Event handler for decrementing refund amount
$(document).on('click', '.decrement-btn', function () {
    var itemId = $(this).data('item-id');
    var $input = $(`input[name="refund_amount_${itemId}"]`);
    var currentValue = parseInt($input.val(), 10) || 0;

    if (currentValue > 0) {
        $input.val(currentValue - 1);
    }
});


    $('#saveRefund').click(function () {
        const items = [];

        $('#order_items_table tbody tr').each(function () {
            const itemId = $(this).find('.refund-reason').data('item-id');
        const reason = $(this).find('.refund-reason').val().trim();
        const amount = $(this).find('.refund-amount').val().trim();  // Ensure to trim any extra spaces
        const status = $(this).find('.refund-status').val();
        const adminResponse = $(this).find('.refund-admin-response').val().trim();

        // Skip rows where any required data is empty
        if (itemId && reason && amount && status && adminResponse) {
            items.push({
                id: itemId,
                refund_reason: reason,
                refund_amount: amount,
                refund_status: status,
                refund_admin_response: adminResponse,
            });
        }
        });

        if (items.length === 0) {
            alert('Please fill in refund details for at least one item.');
            return;
        }

        console.log('Final Items Array:', items);

        $.ajax({
            url: '{{ action("ApplicationDashboard\OrderRefundController@store") }}',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                _token: '{{ csrf_token() }}',
                order_id: $('#refund_order_id').val(),
                items: items,
            }),
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#refundOrderModal').modal('hide');
                    // Reload DataTable or update UI
                } else {
                    alert('Failed to process refund.');
                }
            },
            error: function () {
                alert('An error occurred while processing the refund.');
            }
        });
    });




</script>
@endsection