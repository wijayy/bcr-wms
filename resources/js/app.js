import "./bootstrap";

import Alpine from "alpinejs";

// Tambahkan store global ke Alpine
document.addEventListener('alpine:init', () => {
    Alpine.store('globalFunctions', {
        allowOnlyNumbers(event) {
            event.target.value = event.target.value.replace(/[^0-9]/g, ''); // Hanya izinkan angka
        }
    });
});

window.Alpine = Alpine;

Alpine.start();

