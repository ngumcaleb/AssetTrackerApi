@extends('layouts.dashboard')

@section('title', 'Scan QR')

@section('content')
<div class="max-w-lg mx-auto animate-fade-in">
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Scan QR Code</h1>
        <p class="text-on-surface-variant mt-1">Point your camera at an asset's QR label to instantly view it.</p>
    </div>

    @if(session('error'))
        <div class="flex items-center gap-2.5 bg-red-50 border border-red-200/50 text-red-700 text-sm font-medium px-4 py-3 rounded-xl mb-4">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Insecure Context Alert --}}
    <div id="insecure-context-alert" class="hidden mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200/70 text-amber-800 text-sm">
        <div class="flex gap-3">
            <svg class="w-5 h-5 shrink-0 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            <div>
                <p class="font-semibold text-amber-900">HTTP Connection Detected</p>
                <p class="mt-1 text-xs text-amber-700 leading-relaxed">
                    Mobile web browsers require an <strong>HTTPS connection</strong> or <code>localhost</code> to grant camera access. If camera permissions are blocked on your phone, test via HTTPS, use localhost, or use the manual search below.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6">
        {{-- Camera Selector & Torch Bar --}}
        <div id="camera-controls" class="hidden mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex-1 min-w-[200px]">
                <label for="camera-select" class="block text-xs font-semibold text-on-surface-variant mb-1">Select Camera</label>
                <select id="camera-select" class="w-full h-9 px-3 bg-stone-50 border border-outline-variant/40 rounded-lg text-xs font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    <option value="">Detecting cameras…</option>
                </select>
            </div>
            <button id="torch-btn" type="button" class="hidden h-9 px-3.5 mt-auto bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                <span id="torch-label">Flashlight</span>
            </button>
        </div>

        {{-- QR Reader Viewport --}}
        <div id="qr-reader" class="w-full mx-auto overflow-hidden rounded-xl" style="max-width: 400px;"></div>

        {{-- Explicit Request Permission Button State --}}
        <div id="start-camera-wrapper" class="text-center py-6">
            <div class="w-14 h-14 bg-brand-50 text-brand-700 rounded-2xl mx-auto mb-3 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.16a15.53 15.53 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" /></svg>
            </div>
            <p class="text-sm font-semibold text-on-surface mb-1">Camera Permission Required</p>
            <p class="text-xs text-on-surface-variant mb-4 max-w-xs mx-auto">Tap below to allow camera access for scanning asset QR tags.</p>
            <button id="start-camera-btn" type="button" class="h-11 px-6 bg-brand-700 hover:bg-brand-800 text-white font-semibold rounded-xl text-sm transition-all shadow-sm">
                Enable Camera
            </button>
        </div>

        {{-- Scanning Indicator & Results --}}
        <div id="qr-reader-results" class="text-center mt-4 hidden">
            <div id="qr-status-msg" class="flex items-center justify-center gap-2 text-emerald-700 bg-emerald-50 px-4 py-3 rounded-xl text-sm font-semibold">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>
                Asset found — redirecting…
            </div>
        </div>
    </div>

    {{-- Manual Lookup Form --}}
    <div class="mt-6 text-center">
        <p class="text-sm text-on-surface-variant mb-3">Or enter an asset tag or ID manually:</p>
        <form id="lookup-form" method="POST" action="{{ route('scan.lookup') }}" class="flex gap-3 max-w-sm mx-auto">
            @csrf
            <input type="text" id="lookup-input" name="q" placeholder="e.g. AST-2026-0001 or 42" autocomplete="off"
                class="flex-1 h-11 px-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
            <button type="submit" class="h-11 px-5 bg-brand-700 hover:bg-brand-800 text-white font-semibold rounded-xl text-sm transition-all duration-200 shrink-0">Go</button>
        </form>
    </div>
</div>
@endsection

