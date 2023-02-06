<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  mustVerifyEmail: Boolean,
  status: String,
});

const { user } = usePage().props.auth;

const form = useForm({
  username: user.username,
  email: user.email,
});
function updateUsername() {
  form.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => [message.success('Vartotojo informacija atnaujinta sėkmingai')],
  });
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profilio informacija</h2>

            <p class="mt-1 text-sm text-gray-600">
                Atnaujinkite savo paskyros profilio informaciją ir el. pašto adresą.
            </p>
        </header>

        <form @submit.prevent="updateUsername" class="mt-6 space-y-6">
            <div>
                <InputLabel for="username" value="Vartotojo vardas" />

                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div>
                <InputLabel for="email" value="El. Paštas" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Jūsų el. pašto adresas nepatvirtintas.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Paspauskite čia, kad iš naujo išsiųstumėte patvirtinimo laišką.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    Nauja patvirtinimo nuoroda buvo išsiųsta į jūsų el. pašto adresą.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Išsaugoti</PrimaryButton>
            </div>
        </form>
    </section>
</template>
