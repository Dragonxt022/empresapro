<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import BlueButton from '@/Components/buttons/BlueButton.vue';

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      remember: form.remember ? 'on' : '',
    }))
    .post(route('login'), {
      onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <Head title="Login" />

  <div
    class="min-h-screen bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-700 flex justify-center items-center"
  >
    <AuthenticationCard class="w-full sm:w-96 bg-white shadow-lg rounded-lg">
      <template #logo>
        <!-- Pode adicionar logo aqui -->
      </template>

      <div v-if="status" class="mb-4 text-green-600 text-sm text-center">
        {{ status }}
      </div>

      <h1 class="text-3xl font-bold text-center text-gray-800 mb-4">
        Seja Bem Vindo!
      </h1>
      <p class="text-center text-gray-600 mb-6">
        Informe suas credenciais para acessar o sistema
      </p>

      <form @submit.prevent="submit">
        <div>
          <InputLabel for="email" value="E-mail" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-2 block w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-6">
          <InputLabel for="password" value="Senha" />
          <TextInput
            id="password"
            v-model="form.password"
            type="password"
            class="mt-2 block w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
            autocomplete="current-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4 flex items-center justify-between">
          <label class="flex items-center">
            <Checkbox v-model:checked="form.remember" name="remember" />
            <span class="ml-2 text-sm text-gray-600">Relembrar-me</span>
          </label>

          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-sm text-indigo-600 hover:text-indigo-800"
          >
            Esqueceu a senha?
          </Link>
        </div>

        <div class="mt-6">
          <button
            class="w-full py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Entrar
          </button>
        </div>
      </form>
    </AuthenticationCard>
  </div>
</template>

<style scoped>
.bg-gradient-to-r {
  background-image: linear-gradient(to right, #3b82f6, #6366f1, #8b5cf6);
}
</style>
