<table class="table table-bordered admin-form-table">
  <tbody>
    <tr>
      <th>社員番号</th>
      <td>
        <input type="text" class="form-control @error('employee_number') is-invalid @enderror" id="InputAdminEmployeeNumber"
          name="employee_number" value="{{ old('employee_number',$admin->employee_number ?? '') }}" placeholder="社員番号を入力してください" required>
        @error('employee_number')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </td>
    </tr>
    <tr>
      <th>名前</th>
      <td>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="InputAdminName"
          name="name" value="{{ old('name',$admin->name ?? '') }}" placeholder="山田　太郎" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </td>
    </tr>
    @if($readOnly === false)
    <tr>
      <th>パスワード</th>
      <td>
        <div class="password-box">
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="InputAdminPassword"
            name="password" placeholder="半角英数字8～20文字を入力してください" required>
          <img class="password-img" src="{{ asset('images/close-eye.svg') }}" onclick="togglePassword('InputAdminPassword', this)">
          @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </td>
    </tr>
    <tr>
      <th>パスワード（確認）</th>
      <td>
        <div class="password-box">
          <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
            id="InputAdminPasswordConfirmation" name="password_confirmation" placeholder="確認の為もう一度入力ください">
          <img class="password-img" src="{{ asset('images/close-eye.svg') }}" onclick="togglePassword('InputAdminPasswordConfirmation', this)" required>
          @error('password_confirmation')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </td>
    </tr>
    @endif
  </tbody>
</table>