<template>
    <div class="login-container">
      <div class="login-card">
        <h1>Login Sistema Gestão OKR</h1>
        <form @submit.prevent="handleLogin">
          <label for="email">EMAIL</label>
          <input
            v-model="email"
            type="email"
            id="email"
            placeholder="Digite seu email"
            required
          />
  
          <label for="password">SENHA</label>
          <input
            v-model="password"
            type="password"
            id="password"
            placeholder="Digite sua senha"
            required
          />
  
          <div class="forgot-password">
            <a @click="goToForgotPassword">ESQUECEU A SENHA</a>
          </div>
  
          <button type="submit">Login</button>
        </form>
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "LoginForm",
    data() {
      return {
        email: "",
        password: "",
        errorMessage: "",
      };
    },
    methods: {
      async handleLogin() {
        try {
          const response = await this.$http.post("/auth/login", {
            email: this.email,
            password: this.password,
          });
          localStorage.setItem("token", response.data.token);
          this.$router.push("/dashboard");
        } catch (error) {
          this.errorMessage = "Usuário ou senha incorretos.";
        }
      },
      goToForgotPassword() {
        this.$router.push("/forgot-password");
      },
    },
  };
  </script>
  
  <style scoped>
  .login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f9f9f9;
  }
  
  .login-card {
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
  }
  
  .login-card h1 {
    font-size: 24px;
    margin-bottom: 1rem;
  }
  
  .login-card form {
    display: flex;
    flex-direction: column;
  }
  
  .login-card label {
    font-size: 14px;
    margin-bottom: 0.5rem;
    text-align: left;
  }
  
  .login-card input {
    padding: 10px;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
  }
  
  .forgot-password {
    text-align: right;
    font-size: 12px;
    margin-bottom: 1rem;
  }
  
  .forgot-password a {
    text-decoration: none;
    color: #007bff;
    cursor: pointer;
  }
  
  .login-card button {
    padding: 10px;
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
  }
  
  .login-card button:hover {
    background-color: #333;
  }
  
  .error {
    color: red;
    margin-top: 1rem;
  }
  </style>
  