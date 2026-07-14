import { initializeApp } from 'firebase/app';
import {
    getAuth,
    GoogleAuthProvider,
    signInWithPopup,
    getIdToken,
} from 'firebase/auth';
import axios from 'axios';

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
};

const firebaseEnabled = Boolean(
    firebaseConfig.apiKey && firebaseConfig.projectId && firebaseConfig.appId,
);

let firebaseAuth = null;

if (firebaseEnabled) {
    const app = initializeApp(firebaseConfig);
    firebaseAuth = getAuth(app);
}

window.signInWithGoogle = async function signInWithGoogle() {
    if (!firebaseAuth) {
        throw new Error('Firebase is not configured.');
    }

    const provider = new GoogleAuthProvider();
    const result = await signInWithPopup(firebaseAuth, provider);
    const idToken = await getIdToken(result.user);

    await axios.post('/auth/firebase', { id_token: idToken });

    window.location.href = '/dashboard';
};
