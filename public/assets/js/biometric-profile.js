import { getOrCreateDeviceId, getAllCredentials } from '/assets/js/webauthn-db.js';
import { registerBiometric } from '/assets/js/biometric-register.js';

document.addEventListener("DOMContentLoaded", () => {
    const manualBtn = document.getElementById("manualRegisterBiometricBtn");
    const reminderCheckbox = document.getElementById("reminder_enabled");
    const biometricToggle = document.getElementById("biometricToggle");

    // 🔹 Manual registration button
    if (manualBtn) {
        manualBtn.addEventListener("click", async () => {
            try {
                const deviceId = getOrCreateDeviceId();
                const credentials = await getAllCredentials();
                const current = credentials.find(c => c.deviceId === deviceId);

                if (current) {
                    $.NotificationApp.send(
                        "Error",
                        "This device is already registered for biometric login.",
                        "top-right",
                        "rgba(0,0,0,0.2)",
                        "error"
                    );
                    return;
                }

                const result = await Swal.fire({
                    title: 'Register this device?',
                    text: 'Would you like to enable biometric login for this device?',
                    icon: 'question',
                    reverseButtons: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, register',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#10c469',
                    cancelButtonColor: '#ff5b5b',
                });

                if (result.isConfirmed) {
                    manualBtn.innerHTML =
                        '<i class="mdi mdi-loading mdi-spin me-1"></i> Registering...';
                    manualBtn.disabled = true;

                    await registerBiometric();

                    manualBtn.innerHTML =
                        '<i class="mdi mdi-check-circle-outline me-1"></i> Registered';
                    manualBtn.classList.remove('btn-outline-success');
                    manualBtn.classList.add('btn-success');
                    
                    loadBiometricDevices();
                    window.location.reload();
                }
            } catch (error) {
                //console.error("❌ Manual biometric registration failed:", error);
                $.NotificationApp.send(
                    "Error",
                    "Something went wrong while registering biometric.",
                    "top-right",
                    "rgba(0,0,0,0.2)",
                    "error"
                );
            }
        });
    }
    // 🔹 Load registered devices on page load
    if (typeof loadBiometricDevices === "function") {
        loadBiometricDevices();
    } else {
        //console.warn("⚠️ loadBiometricDevices not found — ensure it's globally available in Blade.");
    }
});
