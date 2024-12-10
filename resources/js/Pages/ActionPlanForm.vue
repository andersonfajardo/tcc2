<template>
    <div class="dashboard-container">
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
          <div class="dashboard-title">
            <h2>PLANO DE AÇÃO</h2>
          </div>
  
          <section class="action-plans">
            <!-- Tabela de planos de ação -->
            <table class="action-plan-table">
              <thead>
                <tr>
                  <th>O QUE?</th>
                  <th>POR QUE?</th>
                  <th>QUEM?</th>
                  <th>QUANDO?</th>
                  <th>ONDE?</th>
                  <th>COMO?</th>
                  <th>QUANTO?</th>
                  <th>INDICADOR</th>
                  <th>AÇÕES</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(plan, index) in actionPlans" :key="index">
                  <td>{{ plan.what }}</td>
                  <td>{{ plan.why }}</td>
                  <td>{{ plan.who }}</td>
                  <td>{{ plan.when }}</td>
                  <td>{{ plan.where }}</td>
                  <td>{{ plan.how }}</td>
                  <td>{{ plan.howmuch }}</td>
                  <td>{{ plan.indicator.kpititle}}</td>
                  <td>
                    <button @click="editPlan(index)" class="action-button">✏️</button>
                    <button @click="deletePlan(index)" class="action-button">🗑️</button>
                  </td>
                </tr>
              </tbody>
            </table>
  
            <!-- Formulário para adicionar/editar planos -->
            <div class="action-plan-form">
              <h3>Novo Plano de Ação</h3>
              <form @submit.prevent="addPlan">
                <input v-model="newPlan.what" type="text" placeholder="O que?" required />
                <input v-model="newPlan.why" type="text" placeholder="Por que?" required />
                <input v-model="newPlan.who" type="text" placeholder="Quem?" required />
                <input v-model="newPlan.when" type="date" placeholder="Quando?" required />
                <input v-model="newPlan.where" type="text" placeholder="Onde?" required />
                <input v-model="newPlan.how" type="text" placeholder="Como?" required />
                <input v-model="newPlan.howmuch" type="text" placeholder="Quanto?" required />
                <div class="form-row">
                        <label for="dashboardType">Selecione o tipo de gráfico:</label>
                        <select v-model="newPlan.dashboardType" id="dashboardType">
                          <option value="Evolucao">Gráfico de Evolução</option>
                          <option value="Barra">Gráfico em Barra</option>
                          <option value="Pizza">Gráfico em Pizza</option>
                          <option value="Linha">Gráfico de Linha</option>
                        </select>
                 </div>
                <button type="submit">Inserir</button>
              </form>
            </div>
          </section>
        </main>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "ActionPlanForm",
        props: {
        user: Object, // Recebe o usuário do back
        company: Object, // Recebe os dados da empresa
        actionplanload: Object,
    },
    data() {
      return {
        actionPlans: this.actionplanload,
        newPlan: {
          what: "",
          why: "",
          who: "",
          when: "",
          where: "",
          how: "",
          howmuch: "",
          dashboard: false,
        },
      };
    },
    methods: {
      goTo(route) {
        //this.$router.push(`/${route}`);
        this.$inertia.visit(this.route(route));
      },
      logout() {
        //localStorage.removeItem("token");
        //this.$router.push("/login");
        this.$inertia.post(this.route('logout'));
      },
      addPlan() {
        this.actionPlans.push({ ...this.newPlan });
        this.resetForm();
      },
      editPlan(index) {
        const plan = this.actionPlans[index];
        Object.assign(this.newPlan, plan);
        this.actionPlans.splice(index, 1);
      },
      deletePlan(index) {
        this.actionPlans.splice(index, 1);
      },
      resetForm() {
        this.newPlan = {
          what: "",
          why: "",
          who: "",
          when: "",
          where: "",
          how: "",
          howmuch: "",
          dashboard: false,
        };
      },
    },
    mounted() {
        console.log(this.actionplanload);
    },
  };
  </script>
  
  <style scoped>

.select-container {
    display: inline-block; /* Isso faz com que o select fique na mesma linha */
    }

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
  /* CSS baseado na identidade visual fornecida */
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
  
  .dashboard-layout {
    display: flex;
    flex: 1;
  }
  
  .sidebar {
    width: 70px;
    background-color: #f4f4f4;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    padding-top: 1rem;
  }
  
  .main-content {
    flex: 1;
    padding: 1rem;
  }
  
  .dashboard-title {
    margin-bottom: 1rem;
  }
  
  .action-plan-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
  }
  
  .action-plan-table th,
  .action-plan-table td {
    border: 1px solid #ddd;
    padding: 0.5rem;
    text-align: center;
  }
  
  .action-plan-table th {
    background-color: #f4f4f4;
    font-weight: bold;
  }
  
  .action-plan-form {
    margin-top: 2rem;
  }
  
  .action-plan-form input,
  .action-plan-form button {
    margin: 0.5rem 0;
    padding: 0.5rem;
  }
  
  .action-plan-form button {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .action-plan-form button:hover {
    background-color: #0056b3;
  }

  .form-row {
    display: flex;
    align-items: center;
    gap: 10px; /* Espaço entre o rótulo e o dropdown */
  }

  .form-row label {
    white-space: nowrap; /* Impede quebra de linha no rótulo */
  }
  </style>
  