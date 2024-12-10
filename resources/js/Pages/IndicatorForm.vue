<template>
    <div class="indicators-container">
      <!-- Barra superior -->
      <header class="dashboard-header">
        <div class="company-name">{{ company.name }}</div>
        <div class="user-info">
          <span class="user-name">{{ user.name }}</span>
          <button @click="logout" class="logout-button">SAIR</button>
        </div>
      </header>
  
      <!-- Layout principal -->
      <div class="dashboard-layout">
        <!-- Barra lateral -->
        <aside class="sidebar">
          <ul>
            <li @click="goTo('dashboard.form')"><i class="icon">🖥️</i></li>
            <li @click="goTo('indicator.form')"><i class="icon">📊</i></li>
            <li @click="goTo('dataform.form')"><i class="icon">📁</i></li>
            <li @click="goTo('actionplan.form')"><i class="icon">📈</i></li>
            <li @click="goTo('dashboard.form')"><i class="icon">🔧</i></li>
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
                  <th>Categoria</th>
                  <th>Tipo</th>
                  <th>Dashboard</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(indicator, index) in this.indicators" :key="indicator.id">
                  <td>{{ indicator.kpititle }}</td>
                  <td>{{ indicator.kpidescription }}</td>
                  <td>{{ indicator.okr }}</td>
                  <td>{{ indicator.indicator_type }}</td>
                  <td>
                    <input type="checkbox" :checked="indicator.dashboard" @change="updateDashboard($event,indicator.id)"/>
                  </td>
                  <td>
                    <button @click="editIndicator(index)" class="action-button">✏️</button>
                    <button @click="updateEnable(indicator.id, !indicator.enable)" class="action-button">🗑️</button>                 
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
                  <label for="kpititle">Nome:</label>
                  <input type="text" v-model="newIndicator.kpititle" id="kpititle" required />
              </div>
              <div class="form-group">
                  <label for="kpidescription">Descrição:</label>
                  <input type="text" v-model="newIndicator.kpidescription" id="kpidescriptio" required />
              </div>
              <div class="form-group">
                  <label for="okr">Categoria:</label>
                  <select v-model="newIndicator.okr" id="okr" required>
                      <option disabled value="">Selecione uma categoria</option>
                      <option value="0">KPI</option>
                      <option value="1">OKR</option>
                  </select>
              </div>
              <div class="form-group">
                  <label for="indicator_type">Tipo:</label>
                  <select v-model="newIndicator.indicator_type" id="indicator_type" @change="updateChartPreview" required>
                      <option disabled value="">Selecione um tipo</option>
                      <option value="1">Pizza</option>
                      <option value="2">Barra</option>
                      <option value="3">Evolução</option>
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
  import axios from "axios";
  
  export default {
    name: "IndicatorForm",
    props: {
        user: Object, // Recebe o usuário do back
        company: Object, // Recebe os dados da empresa
        indicatorsload: Object, 
    },
    data() {
      return {
        newIndicator: {
          kpititle: "",
          kpidescription: "",
          indicator_type: 0,
          okr: null, // Valor inicial vazio
        },
        indicators: this.indicatorsload,
        chartInstance: null,
      };
    },
    methods: {
      //saveIndicator() {
      //  if (this.newIndicator.name && this.newIndicator.description && this.newIndicator.type && this.newIndicator.category) {
      //    this.indicators.push({ ...this.newIndicator });
      //    this.resetForm();
      //  } else {
      //    alert("Por favor, preencha todos os campos!");
      //  }
      //},
      async saveIndicator() {
      try {
        // Envia os dados do formulário ao backend
          const postdata = {
          kpititle: this.newIndicator.kpititle,
          kpidescription: this.newIndicator.kpidescription,
          indicator_type: this.newIndicator.indicator_type, // Converte tipo para numérico
          okr: this.newIndicator.okr, // OKR: 1, KPI: 0
          enable: 1, // Sempre 1 ao criar
          dashboard: 0,} // Sempre visível no dashboard
           
          const response = await axios.post("/indicators", postdata);

        // Tratamento do sucesso
        alert("Indicador salvo com sucesso!");
        indicatorload 
        this.indicadorload.push(postdata);
        console.log(response.data);

        // Limpa o formulário após o sucesso
        this.newIndicator = {
          kpititle: "",
          kpidescription: "",
          kpistargetvalue: null,
          startdate: null,
          enddate: null,
          indicator_type: 2,
          okr: 0,
        };
      } catch (error) {
        // Tratamento de erros
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors; // Exibe erros de validação
        } else {
          alert("Erro ao salvar indicador. Tente novamente.");
        }
      }
    },
      editIndicator(index) {
        this.newIndicator = { ...this.indicators[index] };
        this.indicators.splice(index, 1);
      },
      async updateDashboard(event, id) {
        try {
          const isChecked = event.target.checked; // Retorna true ou false
          const postdata = {id: id, dashboard : isChecked}
          const response = await axios.patch("/indicators", postdata);
          
        }catch{
          alert("Erro ao atualizar indicador. Tente novamente.");
        }
      },
      deleteIndicator(index) {
        this.indicators.splice(index, 1);
      },
      async updateEnable(id, enable) {
        try {
          const postdata = { id: id, enable: enable }; 
          const response = await axios.patch("/indicators/enable", postdata);
          window.location.reload()
        } catch {
          alert("Erro ao atualizar o indicador. Tente novamente.");
        }
      },
      goTo(route) {
        //this.$router.push(`/${route}`);
        //corrigido para rota correta
        this.$inertia.visit(this.route(route));
      },
      logout() {
        //localStorage.removeItem("token");
        //this.$router.push("/login");
        this.$inertia.post(this.route('logout'));
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
      console.log(this.indicators);
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
  