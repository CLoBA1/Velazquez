@props(['id' => 'scannerModal'])

<div x-data="{
        show: false,
        scanner: null,
        scanResult: null,
        cameras: [],
        activeCameraId: null,
        errorMessage: null,
        initScanner() {
            this.$watch('show', value => {
                if (value) {
                    this.$nextTick(() => this.startScanner());
                } else {
                    this.stopScanner();
                }
            });
        },
        async startScanner() {
            this.errorMessage = null;

            // Wait for library to load (max 5 seconds)
            if (typeof Html5Qrcode === 'undefined') {
                let attempts = 0;
                while (typeof Html5Qrcode === 'undefined' && attempts < 50) {
                    await new Promise(resolve => setTimeout(resolve, 100));
                    attempts++;
                }
            }

            if (typeof Html5Qrcode === 'undefined') {
                this.errorMessage = 'Error: La librería de escaneo no se cargó correctamente. Verifique su conexión y recargue la página.';
                return;
            }

            try {
                // Get cameras
                this.cameras = await Html5Qrcode.getCameras();
                if (this.cameras && this.cameras.length) {
                    this.activeCameraId = this.cameras[0].id; // Default to first camera
                    
                    if (!this.scanner) {
                        this.scanner = new Html5Qrcode('reader');
                    }
                    
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                    
                    await this.scanner.start(
                        { facingMode: 'environment' }, // Prefer back camera
                        config,
                        (decodedText, decodedResult) => {
                            // On success
                            this.onScanSuccess(decodedText, decodedResult);
                        },
                        (errorMessage) => {
                            // parse error, ignore it.
                        }
                    );
                } else {
                    this.errorMessage = 'No se encontraron cámaras.';
                }
            } catch (err) {
                this.errorMessage = 'Error al iniciar la cámara: ' + err;
                console.error(err);
            }
        },
        async stopScanner() {
            if (this.scanner && this.scanner.isScanning) {
                await this.scanner.stop();
                this.scanner.clear();
                this.scanner = null;
            }
        },
        onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);
            this.scanResult = decodedText;
            this.show = false; // Close modal
            
            // Dispatch standard Alpine event
            this.$dispatch('scan-completed', { code: decodedText });
            
            // Dispatch global window event (Brute Force)
            window.dispatchEvent(new CustomEvent('global-scan-completed', { 
                detail: { code: decodedText },
                bubbles: true,
                composed: true
            }));

            this.stopScanner();
        }
    }" x-init="initScanner()" @open-scanner.window="show = true" x-show="show" style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" transition:enter="ease-out duration-300" transition:enter-start="opacity-0"
            transition:enter-end="opacity-100" transition:leave="ease-in duration-200"
            transition:leave-start="opacity-100" transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="show = false">
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show" transition:enter="ease-out duration-300"
            transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            transition:enter-end="opacity-100 translate-y-0 sm:scale-100" transition:leave="ease-in duration-200"
            transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Escanear Código
                    </h3>
                    <div class="mt-2">
                        <div id="reader" width="100%"></div>
                        <p x-show="errorMessage" x-text="errorMessage" class="text-red-500 text-sm mt-2"></p>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="button" @click="show = false"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>