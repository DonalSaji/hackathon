import {
    getOrCreateDeviceId,
    storeCredential,
    bufferToBase64,
    base64urlToUint8Array,
    getAllCredentials
} from './webauthn-db.js';

function getDeviceInfo() {
  const ua = navigator.userAgent;
  const uaData = navigator.userAgentData || {};
  const browser = uaData.brands?.[0]?.brand || 'Unknown Browser';

  let os = 'Unknown OS';
  if (/Android/i.test(ua)) os = 'Android';
  else if (/iPhone|iPad|iPod/i.test(ua)) os = 'iOS';
  else if (/Windows/i.test(ua)) os = 'Windows';
  else if (/Mac/i.test(ua)) os = 'macOS';
  else if (/Linux/i.test(ua)) os = 'Linux';

  // ✅ Modern + fallback platform detection
  const platform =
    uaData.platform ||
    (os === 'iOS' ? 'iPhone' : os === 'Android' ? 'Android' : 'Desktop');

  // ✅ Detect device type (mobile/tablet/desktop)
  let deviceType = 'Desktop';
  if (/Mobi/i.test(ua)) deviceType = 'Mobile';
  else if (/Tablet|iPad/i.test(ua)) deviceType = 'Tablet';

  return {
    name: `${os} - ${browser}`,
    os,
    browser,
    platform,
    deviceType,
    userAgent: ua,
    language: navigator.language,
    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
  };
}


export async function registerBiometric() {
    try {
        const deviceId = getOrCreateDeviceId();

        // Prevent duplicate registration
        const credentials = await getAllCredentials();
        const current = credentials.find(c => c.deviceId === deviceId);
        if (current) {
            //console.log("✅ Device already registered for biometrics.");
            return;
        }

        //console.log('🚀 Starting biometric registration...');

        const res = await fetch("/webauthn/register/options", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.csrfToken
            },
            body: JSON.stringify({ device_name: deviceId })
        });

        if (!res.ok) throw new Error('Failed to get registration options from server');
        const options = await res.json();

        options.challenge = base64urlToUint8Array(options.challenge);
        options.user.id = base64urlToUint8Array(options.user.id);

        if (!options.pubKeyCredParams?.length) {
            options.pubKeyCredParams = [
                { type: "public-key", alg: -7 },
                { type: "public-key", alg: -257 }
            ];
        }

        const credential = await navigator.credentials.create({ publicKey: options });

        const verifyRes = await fetch("/webauthn/register/verify", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.csrfToken
            },
            body: JSON.stringify({
                id: credential.id,
                rawId: bufferToBase64(credential.rawId),
                type: credential.type,
                response: {
                    attestationObject: bufferToBase64(credential.response.attestationObject),
                    clientDataJSON: bufferToBase64(credential.response.clientDataJSON)
                },
                device_name: deviceId,
                device_info: getDeviceInfo()
            })
        });

        const result = await verifyRes.json();
        if (!result.success) throw new Error(result.message || "Registration failed on server");

        await storeCredential({
            deviceId,
            userId: bufferToBase64(options.user.id),
            credentialId: credential.id,
            createdAt: new Date()
        });

       // console.log('✅ Biometric registered successfully!');
        await Swal.fire({
            icon: 'success',
            title: 'Biometric Registered!',
            text: 'Your biometric login has been successfully enabled.',
            confirmButtonColor: '#10c469',
        }).then(() => {
            window.location.reload();
        });
    } catch (err) {
        //console.error('❌ Registration error:', err);

        let message = "Biometric registration failed.";
        if (err.name === 'NotAllowedError') {
            message = "Registration was cancelled or not allowed.";
        } else if (err.name === 'NotSupportedError') {
            message = "Biometric authentication is not supported on this device/browser.";
        } else if (err.message) {
            message = err.message;
        }

 await Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: message,
            confirmButtonColor: '#ff5b5b',
        }).then(() => {
            window.location.reload();
        });
    }
}
  

 
// ============================================
// AUTO PROMPT ON DASHBOARD (WITH “DON’T REMIND ME”)
// ============================================
document.addEventListener("DOMContentLoaded", async () => {
    if (!window.PublicKeyCredential) return;

    const path = window.location.pathname;

    if (path.includes('/dashboard')) {
        setTimeout(async () => {
            try {
                const deviceId = getOrCreateDeviceId();
                const credentials = await getAllCredentials();
                const current = credentials.find(c => c.deviceId === deviceId);

                // Always fetch latest reminder status
                const prefRes = await fetch("/webauthn/reminder/status", { cache: "no-store" });
                const prefData = await prefRes.json();
                const reminderEnabled =
                    prefData?.biometric_reminder_enabled === true ||
                    prefData?.biometric_reminder_enabled === 1;

                //console.log("🔍 Reminder status:", reminderEnabled);

                if (reminderEnabled && !current) {
                    //console.log('📱 Device not registered, showing SweetAlert prompt...');

                    const result = await Swal.fire({
                        title: 'Enable Biometric Login?',
                        html: `
                            <p>You haven’t enabled biometric login on this device yet. Enable now?</p>
                            <div style="margin-top:10px; text-align:left;">
                                <input type="checkbox" id="dontRemindChk" />
                                <label for="dontRemindChk"> Don’t remind me again</label>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, enable it',
                        cancelButtonText: 'Later',
                        confirmButtonColor: '#10c469',
                        cancelButtonColor: '#8e9692ff',
                        reverseButtons: true,
                        focusConfirm: true
                        
                    });

                    // 👉 If user clicked "Later"
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        const dontRemind = document.getElementById('dontRemindChk').checked;

                        if (dontRemind) {
                            //console.log('🔕 User opted out of reminders.');

                            try {
                                const disableRes = await fetch("/webauthn/reminder/update", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": window.csrfToken
                                    },
                                    body: JSON.stringify({ reminder_enabled: dontRemind }),
                                    cache: "no-store"
                                });

                                const disableData = await disableRes.json();
                                if (disableData.success) {
                                    window.biometricReminderEnabled = false;
                                    //console.log('✅ Reminder disabled successfully.');
                                }
                            } catch (err) {
                                //console.error('❌ Failed to disable reminder:', err);
                            }
                        } else {
                            //console.log('🕓 User clicked Later but will be reminded again.');
                        }
                        return;
                    }

                    // 👉 If user confirmed
                    if (result.isConfirmed) {
                        await registerBiometric();
                    }
                } else {
                    //console.log("✅ Either already registered or reminder disabled — skipping prompt.");
                }
            } catch (error) {
                //console.error('⚠️ Error checking registration status:', error);
            }
        }, 1500);
    }
});
