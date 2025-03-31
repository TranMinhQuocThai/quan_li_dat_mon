@extends('layouts.dash')

@section('title', 'Chỉnh sửa Nguyên Liệu trong Món Ăn')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-body">
      <form action="{{ route('food_ingredients.update', $foodIngredient->id) }}"
        method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="food_item_id">Món Ăn</label>
          <select name="food_item_id" id="food_item_id" class="form-control">
            @foreach ($foods as $food)
              <option value="{{ $food->id }}"
                {{ $foodIngredient->food_item_id == $food->id ? 'selected' : '' }}>
                {{ $food->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="ingredient_id">Nguyên Liệu</label>
          <select name="ingredient_id" id="ingredient_id" class="form-control">
            @foreach ($ingredients as $ingredient)
              <option value="{{ $ingredient->id }}"
                data-type="{{ $ingredient->unit }}"
              {{ $foodIngredient->ingredient_id == $ingredient->id ? 'selected' : '' }}
              >
              {{ $ingredient->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="quantity">Số Lượng</label>
          <input type="number" name="quantity" id="quantity"
            class="form-control" value="{{ $foodIngredient->quantity }}" required>
        </div>

        <div class="form-group">
          <label for="unit">Đơn Vị</label>
          <input type="text" name="unit" id="unit" class="form-control"
            value="{{ $foodIngredient->ingredient->unit }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="{{ route('food_ingredients.index') }}"
          class="btn btn-secondary">Quay lại</a>
      </form>
    </div>
  </div>
  <script>
    document.getElementById("ingredient_id").addEventListener("change",
      function() {
        var selectedOption = this.options[this.selectedIndex];
        var ingredientType = selectedOption.getAttribute("data-type");

        // Nếu nguyên liệu là chất rắn → đơn vị g, nếu là chất lỏng → đơn vị ml
        document.getElementById("unit").value = ingredientType;
      });
  </script>
@endsection
