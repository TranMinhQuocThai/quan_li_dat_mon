<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thanh toán</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}"
    rel="stylesheet" type="text/css">
</head>

<body>
  @include('components.navbar')

  <section class="container py-5">
    <h1 class="text-center">Xác nhận đặt Món</h1>

    @if ($cart->isEmpty())
      <p class="text-center">Giỏ hàng của bạn đang trống.</p>
      <a href="{{ route('menu') }}"
        class="btn btn-primary d-block mx-auto w-50">Quay lại thực đơn</a>
    @else
      <table class="table table-bordered mt-4">
        <thead class="table-dark">
          <tr>
            <th>Món ăn</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tổng</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cart as $item)
            <tr>
              <td>{{ $item->foodItem->name }}</td>
              <td>{{ number_format($item->price) }} VNĐ</td>
              <td>{{ $item->quantity }}</td>
              <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <h3 class="text-end">Tổng tiền: <strong
          class="text-danger">{{ number_format($total) }} VNĐ</strong></h3>
      <form action="{{ route('cart.processCheckout') }}" method="POST">
        @csrf
        <label>Họ và Tên:</label>
        <input type="text" name="name" class="form-control"
          value="{{ auth()->user()->name }}" required>

        <label>Số điện thoại:</label>
        <input type="text" name="phone" class="form-control"
          value="{{ auth()->user()->phone }}" required>

        <label>Địa chỉ:</label>
        <textarea name="address" class="form-control" required>{{ auth()->user()->address }}</textarea>

        <button type="submit" class="btn btn-success mt-3">Đặt món
          ngay</button>
      </form>

    @endif
  </section>
  <section class="container mt-5">
    <h2>Đơn hàng của tôi</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Mã ĐH</th>
          <th>Người đặt</th>
          <th>SĐT</th>
          <th>Địa chỉ</th>
          <th>Trạng thái</th>
          <th>Ngày tạo</th>
          <th>Thanh toán</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($orders as $order)
          <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->phone }}</td>
            <td>{{ $order->address }}</td>
            <td>{{ $order->status }}
              {{ $order->status == 'không nhận' ? " - $order->reason" : '' }}
            </td>
            <td>{{ $order->created_at }}</td>
            <td>{{ $order->paid ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
            <td>
              <form action="{{ route('online_orders.cancel', $order->id) }}"
                method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="btn btn-danger btn-sm">Hủy</button>
              </form>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                data-bs-target="#orderModal{{ $order->id }}">Xem</button>
              <button data-bs-toggle="modal" data-bs-target="#paymentModal"
                onclick="showPaymentModal({{ $order->id }}, {{ $order->items->sum(fn($i) => $i->price * $i->quantity) }})"
                class="btn btn-secondary btn-sm"
                style="display:  {{ $order->paid ? 'none' : 'inline' }};">{{ $order->paid ? ' ' : 'Thanh toán' }}</button>
            </td>
          </tr>

          <!-- Modal hiển thị chi tiết đơn hàng -->
          <div class="modal fade" id="orderModal{{ $order->id }}"
            tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Chi tiết đơn hàng
                    #{{ $order->id }}</h5>
                  <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <ul>
                    @foreach ($order->items as $item)
                      <li>{{ $item->food->name }} -
                        {{ number_format($item->price) }} VNĐ x
                        {{ $item->quantity }}</li>
                    @endforeach
                  </ul>
                  <p><strong>Tổng tiền:
                    </strong>{{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity)) }}
                    VNĐ</p>
                </div>
              </div>
            </div>
          </div>
          <!-- Payment Modal -->
          <div class="modal fade" id="paymentModal" tabindex="-1"
            aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="paymentModalLabel">Thanh toán</h5>
                  <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body text-center">
                  <p>Quét mã QR để thanh toán:</p>
                  <p id="sotienthanhtoan"></p>
                  <img id="qrCode" src="" alt="QR Code"
                    class="img-fluid">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">Đóng</button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </tbody>
    </table>

  </section>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script>
    function showPaymentModal(orderId, price) {
      // Định dạng số tiền bằng toLocaleString
      let formattedPrice = price.toLocaleString('vi-VN') + '₫';

      // Cấu hình thông tin ngân hàng
      const bankInfo = {
        bankId: "970416", // Mã ngân hàng ACB
        accountNo: "38752307", // Số tài khoản của bạn
        accountName: "TRAN MINH QUOC THAI", // Tên tài khoản của bạn
        amount: price,
        content: `Thanh toan don hang ${orderId}` // Nội dung chuyển khoản
      };

      // Tạo link ảnh QR dựa trên API VietQR
      const qrData =
        `https://api.vietqr.io/image/${bankInfo.bankId}-${bankInfo.accountNo}-compact.jpg?amount=${bankInfo.amount}&addInfo=${bankInfo.content}&accountName=${bankInfo.accountName}`;

      // Cập nhật giao diện modal
      document.getElementById('qrCode').src = qrData;
      document.getElementById('sotienthanhtoan').innerHTML =
        `Số tiền cần thanh toán: <b>${formattedPrice}</b>`;

    }
  </script>
</body>

</html>