@push('head')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<style>
    #qr-reader {
        border: none !important;
    }
    #qr-reader video {
        border-radius: 0.75rem !important;
        object-fit: cover !important;
    }
    #qr-reader__scan_region {
        background: #f1efe9;
        border-radius: 0.75rem;
        min-height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #qr-reader__scan_region img,
    #qr-reader__scan_region video {
        border-radius: 0.75rem;
    }
    #qr-reader__dashboard_section {
        padding: 0.5rem 0;
    }
    #qr-reader__dashboard_section_csr {
        text-align: center;
    }
    #qr-reader__dashboard_section_swap {
        text-align: center;
    }
    #qr-reader__dashboard_section_csr button {
        background: #f9e6e6;
        color: #7a1c1c;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        border: 0;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    #qr-reader__dashboard_section_csr button:hover {
        background: #f3cccc;
    }
    #qr-reader__dashboard_section_swap a {
        color: #6b6862;
        font-size: 0.75rem;
        text-decoration: underline;
        text-underline-offset: 2px;
        transition: color 0.2s;
    }
    #qr-reader__dashboard_section_swap a:hover {
        color: #7a1c1c;
    }
    #qr-reader__status_span {
        font-size: 0.75rem;
        color: #6b6862;
        opacity: 0.7;
    }
    #qr-reader__dashboard_section_csr span {
        font-size: 0.875rem;
        color: #6b6862;
    }
    @media (max-width: 640px) {
        #qr-reader__scan_region {
            min-height: 260px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    var reader = null;
    var currentCameraId = null;
    var isScanning = false;
    var torchEnabled = false;

    // Check secure context
    if (!window.isSecureContext && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
        var alertEl = document.getElementById('insecure-context-alert');
        if (alertEl) alertEl.classList.remove('hidden');
    }

    var startBtnWrapper = document.getElementById('start-camera-wrapper');
    var startBtn = document.getElementById('start-camera-btn');
    var cameraControls = document.getElementById('camera-controls');
    var cameraSelect = document.getElementById('camera-select');
    var torchBtn = document.getElementById('torch-btn');
    var torchLabel = document.getElementById('torch-label');
    var qrResults = document.getElementById('qr-reader-results');

    var qrBoxSize = Math.min(window.innerWidth - 80, 280);
    var scanConfig = {
        fps: 10,
        qrbox: { width: qrBoxSize, height: qrBoxSize },
        aspectRatio: 1.0
    };

    function onScanSuccess(decodedText) {
        if (!isScanning) return;
        isScanning = false;

        if (reader) {
            try { reader.pause(); } catch(e) {}
        }

        qrResults.classList.remove('hidden');
        document.getElementById('qr-status-msg').innerHTML =
            '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg> Asset found — redirecting…';

        var url = decodedText.trim();
        
        // 1. Direct matches for asset URL pattern (e.g., http://.../assets/12)
        var match = url.match(/\/assets\/(\d+)(?:\/|$)/);
        if (match) {
            window.location.href = '{{ url('') }}' + '/assets/' + match[1];
            return;
        }

        // 2. Direct numeric ID (e.g. "12")
        if (/^\d+$/.test(url)) {
            window.location.href = '{{ url('') }}' + '/assets/' + url;
            return;
        }

        // 3. Asset tag format (e.g. AST-2026-0001 or any string tag) -> lookup
        var input = document.getElementById('lookup-input');
        var form = document.getElementById('lookup-form');
        if (input && form) {
            input.value = url;
            form.submit();
            return;
        }

        // Fallback for unrecognised code
        qrResults.innerHTML =
            '<div class="flex items-center justify-center gap-2 text-amber-700 bg-amber-50 px-4 py-3 rounded-xl text-sm font-semibold">Unrecognised code ("' + url + '") — try again</div>';
        setTimeout(function() {
            qrResults.classList.add('hidden');
            if (reader) {
                try {
                    reader.resume();
                    isScanning = true;
                } catch(e) {}
            }
        }, 2500);
    }

    function showError(icon, title, msg, allowRetry) {
        if (startBtnWrapper) startBtnWrapper.classList.add('hidden');
        var retryHtml = allowRetry
            ? '<button onclick="location.reload()" class="mt-4 px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-semibold rounded-lg transition-all">Retry Camera</button>'
            : '';
        document.getElementById('qr-reader').innerHTML =
            '<div class="text-center py-8 px-4"><div class="w-12 h-12 bg-stone-100 rounded-xl mx-auto mb-3 flex items-center justify-center text-stone-600">' + icon + '</div><p class="text-sm font-semibold text-on-surface">' + title + '</p><p class="text-xs text-on-surface-variant/80 mt-1 max-w-xs mx-auto leading-relaxed">' + msg + '</p>' + retryHtml + '</div>';
    }

    function startScanner(cameraId) {
        if (!reader) {
            reader = new Html5Qrcode('qr-reader');
        }

        var cameraConstraint = cameraId
            ? { deviceId: { exact: cameraId } }
            : { facingMode: "environment" };

        if (startBtnWrapper) startBtnWrapper.classList.add('hidden');

        reader.start(cameraConstraint, scanConfig, onScanSuccess, function() {})
            .then(function() {
                isScanning = true;
                currentCameraId = cameraId;
                checkTorchSupport();
            })
            .catch(function(err) {
                console.error("Camera start error:", err);
                if (cameraId) {
                    showError(
                        '<svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>',
                        'Selected Camera Unavailable',
                        'Try switching to another camera or use manual lookup below.',
                        true
                    );
                } else {
                    fallbackToGetCameras();
                }
            });
    }

    function fallbackToGetCameras() {
        Html5Qrcode.getCameras().then(function(cameras) {
            if (cameras && cameras.length > 0) {
                populateCameraSelect(cameras);
                
                // Select rear/back camera if found
                var selectedCam = cameras[0];
                for (var i = 0; i < cameras.length; i++) {
                    var label = cameras[i].label.toLowerCase();
                    if (label.indexOf('back') !== -1 || label.indexOf('rear') !== -1 || label.indexOf('environment') !== -1 || label.indexOf('0') !== -1) {
                        selectedCam = cameras[i];
                        break;
                    }
                }
                cameraSelect.value = selectedCam.id;
                startScanner(selectedCam.id);
            } else {
                showError(
                    '<svg class="w-6 h-6 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.16a15.53 15.53 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" /></svg>',
                    'No Camera Detected',
                    'Please make sure your device has a working camera or use manual lookup below.',
                    true
                );
            }
        }).catch(function(err) {
            console.error("getCameras error:", err);
            showError(
                '<svg class="w-6 h-6 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" /></svg>',
                'Camera Access Blocked',
                'Please check your mobile browser permissions to grant camera access, or enter your asset tag manually below.',
                true
            );
        });
    }

    function populateCameraSelect(cameras) {
        cameraSelect.innerHTML = '';
        cameras.forEach(function(cam, idx) {
            var opt = document.createElement('option');
            opt.value = cam.id;
            opt.textContent = cam.label || ('Camera ' + (idx + 1));
            cameraSelect.appendChild(opt);
        });
        if (cameras.length > 1) {
            cameraControls.classList.remove('hidden');
        }
    }

    function checkTorchSupport() {
        if (!reader) return;
        try {
            var capabilities = reader.getRunningTrackCapabilities();
            if (capabilities && capabilities.torch) {
                torchBtn.classList.remove('hidden');
            } else {
                torchBtn.classList.add('hidden');
            }
        } catch(e) {
            torchBtn.classList.add('hidden');
        }
    }

    // Switch Camera Listener
    cameraSelect.addEventListener('change', function() {
        var selectedId = this.value;
        if (selectedId && reader) {
            reader.stop().then(function() {
                startScanner(selectedId);
            }).catch(function() {
                startScanner(selectedId);
            });
        }
    });

    // Toggle Flashlight Listener
    torchBtn.addEventListener('click', function() {
        if (!reader) return;
        torchEnabled = !torchEnabled;
        reader.applyVideoConstraints({
            advanced: [{ torch: torchEnabled }]
        }).then(function() {
            torchLabel.textContent = torchEnabled ? 'Flashlight On' : 'Flashlight';
            torchBtn.classList.toggle('bg-amber-100', torchEnabled);
            torchBtn.classList.toggle('text-amber-800', torchEnabled);
        }).catch(function(err) {
            console.warn('Torch toggle failed:', err);
        });
    });

    // Explicit User Tap Trigger for Mobile Permission
    function initCameraFlow() {
        // Request media permission explicitly via getUserMedia to prompt browser permission dialog
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                .then(function(stream) {
                    // Stop initial stream tracks before passing control to Html5Qrcode
                    stream.getTracks().forEach(function(track) { track.stop(); });
                    
                    // Fetch camera devices and start scanner
                    Html5Qrcode.getCameras().then(function(cameras) {
                        if (cameras && cameras.length > 0) {
                            populateCameraSelect(cameras);
                            var bestCam = cameras[0];
                            for (var i = 0; i < cameras.length; i++) {
                                var label = cameras[i].label.toLowerCase();
                                if (label.indexOf('back') !== -1 || label.indexOf('rear') !== -1 || label.indexOf('environment') !== -1 || label.indexOf('0') !== -1) {
                                    bestCam = cameras[i];
                                    break;
                                }
                            }
                            cameraSelect.value = bestCam.id;
                            startScanner(bestCam.id);
                        } else {
                            startScanner();
                        }
                    }).catch(function() {
                        startScanner();
                    });
                })
                .catch(function(err) {
                    console.warn("Explicit getUserMedia rejected/failed:", err);
                    startScanner();
                });
        } else {
            startScanner();
        }
    }

    if (startBtn) {
        startBtn.addEventListener('click', function() {
            initCameraFlow();
        });
    }

    // Auto-attempt permission request on page load
    initCameraFlow();
})();
</script>
@endpush
