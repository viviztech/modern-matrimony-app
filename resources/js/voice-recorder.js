/**
 * Voice Recorder Component
 * Handles browser-based audio recording with waveform visualization
 */

class VoiceRecorder {
    constructor(options = {}) {
        this.maxDuration = options.maxDuration || 60; // seconds
        this.onRecordingComplete = options.onRecordingComplete || (() => {});
        this.onRecordingStart = options.onRecordingStart || (() => {});
        this.onRecordingStop = options.onRecordingStop || (() => {});
        this.onError = options.onError || ((error) => console.error(error));

        this.mediaRecorder = null;
        this.audioChunks = [];
        this.audioContext = null;
        this.analyser = null;
        this.recordingStartTime = null;
        this.recordingTimer = null;
        this.stream = null;
    }

    /**
     * Check if browser supports audio recording
     */
    static isSupported() {
        return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    }

    /**
     * Start recording audio
     */
    async startRecording() {
        if (!VoiceRecorder.isSupported()) {
            this.onError(new Error('Audio recording not supported in this browser'));
            return;
        }

        try {
            // Request microphone access
            this.stream = await navigator.mediaDevices.getUserMedia({
                audio: {
                    echoCancellation: true,
                    noiseSuppression: true,
                    autoGainControl: true
                }
            });

            // Create audio context for visualization
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const source = this.audioContext.createMediaStreamSource(this.stream);
            this.analyser = this.audioContext.createAnalyser();
            this.analyser.fftSize = 2048;
            source.connect(this.analyser);

            // Initialize MediaRecorder
            const options = { mimeType: this.getSupportedMimeType() };
            this.mediaRecorder = new MediaRecorder(this.stream, options);
            this.audioChunks = [];

            // Handle data available
            this.mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    this.audioChunks.push(event.data);
                }
            };

            // Handle recording stop
            this.mediaRecorder.onstop = () => {
                this.handleRecordingStop();
            };

            // Start recording
            this.mediaRecorder.start();
            this.recordingStartTime = Date.now();
            this.onRecordingStart();

            // Start timer to auto-stop at max duration
            this.recordingTimer = setTimeout(() => {
                this.stopRecording();
            }, this.maxDuration * 1000);

        } catch (error) {
            this.onError(error);
            this.cleanup();
        }
    }

    /**
     * Stop recording audio
     */
    stopRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
            this.mediaRecorder.stop();
            if (this.recordingTimer) {
                clearTimeout(this.recordingTimer);
                this.recordingTimer = null;
            }
        }
    }

    /**
     * Cancel recording and cleanup
     */
    cancelRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
            this.mediaRecorder.stop();
        }
        this.audioChunks = [];
        this.cleanup();
    }

    /**
     * Handle recording stop event
     */
    handleRecordingStop() {
        const duration = this.recordingStartTime
            ? (Date.now() - this.recordingStartTime) / 1000
            : 0;

        // Create audio blob
        const audioBlob = new Blob(this.audioChunks, {
            type: this.getSupportedMimeType()
        });

        // Create audio URL for playback
        const audioUrl = URL.createObjectURL(audioBlob);

        this.onRecordingStop();
        this.onRecordingComplete({
            blob: audioBlob,
            url: audioUrl,
            duration: duration,
            size: audioBlob.size
        });

        this.cleanup();
    }

    /**
     * Get supported MIME type for recording
     */
    getSupportedMimeType() {
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

        return 'audio/webm'; // fallback
    }

    /**
     * Get current recording duration in seconds
     */
    getRecordingDuration() {
        if (!this.recordingStartTime) return 0;
        return (Date.now() - this.recordingStartTime) / 1000;
    }

    /**
     * Get audio volume data for visualization
     */
    getVolumeData() {
        if (!this.analyser) return 0;

        const dataArray = new Uint8Array(this.analyser.frequencyBinCount);
        this.analyser.getByteFrequencyData(dataArray);

        // Calculate average volume
        let sum = 0;
        for (let i = 0; i < dataArray.length; i++) {
            sum += dataArray[i];
        }
        return sum / dataArray.length / 255; // Normalize to 0-1
    }

    /**
     * Get waveform data for visualization
     */
    getWaveformData() {
        if (!this.analyser) return new Uint8Array(0);

        const bufferLength = this.analyser.frequencyBinCount;
        const dataArray = new Uint8Array(bufferLength);
        this.analyser.getByteTimeDomainData(dataArray);
        return dataArray;
    }

    /**
     * Check if currently recording
     */
    isRecording() {
        return this.mediaRecorder && this.mediaRecorder.state === 'recording';
    }

    /**
     * Cleanup resources
     */
    cleanup() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }

        if (this.audioContext) {
            this.audioContext.close();
            this.audioContext = null;
        }

        this.analyser = null;
        this.recordingStartTime = null;

        if (this.recordingTimer) {
            clearTimeout(this.recordingTimer);
            this.recordingTimer = null;
        }
    }
}

/**
 * Audio Player Component
 * Handles playback of voice messages with waveform
 */
class VoicePlayer {
    constructor(audioUrl, options = {}) {
        this.audioUrl = audioUrl;
        this.audio = new Audio(audioUrl);
        this.onPlay = options.onPlay || (() => {});
        this.onPause = options.onPause || (() => {});
        this.onEnded = options.onEnded || (() => {});
        this.onTimeUpdate = options.onTimeUpdate || (() => {});
        this.onError = options.onError || ((error) => console.error(error));

        this.playbackRate = 1.0;
        this.setupEventListeners();
    }

    /**
     * Setup audio event listeners
     */
    setupEventListeners() {
        this.audio.addEventListener('play', () => this.onPlay());
        this.audio.addEventListener('pause', () => this.onPause());
        this.audio.addEventListener('ended', () => this.onEnded());
        this.audio.addEventListener('timeupdate', () => {
            this.onTimeUpdate({
                currentTime: this.audio.currentTime,
                duration: this.audio.duration,
                progress: this.audio.currentTime / this.audio.duration
            });
        });
        this.audio.addEventListener('error', (e) => this.onError(e));
    }

    /**
     * Play audio
     */
    play() {
        return this.audio.play();
    }

    /**
     * Pause audio
     */
    pause() {
        this.audio.pause();
    }

    /**
     * Toggle play/pause
     */
    togglePlay() {
        if (this.isPlaying()) {
            this.pause();
        } else {
            this.play();
        }
    }

    /**
     * Check if audio is playing
     */
    isPlaying() {
        return !this.audio.paused;
    }

    /**
     * Seek to specific time
     */
    seek(time) {
        this.audio.currentTime = time;
    }

    /**
     * Set playback speed
     */
    setPlaybackRate(rate) {
        this.playbackRate = rate;
        this.audio.playbackRate = rate;
    }

    /**
     * Get current time
     */
    getCurrentTime() {
        return this.audio.currentTime;
    }

    /**
     * Get duration
     */
    getDuration() {
        return this.audio.duration;
    }

    /**
     * Get playback progress (0-1)
     */
    getProgress() {
        if (!this.audio.duration) return 0;
        return this.audio.currentTime / this.audio.duration;
    }

    /**
     * Cleanup
     */
    destroy() {
        this.audio.pause();
        this.audio.src = '';
        this.audio = null;
    }
}

/**
 * Format duration in seconds to MM:SS
 */
function formatDuration(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, '0')}`;
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { VoiceRecorder, VoicePlayer, formatDuration };
}
