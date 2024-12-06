<template>
    <div class="forgot-password-container">
      <div class="forgot-password-card">
        <h1>Esqueceu a Senha</h1>
        <form @submit.prevent="handleForgotPassword">
          <label for="email">EMAIL</label>
          <input
            v-model="email"
            type="email"
            id="email"
            placeholder="Digite seu email"
            required
          />
          <button type="submit">Enviar</button>
        </form>
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "ForgotPasswordForm",
    data() {
      return {
        email: "",
        successMessage: "",
      };
    },
    methods: {
      async handleForgotPassword() {
        try {
          await this.$http.post("/auth/password/recovery", {
            email: this.email,
          });
          this.successMessage =
            "As instruções para redefinir a senha foram enviadas para o seu email.";
        } catch (error) {
          this.successMessage =
            "Se o email estiver cadastrado, as instruções serão enviadas.";
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .forgot-password-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f9f9f9;
  }
  
  .forgot-password-card {
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
  }
  
  .forgot-password-card h1 {
    font-size: 24px;
    margin-bottom: 1rem;
  }
  
  .forgot-password-card form {
    display: flex;
    flex-direction: column;
  }
  
  .forgot-password-card label {
    font-size: 14px;
    margin-bottom: 0.5rem;
    text-align: left;
  }
  
  .forgot-password-card input {
    padding: 10px;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
  }
  
  .forgot-password-card button {
    padding: 10px;
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
  }
  
  .forgot-password-card button:hover {
    background-color: #333;
  }
  
  .success {
    color: green;
    margin-top: 1rem;
  }
  </style>
  