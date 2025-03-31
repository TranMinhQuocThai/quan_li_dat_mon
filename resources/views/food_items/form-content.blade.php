<div class="card mb-3">
  <div class="card-body">
    @php
      $form;
      switch ($mode) {
        case 'update':
          $form = [
            'action' => route('food-items.update', $foodItem),
            'method' => 'PATCH',
          ];
          break;
        default:
          $form = [
            'action' => route('food-items.store'),
            'method' => 'POST',
          ];
          break;
      }
    @endphp
    <!-- Form chỉnh sửa -->
    <form action="{{ $form['action'] }}" method="POST" enctype="multipart/form-data">
      @method($form['method'])
      @csrf
    
      <div class="mb-3">
        <label for="name" class="form-label">
          Tên món
        </label>
        <input type="text" name="name" id="name"
          class="form-control @error('name') is-invalid @enderror"
          @isset($foodItem)
            value="{{ old('name', $foodItem->name) }}"
          @endisset
          value="{{ old('name') }}">
        @error('name')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="image" class="form-label">
          Hình
        </label>
        <input type="file" name="image" id="image"
          class="form-control-file @error('image') is-invalid @enderror"
          @isset($foodItem)
            value="{{ old('image', $foodItem->image) }}"
          @endisset
          value="{{ old('image') }}">
        @error('image')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
        @if (isset($foodItem) && $foodItem->image)
        <img src="{{ asset('storage/' . $foodItem->image) }}" alt=""
          style="width: 2em; aspect-ratio: 1; object-fit:contain">
        @endif
      </div>
      

      <div class="mb-3">
        <label for="food_type_id" class="form-label">
          Loại món
        </label>
        <select 
          name="food_type_id" 
          id="food_type_id"
          @isset($foodItem)
            value="{{ old('food_type_id', $foodItem->food_type_id) }}"
          @endisset
          value="{{ old('food_type_id') }}"
          class="form-control @error('food_type_id') is-invalid @enderror"
          required>
          @foreach($foodTypes as $foodType)
              <option value="{{ $foodType->id }}">
                  {{ $foodType->name }}
              </option>
          @endforeach
        </select>
        @error('food_type_id')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="price" class="form-label">
          Giá
        </label>
        <input type="number" name="price" id="price"
          class="form-control @error('price') is-invalid @enderror"
          @isset($foodItem)
            value="{{ old('price', $foodItem->price) }}"
          @endisset
          value="{{ old('price') }}">
        @error('price')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">
          Mô tả
        </label>
        <textarea name="description" id="description"
          class="form-control @error('price') is-invalid @enderror" cols="30" rows="10"
          >{{isset($foodItem) ? old('description', $foodItem->description) : old('description') }}</textarea>
        @error('description')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Nút hành động -->
      <button type="submit" class="btn btn-primary">Ok</button>
    </form>
  </div>
</div>