@extends('layout.app')

@section('title')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Sửa Hóa Đơn</h1>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <!-- Hiển thị lỗi nếu có -->
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Form chỉnh sửa -->
    <form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
      @csrf
      @method('PUT') <!-- Phương thức PUT để cập nhật -->

      <!-- Mã Đơn Hàng -->
      <div class="mb-3">
        <label for="order_id" class="form-label">Mã Đơn Hàng</label>
        <input 
          type="text" 
          class="form-control @error('order_id') is-invalid @enderror" 
          name="order_id" 
          value="{{ $invoice->order_id }}"
        >
        @error('order_id')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @enderror
      </div>

      <!-- Ngày Thanh Toán -->
      <div class="mb-3">
        <label for="invoice_date" class="form-label">Ngày Thanh Toán</label>
        <input 
          type="date" 
          class="form-control @error('invoice_date') is-invalid @enderror" 
          name="invoice_date" 
          value="{{ $invoice->invoice_date }}"
        >
        @error('invoice_date')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @enderror
      </div>

      <!-- Tổng Tiền -->
      <div class="mb-3">
        <label for="total_amount" class="form-label">Tổng Tiền</label>
        <input 
          type="number" 
          step="0.01" 
          class="form-control @error('total_amount') is-invalid @enderror" 
          name="total_amount" 
          value="{{ $invoice->total_amount }}"
        >
        @error('total_amount')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @enderror
      </div>

      <!-- Trạng Thái -->
      <div class="mb-3">
        <label for="status" class="form-label">Trạng Thái</label>
        <select class="form-control @error('status') is-invalid @enderror" name="status">
          <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
          <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
        </select>
        @error('status')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @enderror
      </div>

      <!-- Nút hành động -->
      <button type="submit" class="btn btn-primary">Cập nhật</button>
      <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
  </div>
</div>
@endsection

@section('script')
<!-- Hiển thị SweetAlert2 khi có thông báo thành công -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: "{{ session('success') }}",
        confirmButtonText: 'OK'
    });
</script>
@endif

<!-- Hiển thị SweetAlert2 khi có lỗi -->
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        html: `
            <ul style="text-align: left;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        `,
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection
