import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/auth/Login.vue';
import BlogManagement from './components/BlogManagement.vue';

const routes = [
  { path: '/', redirect: '/dashboard' },
  { path: '/login', component: Login, name: 'Login' },
  { path: '/dashboard', component: BlogManagement, name: 'Dashboard' }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;