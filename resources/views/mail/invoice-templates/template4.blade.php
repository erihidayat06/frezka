<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('messages.invoice') }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .invoice {
      width: 190mm;
      height: auto;
      box-sizing: border-box;
    }

    .invoice-header,
    .invoice-footer {
      text-align: center;
    }

    .invoice-header h1 {
      margin: 0;
    }

    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .invoice-table th, .invoice-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .invoice-table th {
      background-color: #f2f2f2;
    }

    .total {
      margin-top: 20px;
      text-align: right;
    }

    .thank-you {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div class="invoice">
    <div class="invoice-header">
      <h1>{{ __('messages.invoice_template') }}</h1>
    </div>

    <div class="invoice-details">
      <p><strong>{{__('messages.invoice_number')}}</strong> {{__('messages.ORDER')}}{{$data['id']}}</p>
      <p><strong>{{__('messages.date')}}</strong>{{$data['booking_date']}}</p>
    </div>

    <table class="invoice-table">
      <thead>
        <tr>
          <th>{{__('messages.item_description')}}</th>
          <th>{{__('messages.quantity')}}</th>
          <th>{{__('messages.unit_price')}}</th>
          <th>{{__('messages.total')}}</th>
        </tr>
      </thead>
      @php
            $productPrice = 0;
          @endphp
     <tbody>
        @foreach($data['extra']['services'] as $key => $value)
        <tr>
          <td>{{$value['service_name']}}</td>
          <td>1</td>
          <td>{{$value['service_price']}}</td>
          <td>{{$value['service_price']}}</td>
        </tr>
        @endforeach


        @foreach($data['extra']['products'] as $key => $value)
        <tr>
        <td>{{$value['product_name']}}</td>
        <td>{{$value['product_qty']}}</td>

          @php
                $price = $value['product_price'];
                $delPrice = false;
                $discountType = $value['discount_type'];
                $discountValue = $value['discount_value'] . ($discountType == 'percent' ? '%' : '');
                if($price != $value['discounted_price']) {
                    $delPrice = $price;
                    $price = $value['discounted_price'];
                }
                $productPrice = $price * $value['product_qty'] +$productPrice
          @endphp

        <td>{{$price}}</td>
        <td>{{ $price * $value['product_qty'] }}</td>
        </tr>

      @endforeach
      </tbody>
    </table>

    <div class="total">
      <p><strong>{{__('messages.total')}}</strong>{{ \Currency::format($data['serviceAmount'] + $productPrice) }}

</p>
    </div>

    <div class="thank-you">
      <p>{{ setting('spacial_note') }}</p>
    </div>
  </div>

</body>
</html>
