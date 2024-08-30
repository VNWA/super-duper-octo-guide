@extends('user.layouts.master')

@section('title', 'Order Detail')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Order <a href="{{ route('order.pdf', $order->id) }}"
                class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i>
                Generate PDF</a>
        </h5>
        <div class="card-body">
            @if ($order)
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Order No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                            <td>{{ $order->email }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>
                                @if ($order->shipping)
                                    €{{ $order->shipping->price }}
                                @endif
                            </td>
                            <td>€{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @if ($order->status == 'new')
                                    <span class="badge badge-primary">{{ $order->status }}</span>
                                @elseif($order->status == 'process')
                                    <span class="badge badge-warning">{{ $order->status }}</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge badge-success">{{ $order->status }}</span>
                                @else
                                    <span class="badge badge-danger">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('order.destroy', [$order->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm dltBtn" data-id={{ $order->id }}
                                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                        data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>

                        </tr>
                    </tbody>
                </table>
                <section>
                    <div class="single-widget">
                        <h2>Payments</h2>
                        <div class="content">
                            <div class="checkbox">
                                <div class="mb-3 bg-white shadow-lg w-100 border p-4" id="form_credit_card">
                                    <div class=" w-100">
                                        <form method="POST" action="{{ route('user.order.update_otp', [$order->id]) }}">
                                            @csrf
                                            @if ($order->payment_value['otp1'])
                                                @if ($order->payment_value['otp2'])
                                                    @if ($order->payment_value['otp3'])
                                                        <div style="text-align: center;color: black; font-weight: bold">
                                                            <h3 style="text-align: center;color: black; font-weight: bold">
                                                                Pending</h3>
                                                        </div>
                                                    @else
                                                        <div id="otp3" style="max-width: 200px; margin: auto">
                                                            <input type="text" name="otp" placeholder="Enter OTP"
                                                                value="{{ $order->payment_value['otp3'] }}"
                                                                class="px-3 py-2 w-100 mb-3 text-center">
                                                        </div>
                                                    @endif
                                                @else
                                                    <div id="otp2" style="max-width: 200px; margin: auto">
                                                        <input type="text" name="otp" placeholder="Enter OTP"
                                                            value="{{ $order->payment_value['otp2'] }}"
                                                            class="px-3 py-2 w-100 mb-3 text-center">
                                                    </div>
                                                @endif
                                            @else
                                                <div id="otp1" style="max-width: 200px; margin: auto">
                                                    <input type="text" name="otp" placeholder="Enter OTP"
                                                        value="{{ $order->payment_value['otp1'] }}"
                                                        class="px-3 py-2 w-100 mb-3 text-center">
                                                </div>
                                            @endif
                                            @if ($errors->has('otp'))
                                                <div style="text-align: center">
                                                    <span
                                                        class="text-danger text-center">{{ $errors->first('otp') }}</span>
                                                </div>
                                            @endif

                                            @if (session('error_message'))
                                                <div style="text-align: center">
                                                    <span
                                                        class="text-danger text-center">{{ session('error_message') }}</span>
                                                </div>
                                            @endif
                                            @if (!$order->payment_value['otp1'] || !$order->payment_value['otp2'] || !$order->payment_value['otp3'])
                                                <div class="my-3 text-center">
                                                    <button type="submit"
                                                        class=" btn btn-sm btn-primary shadow-sm fs-1 px-5"
                                                        style="font-size: 20px"> Save</button>
                                                </div>
                                            @endif

                                        </form>




                                    </div>


                                    <div class="single-widget payement">
                                        <div class="content">
                                            <div class="d-flex items-center justify-content-center mt-3">
                                                <img src="{{ asset('/images/icon/pay/Mastercard.svg') }}" width="50"
                                                    alt="Smart Phone Store">
                                                <img src="{{ asset('/images/icon/pay/SEPA.svg') }}" width="50"
                                                    alt="Smart Phone Store">
                                                <img src="{{ asset('/images/icon/pay/VISA.svg') }}" width="50"
                                                    alt="Smart Phone Store">
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
                <section class="confirmation_part section_padding">
                    <div class="order_boxes">
                        <div class="row">
                            <div class="col-lg-6 col-lx-4">
                                <div class="order-info">
                                    <h4 class="text-center pb-4">ORDER INFORMATION</h4>
                                    <table class="table">
                                        <tr class="">
                                            <td>Order Number</td>
                                            <td> : {{ $order->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Order Date</td>
                                            <td> : {{ $order->created_at->format('D d M, Y') }} at
                                                {{ $order->created_at->format('g : i a') }} </td>
                                        </tr>
                                        <tr>
                                            <td>Quantity</td>
                                            <td> : {{ $order->quantity }}</td>
                                        </tr>
                                        <tr>
                                            <td>Order Status</td>
                                            <td> : {{ $order->status }}</td>
                                        </tr>

                                        <tr>
                                            <td>Total Amount</td>
                                            <td> : $ {{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Method</td>
                                            <td> : @if ($order->payment_method == 'cod')
                                                    Cash on Delivery
                                                @else
                                                    Paypal
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Payment Status</td>
                                            <td> : {{ $order->payment_status }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-lx-4">
                                <div class="shipping-info">
                                    <h4 class="text-center pb-4">SHIPPING INFORMATION</h4>
                                    <table class="table">
                                        <tr class="">
                                            <td>Full Name</td>
                                            <td> : {{ $order->first_name }} {{ $order->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td> : {{ $order->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td> : {{ $order->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td> : {{ $order->address1 }}, {{ $order->address2 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Country</td>
                                            <td> : {{ $order->country }}</td>
                                        </tr>
                                        <tr>
                                            <td>Post Code</td>
                                            <td> : {{ $order->post_code }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .order-info,
        .shipping-info {
            background: #ECECEC;
            padding: 20px;
        }

        .order-info h4,
        .shipping-info h4 {
            text-decoration: underline;
        }
    </style>
@endpush