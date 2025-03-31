@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('food_ingredients.index') }}">
        Công thức
      </a>
    </h1>
    <div>
      <a href="{{ route('food_ingredients.create') }}"
        class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-upload fa-sm text-white-50"></i>
        Thêm
      </a>
    </div>
  </div>

  <div class="card shadow mb-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%"
          cellspacing="0">
          <thead>
            <tr class="text-center">
              <th colspan="5"><strong>Món Ăn</strong></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($groupedFoodIngredients as $foodName => $ingredients)
              <tr class="food-group"
                onclick="toggleFoodIngredients('{{ Str::slug($foodName) }}')">
                <td colspan="5">
                  <strong>{{ $foodName }}</strong>
                  <span class="toggle-icon float-right">+</span>
                </td>
              </tr>
          <tbody id="{{ Str::slug($foodName) }}" class="food-ingredients d-none">
            @foreach ($ingredients as $foodIngredient)
              <tr>
                <td>{{ $foodIngredient->ingredient->name }}</td>
                <td>{{ $foodIngredient->quantity }}</td>
                <td>{{ $foodIngredient->ingredient->unit }}</td>
                <td>
                  <a href="{{ route('food_ingredients.edit', $foodIngredient->id) }}"
                    class="btn btn-sm btn-primary">Sửa</a>
                  <form
                    action="{{ route('food_ingredients.destroy', $foodIngredient->id) }}"
                    method="POST" class="d-inline"
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="btn btn-danger btn-sm">Xóa</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pusher để cập nhật real-time -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
      function toggleFoodIngredients(foodId) {
        var element = document.getElementById(foodId);
        var icon = element.previousElementSibling.querySelector(".toggle-icon");

        if (element.classList.contains("d-none")) {
          element.classList.remove("d-none");
          icon.textContent = "-";
        } else {
          element.classList.add("d-none");
          icon.textContent = "+";
        }
      }

      Pusher.logToConsole = true;

      var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
      });

      var channel = pusher.subscribe("food_ingredients");

      channel.bind("FoodIngredientCreated", function(data) {
        console.log("Nhận sự kiện FoodIngredientCreated:", data);
        location.reload(); // Tải lại trang để cập nhật danh sách
      });
    </script>
  </div>
@endsection
