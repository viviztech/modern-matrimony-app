<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Video Selfie Verification
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        Record a short video to verify your identity and increase trust on your profile.
                    </p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Instructions
                </h3>
                <ul class="space-y-2 text-blue-800 dark:text-blue-200">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">1.</span>
                        <span>Record a 5-10 second video of yourself in good lighting</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">2.</span>
                        <span>Look directly at the camera and slowly turn your head left and right</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">3.</span>
                        <span>Blink naturally during the recording</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">4.</span>
                        <span>Make sure your entire face is visible and matches your profile photo</span>
                    </li>
                </ul>
            </div>

            @if(isset($latestVerification))
                @if($latestVerification->isFailed())
                    <!-- Failed Attempt -->
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 mb-6">
                        <h3 class="font-semibold text-red-900 dark:text-red-100 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Previous Verification Failed
                        </h3>
                        <p class="text-red-800 dark:text-red-200">{{ $latestVerification->failure_reason }}</p>
                        <p class="text-red-700 dark:text-red-300 mt-2 text-sm">Please try again with a new video.</p>
                    </div>
                @elseif($latestVerification->needsManualReview())
                    <!-- Manual Review -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 mb-6">
                        <h3 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Under Review
                        </h3>
                        <p class="text-yellow-800 dark:text-yellow-200">Your video is being manually reviewed by our team. This usually takes 24-48 hours.</p>
                    </div>
                @elseif($latestVerification->isProcessing())
                    <!-- Processing -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                        <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Processing Video
                        </h3>
                        <p class="text-blue-800 dark:text-blue-200">Your video is being processed. This usually takes a few minutes.</p>
                    </div>
                @endif
            @endif

            <!-- Video Recording Interface -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="videoRecorder">
                        <!-- Step 1: Camera Preview -->
                        <div id="cameraPreview" class="space-y-4">
                            <div class="relative bg-gray-900 rounded-lg overflow-hidden" style="height: 400px;">
                                <video id="preview" autoplay playsinline class="w-full h-full object-cover"></video>

                                <!-- Recording Timer -->
                                <div id="recordingTimer" class="hidden absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full flex items-center gap-2">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    <span id="timerDisplay">0:00</span>
                                </div>

                                <!-- Face Guide Overlay -->
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-64 h-80 border-4 border-white border-dashed rounded-full opacity-30"></div>
                                </div>
                            </div>

                            <div class="flex gap-3 justify-center">
                                <button id="startRecording" type="button" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="8"/>
                                    </svg>
                                    Start Recording
                                </button>
                                <button id="stopRecording" type="button" class="hidden px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <rect x="5" y="5" width="10" height="10" rx="1"/>
                                    </svg>
                                    Stop Recording
                                </button>
                            </div>

                            <div id="cameraError" class="hidden bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <p class="text-red-800 dark:text-red-200 text-sm">Failed to access camera. Please check your browser permissions.</p>
                            </div>
                        </div>

                        <!-- Step 2: Video Review -->
                        <div id="videoReview" class="hidden space-y-4">
                            <div class="relative bg-gray-900 rounded-lg overflow-hidden" style="height: 400px;">
                                <video id="playback" controls class="w-full h-full object-cover"></video>
                            </div>

                            <div class="flex gap-3 justify-center">
                                <button id="retake" type="button" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                                    Retake Video
                                </button>
                                <button id="upload" type="button" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg transition-colors">
                                    Upload & Verify
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Upload Progress -->
                        <div id="uploadProgress" class="hidden space-y-4">
                            <div class="flex flex-col items-center justify-center py-12">
                                <svg class="w-16 h-16 text-primary animate-spin mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">Uploading and processing video...</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">This may take a few moments</p>
                            </div>
                        </div>

                        <!-- Step 4: Result -->
                        <div id="result" class="hidden space-y-4">
                            <div id="successResult" class="hidden bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <div class="flex items-start gap-3">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h3 class="font-semibold text-green-900 dark:text-green-100 text-lg mb-2">Verification Successful!</h3>
                                        <p class="text-green-800 dark:text-green-200">Your video has been verified. Your profile now has a verified badge.</p>
                                        <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                            Go to Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div id="failureResult" class="hidden bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                                <div class="flex items-start gap-3">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h3 class="font-semibold text-red-900 dark:text-red-100 text-lg mb-2">Verification Failed</h3>
                                        <p class="text-red-800 dark:text-red-200" id="failureReason"></p>
                                        <button id="tryAgain" type="button" class="inline-block mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                            Try Again
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(app()->environment('local'))
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('verification.video.skip') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                Skip verification (development only)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        let mediaStream = null;
        let mediaRecorder = null;
        let recordedChunks = [];
        let recordingStartTime = null;
        let timerInterval = null;
        const MAX_RECORDING_TIME = 10000; // 10 seconds

        // Get DOM elements
        const preview = document.getElementById('preview');
        const playback = document.getElementById('playback');
        const cameraPreview = document.getElementById('cameraPreview');
        const videoReview = document.getElementById('videoReview');
        const uploadProgress = document.getElementById('uploadProgress');
        const result = document.getElementById('result');
        const startRecordingBtn = document.getElementById('startRecording');
        const stopRecordingBtn = document.getElementById('stopRecording');
        const retakeBtn = document.getElementById('retake');
        const uploadBtn = document.getElementById('upload');
        const tryAgainBtn = document.getElementById('tryAgain');
        const recordingTimer = document.getElementById('recordingTimer');
        const timerDisplay = document.getElementById('timerDisplay');
        const cameraError = document.getElementById('cameraError');

        // Initialize camera
        async function initCamera() {
            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: 1280, height: 720 },
                    audio: false
                });
                preview.srcObject = mediaStream;
                cameraError.classList.add('hidden');
            } catch (error) {
                console.error('Camera access error:', error);
                cameraError.classList.remove('hidden');
            }
        }

        // Start recording
        function startRecording() {
            recordedChunks = [];
            recordingStartTime = Date.now();

            mediaRecorder = new MediaRecorder(mediaStream, {
                mimeType: 'video/webm;codecs=vp8'
            });

            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    recordedChunks.push(event.data);
                }
            };

            mediaRecorder.onstop = () => {
                const blob = new Blob(recordedChunks, { type: 'video/webm' });
                const url = URL.createObjectURL(blob);
                playback.src = url;
                playback.load();

                // Show review screen
                cameraPreview.classList.add('hidden');
                videoReview.classList.remove('hidden');
            };

            mediaRecorder.start();
            startRecordingBtn.classList.add('hidden');
            stopRecordingBtn.classList.remove('hidden');
            recordingTimer.classList.remove('hidden');

            // Start timer
            timerInterval = setInterval(updateTimer, 100);

            // Auto-stop after MAX_RECORDING_TIME
            setTimeout(() => {
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    stopRecording();
                }
            }, MAX_RECORDING_TIME);
        }

        // Update timer display
        function updateTimer() {
            const elapsed = Date.now() - recordingStartTime;
            const seconds = Math.floor(elapsed / 1000);
            const milliseconds = Math.floor((elapsed % 1000) / 100);
            timerDisplay.textContent = `${seconds}:${milliseconds}`;

            // Warning color when approaching max time
            if (elapsed > 8000) {
                recordingTimer.classList.add('bg-yellow-600');
                recordingTimer.classList.remove('bg-red-600');
            }
        }

        // Stop recording
        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                stopRecordingBtn.classList.add('hidden');
                startRecordingBtn.classList.remove('hidden');
                recordingTimer.classList.add('hidden');
                recordingTimer.classList.remove('bg-yellow-600');
                recordingTimer.classList.add('bg-red-600');
                clearInterval(timerInterval);
            }
        }

        // Retake video
        function retake() {
            videoReview.classList.add('hidden');
            cameraPreview.classList.remove('hidden');
            playback.src = '';
            recordedChunks = [];
        }

        // Upload video
        async function upload() {
            const blob = new Blob(recordedChunks, { type: 'video/webm' });
            const formData = new FormData();
            formData.append('video', blob, 'verification.webm');

            videoReview.classList.add('hidden');
            uploadProgress.classList.remove('hidden');

            try {
                const response = await fetch('{{ route("verification.video.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Poll for status
                    pollVerificationStatus();
                } else {
                    showFailure(data.message);
                }
            } catch (error) {
                console.error('Upload error:', error);
                showFailure('Failed to upload video. Please try again.');
            }
        }

        // Poll verification status
        async function pollVerificationStatus() {
            const maxAttempts = 30; // 30 seconds
            let attempts = 0;

            const interval = setInterval(async () => {
                attempts++;

                try {
                    const response = await fetch('{{ route("verification.video.status") }}');
                    const data = await response.json();

                    if (data.success) {
                        if (data.status === 'passed') {
                            clearInterval(interval);
                            showSuccess();
                        } else if (data.status === 'failed') {
                            clearInterval(interval);
                            showFailure(data.failure_reason);
                        } else if (data.status === 'manual_review') {
                            clearInterval(interval);
                            window.location.reload();
                        }
                        // Continue polling for 'pending' and 'processing'
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(interval);
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                }
            }, 1000);
        }

        // Show success
        function showSuccess() {
            uploadProgress.classList.add('hidden');
            result.classList.remove('hidden');
            document.getElementById('successResult').classList.remove('hidden');

            // Stop camera
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
            }
        }

        // Show failure
        function showFailure(reason) {
            uploadProgress.classList.add('hidden');
            result.classList.remove('hidden');
            document.getElementById('failureResult').classList.remove('hidden');
            document.getElementById('failureReason').textContent = reason;
        }

        // Try again
        function tryAgain() {
            result.classList.add('hidden');
            document.getElementById('successResult').classList.add('hidden');
            document.getElementById('failureResult').classList.add('hidden');
            cameraPreview.classList.remove('hidden');
            recordedChunks = [];
        }

        // Event listeners
        startRecordingBtn.addEventListener('click', startRecording);
        stopRecordingBtn.addEventListener('click', stopRecording);
        retakeBtn.addEventListener('click', retake);
        uploadBtn.addEventListener('click', upload);
        tryAgainBtn.addEventListener('click', tryAgain);

        // Initialize on page load
        initCamera();

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
            }
        });
    </script>
</x-app-layout>
