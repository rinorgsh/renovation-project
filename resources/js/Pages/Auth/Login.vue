<template>
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-container">
            <img 
                src="/image/logo.png" 
                alt="Logo Renovation" 
                class="logo"
            />
        </div>

        <div class="login-box">
            <h2 class="login-title">Connexion</h2>

            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        id="email"
                        type="email" 
                        v-model="form.email"
                        class="form-control"
                        required
                        autofocus
                    >
                    <div v-if="form.errors.email" class="error-message">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        id="password"
                        type="password" 
                        v-model="form.password"
                        class="form-control"
                        required
                    >
                    <div v-if="form.errors.password" class="error-message">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="form-group remember-me">
                    <label>
                        <input 
                            type="checkbox" 
                            v-model="form.remember"
                        > Se souvenir de moi
                    </label>
                </div>

                <button 
                    type="submit" 
                    class="login-button" 
                    :disabled="form.processing"
                >
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>
.login-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    padding: 20px;
}

.logo-container {
    position: absolute;
    top: 20px;
    left: 20px;
}

.logo {
    max-width: 200px;
    height: auto;
}

.login-box {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

.login-title {
    text-align: center;
    margin-bottom: 2rem;
    font-size: 1.5rem;
    color: #333;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #666;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #4CAF50;
    outline: none;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

.remember-me {
    display: flex;
    align-items: center;
}

.remember-me label {
    margin: 0;
    margin-left: 0.5rem;
    cursor: pointer;
}

.login-button {
    width: 100%;
    padding: 0.75rem;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.login-button:hover {
    background: #45a049;
}

.login-button:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

@media (max-width: 480px) {
    .login-box {
        padding: 1.5rem;
    }

    .logo {
        max-width: 150px;
    }
}
</style>