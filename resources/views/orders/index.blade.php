@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      Đơn hàng
    </h1>
    <div>
      <a href="{{ route('orders.create') }}"
        class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-upload fa-sm text-white-50"></i>
        Tạo
      </a>
    </div>
  </div>

  @php
    $statuses = ['đang ăn', 'đã ăn', 'đã thanh toán'];
  @endphp
  <form method="GET" action="{{ route('orders.index') }}">
    <select name="paid" onchange="this.form.submit()"
      class="form-control form-control-sm mb-1" style="width: fit-content;">
      <option value="">Tất cả</option>
      {{-- @foreach ($statuses as $status) --}}
        <option value="true"
          @if (urldecode(request('paid')) == 'true') selected @endif>
          Đã thanh toán
        </option>
        <option value="false"
          @if (urldecode(request('paid')) == 'false') selected @endif>
          Chưa thanh toán
        </option>
      {{-- @endforeach --}}
    </select>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%"
      cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Khách</th>
          <th>Bàn</th>
          <th>Giảm</th>
          <th>Tổng </th>
          <th>Còn lại</th>
          <th>Thanh toán</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($orders as $order)
          <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user ? $order->user->name : 'Khách vãng lai' }}</td>
            <td>{{ $order->table->name }}</td>
            <td>{{ number_format($order->discount, 0, '', ' ') }}%</td>
            <td>{{ number_format($order->total, 0, '.', ',') }}₫</td>
            <td>
              {{ number_format(($order->total / 100) * (100 - $order->discount), 0, '.', ',') }}₫
            </td>
            <td>
              <form action="{{ route('orders.updatePaid', $order) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="paid" id="customCheck{{$order->id}}" class="custom-control-input"
                    value="1"
                    {{ $order->paid ? 'checked' : '' }}
                    onclick="return confirmSweet(this)"
                    {{ $order->paid ? 'disabled' : '' }}
                    >
                  <label class="custom-control-label" for="customCheck{{$order->id}}">
                    {{ $order->paid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                  </label>
                </div>
                @error('status')
                  <div class="text-danger">
                    {{ $message }}
                  </div>
                @enderror
              </form>
            </td>
            {{-- <td>
              <form action="{{ route('orders.update', $order) }}" method="POST">
                @method('PATCH')
                @csrf
                <input type="hidden" name="user_id"
                  value="{{ $order->user_id }}">
                <input type="hidden" name="table_id"
                  value="{{ $order->table_id }}">
                <input type="hidden" name="discount_id"
                  value="{{ $order->discount }}">
                <div>
                  <select name="status" id="status"
                    old="{{ $order->status }}"
                    onchange="return confirmSweet(this)"
                    class="form-control form-control-sm @error('status') is-invalid @enderror"
                    style="width: fit-content">
                    @foreach ($statuses as $status)
                      <option value="{{ $status }}"
                        @if (isset($order) && old('status', $order->status) == $status) selected @endif>
                        {{ Str::ucfirst($status) }}
                      </option>
                    @endforeach
                  </select>
                  @error('status')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </form>
            </td> --}}
            <td>
              <a href="{{ route('orders.show', [$order, ...request()->query()]) }}"
                class="btn btn-sm btn-info">Đặt</a>
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop{{ $order->id }}">
                Hóa đơn
              </button>
              <div class="modal fade" id="staticBackdrop{{ $order->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">Hóa đơn {{ $order->id }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Bàn:</strong> {{ $order->table->name }}</p>
                        <p><strong>Khách:</strong> {{ $order->user ? $order->user->name : 'Khách vãng lai'}}</p>
                        <table>
                            <tr>
                                <th>Món</th>
                                <th>SL</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                            @foreach ($order->bill['items'] as $item)
                            <tr>
                                <td>{{ $item['food_item'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['price']) }}</td>
                                <td>{{ number_format($item['subtotal']) }}</td>
                            </tr>
                            @endforeach
                        </table>

                        <p><strong>Tổng:</strong> {{ number_format($order->bill['total_price']) }}₫</p>
                        <p><strong>Giảm giá:</strong> {{ number_format($order->bill['discount']) }}%</p>
                        <p><strong>Thành tiền:</strong> {{ number_format($order->bill['final_amount']) }}₫</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Ok</button>
                    </div>
                  </div>
                </div>
              </div>
              @if (!$order->paid)
                <button class="btn btn-sm btn-success"
                  onclick="showPaymentModal({{ $order->id }}, {{ ($order->total / 100) * (100 - $order->discount) }})">
                  Thanh toán
                </button>
              @endif
              <form action="{{ route('orders.destroy', $order) }}" method="POST"
                class="d-inline">
                @method('DELETE')
                @csrf
                <button class="btn btn-sm btn-danger"
                  onclick="return confirmSweet(this)">Hủy</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
      {{ $orders->appends(request()->query())->links() }}
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
          <img id="qrCode" src="" alt="QR Code" class="img-fluid">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @include('components.scripts.alert-script')
  <script>
    function confirmSweet(elem) {
      Swal.fire({
        title: "Xác nhận?",
        text: "Thực hiện hành động này",
        icon: "warning",
        reverseButtons: true,
        showCancelButton: true,
        cancelButtonText: 'Hủy',
        confirmButtonColor: "#4e73df",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ok",
      }).then((result) => {
        if (result.isConfirmed) {
          elem.checked = !elem.checked;
          elem.form.submit();
        }
      });
      return false;
    }

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

      // Hiển thị modal
      $('#paymentModal').modal('show');
    }
  </script>
@endsection
