<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
});

const submit = () => {
  form.post(
    route('register'),
    {
      onFinish: () => form.reset('password', 'password_confirmation'),
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Paskyra sukurta sėkmingai'),
      onError: () => message.error('Klaida sukuriant paskyrą'),
    },
  );
};
</script>

<template>
    <GuestLayout>
        <Head title="Registracija" />
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="username" value="Slapyvardis" />

                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    autocomplete="user"
                />

                <InputError class="mt-2"  :message="form.errors.username" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="El. paštas" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Slaptažodis" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Patvirtiniti slaptažodį" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Jau turite paskyrą?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registruotis
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
