@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      Đặt món order #{{ $order->id }}
    </h1>
  </div>

  <!-- Order Information -->
  <div class="card mb-3 d-none">
    <div class="card-body">
      <p><strong>User:</strong> {{ $order->user->name ?? 'Guest' }}</p>
      <p><strong>Table:</strong> {{ $order->table->name }}</p>
      <p><strong>Status:</strong> {{ $order->paid ? 'Paid' : 'Unpaid' }}</p>
      <p><strong>Discount:</strong> {{ $order->discount }}%</p>
    </div>
  </div>

  @include('orders.form-content', [
      'mode' => 'update',
  ])

  <div class="card mb-3">
    <div class="card-body">
      <form action="{{ route('orders.addDetail', $order->id) }}" method="POST">
        @csrf

        <!-- Food Item Selection -->
        <div class="mb-3">
          <label for="food_item_id" class="form-label">
            Món
          </label>
          <select name="food_item_id" id="food_item_id"
            class="form-control @error('food_item_id') is-invalid @enderror"
            >
            <option value="">Select a food item</option>
            @foreach ($foodItems as $item)
              <option value="{{ $item->id }}"
                @if (isset($order) && old('food_item_id', $order->food_item_id) == $item->id) selected @endif>
                {{ $item->name }} - {{ number_format($item->price) }}
              </option>
            @endforeach
          </select>
          @error('food_item_id')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <!-- Quantity Input -->
        <div class="mb-3">
          <label for="quantity" class="form-label">Quantity</label>
          <input type="number" name="quantity" id="quantity"
            class="form-control" min="1" value="{{ old('quantity', 1) }}"
            required>
          @error('quantity')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Add</button>
      </form>
    </div>
  </div>

  <!-- Order Items -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Order Items</h5>
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th>Food Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->orderDetails as $index => $detail)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $detail->foodItem->name }}</td>
              <td>{{ number_format($detail->price, 0) }} VND</td>
              <td>{{ $detail->quantity }}</td>
              <td>{{ number_format($detail->price * $detail->quantity, 0) }} VND
              </td>
              <td>
                <form action="{{ route('order-details.updateStatus', $detail) }}"
                  method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <select name="status" onchange="this.form.submit()"
                    class="form-control form-control-sm d-inline @error('status') is-invalid @enderror"
                    style="width: fit-content;">
                    <option value="chuẩn bị"
                      {{ $detail->status == 'chuẩn bị' ? 'selected' : '' }}>Chuẩn
                      bị</option>
                    <option value="đã nấu"
                      {{ $detail->status == 'đã nấu' ? 'selected' : '' }}>Đã nấu
                    </option>
                    <option value="đã ra"
                      {{ $detail->status == 'đã ra' ? 'selected' : '' }}>Đã ra
                    </option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </form>
                <form action="{{ route('order-details.destroy', $detail) }}"
                  method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn btn-sm btn-danger"
                    {{ $detail->status == 'đã ra' ? 'disabled1' : '' }}>
                    Hủy
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Order Total -->
  <div class="mt-4">
    <h5>
      Tổng:
      {{ number_format($order->total, 0, '.', ',') }}₫
    </h5>
    <h5>
      Giảm:
      {{ number_format($order->discount, 0, '', ' ') }}%
    </h5>
    <h5>
      Cần thanh toán
      {{ number_format(($order->total / 100) * (100 - $order->discount), 0, '.', ',') }}₫
    </h5>
  </div>

  <!-- Back Button -->
  <a href="{{ route('orders.index', request()->query()) }}" class="btn btn-secondary mt-3">
    Quay lại
  </a>
@endsection

@section('script')
  @if (session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Thành công',
        text: '{{ session('success') }}',
        confirmButtonColor: '#4e73df',
      })
    </script>
  @endif
  @if (session('error'))
    <script>
      Swal.fire({
        icon: 'warning',
        title: 'Lỗi',
        html: {!! json_encode(session('error')) !!},
        confirmButtonColor: '#4e73df',
      })
    </script>
  @endif
  <script>
    @foreach ($errors->all() as $error)
      console.warn(`{{ $error }}`)
    @endforeach
  </script>
@endsection
