<template>
    <div class="container mx-auto">
      <h1 class="text-4xl font-bold mb-6">Panel de Control de Trabajos</h1>
      
      <!-- Filtros -->
      <div class="flex justify-between mb-4">
        <button class="btn btn-primary">Trabajos Pendientes</button>
        <button class="btn btn-secondary">Trabajos Completados</button>
      </div>
      
      <!-- Lista de Trabajos -->
      <table class="table-auto w-full">
        <thead>
          <tr>
            <th>Trabajo</th>
            <th>Clase</th>
            <th>Método</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="job in jobs" :key="job.id">
            <td>{{ job.name }}</td>
            <td>{{ job.class }}</td>
            <td>{{ job.method }}</td>
            <td :class="{'text-red-500': job.status === 'failed', 'text-green-500': job.status === 'completed'}">
              {{ job.status }}
            </td>
            <td>
              <button class="btn btn-warning" @click="retryJob(job.id)">Reintentar</button>
              <button class="btn btn-danger" @click="cancelJob(job.id)">Cancelar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        jobs: [],
      };
    },
    mounted() {
      axios.get('/api/jobs').then(response => {
        this.jobs = response.data;
      });
    },
    methods: {
      retryJob(id) {
        axios.post('/api/job/retry', { id }).then(() => {
          this.fetchJobs();
        });
      },
      cancelJob(id) {
        axios.post('/api/job/cancel', { id }).then(() => {
          this.fetchJobs();
        });
      },
      fetchJobs() {
        axios.get('/api/jobs').then(response => {
          this.jobs = response.data;
        });
      }
    }
  };
  </script>
  
  <style scoped>
  /* Puedes agregar estilos aquí si lo necesitas */
  </style>
  