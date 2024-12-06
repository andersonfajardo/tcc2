<template>
    <div class="indicator-container">
      <!-- Barra superior -->
      <header class="dashboard-header">
        <div class="company-name">NOME DA EMPRESA</div>
        <div class="user-info">
          <span class="user-name">USUÁRIO</span>
          <button @click="logout" class="logout-button">SAIR</button>
        </div>
      </header>
  
      <!-- Layout principal -->
      <div class="dashboard-layout">
        <!-- Barra lateral -->
        <aside class="sidebar">
          <ul>
            <li @click="goTo('dashboard')"><i class="icon">🖥️</i></li>
            <li @click="goTo('indicators')"><i class="icon">📊</i></li>
            <li @click="goTo('data')"><i class="icon">📁</i></li>
            <li @click="goTo('teams')"><i class="icon">👥</i></li>
            <li @click="goTo('settings')"><i class="icon">🔧</i></li>
          </ul>
        </aside>
  
        <!-- Conteúdo principal -->
        <main class="main-content">
          <div class="indicator-header">
            <h2>CHURN</h2>
          </div>
  
          <!-- Gráfico do indicador -->
          <div class="chart-container">
            <canvas id="indicator-chart"></canvas>
          </div>
  
          <!-- CRUD dos dados -->
          <div class="crud-container">
            <table class="crud-table">
              <thead>
                <tr>
                  <th>VALOR</th>
                  <th>DATA</th>
                  <th>AÇÕES</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, index) in indicatorData" :key="index">
                  <td>{{ data.value }}</td>
                  <td>{{ data.date }}</td>
                  <td class="actions">
                    <button @click="editData(index)" class="edit-button">✏️</button>
                    <button @click="deleteData(index)" class="delete-button">🗑️</button>
                  </td>
                </tr>
              </tbody>
            </table>
  
            <!-- Formulário para inserir dados -->
            <div class="data-form">
              <input v-model="newData.value" type="number" placeholder="Valor" required />
              <input v-model="newData.date" type="date" placeholder="Data" required />
              <button @click="addData">INSERIR</button>
            </div>
          </div>
        </main>
      </div>
    </div>
  </template>
  
  <script>
  import Chart from "chart.js/auto";
  
  export default {
    name: "IndicatorForm",
    data() {
      return {
        indicatorData: [], // Dados do indicador (CRUD)
        newData: {
          value: "",
          date: "",
        },
        chart: null, // Instância do gráfico
      };
    },
    methods: {
      goTo(route) {
        this.$router.push(`/${route}`);
      },
      logout() {
        localStorage.removeItem("token");
        this.$router.push("/login");
      },
      addData() {
        if (this.newData.value && this.newData.date) {
          this.indicatorData.push({ ...this.newData });
          this.newData = { value: "", date: "" };
          this.updateChart();
        }
      },
      editData(index) {
        const data = this.indicatorData[index];
        this.newData = { ...data };
        this.indicatorData.splice(index, 1);
      },
      deleteData(index) {
        this.indicatorData.splice(index, 1);
        this.updateChart();
      },
      updateChart() {
        const labels = this.indicatorData.map((data) => data.date);
        const values = this.indicatorData.map((data) => parseFloat(data.value));
  
        if (this.chart) this.chart.destroy(); // Limpa o gráfico anterior
  
        const ctx = document.getElementById("indicator-chart").getContext("2d");
        this.chart = new Chart(ctx, {
          type: "pie",
          data: {
            labels,
            datasets: [
              {
                label: "Indicadores",
                data: values,
                backgroundColor: [
                  "#FF6384",
                  "#36A2EB",
                  "#FFCE56",
                  "#4BC0C0",
                  "#9966FF",
                  "#FF9F40",
                ],
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false, // Permite ajustar o tamanho
          },
        });
      },
    },
    mounted() {
      this.updateChart();
    },
  };
  </script>
  
  <style scoped>
  /* Layout principal */
  .indicator-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
  }
  
  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background-color: #f4f4f4;
    border-bottom: 1px solid #ddd;
  }
  
  .company-name {
    font-size: 1.5rem;
    font-weight: bold;
  }
  
  .user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .logout-button {
    background-color: #ff4d4f;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .logout-button:hover {
    background-color: #e33e3f;
  }
  
  /* Barra lateral 
  .sidebar {
    width: 70px;
    background-color: #f4f4f4;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    padding-top: 1rem;
  }*/

  .sidebar {
  width: 70px;
  background-color: #f4f4f4;
  border-right: 1px solid #ddd;
  position: fixed; /* Fixa a barra lateral */
  height: 100%; /* Preenche toda a altura da tela */
  top: 20;
  /*left: 0;*/
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 1rem;
}
  
  .sidebar ul {
    list-style: none;
    padding: 0;
  }
  
  .sidebar li {
    text-align: center;
    padding: 1rem 0;
    cursor: pointer;
  }
  
  .sidebar li:hover {
    background-color: #ddd;
    border-radius: 5px;
  }
  
  .icon {
    font-size: 2.5rem;
  }
  
  .sidebar li:hover {
    background-color: #ddd;
  }
  
  /* Conteúdo principal */
  .main-content {
  flex: 1;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  align-items: center; /* Centraliza horizontalmente */
  justify-content: flex-start; /* Alinha o conteúdo no topo */
  max-width: calc(100% - 70px); /* Subtrai a largura da barra lateral */
  margin-left: 70px; /* Cria espaço para a barra lateral */
}
  
  .indicator-header {
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
  }
  
  /* Gráfico */
  .chart-container {
    width: 400px;
    height: 400px;
    margin-top: 1rem;
    background: white;
    padding: 1rem;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  /* CRUD */
  .crud-container {
    margin-top: 2rem;
    width: 100%;
  }
  
  .crud-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
  }
  
  .crud-table th,
  .crud-table td {
    border: 1px solid #ddd;
    text-align: center;
    padding: 0.8rem;
  }
  
  .actions button {
    margin: 0 5px;
    font-size: 16px;
    cursor: pointer;
    background: none;
    border: none;
  }
  
  .edit-button {
    color: #007bff;
  }
  
  .delete-button {
    color: #ff4d4f;
  }
  
  /* Formulário */
  .data-form {
  margin-top: 1rem;
  display: flex;
  gap: 1rem;
  align-items: center;
  justify-content: center; /* Centraliza o formulário horizontalmente */
  flex-wrap: wrap; /* Permite quebra de linha caso necessário */
  max-width: 100%; /* Garante que o formulário não ultrapasse o layout */
}
  
  .data-form input {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  
  .data-form button {
    padding: 0.5rem 1rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .data-form button:hover {
    background-color: #0056b3;
  }
  </style>
  