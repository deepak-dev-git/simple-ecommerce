@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold fs-5">
                    Account Registration
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Basic Info --}}
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       required>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h6 class="fw-semibold mb-3">Address Details</h6>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Building No</label>
                                <input type="text"
                                       name="building_no"
                                       value="{{ old('building_no') }}"
                                       class="form-control @error('building_no') is-invalid @enderror"
                                       required>
                                @error('building_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Street</label>
                                <input type="text"
                                       name="street"
                                       value="{{ old('street') }}"
                                       class="form-control @error('street') is-invalid @enderror"
                                       required>
                                @error('street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text"
                                       name="city"
                                       value="{{ old('city') }}"
                                       class="form-control @error('city') is-invalid @enderror"
                                       required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">District</label>
                                <input type="text"
                                       name="district"
                                       value="{{ old('district') }}"
                                       class="form-control @error('district') is-invalid @enderror"
                                       required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <input type="text"
                                       name="state"
                                       value="{{ old('state') }}"
                                       class="form-control @error('state') is-invalid @enderror"
                                       required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Postal Code</label>
                                <input type="text"
                                       name="postal_code"
                                       value="{{ old('postal_code') }}"
                                       class="form-control @error('postal_code') is-invalid @enderror"
                                       required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Recaptcha --}}
                        <div class="my-4 text-center">
                            <div class="g-recaptcha d-inline-block"
                                 data-sitekey="{{ config('services.recaptcha.site_key') }}">
                            </div>
                        </div>

                        @error('captcha')
                            <div class="text-danger text-center mb-3">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">
                                Register
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
