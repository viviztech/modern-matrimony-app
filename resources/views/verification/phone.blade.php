@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/10 via-white to-secondary/10 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                Verify Your Phone
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                We'll send you a 6-digit code to verify your phone number
            </p>
        </div>

        <!-- Phone Input Step -->
        <div id="phoneStep" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
            <form id="phoneForm">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">+91</span>
                            </div>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   class="pl-12 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="9876543210"
                                   required
                                   maxlength="10"
                                   pattern="[0-9]{10}">
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Enter your 10-digit mobile number</p>
                    </div>

                    <button type="submit"
                            id="sendOtpBtn"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary hover:shadow-glow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                        <span id="sendOtpText">Send OTP</span>
                        <svg id="sendOtpLoader" class="hidden animate-spin ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>

                    @if(app()->environment('local'))
                        <div class="text-center">
                            <a href="{{ route('verification.skip') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary">
                                Skip verification (dev only)
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- OTP Verification Step -->
        <div id="otpStep" class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Enter the 6-digit code sent to
                </p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white" id="displayPhone"></p>
                <button type="button" onclick="changePhone()" class="text-sm text-primary hover:underline mt-1">
                    Change number
                </button>
            </div>

            <form id="otpForm">
                @csrf
                <div class="space-y-6">
                    <!-- OTP Input Fields -->
                    <div class="flex gap-2 justify-center">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-primary dark:bg-gray-700 dark:text-white" data-index="0">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-primary dark:bg-gray-700 dark:text-white" data-index="1">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-primary dark:bg-gray-700 dark:text-white" data-index="2">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-primary dark:bg-gray-700 dark:text-white" data-index="3">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-primary dark:bg-gray-700 dark:text-white" data-index="4">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-primary dark:bg-gray-700 dark:text-white" data-index="5">
                    </div>

                    <!-- Error/Success Messages -->
                    <div id="otpMessage" class="hidden text-center text-sm"></div>

                    <!-- Timer -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span id="timerText">Code expires in <span id="timer" class="font-semibold text-primary">10:00</span></span>
                        </p>
                    </div>

                    <!-- Resend OTP -->
                    <div class="text-center">
                        <button type="button"
                                id="resendBtn"
                                onclick="resendOTP()"
                                class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <span id="resendText">Resend OTP</span>
                            <span id="resendTimer" class="font-semibold">(60s)</span>
                        </button>
                    </div>

                    <button type="submit"
                            id="verifyBtn"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary hover:shadow-glow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                        <span id="verifyText">Verify</span>
                        <svg id="verifyLoader" class="hidden animate-spin ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Step -->
        <div id="successStep" class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 text-center">
            <div class="mx-auto h-16 w-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Verified!</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Your phone number has been verified successfully</p>
            <a href="{{ route('dashboard') }}" class="inline-flex justify-center py-3 px-6 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary to-secondary hover:shadow-glow transition-all duration-200">
                Continue to Dashboard
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentPhone = '';
    let expiryTime = null;
    let timerInterval = null;
    let resendTimerInterval = null;

    // Phone form submission
    document.getElementById('phoneForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const phone = document.getElementById('phone').value;
        const btn = document.getElementById('sendOtpBtn');
        const btnText = document.getElementById('sendOtpText');
        const loader = document.getElementById('sendOtpLoader');

        // Disable button
        btn.disabled = true;
        btnText.classList.add('hidden');
        loader.classList.remove('hidden');

        try {
            const response = await fetch('{{ route("verification.send-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ phone })
            });

            const data = await response.json();

            if (data.success) {
                currentPhone = phone;
                document.getElementById('displayPhone').textContent = '+91 ' + phone;

                // Show OTP step
                document.getElementById('phoneStep').classList.add('hidden');
                document.getElementById('otpStep').classList.remove('hidden');

                // Start timer
                expiryTime = new Date(data.expires_at);
                startTimer();
                startResendTimer();

                // Focus first OTP input
                document.querySelector('.otp-input').focus();
            } else {
                alert(data.message);
            }
        } catch (error) {
            alert('An error occurred. Please try again.');
        } finally {
            btn.disabled = false;
            btnText.classList.remove('hidden');
            loader.classList.add('hidden');
        }
    });

    // OTP input auto-focus
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').slice(0, 6);
            pastedData.split('').forEach((char, idx) => {
                if (otpInputs[idx]) {
                    otpInputs[idx].value = char;
                }
            });
            if (pastedData.length === 6) {
                document.getElementById('otpForm').dispatchEvent(new Event('submit'));
            }
        });
    });

    // OTP form submission
    document.getElementById('otpForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const otp = Array.from(otpInputs).map(input => input.value).join('');

        if (otp.length !== 6) {
            showMessage('Please enter all 6 digits', 'error');
            return;
        }

        const btn = document.getElementById('verifyBtn');
        const btnText = document.getElementById('verifyText');
        const loader = document.getElementById('verifyLoader');

        btn.disabled = true;
        btnText.classList.add('hidden');
        loader.classList.remove('hidden');

        try {
            const response = await fetch('{{ route("verification.verify-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ phone: currentPhone, otp })
            });

            const data = await response.json();

            if (data.success) {
                // Show success
                document.getElementById('otpStep').classList.add('hidden');
                document.getElementById('successStep').classList.remove('hidden');

                // Stop timers
                if (timerInterval) clearInterval(timerInterval);
                if (resendTimerInterval) clearInterval(resendTimerInterval);
            } else {
                showMessage(data.message, 'error');
                // Clear OTP inputs
                otpInputs.forEach(input => input.value = '');
                otpInputs[0].focus();
            }
        } catch (error) {
            showMessage('An error occurred. Please try again.', 'error');
        } finally {
            btn.disabled = false;
            btnText.classList.remove('hidden');
            loader.classList.add('hidden');
        }
    });

    function changePhone() {
        document.getElementById('otpStep').classList.add('hidden');
        document.getElementById('phoneStep').classList.remove('hidden');
        if (timerInterval) clearInterval(timerInterval);
        if (resendTimerInterval) clearInterval(resendTimerInterval);
        otpInputs.forEach(input => input.value = '');
    }

    async function resendOTP() {
        try {
            const response = await fetch('{{ route("verification.resend-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ phone: currentPhone })
            });

            const data = await response.json();

            if (data.success) {
                showMessage('OTP resent successfully', 'success');
                expiryTime = new Date(data.expires_at);
                startTimer();
                startResendTimer();
            } else {
                showMessage(data.message, 'error');
            }
        } catch (error) {
            showMessage('Failed to resend OTP', 'error');
        }
    }

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);

        timerInterval = setInterval(() => {
            const now = new Date();
            const diff = expiryTime - now;

            if (diff <= 0) {
                clearInterval(timerInterval);
                document.getElementById('timer').textContent = '0:00';
                showMessage('OTP expired. Please request a new one.', 'error');
                return;
            }

            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('timer').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    function startResendTimer() {
        const resendBtn = document.getElementById('resendBtn');
        const resendTimer = document.getElementById('resendTimer');
        let seconds = 60;

        resendBtn.disabled = true;

        if (resendTimerInterval) clearInterval(resendTimerInterval);

        resendTimerInterval = setInterval(() => {
            seconds--;
            resendTimer.textContent = `(${seconds}s)`;

            if (seconds <= 0) {
                clearInterval(resendTimerInterval);
                resendBtn.disabled = false;
                resendTimer.textContent = '';
            }
        }, 1000);
    }

    function showMessage(message, type) {
        const messageDiv = document.getElementById('otpMessage');
        messageDiv.textContent = message;
        messageDiv.className = `text-center text-sm ${type === 'error' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'}`;
        messageDiv.classList.remove('hidden');

        setTimeout(() => {
            messageDiv.classList.add('hidden');
        }, 5000);
    }
</script>
@endpush
@endsection
