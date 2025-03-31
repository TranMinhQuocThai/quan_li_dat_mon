<div class="card mb-3">
  <div class="card-body">
    @php
      $form;
      switch ($mode) {
        case 'update':
          $form = [
            'action' => route('orders.update', $order),
            'method' => 'PATCH',
          ];
          break;
        default:
          $form = [
            'action' => route('orders.store'),
            'method' => 'POST',
          ];
          break;
      }
    @endphp
    <!-- Form chỉnh sửa -->
    <form action="{{ $form['action'] }}" method="POST">
      @method($form['method'])
      @csrf

      <div class="mb-3">
        <label for="table_id" class="form-label">
          Bàn
        </label>
        <select name="table_id" id="table_id"
          class="form-control @error('table_id') is-invalid @enderror"
          >
          @foreach($tables as $table)
              <option value="{{ $table->id }}"
                @if (isset($order) && old('table_id', $order->table_id) == $table->id)
                  selected
                @endif>
                  {{ $table->name }}
              </option>
          @endforeach
        </select>
        @error('table_id')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="user_id" class="form-label">
          Khách
        </label>
        <select name="user_id" id="user_id"
          class="form-control @error('user_id') is-invalid @enderror"
          >
          <option value="">Khách vãng lai</option>
          @foreach($users as $user)
              <option value="{{ $user->id }}"
                @if (isset($order) && old('user_id', $order->user_id) == $user->id)
                  selected
                @endif>
                  {{ $user->name }}
              </option>
          @endforeach
        </select>
        @error('user_id')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="discount" class="form-label">
          Giảm giá
        </label>
        <input type="number" name="discount" id="discount"
          class="form-control @error('discount') is-invalid @enderror"
          @isset($order)
            value="{{ old('discount', $order->discount) }}"
          @endisset
          value="{{ old('discount', '0') }}">
        @error('discount')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      @if ($mode == 'update')
        <div class="mb-3">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" name="paid" id="customCheck" class="custom-control-input"
              value="1"
              {{ $order->paid ? 'checked' : '' }}
              >
            <label class="custom-control-label" for="customCheck">
              Đã thanh toán
            </label>
          </div>
          @error('status')
            <div class="text-danger">
              {{ $message }}
            </div>
          @enderror
        </div>
        {{-- <div class="mb-3">
          <label for="status" class="form-label">
            Trạng thái
          </label>
          <select name="status" id="status"
            class="form-control @error('status') is-invalid @enderror"
            >
            @php
              $statuses = ['đang ăn', 'đã ăn', 'đã thanh toán'];
            @endphp
            @foreach($statuses as $status)
                <option value="{{ $status }}"
                  @if (isset($order) && old('status', $order->status) == $status)
                    selected
                  @endif
                  >
                    {{ ucfirst($status) }}
                </option>
            @endforeach
          </select>
          @error('status')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div> --}}
      @endif

      <!-- Nút hành động -->
      <button type="submit" class="btn btn-primary">Ok</button>
    </form>
  </div>
</div>