<div class="barcode-scanner">
    <div class="flex items-center space-x-2">
        <input type="text" 
               id="barcode-input" 
               name="{{ $name ?? 'barcode' }}" 
               value="{{ $value ?? '' }}"
               placeholder="Scan or enter barcode manually"
               class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <button type="button" 
                onclick="startBarcodeScanner()" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg transition duration-200"
                title="Start Camera Scanner">
            📷 Scan
        </button>
        
        <button type="button" 
                onclick="generateRandomBarcode()" 
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg transition duration-200"
                title="Generate Random Barcode">
            🔄 Generate
        </button>
    </div>
    
    <!-- Camera Scanner Modal -->
    <div id="scanner-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">📷 Barcode Scanner</h3>
                <button onclick="stopBarcodeScanner()" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>
            
            <div class="text-center">
                <div id="scanner-video" class="mb-4">
                    <video id="barcode-video" width="300" height="200" class="border rounded"></video>
                </div>
                
                <div class="space-y-2">
                    <p class="text-sm text-gray-600">Position barcode within the camera view</p>
                    <div class="flex space-x-2 justify-center">
                        <button onclick="stopBarcodeScanner()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let barcodeScanner = null;
let videoStream = null;

function startBarcodeScanner() {
    const modal = document.getElementById('scanner-modal');
    const video = document.getElementById('barcode-video');
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Request camera access
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'environment' // Use back camera if available
        } 
    })
    .then(function(stream) {
        videoStream = stream;
        video.srcObject = stream;
        video.play();
        
        // Simple barcode detection simulation
        // In a real implementation, you would use a library like QuaggaJS or ZXing
        setTimeout(() => {
            const simulatedBarcode = 'BC' + Date.now().toString().slice(-8);
            document.getElementById('barcode-input').value = simulatedBarcode;
            stopBarcodeScanner();
            alert('Barcode scanned: ' + simulatedBarcode);
        }, 3000);
    })
    .catch(function(err) {
        console.error('Camera access denied:', err);
        alert('Camera access is required for barcode scanning. Please allow camera access and try again.');
        stopBarcodeScanner();
    });
}

function stopBarcodeScanner() {
    const modal = document.getElementById('scanner-modal');
    const video = document.getElementById('barcode-video');
    
    // Hide modal
    modal.classList.add('hidden');
    
    // Stop video stream
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
    
    // Clear video source
    video.srcObject = null;
}

function generateRandomBarcode() {
    const timestamp = Date.now().toString();
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    const barcode = 'BC' + timestamp.slice(-8) + random;
    document.getElementById('barcode-input').value = barcode;
}

// Auto-focus and enter key support
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode-input');
    if (barcodeInput) {
        // Auto-submit on Enter key (common for barcode scanners)
        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Trigger form submission or search
                const form = this.closest('form');
                if (form) {
                    form.submit();
                }
            }
        });
        
        // Auto-focus for quick scanning
        barcodeInput.addEventListener('focus', function() {
            this.select(); // Select all text for easy replacement
        });
    }
});
</script>

<style>
.barcode-scanner {
    position: relative;
}

#scanner-modal video {
    background: #000;
    border-radius: 8px;
}

/* Barcode input styling */
#barcode-input {
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
}

#barcode-input:focus {
    background-color: #f0f9ff;
}
</style>