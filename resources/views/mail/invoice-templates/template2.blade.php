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

    h1, h2, h3, h4, h5, h6 {
      color: #000000;
    }

    p {
      margin: 0 0 8px;
    }

    .invoice {
      width: 190mm;
      height: auto;
      box-sizing: border-box;
    }

    .invoice-header {
      text-align: right;
    }

    .invoice-header h1 {
      margin: 0 0 10px;
    }

    .invoice-details {
      text-align: right;
    }
    .invoice-footer {
      text-align: center;
    }

    .invoice-logo img {
      height: 40px;
    }

    .invoice-logo-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 0 0 20px;
      padding: 0 0 20px;
      border-bottom: 1px solid #f1f1f1;
    }
    .invoice-detail-part {
        display: flex;
        justify-content: space-between;
        margin: 16px 0;
    }



    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .invoice-table th, .invoice-table td {
      border: 1px solid #f1f1f1;
      padding: 16px 16px;
      text-align: left;
      font-size: 14px;
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
      border-top: 1px solid #f1f1f1;
      border-bottom: 1px solid #f1f1f1;
      padding: 16px;
      text-align: center;
    }
    .thank-you p {
      margin: 0;
    }
    .invoice-customer p {
      margin: 0 0 10px;
    }
    .invoice-customer h3,
    .invoice-billing h3 {
      margin-top: 0;
      margin-bottom: 8px;
    }

    strong {
      color: #000000;
    }

    table th {
      color: #000000;
    }

    table.invoice-table tr th:last-child,
    table.invoice-table tr td:last-child {
        text-align: right;
    }

    .invoice-payment {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 20px;
    }

    .invoice-pay-info h3 {
      margin: 0 0 8px;
    }

    .invoice-payment ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .invoice-payment ul li {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 3rem;
      margin-top: 10px;
    }

    .invoice-payment ul li label {
      font-weight: 600;
    }


  </style>
</head>
<body>

  <div class="invoice">


    <div class="invoice-logo-section">
      <div class="invoice-logo">
      </div>
      <div class="invoice-header">
        <h1>{{ __('messages.invoice') }}</h1>
        <div class="invoice-details">
          <p><strong>{{ __('messages.invoice_ID') }}:</strong>{{ __('messages.ORDER') }}{{$data['id']}}</p>
          <p><strong>{{ __('messages.date') }}: </strong>{{$data['booking_date']}}</p>
        </div>
      </div>

    </div>

    <div class="invoice-detail-part">
      <div class="invoice-customer">
        <h3>{{ __('messages.customer_info') }}</h3>
        <p>{{$data['user_name']}}</p>
        <p>{{$data['email']}}</p>
        <p>{{$data['mobile']}}</p>
      </div>
      <div class="invoice-billing">

        <h3>{{ __('messages.billing_address') }}</h3>
        <p>{{$data['venue_address']}}</p>

      </div>

    </div>

    <table class="invoice-table">
      <thead>
        <tr>
            <th>{{ __('messages.item_name') }}</th>
            <th>{{ __('messages.quantity') }}</th>
            <th>{{ __('messages.unit_price') }}</th>
            <th class="text-end">{{ __('messages.total') }}</th>
        </tr>
      </thead>
   <tbody>
    @php
          $productPrice = 0;
          $package_price = 0;
    @endphp
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
        @foreach($data['extra']['packages'] as $key => $value)

        <tr>
          <td>{{$value['name']}}</td>
          <td>1</td>
          <td>{{$value['package_price']}}</td>
          <td>{{$value['package_price']}}</td>
        </tr>

      @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="color: #000000; text-align: right;"><strong>{{ __('messages.sub_total') }}:</strong></td>
          <td>{{ \Currency::format($data['serviceAmount'] + $productPrice + $data['package_price']) }}</td>


        </tr>
        <tr>
          <td colspan="3" style="color: #000000; text-align: right;"><strong>{{ __('messages.tips') }}:</strong></td>
          <td>{{ \Currency::format($data['tip_amount']) }}</td>
        </tr>
        <tr>
          <td colspan="3" style="color: #000000; text-align: right;"><strong>{{ __('messages.tax') }}:</strong></td>
          <td>{{ \Currency::format($data['tax_amount']) }}</td>

        </tr>
        @if($data['coupon_discount'])
      <tr>
        <td colspan="3" style="color: #000000; text-align: right;"><strong>{{ __('messages.coupon_discount') }}:</strong></td>
        <td>{{ \Currency::format($data['coupon_discount']) }}</td>
        </tr>
        @endif
        <tr>
          <td colspan="3" style="color: #000000; text-align: right;"><strong>{{ __('booking.grand_total') }}</strong></td>
          <td>{{ \Currency::format($data['grand_total']) }}</td>
        </tr>
      </tfoot>
    </table>

    <div class="invoice-payment">
      <div class="invoice-pay-info">
        <h3>{{ __('messages.payment_information') }}:</h3>
        <p>
            {{ $data['transaction_type'] === 'upi' ? 'UPI' : ucwords($data['transaction_type']) }}
        </p>

      </div>
    </div>

    <div class="thank-you">
      <p>{{ setting('spacial_note') }}</p>
    </div>
  </div>

</body>
</html>
