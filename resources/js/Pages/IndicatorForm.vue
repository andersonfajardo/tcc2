<template>
    <div class="indicators-container">
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
          <h1 class="main-title">Cadastro de Indicadores</h1>
  
          <!-- Lista de Indicadores -->
          <div class="indicator-list">
            <h3>Indicadores Cadastrados</h3>
            <table>
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Descrição</th>
                  <th>Tipo</th>
                  <th>Dashboard</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(indicator, index) in indicators" :key="index">
                  <td>{{ indicator.name }}</td>
                  <td>{{ indicator.description }}</td>
                  <td>{{ indicator.type }}</td>
                  <td>
                    <input type="checkbox" v-model="indicator.selected" />
                  </td>
                  <td>
                    <button @click="editIndicator(index)" class="action-button">✏️</button>
                    <button @click="deleteIndicator(index)" class="action-button">🗑️</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
  
          <!-- Formulário de Indicadores -->
          <div class="indicator-form">
            <h3>Novo Indicador</h3>
            <div class="form-container">
              <form @submit.prevent="saveIndicator" class="form-inline">
                <div class="form-group">
                  <label for="name">Nome:</label>
                  <input type="text" v-model="newIndicator.name" id="name" required />
                </div>
                <div class="form-group">
                  <label for="description">Descrição:</label>
                  <input type="text" v-model="newIndicator.description" id="description" required />
                </div>
                <div class="form-group">
                  <label for="type">Tipo:</label>
                  <select v-model="newIndicator.type" id="type" @change="updateChartPreview" required>
                    <option disabled value="">Selecione um tipo</option>
                    <option value="pizza">Pizza</option>
                    <option value="barra">Barra</option>
                    <option value="evolucao">Evolução</option>
                  </select>
                </div>
                <button type="submit" class="btn-save">Inserir</button>
              </form>
  
              <!-- Preview do Gráfico -->
              <div class="chart-preview">
                <h4>Preview</h4>
                <canvas ref="chartCanvas" id="chart-preview" width="250" height="250"></canvas>
              </div>
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
        newIndicator: {
          name: "",
          description: "",
          type: "", // Valor inicial vazio
          selected: false,
        },
        indicators: [],
        chartInstance: null,
      };
    },
    methods: {
      saveIndicator() {
        if (this.newIndicator.name && this.newIndicator.description && this.newIndicator.type) {
          this.indicators.push({ ...this.newIndicator });
          this.resetForm();
        } else {
          alert("Por favor, preencha todos os campos!");
        }
      },
      editIndicator(index) {
        this.newIndicator = { ...this.indicators[index] };
        this.indicators.splice(index, 1);
      },
      deleteIndicator(index) {
        this.indicators.splice(index, 1);
      },
      goTo(route) {
        //this.$router.push(`/${route}`);
        //corrigido para rota correta usando inertia
        this.$inertia.visit(this.route(route));
      },
      logout() {
        localStorage.removeItem("token");
        this.$router.push("/login");
      },
      resetForm() {
        this.newIndicator = { name: "", description: "", type: "", selected: false };
        this.updateChartPreview();
      },
      updateChartPreview() {
        if (this.chartInstance) {
          this.chartInstance.destroy();
        }
  
        const ctx = this.$refs.chartCanvas.getContext("2d");
        let chartType = "doughnut"; // Default for "Pizza"
        if (this.newIndicator.type === "barra") {
          chartType = "bar";
        } else if (this.newIndicator.type === "evolucao") {
          chartType = "line";
        }
  
        this.chartInstance = new Chart(ctx, {
          type: chartType,
          data: {
            labels: ["A", "B", "C"],
            datasets: [
              {
                label: "Exemplo",
                data: [10, 20, 30],
                backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
                borderColor: "#ddd",
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: false,
            plugins: {
              legend: {
                display: false,
              },
            },
          },
        });
      },
    },
    mounted() {
      this.updateChartPreview();
    },
  };
  </script>
  
  <style scoped>
  /* Estilo baseado na identidade visual */
  .indicators-container {
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
  
  .dashboard-layout {
    display: flex;
    flex: 1;
  }
  .user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}
  
  .sidebar {
    width: 70px;
    background-color: #f4f4f4;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
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
  
  .main-content {
    flex: 1;
    padding: 2rem;
  }
  
  .main-title {
    font-size: 2rem; /* Ajuste no tamanho da fonte */
    font-weight: bold;
    margin-bottom: 1.5rem;
  }
  
  .indicator-list {
    margin-bottom: 2rem;
  }
  
  .indicator-list table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
  }
  
  .indicator-list th,
  .indicator-list td {
    border: 1px solid #ddd;
    padding: 0.5rem;
    text-align: left;
  }
  
  .indicator-list th {
    background-color: #f4f4f4;
  }
  
  .form-container {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem; /* Ajuste no espaço para o gráfico */
  }
  
  .form-inline {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .chart-preview {
    text-align: center;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
  }
  
  .chart-preview canvas {
    display: block;
    margin: 0 auto;
  }
  
  .btn-save {
    padding: 0.5rem 1rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .btn-save:hover {
    background-color: #0056b3;
  }
  
  .action-button {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
  }
  
  .action-button:hover {
    color: #007bff;
  }
  </style>
  