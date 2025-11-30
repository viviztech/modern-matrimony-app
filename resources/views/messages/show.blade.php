@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-4rem)] flex flex-col">
    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col px-4 sm:px-6 lg:px-8 py-4">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-t-2xl shadow-xl p-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <a href="{{ route('messages') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                @if($otherUser->primaryPhoto)
                    <img src="{{ $otherUser->primaryPhoto->url }}"
                         alt="{{ $otherUser->name }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-primary">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold">
                        {{ substr($otherUser->name, 0, 1) }}
                    </div>
                @endif

                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $otherUser->name }}
                        @if($otherUser->age)
                            <span class="text-gray-500 dark:text-gray-400 font-normal">, {{ $otherUser->age }}</span>
                        @endif
                    </h2>
                    @if($otherUser->profile)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $otherUser->profile->occupation ?? 'Professional' }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Video Call Button -->
                <button onclick="initiateCall('video')" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" title="Video Call">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </button>

                <!-- Audio Call Button -->
                <button onclick="initiateCall('audio')" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" title="Audio Call">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </button>

                <!-- View Profile Button -->
                <a href="{{ route('profile.show') }}?user={{ $otherUser->id }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>

                <!-- More Options -->
                <button class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messagesContainer" class="flex-1 bg-white dark:bg-gray-800 overflow-y-auto p-6 space-y-4">
            @forelse($messages as $message)
                @php
                    $isSent = $message->sender_id === auth()->id();
                @endphp
                <div class="flex {{ $isSent ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-end gap-2 max-w-md {{ $isSent ? 'flex-row-reverse' : '' }}">
                        @if(!$isSent)
                            @if($otherUser->primaryPhoto)
                                <img src="{{ $otherUser->primaryPhoto->url }}"
                                     alt="{{ $otherUser->name }}"
                                     class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                    {{ substr($otherUser->name, 0, 1) }}
                                </div>
                            @endif
                        @endif

                        <div>
                            <div class="px-4 py-2 rounded-2xl {{ $isSent ? 'bg-gradient-to-r from-primary to-secondary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                @if($message->type === 'voice' && $message->media_url)
                                    <!-- Voice Message Player -->
                                    <div class="flex items-center gap-3 min-w-[200px]">
                                        <button onclick="toggleAudioPlayback('{{ $message->id }}')" class="flex-shrink-0 p-2 rounded-full {{ $isSent ? 'bg-white/20 hover:bg-white/30' : 'bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500' }} transition-colors">
                                            <svg class="w-5 h-5 voice-play-icon-{{ $message->id }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-5 h-5 voice-pause-icon-{{ $message->id }} hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 h-1 bg-white/30 dark:bg-gray-500 rounded-full overflow-hidden">
                                                    <div class="voice-progress-{{ $message->id }} h-full {{ $isSent ? 'bg-white' : 'bg-primary' }} rounded-full" style="width: 0%"></div>
                                                </div>
                                                <span class="voice-time-{{ $message->id }} text-xs font-mono">0:00</span>
                                            </div>
                                        </div>
                                        <audio class="hidden voice-audio-{{ $message->id }}" src="{{ $message->media_url }}" preload="metadata"></audio>
                                    </div>
                                @else
                                    <p class="text-sm break-words">{{ $message->content }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 mt-1 {{ $isSent ? 'justify-end' : '' }}">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $message->created_at->diffForHumans() }}
                                </span>
                                @if($isSent)
                                    @if($message->read_at)
                                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <svg class="w-4 h-4 text-primary -ml-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @elseif($message->delivered_at)
                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="bg-white dark:bg-gray-800 rounded-b-2xl shadow-xl p-4 border-t border-gray-200 dark:border-gray-700">
            <!-- Voice Recording UI (hidden by default) -->
            <div id="voiceRecordingUI" class="hidden mb-3 p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border-2 border-red-200 dark:border-red-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Recording voice message</span>
                            <span id="recordingTimer" class="text-sm text-red-600 dark:text-red-400 font-mono">0:00</span>
                        </div>
                        <!-- Waveform visualization -->
                        <div class="flex items-center gap-0.5 h-8">
                            <div id="waveform" class="flex items-center gap-0.5 h-full flex-1"></div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="cancelVoiceRecording()" class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" title="Cancel">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <button type="button" onclick="stopVoiceRecording()" class="p-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" title="Stop & Send">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Normal Message Form -->
            <form id="messageForm" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <textarea id="messageInput"
                              name="content"
                              rows="1"
                              placeholder="Type your message..."
                              class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-0 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary resize-none"
                              style="min-height: 48px; max-height: 120px;"
                              required></textarea>
                </div>

                <!-- Voice Record Button -->
                <button type="button" id="voiceRecordBtn" onclick="startVoiceRecording()"
                        class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary p-3 rounded-xl transition-colors flex-shrink-0"
                        title="Record voice message">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                </button>

                <button type="submit"
                        class="bg-gradient-to-r from-primary to-secondary text-white p-3 rounded-xl hover:shadow-glow transition-all duration-200 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const conversationId = {{ $conversation->id }};
    let lastMessageId = {{ $messages->last()->id ?? 0 }};
    let polling;

    // Auto-resize textarea
    const textarea = document.getElementById('messageInput');
    textarea.addEventListener('input', function() {
        this.style.height = '48px';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Handle form submission
    document.getElementById('messageForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const content = textarea.value.trim();
        if (!content) return;

        try {
            const response = await fetch(`/messages/${conversationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content })
            });

            const data = await response.json();

            if (data.success) {
                textarea.value = '';
                textarea.style.height = '48px';
                appendMessage(data.message, true);
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });

    // Poll for new messages
    async function pollMessages() {
        try {
            const response = await fetch(`/messages/${conversationId}/messages?since=${lastMessageId}`);
            const data = await response.json();

            data.messages.forEach(message => {
                if (message.id > lastMessageId) {
                    appendMessage(message, false);
                    lastMessageId = message.id;
                }
            });
        } catch (error) {
            console.error('Error polling messages:', error);
        }
    }

    // Append message to container
    function appendMessage(message, isSent) {
        const container = document.getElementById('messagesContainer');
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isSent ? 'justify-end' : 'justify-start'}`;

        messageDiv.innerHTML = `
            <div class="flex items-end gap-2 max-w-md ${isSent ? 'flex-row-reverse' : ''}">
                <div>
                    <div class="px-4 py-2 rounded-2xl ${isSent ? 'bg-gradient-to-r from-primary to-secondary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'}">
                        <p class="text-sm break-words">${escapeHtml(message.content)}</p>
                    </div>
                    <div class="flex items-center gap-2 mt-1 ${isSent ? 'justify-end' : ''}">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Just now</span>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(messageDiv);
        container.scrollTop = container.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Scroll to bottom on load
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;

    // Start polling every 3 seconds
    polling = setInterval(pollMessages, 3000);

    // Stop polling when user leaves
    window.addEventListener('beforeunload', () => {
        clearInterval(polling);
    });

    // Video call functionality
    async function initiateCall(callType) {
        try {
            const response = await fetch(`/video-calls/initiate/{{ $otherUser->id }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    call_type: callType,
                    conversation_id: conversationId
                })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = `/video-call/${data.call.id}`;
            } else {
                alert(data.message || 'Unable to initiate call');
            }
        } catch (error) {
            console.error('Error initiating call:', error);
            alert('Failed to initiate call. Please try again.');
        }
    }

    // Voice Message Recording
    let mediaRecorder = null;
    let audioChunks = [];
    let recordingStartTime = null;
    let recordingTimerInterval = null;
    let waveformInterval = null;
    let audioContext = null;
    let analyser = null;

    async function startVoiceRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Voice recording is not supported in this browser');
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: {
                    echoCancellation: true,
                    noiseSuppression: true,
                    autoGainControl: true
                }
            });

            // Setup audio context for visualization
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const source = audioContext.createMediaStreamSource(stream);
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 2048;
            source.connect(analyser);

            // Setup MediaRecorder
            const mimeType = getSupportedMimeType();
            mediaRecorder = new MediaRecorder(stream, { mimeType });
            audioChunks = [];

            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    audioChunks.push(event.data);
                }
            };

            mediaRecorder.onstop = handleRecordingStop;

            // Start recording
            mediaRecorder.start();
            recordingStartTime = Date.now();

            // Show recording UI
            document.getElementById('voiceRecordingUI').classList.remove('hidden');
            document.getElementById('messageForm').classList.add('hidden');

            // Start timer
            updateRecordingTimer();
            recordingTimerInterval = setInterval(updateRecordingTimer, 100);

            // Start waveform visualization
            visualizeWaveform();
            waveformInterval = setInterval(visualizeWaveform, 100);

            // Auto-stop after 60 seconds
            setTimeout(() => {
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    stopVoiceRecording();
                }
            }, 60000);

        } catch (error) {
            console.error('Error starting recording:', error);
            alert('Failed to access microphone. Please check permissions.');
        }
    }

    function stopVoiceRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            clearInterval(recordingTimerInterval);
            clearInterval(waveformInterval);
        }
    }

    function cancelVoiceRecording() {
        if (mediaRecorder) {
            mediaRecorder.stop();
            audioChunks = [];
            clearInterval(recordingTimerInterval);
            clearInterval(waveformInterval);
        }
        hideRecordingUI();
        cleanup();
    }

    async function handleRecordingStop() {
        const duration = recordingStartTime ? (Date.now() - recordingStartTime) / 1000 : 0;
        const audioBlob = new Blob(audioChunks, { type: getSupportedMimeType() });

        hideRecordingUI();

        // Send voice message
        try {
            const formData = new FormData();
            formData.append('voice', audioBlob, `voice_${Date.now()}.webm`);
            formData.append('duration', Math.floor(duration));

            const response = await fetch(`/messages/${conversationId}/voice`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                appendMessage(data.message, true);
            } else {
                alert(data.message || 'Failed to send voice message');
            }
        } catch (error) {
            console.error('Error sending voice message:', error);
            alert('Failed to send voice message');
        }

        cleanup();
    }

    function updateRecordingTimer() {
        if (!recordingStartTime) return;
        const duration = (Date.now() - recordingStartTime) / 1000;
        const mins = Math.floor(duration / 60);
        const secs = Math.floor(duration % 60);
        document.getElementById('recordingTimer').textContent =
            `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    function visualizeWaveform() {
        if (!analyser) return;

        const waveformContainer = document.getElementById('waveform');
        const dataArray = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(dataArray);

        // Sample 20 bars
        const bars = 20;
        let html = '';
        for (let i = 0; i < bars; i++) {
            const index = Math.floor((i / bars) * dataArray.length);
            const value = dataArray[index];
            const height = Math.max(10, (value / 255) * 100);
            html += `<div class="w-1 bg-red-500 rounded-full transition-all" style="height: ${height}%"></div>`;
        }
        waveformContainer.innerHTML = html;
    }

    function hideRecordingUI() {
        document.getElementById('voiceRecordingUI').classList.add('hidden');
        document.getElementById('messageForm').classList.remove('hidden');
    }

    function cleanup() {
        if (audioContext) {
            audioContext.close();
            audioContext = null;
        }
        analyser = null;
        mediaRecorder = null;
        audioChunks = [];
        recordingStartTime = null;
    }

    function getSupportedMimeType() {
        const types = [
            'audio/webm;codecs=opus',
            'audio/webm',
            'audio/ogg;codecs=opus',
            'audio/mp4'
        ];
        for (const type of types) {
            if (MediaRecorder.isTypeSupported(type)) {
                return type;
            }
        }
        return 'audio/webm';
    }

    // Voice Message Playback
    const activePlayers = {};

    function toggleAudioPlayback(messageId) {
        const audio = document.querySelector(`.voice-audio-${messageId}`);
        if (!audio) return;

        // Pause all other audio players
        Object.keys(activePlayers).forEach(id => {
            if (id !== messageId.toString()) {
                activePlayers[id].pause();
            }
        });

        if (!activePlayers[messageId]) {
            setupAudioPlayer(messageId, audio);
        }

        if (audio.paused) {
            audio.play();
        } else {
            audio.pause();
        }
    }

    function setupAudioPlayer(messageId, audio) {
        const playIcon = document.querySelector(`.voice-play-icon-${messageId}`);
        const pauseIcon = document.querySelector(`.voice-pause-icon-${messageId}`);
        const progress = document.querySelector(`.voice-progress-${messageId}`);
        const timeDisplay = document.querySelector(`.voice-time-${messageId}`);

        audio.addEventListener('play', () => {
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
        });

        audio.addEventListener('pause', () => {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
        });

        audio.addEventListener('ended', () => {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            progress.style.width = '0%';
            audio.currentTime = 0;
        });

        audio.addEventListener('timeupdate', () => {
            const percentage = (audio.currentTime / audio.duration) * 100;
            progress.style.width = `${percentage}%`;

            const mins = Math.floor(audio.currentTime / 60);
            const secs = Math.floor(audio.currentTime % 60);
            timeDisplay.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
        });

        audio.addEventListener('loadedmetadata', () => {
            const mins = Math.floor(audio.duration / 60);
            const secs = Math.floor(audio.duration % 60);
            timeDisplay.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
        });

        activePlayers[messageId] = audio;
    }
</script>
@endpush
@endsection
