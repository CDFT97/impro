<template>
    <Head title="Forgot Password" />

    <GuestLayout>
        <Link href="/" class="flex justify-center items-center mb-4">
            <ApplicationLogo class="h-20 w-full text-gray-500 fill-current" />
        </Link>

        <div class="mb-4 text-sm text-gray-600">
            Olvió su contraseña? No hay problema. Ingrese su correo y le enviaremos un link para restablecer su contraseña.
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Correo" />
                <TextInput id="email" type="email" class="block mt-1 w-full" v-model="form.email" required autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex justify-end items-center mt-4">
                <PrimaryButton class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Restablecer contraseña
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>
