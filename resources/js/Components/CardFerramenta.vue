<template>
    <div class="card text-center shadow-sm">
      <div class="card-body d-flex flex-column align-items-center">
        <!-- Ícone -->
        <img
          src="@/assets/produtos-icon.svg"
          alt="Ícone de Produtos"
          class="icon mb-3"
        />
        <!-- Texto com link -->
        <a :href="link" class="stretched-link text-decoration-none">
          <p class="card-text fw-bold text-secondary">{{ nome }}</p>
        </a>
      </div>
    </div>
  </template>

  <script>
  export default {
    name: "CardProduto",
    props: {
      nome: {
        type: String,
        required: true, // Define o nome do card (ex.: Produtos)
      },
      link: {
        type: String,
        required: true, // Define o link que o card aponta (ex.: "/produtos")
      },
    },
  };
  </script>

  <style scoped>
  .card {
    width: 100%;
    max-width: 200px;
    border: 1px solid #ddd;
    border-radius: 10px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }

  .icon {
    width: 60px;
    height: 60px;
  }

  @media (max-width: 576px) {
    .card {
      max-width: 150px;
    }

    .icon {
      width: 50px;
      height: 50px;
    }

    .card-text {
      font-size: 14px;
    }
  }
  </style>
