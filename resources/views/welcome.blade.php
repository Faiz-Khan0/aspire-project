<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
    <meta charset="UTF-8">
    <title>Service Check-In</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Tablet-friendly styling --}}
    <style>
        body {
            font-size: 1.4rem;
            /* Larger base font for readability */
            padding: 0;
            margin: 0;
            background-color: #f9f9f9;
        }

        header {
            background-color: #005b96;
            color: white;
            padding: 1rem 2rem;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            width: 100%;
        }

        main {
            padding: 2rem;
            max-width: 800px;
            margin: auto;
        }

        label {
            font-weight: 600;
        }

        .form-control,
        .form-select {
            font-size: 1.4rem;
            padding: 1rem;
        }

        button.btn {
            font-size: 1.5rem;
            padding: 1rem 2rem;
        }

        .modal-content {
            font-size: 1.4rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark py-3 mb-4 shadow-sm" style="background: #e3f2fd;">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

            {{-- Aspire Logo --}}
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('alogo.png') }}" alt="Aspire Logo" style="height: 100px" class="me-3">
                <span class="fs-3 fw-bold text-dark">Aspire Check-In</span>
            </a>

            {{-- Auth Area: Login or Logout --}}
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 btn btn-primary rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block btn btn-success px-5 py-1.5 rounded-sm text-sm leading-normal">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 btn btn-primary rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </nav>

    <main>
        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Check-In Form --}}
        <form method="POST" action="/checkin">
            @csrf

            {{-- PIN --}}
            <div class="mb-4">
                <label for="pin" class="form-label">Enter Your 4-Digit PIN</label>
                <input type="text" class="form-control" id="pin" name="pin" maxlength="4" minlength="4" pattern="\d{4}"
                    placeholder="Your pin" required>
            </div>

            {{-- Main Service Dropdown --}}
            <div class="mb-4">
                <label for="service_id" class="form-label">Select Service Area</label>
                <select id="serviceSelect" name="service_id" class="form-select" required>
                    <option value="">-- Choose a Service --</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Subservice Dropdown (Filtered) --}}
            <div class="mb-4" id="subserviceWrapper" style="display: none;">
                <label for="subservice_id" class="form-label">Select Subservice</label>
                <select id="subserviceSelect" name="subservice_id" class="form-select" required>
                    <option value="">-- Choose a Subservice --</option>
                    @foreach ($services as $service)
                        @foreach ($service->subservices as $sub)
                            <option value="{{ $sub->id }}" data-service="{{ $service->id }}">
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            {{-- Submit Button --}}
            <div class="text-center">
                <button type="submit" class="btn btn-warning">Check In</button>
            </div>
        </form>
    </main>

    {{-- Confirm Modal --}}
    @if(session('confirm_user_id'))
        <div class="modal fade show" id="confirmModal" tabindex="-1" style="display:block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="/checkin">
                        @csrf
                        <input type="hidden" name="confirmed" value="yes">
                        <input type="hidden" name="user_id" value="{{ session('confirm_user_id') }}">
                        <input type="hidden" name="subservice_id" value="{{ session('confirm_subservice_id') }}">
                        <input type="hidden" name="service_id" value="{{ session('confirm_service_id') }}">

                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Identity</h5>
                        </div>
                        <div class="modal-body">
                            <p>Are you <strong>{{ session('confirm_name') }}</strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" onclick="closeModal()">Yes, Confirm</button>
                            <a href="/" class="btn btn-danger">Cancel</a>
                        </div>
                        <script>
                            function closeModal() {
                                const modal = document.getElementById('confirmModal');
                                modal.style.display = 'none';
                                modal.classList.remove('show');
                            }
                        </script>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Success Modal --}}
    @if(session('checked_in'))
        <div class="modal fade show" id="successModal" tabindex="-1" style="display: block;" aria-modal="true"
            role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header">
                        <h5 class="modal-title">Check-In Successful</h5>
                    </div>
                    <div class="modal-body">
                        <p>Thank you for signing in. You may now proceed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="closeModal()">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function closeModal() {
                const modal = document.getElementById('successModal');
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.querySelector('form').reset();
                document.getElementById('subserviceWrapper').style.display = 'none';
            }
            setTimeout(closeModal, 5000);
        </script>
    @endif

    {{-- JavaScript for Filtering Subservices --}}
    <script>
        const serviceSelect = document.getElementById('serviceSelect');
        const subserviceWrapper = document.getElementById('subserviceWrapper');
        const subserviceSelect = document.getElementById('subserviceSelect');

        serviceSelect.addEventListener('change', function () {
            const selectedServiceId = this.value;

            subserviceWrapper.style.display = selectedServiceId ? 'block' : 'none';
            subserviceSelect.value = '';

            Array.from(subserviceSelect.options).forEach(option => {
                const match = option.dataset.service === selectedServiceId || option.value === '';
                option.style.display = match ? 'block' : 'none';
            });
        });
    </script>

</body>

</html>

</div>

@if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
@endif
</body>

</html>