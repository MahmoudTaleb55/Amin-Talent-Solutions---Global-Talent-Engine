@extends('layouts.dashboard')
@section('title','CEO Portal')
@section('body')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-3">CEO Portal</h5>
          <form method="post" action="{{ url('/ceo-portal') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Access Key</label>
              <input name="access_key" type="password" class="form-control" placeholder="Enter CEO passphrase" required>
            </div>
            <button class="btn btn-success w-100">Enter</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection