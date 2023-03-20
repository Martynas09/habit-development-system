<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

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

<template>
    <GuestLayout>

        <Head title="Slaptažodžio atkurimas" />

        <div class="mb-4 text-sm text-gray-600">
            Pamiršote slaptažodį? Jokių problemų. Tiesiog pateikite savo el. pašto adresą ir mes jums atsiųsime el.
            paštu slaptažodžio atkurimo nuorodą, kurią naudodami galėsite susikurti naują slaptažodį.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="El. pašto adresas:" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Išsiųsti slaptažodžio atkūrimo nuoroda
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
