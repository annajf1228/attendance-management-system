<table class="table table-bordered admin-form-table">
  <tbody>
    <tr>
      <th>社員番号</th>
      <td>
        <input type="text" class="form-control @error('employee_number') is-invalid @enderror" id="InputUserEmployeeNumber"
          name="employee_number" value="{{ old('employee_number',$user->employee_number ?? '') }}" placeholder="社員番号を入力してください" required>
        @error('employee_number')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </td>
    </tr>
    <tr>
      <th>名前</th>
      <td>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="InputUserName"
          name="name" value="{{ old('name',$user->name ?? '') }}" placeholder="山田　太郎" required>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </td>
    </tr>
    <tr>
      <th>メールアドレス</th>
      <td>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="InputUserEmail"
          name="email" value="{{ old('email',$user->email ?? '') }}" placeholder="xxx@exsample.com" required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </td>
    </tr>
    <tr>
      <th>入社日</th>
      <td>
        <input type="date" class="form-control @error('join_date') is-invalid @enderror" id="InputUserJoinDate"
          name="join_date" value="{{ old('join_date', isset($user) ? $user->join_date?->format('Y-m-d') : '') }}" required>
        @error('join_date')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </td>
    </tr>
    @if($readOnly === false)
    <tr>
      <th>パスワード</th>
      <td>
        <div class="password-box">
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="InputUserPassword"
            name="password" placeholder="半角英数字8～20文字を入力してください" required>
          <img class="password-img" src="{{ asset('images/close-eye.svg') }}" onclick="togglePassword('InputUserPassword', this)">
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
            id="InputUserPasswordConfirmation" name="password_confirmation" placeholder="確認の為もう一度入力ください" required>
          <img class="password-img" src="{{ asset('images/close-eye.svg') }}" onclick="togglePassword('InputUserPasswordConfirmation', this)">
          @error('password_confirmation')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </td>
    </tr>
    @endif
  </tbody>
</table>