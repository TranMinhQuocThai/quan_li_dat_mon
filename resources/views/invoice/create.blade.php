@extends('layout.app')
@section('title')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tạo hóa đơn</h1>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <!-- Hiển thị lỗi nếu có -->
    <form action="{{ route('invoice.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="order_id" class="form-label">Mã đơn hàng</label>
        <input 
          type="text" 
          class="form-control @error('order_id') is-invalid @enderror" 
          name="order_id" 
          value="{{ old('order_id') }}">
        
        @error('order_id')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="invoice_date" class="form-label">Ngày hóa đơn</label>
        <input 
          type="date" 
          class="form-control @error('invoice_date') is-invalid @enderror" 
          name="invoice_date" 
          value="{{ old('invoice_date') }}">
        
        @error('invoice_date')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="total_amount" class="form-label">Tổng tiền</label>
        <input 
          type="number" 
          class="form-control @error('total_amount') is-invalid @enderror" 
          name="total_amount" 
          step="0.01" 
          value="{{ old('total_amount') }}">
        
        @error('total_amount')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Trạng thái</label>
        <select 
          class="form-control @error('status') is-invalid @enderror" 
          name="status">
          <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
          <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
        </select>
        
        @error('status')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Thêm mới</button>
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
