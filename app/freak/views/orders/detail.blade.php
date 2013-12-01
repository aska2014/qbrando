@extends('freak::master.layout1')

@section('content')
<div class="row-fluid">
    <div class="span12 widget">
        <div class="widget-header">
            <span class="title">Order information</span>
            <div class="toolbar">
                <div class="btn-group">
                    <a href="{{ freakUrl($element->getUri('delete/'.$id)) }}" class="btn"><i class="icos-cross"></i> Delete</a>
                </div>
            </div>
        </div>
        <div class="widget-content table-container">

            @if($user = $order->userInfo)
            <table class="table table-striped table-detail-view">
                <thead>
                <tr>
                    <th colspan="2"><li class="icol-doc-text-image"></li> Order information</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Products</th>
                    <td>
                        @foreach($order->products as $product)
                        <strong>{{ $product->pivot->qty }}</strong>&nbsp&nbsp&nbsp * &nbsp&nbsp&nbsp
                        <a href="{{ freakUrl('element/product/show/' . $product->id) }}">{{ $product->title }}</a><br/>
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>Price after offer</th>
                    <td><strong>{{ $order->getOfferPrice() }} Q.R</strong></td>
                </tr>
                <tr>
                    <th>Actual total price</th>
                    <td><strong style="text-decoration: line-through">{{ $order->getTotal() }} Q.R</strong></td>
                </tr>
                </tbody>
            </table>
            <table class="table table-striped table-detail-view">
                <thead>
                <tr>
                    <th colspan="2"><li class="icol-doc-text-image"></li> User information</th>
                </tr>
                </thead>
                <tr>
                    <th>User name</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>User number</th>
                    <td>{{ $user->contact_number }}</td>
                </tr>
                <tr>
                    <th>User delivery location</th>
                    <td>{{ $user->delivery_location }}</td>
                </tr>
                @if($user->contact_email)
                <tr>
                    <th>User email</th>
                    <td>{{ $user->contact_email }}</td>
                </tr>
                @endif
                <tr>
                    <th>Created at</th>
                    <td>{{ date('d F, H:i', strtotime($order->created_at)) }}</td>
                </tr>
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@stop
