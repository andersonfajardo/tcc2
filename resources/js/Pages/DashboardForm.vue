<template>
    <div class="dashboard-container">
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
            <li @click="goTo('dashboard.form')"><i class="icon">🖥️</i></li>
            <li @click="goTo('indicators')"><i class="icon">📊</i></li>
            <li @click="goTo('data')"><i class="icon">📁</i></li>
            <li @click="goTo('teams')"><i class="icon">📈</i></li>
            <li @click="goTo('settings')"><i class="icon">🔧</i></li>
          </ul>
        </aside>
  
        <!-- Conteúdo principal -->
        <main class="main-content">
          <div class="dashboard-title">
            <h2>DASHBOARD</h2>
            <select v-model="selectedDate" @change="updateDashboard">
              <option v-for="date in availableDates" :key="date" :value="date">
                {{ date }}
              </option>
            </select>
          </div>
  
          <section class="indicators">
            <div class="okr-section">
              <h3>OKR</h3>
              <div class="charts">
                <div v-for="(okr, index) in okrData" :key="index" class="chart">
                  <h4>{{ okr.title }}</h4>
                  <canvas :id="'okr-chart-' + index"></canvas>
                </div>
              </div>
            </div>
  
            <div class="kpi-section">
              <h3>KPI</h3>
              <div class="charts">
                <div v-for="(kpi, index) in kpiData" :key="index" class="chart">
                  <h4>{{ kpi.title }}</h4>
                  <canvas :id="'kpi-chart-' + index"></canvas>
                </div>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>
  </template>
  
  <script>
  import Chart from "chart.js/auto";
  import { format, subMonths } from "date-fns";
  
  export default {
    name: "DashboardForm",
    data() {
      return {
        selectedDate: "",
        availableDates: [],
        okrData: [
          { title: "Faturamento", type: "line" },
          { title: "Rentabilidade (%)", type: "bar" },
          { title: "Churn", type: "pie" },
        ],
        kpiData: [
          { title: "Crescimento de Vendas", type: "line" },
          { title: "Engajamento de Clientes", type: "bar" },
          { title: "Taxa de Conversão", type: "pie" },
        ],
      };
    },
    methods: {
      updateDashboard() {
        console.log("Atualizando dashboard para a data:", this.selectedDate);
        // Aqui você pode carregar novos dados do backend   
      },
      logout() {
        localStorage.removeItem("token");
        this.$router.push("/login");
      },
      goTo(route) {
        console.log(`Navegando para ${route}`);
        this.$router.push(`/${route}`);
      },
      generateDates() {
        const today = new Date();
        this.availableDates = Array.from({ length: 4 }, (_, i) =>
          format(subMonths(today, i), "MMMM/yy")
        ).reverse();
        this.selectedDate = this.availableDates[0];
      },
      renderCharts() {
        this.okrData.forEach((okr, index) => {
          new Chart(document.getElementById(`okr-chart-${index}`), {
            type: okr.type,
            data: {
              labels: ["Ago", "Set", "Out", "Nov"],
              datasets: [
                {
                  label: okr.title,
                  data: [10, 12, 9, 17],
                  backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"],
                },
              ],
            },
          });
        });
  
        this.kpiData.forEach((kpi, index) => {
          new Chart(document.getElementById(`kpi-chart-${index}`), {
            type: kpi.type,
            data: {
              labels: ["Meta 1", "Meta 2", "Meta 3"],
              datasets: [
                {
                  label: kpi.title,
                  data: [50, 30, 20],
                  backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
                },
              ],
            },
          });
        });
      },
    },
    mounted() {
      this.generateDates();
      this.renderCharts();
    },
  };
  </script>
  
  <style scoped>
  /* Estilo permanece o mesmo */
  .dashboard-container {
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
  
  .dashboard-layout {
    display: flex;
    flex: 1;
  }
  
 /* Barra lateral */
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
  
  .main-content {
    flex: 1;
    padding: 1rem;
  }
  
  .dashboard-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }
  
  .dashboard-title select {
    padding: 0.5rem;
  }
  
  .indicators {
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
  
  .charts {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }
  
  .chart {
    flex: 1 1 calc(33.333% - 1rem);
    min-width: 300px;
  }
  </style>
  