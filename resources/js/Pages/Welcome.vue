<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />

    <div>
        <h1>Bienvenue sur Renovation</h1>
        
        <div v-if="canLogin" class="p-6">
            <Link v-if="$page.props.auth.user" 
                  href="/dashboard" 
                  class="btn btn-primary">
                Dashboard
            </Link>

            <template v-else>
                <Link href="/login" 
                      class="btn btn-primary me-2">
                    Log in
                </Link>

                <Link v-if="canRegister" 
                      href="/register" 
                      class="btn btn-secondary">
                    Register
                </Link>
            </template>
        </div>
    </div>
</template>