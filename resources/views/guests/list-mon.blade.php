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

<body
  style="background-image: url('{{ asset('img/wallpaper.jpg') }}');
             background-position:center;
             background-size:cover;
             background-attachment: fixed;
             color:white;">

  @include('components.navbar')

  <section class="container py-5 px-3">
    <h1 class="text-center mt-5 text-dark"
      style="font-family:'Dancing Script',cursive;">Menu</h1>
    <!-- Tabs Phân Loại Món Ăn -->
    <div class=" bg-dark p-2 bg-opacity-50">
      <ul class="nav nav-tabs mb-4" id="foodTabs">
        <li class="nav-item">
          <button class="nav-link active" data-food-type="all">Tất cả</button>
        </li>
        @foreach ($foodTypes as $type)
          <li class="nav-item">
            <button class="nav-link text-light"
              data-food-type="{{ $type->id }}">{{ $type->name }}</button>
          </li>
        @endforeach
      </ul>
    </div>

    <!-- Dropdown sắp xếp giá -->
    <form method="GET" action="{{ route('menu') }}" class="d-flex">
      <input type="hidden" name="food_type" value="{{ request('food_type') }}">
      <select name="sort" onchange="this.form.submit()"
        class="form-select w-auto">
        <option value="" disabled selected>Sắp xếp theo giá</option>
        <option value="price_asc"
          {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần
        </option>
        <option value="price_desc"
          {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần
        </option>
      </select>
      <!-- Ô tìm kiếm món ăn -->
      <input type="text" id="searchFood"
      class="form-control w-50 ms-auto z-0"
      placeholder="Tìm kiếm món ăn...">
    </form>

    <!-- Danh sách món ăn -->
    <div class="row my-3" id="foodList">
      @forelse($foodItems as $food)
        <div class="col-md-3 food-item"
          data-food-type="{{ $food->food_type_id }}">
          <div class="card mb-4">
            <img src="{{ asset('storage/' . $food->image) }}"
              class="card-img-top" alt="{{ $food->name }}" width="152px"
              height="220px" style="object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title">{{ $food->name }}</h5>
              <p class="card-text text-muted">{{ $food->foodType->name }}</p>
              <p class="card-text fw-bold">Giá:
                {{ number_format($food->price) }} VNĐ</p>
              <!-- Nút thêm vào giỏ hàng -->
              <form action="{{ route('cart.add', $food->id) }}" method="POST"
                class="add-to-cart-form">
                @csrf
                <button type="submit" class="btn btn-success w-100">🛒 Thêm vào
                  giỏ hàng</button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center">Không tìm thấy món ăn nào!</p>
      @endforelse
    </div>

  </section>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Search --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchFood");
        const foodItems = document.querySelectorAll(".food-item");

        searchInput.addEventListener("keyup", function () {
            const searchText = this.value.toLowerCase();

            foodItems.forEach(item => {
                const foodName = item.querySelector(".card-title").textContent.toLowerCase();
                if (foodName.includes(searchText)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        });
    });
</script>


<!-- JavaScript Tabs Lọc Món Ăn -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll("#foodTabs .nav-link");
    const foodItems = document.querySelectorAll(".food-item");

    tabs.forEach(tab => {
      tab.addEventListener("click", function() {
        // Xóa class 'active' khỏi tất cả tabs
        tabs.forEach(t => t.classList.remove("active"));
        tabs.forEach(t => t.classList.remove("text-dark"));
        // Thêm class 'active' vào tab được click
        this.classList.add("active");
        this.classList.add("text-dark");


        // Lấy loại món ăn từ tab
        const foodType = this.getAttribute("data-food-type");

        // Hiển thị món ăn phù hợp
        foodItems.forEach(item => {
          if (foodType === "all" || item.getAttribute(
              "data-food-type") === foodType) {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
        });
      });
    });
  });
</script>

<!-- AJAX thêm vào giỏ hàng -->
<script>
  $(document).ready(function() {
    $(".add-to-cart-form").submit(function(e) {
      e.preventDefault();

      var form = $(this);
      var url = form.attr("action");

      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Đã thêm vào giỏ hàng!',
            text: 'Món ăn đã được thêm thành công.',
            confirmButtonColor: '#4e73df'
          });
        },
        error: function(xhr) {
          Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Có lỗi xảy ra khi thêm vào giỏ hàng.',
            confirmButtonColor: '#dc3545'
          });
        }
      });
    });
  });
</script>

</html>
