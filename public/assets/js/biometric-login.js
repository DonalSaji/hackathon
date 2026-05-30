// biometric-login.js
import {
    getAllCredentials,
    getOrCreateDeviceId,
    bufferToBase64,
    base64urlToUint8Array,
    completeDeviceCleanup
} from './webauthn-db.js';

let loginInProgress = false;

/* ------------------------------ Loader ------------------------------ */
function showLoader() {
    let loader = document.getElementById('biometric-loader');
    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'biometric-loader';
        loader.innerHTML = `
            <div style="
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(255,255,255,0.95);
                display: flex; flex-direction: column;
                justify-content: center; align-items: center;
                z-index: 9999; font-family: Arial, sans-serif;
            ">
                <div style="
                    border: 3px solid #f3f3f3;
                    border-top: 3px solid #2c7be5;
                    border-radius: 50%;
                    width: 40px; height: 40px;
                    animation: spin 1s linear infinite;
                    margin-bottom: 15px;
                "></div>
                <p style="margin:0;color:#2c7be5;font-weight:600;font-size:16px;">Verifying Biometric...</p>
                <p style="margin:5px 0 0 0;color:#6c757d;font-size:13px;">Please wait a moment</p>
            </div>
            <style>@keyframes spin {0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}</style>
        `;
        document.body.appendChild(loader);
    }
    loader.style.display = 'flex';
}

function hideLoader() {
    const loader = document.getElementById('biometric-loader');
    if (loader) loader.style.display = 'none';
}

/* ----------------------- Server status checks ----------------------- */
async function checkBiometricEnabled() {
    try {
        const deviceId = getOrCreateDeviceId();
        const url = `/api/user/biometric-status?device_id=${encodeURIComponent(deviceId)}`;
        const response = await fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        if (response.ok) {
            const data = await response.json();
            //console.log('🔍 Biometric status from server:', data);

            // Return both for flexibility
            return {
                enabled: data.biometric_enabled === true,
                valid: data.device_valid !== false
            };
        }

        //console.warn('⚠️ Failed to get biometric status');
        return { enabled: false, valid: false };
    } catch (err) {
        //console.error('❌ Error checking biometric status:', err);
        return { enabled: false, valid: false };
    }
}


async function checkDeviceValid(deviceId) {
    try {
        const res = await fetch(`/api/user/device-valid?device_id=${encodeURIComponent(deviceId)}`, {
            method: 'GET',
            credentials: 'same-origin'
        });
        const data = await res.json();
        //console.log('🔍 Device valid check:', data);
        return Boolean(data.device_valid);
    } catch (err) {
        //console.error('⚠️ Error checking device validity:', err);
        return true; // assume valid if check fails
    }
}

/* ----------------------------- Biometric Login ----------------------------- */
export async function loginWithBiometric() {
    if (loginInProgress) return;
    loginInProgress = true;

    try {
        //console.log('🚀 Starting biometric login...');
        const deviceId = getOrCreateDeviceId();
        const credentials = await getAllCredentials();
        const current = credentials.find(c => c.deviceId === deviceId);

        if (!current) {
            alert("Device not registered for biometric login.");
            return;
        }

        const stillEnabled = await checkBiometricEnabled();
        if (!stillEnabled) {
            alert('Biometric login is disabled for this account.');
            return;
        }

        //console.log('📡 Requesting login options...');
        const res = await fetch("/webauthn/login/options", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": window.csrfToken },
            body: JSON.stringify({ device_name: deviceId })
        });

        if (res.status === 404) {
            //console.warn("⚠️ Device missing on server — cleaning up local data...");
            await completeDeviceCleanup(deviceId);

            $.NotificationApp.send(
                "warning",
                "Your biometric registration was removed remotely. Please log in with OTP to re-register.",
                "top-right",
                "rgba(250, 246, 28, 1)",
                "warning"
            );

            // Optionally hide the button to avoid retrying
            document.getElementById("biometric-section").style.display = "none";
            return;
        }

        if (!res.ok) {
            const errTxt = await res.text();
            //console.error("❌ Server error:", errTxt);
            alert("Device not recognized or server error. Status: " + res.status);
            return;
        }


        const options = await res.json();
        const publicKey = {
            ...options,
            challenge: base64urlToUint8Array(options.challenge),
            allowCredentials: (options.allowCredentials || []).map(c => ({
                ...c,
                id: base64urlToUint8Array(c.id),
                type: c.type || 'public-key'
            }))
        };

        //console.log('👆 Prompting user for biometric...');
        const assertion = await navigator.credentials.get({ publicKey });
        if (!assertion) throw new Error('User cancelled biometric prompt');

        showLoader();
        const verifyRes = await fetch("/webauthn/login/verify", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": window.csrfToken },
            body: JSON.stringify({
                id: assertion.id,
                rawId: bufferToBase64(assertion.rawId),
                type: assertion.type,
                response: {
                    authenticatorData: bufferToBase64(assertion.response.authenticatorData),
                    clientDataJSON: bufferToBase64(assertion.response.clientDataJSON),
                    signature: bufferToBase64(assertion.response.signature),
                    userHandle: assertion.response.userHandle ? bufferToBase64(assertion.response.userHandle) : null
                },
                device_name: deviceId
            })
        });

        const result = await verifyRes.json();
        if (result.success) {
            const loaderText = document.querySelector('#biometric-loader p');
            if (loaderText) {
                loaderText.textContent = 'Login Successful!';
                loaderText.style.color = '#00d97e';
            }
            setTimeout(() => window.location.href = result.redirect || '/dashboard', 800);
        } else {
            hideLoader();
            alert("Login failed: " + (result.message || "Unknown error"));
        }
    } catch (err) {
        hideLoader();
        //console.error('❌ Biometric login error:', err);
       // ✨ SweetAlert replacement for alert()
    await Swal.fire({
        icon: 'error',
        title: 'Biometric Login Error',
        html: `
            <p style="text-align:center;">
                The operation either timed out or was not allowed.
            </p>
        `,
        confirmButtonColor: '#d33',
    });
    } finally {
        loginInProgress = false;
    }
}

/* ---------------------- Initialization Logic ---------------------- */

document.addEventListener("DOMContentLoaded", async () => {
    const btn = document.getElementById("biometricLoginBtn");
    const section = document.getElementById("biometric-section");
    const deviceId = getOrCreateDeviceId();

    if (!btn || !section) return;

    //console.log("🔍 Checking local biometric registration (login page)...");

    try {
        const creds = await getAllCredentials();
        const current = creds.find(c => c.deviceId === deviceId);

        if (!current) {
            //console.warn("⚠️ No local biometric data found. Cleaning up...");
            await completeDeviceCleanup(deviceId);
            section.style.display = "none";
            return;
        }

        // Local data exists → show biometric login button
        section.style.display = "block";
        btn.textContent = "Login with Biometric";
        btn.onclick = (e) => {
            e.preventDefault();
            loginWithBiometric().catch(err => console.error("Login error:", err));
        };
        //console.log("✅ Biometric login ready (local data found)");
    } catch (err) {
        //console.error("❌ Local biometric check failed:", err);
        section.style.display = "none";
    }
});
