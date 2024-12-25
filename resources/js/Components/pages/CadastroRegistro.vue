<script setup>
import BlueButton from '@/Components/buttons/BlueButton.vue';
import GreenButton from '@/Components/buttons/GreenButton.vue';
import PrimaryButton from '@/Components/buttons/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';


const step = ref(1);
const totalSteps = 3;

const form = reactive({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: '',
  name: '',
  cnpj: '',
  address: '',
  city: '',
  state: '',
  zip_code: '',
  errors: {}
});

const nextStep = () => {
  if (validateStep(step.value)) {
    step.value++;
  }
};

const prevStep = () => {
  if (step.value > 1) {
    step.value--;
  }
};

const validateStep = (currentStep) => {
  form.errors = {};
  if (currentStep === 1) {
    if (!form.first_name) form.errors.first_name = 'O primeiro nome é obrigatório.';
    if (!form.last_name) form.errors.last_name = 'O último nome é obrigatório.';
    if (!form.email) form.errors.email = 'O e-mail é obrigatório.';
    if (!form.password) form.errors.password = 'A senha é obrigatória.';
    if (form.password !== form.password_confirmation)
      form.errors.password_confirmation = 'As senhas não correspondem.';
  } else if (currentStep === 2) {
    if (!form.name) form.errors.name = 'O nome da empresa é obrigatório.';
    if (!form.cnpj) form.errors.cnpj = 'O CNPJ é obrigatório.';
    if (!form.address) form.errors.address = 'O endereço é obrigatório.';
    if (!form.city) form.errors.city = 'A cidade é obrigatória.';
    if (!form.state) form.errors.state = 'O estado é obrigatório.';
    if (!form.zip_code) form.errors.zip_code = 'O CEP é obrigatório.';
  }
  return Object.keys(form.errors).length === 0;
};

const isActiveStep = (currentStep) => step.value === currentStep;
const isCompletedStep = (currentStep) => step.value > currentStep;

const submit = () => {
  if (validateStep(step.value)) {
    alert('Cadastro finalizado com sucesso!');
  }
};
</script>

<template>
  <Head title="Cadastro | Empresa Pro" />

  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 shadow-md rounded-lg py-8">
      <h1 class="text-2xl font-bold text-center text-gray-700 py-3">Realizar Cadastro</h1>

      <!-- Step Indicators -->
      <div class="flex items-center justify-between mb-8">
        <template v-for="n in totalSteps" :key="n">
          <div class="flex items-center space-x-2">
            <div
              class="flex items-center justify-center w-10 h-10 rounded-full border-2"
              :class="isActiveStep(n) ? 'border-indigo-500 bg-indigo-100' : isCompletedStep(n) ? 'border-green-500 bg-green-100' : 'border-gray-300 bg-gray-100'"
            >
              <span
                :class="isActiveStep(n) ? 'text-indigo-600' : isCompletedStep(n) ? 'text-green-600' : 'text-gray-500'"
                class="font-bold text-lg"
              >
                {{ n }}
              </span>
            </div>

            <!-- Linha de progresso entre os passos -->
            <div v-if="n < totalSteps" class="h-1 w-16 bg-gray-300" :class="isCompletedStep(n) && 'bg-green-500'"></div>
          </div>
        </template>
      </div>

      <!-- Steps -->
      <transition name="fade" mode="out-in">
        <div>
            <div v-if="step === 1" key="step-1">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Dados do Usuário</h2>
                <div class="mb-4">
                <InputLabel for="first_name" value="Primeiro Nome" />
                <TextInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div class="mb-4">
                <InputLabel for="last_name" value="Último Nome" />
                <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
                <div class="mb-4">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="mb-4">
                <InputLabel for="password" value="Senha" />
                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div class="mb-4">
                <InputLabel for="password_confirmation" value="Confirme a Senha" />
                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
                <div class="flex gap-4 mt-4">
                <BlueButton @click="nextStep" class="w-full flex justify-center items-center">
                    Próximo
                </BlueButton>
                </div>
            </div>

            <div v-if="step === 2" key="step-2">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Dados da Empresa</h2>
                <!-- Dados da Empresa -->
                <div class="mb-4">
                <InputLabel for="name" value="Nome da Empresa" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mb-4">
                <InputLabel for="cnpj" value="CNPJ/CPF" />
                <TextInput id="cnpj" v-model="form.cnpj" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.cnpj" />
                </div>

                <div class="mb-4">
                <InputLabel for="address" value="Endereço" />
                <TextInput id="address" v-model="form.address" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.address" />
                </div>

                <div class="mb-4">
                <InputLabel for="city" value="Cidade" />
                <TextInput id="city" v-model="form.city" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.city" />
                </div>

                <div class="mb-4">
                <InputLabel for="state" value="Estado" />
                <TextInput id="state" v-model="form.state" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.state" />
                </div>

                <div class="mb-4">
                <InputLabel for="zip_code" value="CEP" />
                <TextInput id="zip_code" v-model="form.zip_code" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.zip_code" />
                </div>

                <div class="flex gap-4 mt-4">
                <PrimaryButton @click="prevStep" class="w-1/2 flex justify-center items-center">
                    Voltar
                </PrimaryButton>
                <BlueButton @click="nextStep" class="w-1/2 flex justify-center items-center">
                    Próximo
                </BlueButton>
                </div>
            </div>

            <div v-if="step === 3" key="step-3">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Confirmação</h2>

                <!-- Resumo dos dados -->
                <div class="space-y-6">
                    <!-- Dados do Usuário -->
                    <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-white">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados do Usuário</h3>
                    <ul class="space-y-3">
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">Nome:</span>
                        <span class="text-gray-800">{{ form.first_name }} {{ form.last_name }}</span>
                        </li>
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">Email:</span>
                        <span class="text-gray-800">{{ form.email }}</span>
                        </li>
                    </ul>
                    </div>

                    <!-- Dados da Empresa -->
                    <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-white">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados da Empresa</h3>
                    <ul class="space-y-3">
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">Nome da Empresa:</span>
                        <span class="text-gray-800">{{ form.name }}</span>
                        </li>
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">CNPJ/CPF:</span>
                        <span class="text-gray-800">{{ form.cnpj }}</span>
                        </li>
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">Endereço:</span>
                        <span class="text-gray-800">{{ form.address }}</span>
                        </li>
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">Cidade:</span>
                        <span class="text-gray-800">{{ form.city }}</span>
                        </li>
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">Estado:</span>
                        <span class="text-gray-800">{{ form.state }}</span>
                        </li>
                        <li class="flex justify-between">
                        <span class="font-medium text-gray-600">CEP:</span>
                        <span class="text-gray-800">{{ form.zip_code }}</span>
                        </li>
                    </ul>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex gap-4 mt-6">
                    <PrimaryButton @click="prevStep" class="w-1/2 flex justify-center items-center">
                    Voltar
                    </PrimaryButton>
                    <GreenButton @click="submit" class="w-1/2 flex justify-center items-center">
                    Confirmar
                    </GreenButton>
                </div>
            </div>
        </div>
      </transition>
    </div>
  </div>
</template>
