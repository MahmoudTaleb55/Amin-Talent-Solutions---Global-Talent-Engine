import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api', // Your Laravel API endpoint
  withCredentials: false, // Not needed for token-based auth
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});

// Interceptor to add the auth token to requests
apiClient.interceptors.request.use(config => {
  const token = localStorage.getItem('authToken');
  if (token && !config.url.includes('/login')) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default {
  // Generic HTTP methods
  get(url, config) {
    return apiClient.get(url, config);
  },
  post(url, data, config) {
    return apiClient.post(url, data, config);
  },
  put(url, data, config) {
    return apiClient.put(url, data, config);
  },
  delete(url, config) {
    return apiClient.delete(url, config);
  },
  // Auth Methods
  register(user) {
    return apiClient.post('/register', user);
  },
  login(credentials) {
    return apiClient.post('/login', credentials);
  },
  logout() {
    return apiClient.post('/logout');
  },
  getUser() {
    return apiClient.get('/user');
  },
  // Assignment Methods
  getUnassignedProjects() {
    return apiClient.get('/projects?status=pending_assignment');
  },
  getFreelancers() {
    return apiClient.get('/users?role=Freelancer');
  },
  createAssignment(assignmentData) {
    return apiClient.post('/assignments', assignmentData);
  },

  // Admin API methods
  admin: {
    getUsers: () => apiClient.get('/admin/users'),
    createUser: (userData) => apiClient.post('/admin/users', userData),
    updateUser: (id, userData) => apiClient.put(`/admin/users/${id}`, userData),
    deleteUser: (id) => apiClient.delete(`/admin/users/${id}`),
    getStats: () => apiClient.get('/admin/stats'),
  },

  // CEO API methods
  ceo: {
    getOverview: () => apiClient.get('/ceo/overview'),
    getFinancialReports: () => apiClient.get('/ceo/financial-reports'),
    getUserManagement: () => apiClient.get('/ceo/user-management'),
    updateUserRole: (id, roleData) => apiClient.put(`/ceo/users/${id}/role`, roleData),
  },

  // Company API methods
  company: {
    postJob: (jobData) => apiClient.post('/company/post-job', jobData),
    getMyJobs: () => apiClient.get('/company/my-jobs'),
    getApplications: () => apiClient.get('/company/applications'),
    getOngoingProjects: () => apiClient.get('/company/ongoing-projects'),
    getBilling: () => apiClient.get('/company/billing'),
  },

  // Freelancer API methods
  freelancer: {
    getAssignments: () => apiClient.get('/freelancer/assignments'),
    submitDeliverable: (assignmentId, formData) => 
      apiClient.post(`/freelancer/assignments/${assignmentId}/deliverable`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      }),
    postStatusUpdate: (assignmentId, statusData) => 
      apiClient.post(`/freelancer/assignments/${assignmentId}/status`, statusData),
  },

  // Project Manager API methods
  projectManager: {
    getActiveProjects: () => apiClient.get('/project/active-projects'),
    allocateResource: (projectId, resourceData) => 
      apiClient.post(`/project/${projectId}/allocate`, resourceData),
    getProjectReports: () => apiClient.get('/project/reports'),
    getTeamPerformance: () => apiClient.get('/project/team-performance'),
  },

  // Generic Projects API
  projects: {
    list: () => apiClient.get('/projects'),
    get: (id) => apiClient.get(`/projects/${id}`),
    create: (projectData) => apiClient.post('/projects', projectData),
    update: (id, projectData) => apiClient.put(`/projects/${id}`, projectData),
    delete: (id) => apiClient.delete(`/projects/${id}`),
  },
  // Payments / Connect
  payments: {
    createPaymentIntent: (invoiceId) => apiClient.post(`/payments/invoice/${invoiceId}/create-intent`),
    webhook: (payload) => apiClient.post('/payments/webhook', payload),
    simulate: (invoiceId) => apiClient.post(`/payments/test/invoice/${invoiceId}/simulate`),
    connect: {
      create: () => apiClient.post('/payments/connect/create'),
      onboard: (userId) => apiClient.get(`/payments/connect/${userId}/onboard`)
    }
  },
};
