@extends('layouts.dash')

@section('head')
  <title>Đơn hàng Online</title>
@endsection
<style>
  /* Đảm bảo bảng không bị kéo giãn quá mức */
.table {
    table-layout: fixed;
    width: 100%;
}

th, td {
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px; /* Điều chỉnh nếu cần */
}

/* Điều chỉnh modal để không làm vỡ layout */
.modal-dialog {
    max-width: 80%; /* Điều chỉnh độ rộng modal */
    overflow-x: auto; /* Cho phép cuộn ngang nếu nội dung quá rộng */
}

.modal-body {
    overflow-x: auto;
}

/* Đảm bảo bảng trong modal không bị mất cân đối */
.modal-body table {
    width: 100%;
}

.modal-body th,
.modal-body td {
    max-width: unset;
    white-space: normal;
}

</style>
@section('content')
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('online_orders.index') }}">Đơn hàng Online</a>
    </h1>
  </div>

  @php
    use Illuminate\Support\Str;
    $statuses = ['chờ xác nhận', 'đã xác nhận', 'không nhận', 'đã giao', 'đã hủy'];
  @endphp

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Khách hàng</th>
          <th>Số điện thoại</th>
          <th>Địa chỉ</th>
          <th>Trạng thái</th>
          <th>Tổng tiền</th>
          <th>Ngày đặt hàng</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($onlineOrders as $order)
        <tr>
          <td>{{ $order->id }}</td>
          <td>{{ optional($order->user)->name ?? 'Khách vãng lai' }}</td>
          <td>{{ $order->phone }}</td>
          <td>{{ $order->address }}</td>
          <td>
            <form method="POST" action="{{ route('online_orders.update_status', $order->id) }}"
              class="status-form m-0">
              @csrf
              @method('PATCH')
              <select name="status" data-order-id="{{ $order->id }}"
                class="status-select form-control form-control-sm" style="width: fit-content;">
                @foreach($statuses as $status)
                  <option value="{{ $status }}" @if($order->status == $status) selected @endif>
                    {{ Str::ucfirst($status) }}
                  </option>
                  
                @endforeach
              </select>
              <input type="text" name="reason" class="reason-input form-control form-control-sm" 
                placeholder="Nhập lý do..." value="{{ $order->reason }}"
                style="display: none; margin-top: 5px;"/>
              <button type="submit" class="btn btn-sm btn-success update-btn" style="display: none; margin-top: 5px;">Cập nhật</button>
            </form>
          </td>
          <td>{{ number_format($order->items_sum_price, 0, ',', '.') }}đ</td>
          <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#orderDetailModal{{ $order->id }}">Xem chi tiết</button>
            <!-- Modal Chi Tiết Đơn Hàng -->
            <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderDetailModalLabel{{ $order->id }}">Chi tiết đơn hàng</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tên món</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->food->name }}</td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
          </td>
          
        </tr>
        @endforeach
      </tbody>
    </table>
    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
      {{ $onlineOrders->appends(request()->query())->links() }}
    </div>
  </div>
  
@endsection

<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".status-select").forEach(select => {
      select.addEventListener("change", function() {
        let orderId = this.getAttribute("data-order-id");
        let reasonInput = this.closest(".status-form").querySelector(".reason-input");
        let updateBtn = this.closest(".status-form").querySelector(".update-btn");
        
        if (this.value === "không nhận") {
          reasonInput.style.display = "block";
          updateBtn.style.display = "block";
        } else {
          reasonInput.style.display = "none";
          updateBtn.style.display = "none";
          this.closest(".status-form").submit();
        }
      });
    });
  });
</script>