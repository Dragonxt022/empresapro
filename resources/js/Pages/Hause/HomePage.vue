<template>
    <div>
      <!-- Navbar -->
      <header class="sticky top-0 z-50 bg-white shadow-md">
        <nav class="flex items-center justify-between px-6 py-4">
          <!-- Logo -->
          <div class="text-2xl font-bold text-blue-600">
            <a href="#">Minha Empresa</a>
          </div>
          <!-- Menu Items -->
          <ul class="flex space-x-4 text-gray-700">
            <li><a href="#about" class="hover:text-blue-600">Sobre</a></li>
            <li><a href="#services" class="hover:text-blue-600">Serviços</a></li>
            <li><a href="#contact" class="hover:text-blue-600">Contato</a></li>
            <li v-if="canLogin">
              <a href="/login" class="hover:text-blue-600">Login</a>
            </li>

            <li v-if="canRegister">
              <a href="/register" class="hover:text-blue-600">Registrar</a>
            </li>
          </ul>
        </nav>
      </header>

      <!-- Hero Section -->
      <main class="mt-6">
        <section class="bg-blue-600 text-white text-center py-20">
          <h1 class="text-4xl font-bold">Bem-vindo à Minha Empresa</h1>
          <p class="mt-4 text-lg">Oferecemos as melhores soluções para o seu negócio.</p>
          <a
            href="#contact"
            class="mt-6 inline-block bg-white text-blue-600 px-6 py-3 rounded-full shadow hover:bg-gray-200"
          >
            Fale Conosco
          </a>
        </section>

        <!-- About Section -->
        <section id="about" class="py-16 px-6">
          <h2 class="text-3xl font-bold text-center text-gray-800">Sobre Nós</h2>
          <p class="mt-4 text-center text-gray-600">
            Somos uma empresa dedicada a oferecer soluções personalizadas para nossos clientes, focando em inovação e qualidade.
          </p>
        </section>

        <!-- Services Section -->
        <section id="services" class="bg-gray-100 py-16 px-6">
          <h2 class="text-3xl font-bold text-center text-gray-800">Nossos Serviços</h2>
          <div class="mt-8 grid gap-8 md:grid-cols-3">
            <div class="p-6 bg-white shadow rounded">
              <h3 class="text-xl font-semibold text-blue-600">Serviço 1</h3>
              <p class="mt-2 text-gray-600">Descrição do serviço 1.</p>
            </div>
            <div class="p-6 bg-white shadow rounded">
              <h3 class="text-xl font-semibold text-blue-600">Serviço 2</h3>
              <p class="mt-2 text-gray-600">Descrição do serviço 2.</p>
            </div>
            <div class="p-6 bg-white shadow rounded">
              <h3 class="text-xl font-semibold text-blue-600">Serviço 3</h3>
              <p class="mt-2 text-gray-600">Descrição do serviço 3.</p>
            </div>
          </div>
        </section>
      </main>
    </div>
  </template>



  <script>
  export default {
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
    },
  };
  </script>
