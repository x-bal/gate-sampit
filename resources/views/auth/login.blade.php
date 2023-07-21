@extends('layouts.master')

@section('content')
<div class="container mt-3 ">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Login
                </div>

                <div class="card-body">
                    <form action="{{ route('login.store') }}" class="row" id="form-card" method="post">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control">

                            @error('username')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">

                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@stop