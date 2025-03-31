@extends('layouts.dash')

@section('title', 'Thêm Nguyên Liệu vào Món Ăn')

@section('content')
  <div class="card shadow mb-4">
    <div class="card-body">
      <form action="{{ route('food_ingredients.store') }}" method="POST">
        @csrf

        <!-- Chọn Món Ăn -->
        <div class="form-group">
          <label for="food_item_id">Món Ăn</label>
          <select name="food_item_id" id="food_item_id" class="form-control">
            <option value="">-- Chọn món ăn --</option>
            @foreach ($foods as $food)
              <option value="{{ $food->id }}">{{ $food->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Chọn Nguyên Liệu -->
        <div class="form-group">
          <label for="ingredient_id">Nguyên Liệu</label>
          <select name="ingredient_id" id="ingredient_id" class="form-control"
            required>
            <option value="">-- Chọn nguyên liệu --</option>
            @foreach ($ingredients as $ingredient)
              <option value="{{ $ingredient->id }}"
                data-type="{{ $ingredient->unit }}"
                >
                {{ $ingredient->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Số Lượng -->
        <div class="form-group">
          <label for="quantity">Số Lượng</label>
          <input type="number" name="quantity" id="quantity"
            class="form-control" required step=".001">
        </div>

        <!-- Đơn Vị (Chỉ Hiển Thị g hoặc ml) -->
        <div class="form-group">
          <label for="unit">Đơn Vị</label>
          <input type="text" name="unit" id="unit" class="form-control"
            readonly>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Nguyên Liệu</button>
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
