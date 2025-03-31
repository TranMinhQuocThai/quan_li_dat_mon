<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đặt món</title>
  <link rel="stylesheet" href="{{ asset('css/nunito.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}"
    rel="stylesheet" type="text/css">
</head>
<style>
  button:hover {
    background: pink;
    color: black;
  }

  * {
    transition: 0.5s;
  }

  .col-md-3>.card:hover {
    translate: 0 -0.6em;
    filter: none;
    box-shadow: 0 0 1em gray;
  }
</style>

<body class="bg-light">
  @include('components.navbar')
  <div class="container mb-5">
    <h2 class="text-center m-3">Giỏ Hàng</h2>
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

    @if ($cart->isEmpty())
        <p>Giỏ hàng của bạn đang trống.</p>
    @else
      <table class="table">
        <thead>
          <tr>
            <th>Món ăn</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tổng</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cart as $item)
            <tr>
              <td>{{ $item->foodItem->name }}</td>
              <td>{{ number_format($item->price) }} VND</td>
              <td>
                <form action="{{ route('cart.update', $item->food_item_id) }}" method="POST">
                  @csrf
                  <input type="number" name="quantity" 
                    value="{{ $item->quantity }}" min="1"
                    class="form-control form-control-sm d-inline" style="width: 50px;">
                  <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                </form>
              </td>
              <td>{{ number_format($item->price * $item->quantity) }} VND</td>
              <td>
                <form action="{{ route('cart.remove', $item->food_item_id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <h4>Tổng tiền: {{ number_format($total) }} VND</h4>
      <a href="{{ route('cart.clear') }}" class="btn btn-warning">Xóa tất cả</a>
      <a href="{{ route('cart.checkout') }}" class="btn btn-success">Thanh toán</a>
    @endif
    @if (!$suggestedFoods->isEmpty())
      <div class="">
        <h3 class="text-center mb-5">Có thể bạn cũng muốn</h3>
        <div class="row">
          @foreach ($suggestedFoods as $food)
            <div class="col-md-3">
              <div class="card h-100">
                <img src="{{ asset('storage/' . $food->image) }}"
                  class="card-img-top" alt="{{ $food->name }}">
                <div class="card-body text-center">
                  <h5 class="card-title">{{ $food->name }}</h5>
                  <p class="card-text text-danger">
                    {{ number_format($food->price) }} VND</p>
                  <form action="{{ route('cart.add', $food->id) }}"
                    method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">🛒 Thêm vào
                      giỏ hàng</button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    @endif
  </div>
  <!-- Contact Section -->
  <section id="contact" class="bg-dark text-white py-5 text-center">
    <h2>Contact & Reservations</h2>
    <p>📍 123 Main Street, Your City | 📞 (123) 456-7890</p>
    <a href="tel:1234567890" class="btn btn-warning">Call Now</a>
  </section>

  <!-- Footer -->
  <footer class="bg-black text-white text-center py-3">
    <p>&copy; 2025 Restaurant Name. All Rights Reserved.</p>
  </footer>


</body>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
@if (session('error'))
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Lỗi',
      text: '{{ session('error') }}',
      confirmButtonColor: '#4e73df',
    })
  </script>
@endif

</html>
