// webauthn-db.js
export async function openWebAuthnDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open("WebAuthnDB", 1);
        request.onupgradeneeded = event => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains("credentials")) {
                db.createObjectStore("credentials", { keyPath: "deviceId" });
            }
        };
        request.onsuccess = e => resolve(e.target.result);
        request.onerror = e => reject(e.target.error);
    });
}

export async function storeCredential(data) {
    const db = await openWebAuthnDB();
    const tx = db.transaction("credentials", "readwrite");
    tx.objectStore("credentials").put(data);
    return new Promise((resolve, reject) => {
        tx.oncomplete = () => resolve(true);
        tx.onerror = () => reject(tx.error);
    });
}

export async function getAllCredentials() {
    const db = await openWebAuthnDB();
    return new Promise(resolve => {
        const tx = db.transaction("credentials", "readonly");
        const req = tx.objectStore("credentials").getAll();
        req.onsuccess = () => resolve(req.result || []);
        req.onerror = () => resolve([]);
    });
}

// -----------------------------
// Device & encoding helpers
// -----------------------------
export function getOrCreateDeviceId() {
    let id = localStorage.getItem("deviceId");
    if (!id) {
        id = crypto.randomUUID();
        localStorage.setItem("deviceId", id);
    }
    return id;
}

export function bufferToBase64(buffer) {
    return btoa(String.fromCharCode(...new Uint8Array(buffer)));
}

export function base64urlToUint8Array(base64url) {
    const padding = "=".repeat((4 - (base64url.length % 4)) % 4);
    const base64 = (base64url + padding).replace(/-/g, "+").replace(/_/g, "/");
    return Uint8Array.from(atob(base64), c => c.charCodeAt(0));
}
// webauthn-db.js - ADD THESE FUNCTIONS

export async function deleteCredential(deviceId) {
    try {
        const db = await openWebAuthnDB();
        const tx = db.transaction("credentials", "readwrite");
        tx.objectStore("credentials").delete(deviceId);
        
        return new Promise((resolve, reject) => {
            tx.oncomplete = () => resolve(true);
            tx.onerror = () => reject(tx.error);
        });
    } catch (error) {
        //console.error('Error deleting credential from IndexedDB:', error);
        throw error;
    }
}

export function clearLocalStorageForDevice(deviceId) {
    try {
        const currentDeviceId = localStorage.getItem("deviceId");
        // Only clear localStorage if we're deleting the current device
        if (currentDeviceId === deviceId) {
            localStorage.removeItem("deviceId");
            //console.log('✅ LocalStorage cleared for current device');
        }
        return true;
    } catch (error) {
        //console.error('Error clearing localStorage:', error);
        return false;
    }
}

// Complete cleanup function
export async function completeDeviceCleanup(deviceId) {
    try {
        // 1. Delete from IndexedDB
        await deleteCredential(deviceId);
        
        // 2. Clear localStorage if it's the current device
        clearLocalStorageForDevice(deviceId);
        
        //console.log('✅ Complete device cleanup done for:', deviceId);
        return true;
    } catch (error) {
        //console.error('❌ Complete cleanup failed:', error);
        return false;
    }
}