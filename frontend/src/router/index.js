import { createRouter, createWebHistory } from 'vue-router';
// Lazy-load route components to reduce initial bundle size
const Login = () => import(/* webpackChunkName: "auth" */ '../components/Login.vue');
const RegisterForm = () => import(/* webpackChunkName: "auth" */ '../components/Register.vue');
const DashboardLayout = () => import(/* webpackChunkName: "layout" */ '../components/layouts/DashboardLayout.vue');

// Dashboard components (lazy-loaded)
const AdminDashboard = () => import(/* webpackChunkName: "dashboard-admin" */ '../components/dashboards/AdminDashboard.vue');
const CeoDashboard = () => import(/* webpackChunkName: "dashboard-ceo" */ '../components/dashboards/CeoDashboard.vue');
const CompanyDashboard = () => import(/* webpackChunkName: "dashboard-company" */ '../components/dashboards/CompanyDashboard.vue');
const FreelancerDashboard = () => import(/* webpackChunkName: "dashboard-freelancer" */ '../components/dashboards/FreelancerDashboard.vue');
const ProjectManagerDashboard = () => import(/* webpackChunkName: "dashboard-pm" */ '../components/dashboards/ProjectManagerDashboard.vue');
const ProfileCardDemo = () => import(/* webpackChunkName: "profile-demo" */ '../components/ProfileCardDemo.vue');
const Profile = () => import(/* webpackChunkName: "profile" */ '../components/Profile.vue');
const UserManagement = () => import(/* webpackChunkName: "admin-users" */ '../components/pages/UserManagement.vue');
const RoleManagement = () => import(/* webpackChunkName: "admin-roles" */ '../components/pages/RoleManagement.vue');
const Invoices = () => import(/* webpackChunkName: "invoices" */ '../components/pages/Invoices.vue');
const Portfolio = () => import(/* webpackChunkName: "portfolio" */ '../components/pages/Portfolio.vue');
const AdminPaymentSettings = () => import(/* webpackChunkName: "admin-payments" */ '../components/pages/AdminPaymentSettings.vue');
const AdminConnect = () => import(/* webpackChunkName: "admin-connect" */ '../components/pages/AdminConnect.vue');


const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresAuth: false }
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterForm,
    meta: { requiresAuth: false }
  },
  {
    path: '/profile-card-demo',
    name: 'ProfileCardDemo',
    component: ProfileCardDemo,
    meta: { requiresAuth: false }
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile,
    meta: { requiresAuth: true }
  },

  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: 'freelancer' // Default redirect to freelancer dashboard
      },
      {
        path: 'admin',
        name: 'AdminDashboard',
        component: AdminDashboard,
        meta: { role: 'admin' }
      },
      {
        path: 'ceo',
        name: 'CeoDashboard',
        component: CeoDashboard,
        meta: { role: 'ceo' }
      },
      {
        path: 'company',
        name: 'CompanyDashboard',
        component: CompanyDashboard,
        meta: { role: 'company' }
      },
      {
        path: 'freelancer',
        name: 'FreelancerDashboard',
        component: FreelancerDashboard,
        meta: { role: 'freelancer' }
      },
      {
        path: 'project-manager',
        name: 'ProjectManagerDashboard',
        component: ProjectManagerDashboard,
        meta: { role: 'project-manager' }
      },
      {
        path: 'admin/users',
        name: 'UserManagement',
        component: UserManagement,
        meta: { role: 'admin' }
      },
      {
        path: 'admin/roles',
        name: 'RoleManagement',
        component: RoleManagement,
        meta: { role: 'admin' }
      }
      ,{
        path: 'admin/payment-settings',
        name: 'AdminPaymentSettings',
        component: AdminPaymentSettings,
        meta: { role: 'admin' }
      }
      ,{
        path: 'admin/connect',
        name: 'AdminConnect',
        component: AdminConnect,
        meta: { role: 'admin' }
      }
      ,{
        path: 'invoices',
        name: 'Invoices',
        component: Invoices
      }
      ,{
        path: 'portfolio',
        name: 'Portfolio',
        component: Portfolio
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('authToken');

  if (to.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
    next('/login');
  } else {
    next();
  }
});

export default router;
