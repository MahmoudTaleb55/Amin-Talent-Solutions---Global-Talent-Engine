// Freelance Management System Frontend
class FreelanceApp {
    constructor() {
        // Pick API base dynamically. If running via HTTP(S), use same origin; otherwise fallback to 127.0.0.1:8000
        const origin = window.location.origin;
        if (origin && origin.startsWith('http')) {
            this.apiBaseUrl = `${origin}/api/v1`;
        } else {
            this.apiBaseUrl = 'http://127.0.0.1:8000/api/v1';
        }
        this.token = null;
        this.user = null;
        this.currentView = 'dashboard';

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkAuthStatus();
        // On load, attempt to prefill job requests if already authenticated and role is company
        window.addEventListener('load', () => {
            if (this.user && this.user.role === 'company') {
                // Populate main company section immediately
                this.loadCompanyDashboard();
            }
        });
    }

    setupEventListeners() {
        // Login form
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.login();
            });
        }

        // Company registration
        const showRegisterBtn = document.getElementById('showRegisterBtn');
        if (showRegisterBtn) {
            showRegisterBtn.addEventListener('click', () => this.showCompanyRegisterModal());
        }

        const companyRegisterForm = document.getElementById('companyRegisterForm');
        if (companyRegisterForm) {
            companyRegisterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.registerCompany();
            });
        }

        // Password validation for registration
        const passwordInput = document.getElementById('companyPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        if (passwordInput) {
            passwordInput.addEventListener('input', () => this.validatePassword());
        }

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', () => this.validatePasswordMatch());
        }

        // Navigation (guarded for missing elements)
        const dashboardLink = document.getElementById('dashboardLink');
        if (dashboardLink) dashboardLink.addEventListener('click', () => this.showDashboard());
        const logoutLink = document.getElementById('logoutLink');
        if (logoutLink) logoutLink.addEventListener('click', () => this.logout());
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) logoutBtn.addEventListener('click', () => this.logout());

        // Company navigation
        const jobRequestsLink = document.getElementById('jobRequestsLink');
        if (jobRequestsLink) jobRequestsLink.addEventListener('click', () => this.showJobRequests());
        const assignmentsLink = document.getElementById('assignmentsLink');
        if (assignmentsLink) assignmentsLink.addEventListener('click', () => this.showAssignments());
        const trackProgressLink = document.getElementById('trackProgressLink');
        if (trackProgressLink) trackProgressLink.addEventListener('click', () => this.showTrackProgress());

        // Freelancer navigation
        const myAssignmentsLink = document.getElementById('myAssignmentsLink');
        if (myAssignmentsLink) myAssignmentsLink.addEventListener('click', () => this.showMyAssignments());
        const submitDeliverableLink = document.getElementById('submitDeliverableLink');
        if (submitDeliverableLink) submitDeliverableLink.addEventListener('click', () => this.showSubmitDeliverable());
        const updateStatusLink = document.getElementById('updateStatusLink');
        if (updateStatusLink) updateStatusLink.addEventListener('click', () => this.showUpdateStatus());

        // Admin navigation
        const manageUsersLink = document.getElementById('manageUsersLink');
        if (manageUsersLink) manageUsersLink.addEventListener('click', () => this.showManageUsers());
        const overseeJobsLink = document.getElementById('overseeJobsLink');
        if (overseeJobsLink) overseeJobsLink.addEventListener('click', () => this.showOverseeJobs());
        const analyticsLink = document.getElementById('analyticsLink');
        if (analyticsLink) analyticsLink.addEventListener('click', () => this.showAnalytics());

        // Modal form submissions
        const createJobForm = document.getElementById('createJobForm');
        if (createJobForm) {
            createJobForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createJobRequest();
            });
        }

        // Resend verification email
        const resendBtn = document.getElementById('resendVerificationBtn');
        if (resendBtn) {
            resendBtn.addEventListener('click', async () => {
                const email = (this.user && this.user.email) || document.getElementById('email')?.value;
                if (!email) return this.showToast('No email to verify', 'error');
                try {
                    await fetch(`${this.apiBaseUrl}/auth/resend-verification`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email })
                    });
                    this.showToast('Verification email sent (if account exists)', 'success');
                } catch (_) {
                    this.showToast('Failed to send verification email', 'error');
                }
            });
        }

        // Fallback: if create button exists on page
        const submitJobBtn = document.getElementById('submitJobBtn');
        if (submitJobBtn) submitJobBtn.addEventListener('click', () => this.createJobRequest());

        // Create job button (opens modal)
        const createJobBtn = document.getElementById('createJobBtn');
        if (createJobBtn) {
            createJobBtn.addEventListener('click', () => this.showCreateJobModal());
        }


    }

    checkAuthStatus() {
        const token = localStorage.getItem('auth_token');
        const user = localStorage.getItem('user');

        if (token) {
            this.token = token;
            if (user) {
                this.user = JSON.parse(user);
                this.showApp();
                this.loadDashboard();
            } else {
                // Hide login while we recover the user, to avoid flashing the login page
                const login = document.getElementById('loginPage');
                if (login) login.classList.add('d-none');
                // Recover user via token
                this.apiCall('/auth/me')
                    .then(u => {
                        if (!u || !u.role) throw new Error('Invalid user');
                        this.user = u;
                        localStorage.setItem('user', JSON.stringify(u));
                        this.showApp();
                        this.loadDashboard();
                    })
                    .catch(() => {
                        this.logout();
                    });
            }
        }
    }

    showLoginModal() {
        if (!this.token) {
            // Show login page only
            document.getElementById('loginPage').classList.remove('d-none');
            document.getElementById('app').classList.add('d-none');
            // Ensure all dashboards are hidden to avoid overlap when user logs in
            this.hideAllViews();
        }
    }

    async login() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const userRole = document.getElementById('userRole').value;

        try {
            const response = await fetch(`${this.apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email,
                    password,
                    device_name: 'web-browser'
                })
            });

            // If unverified, server may respond 403 with requires_verification
            if (response.status === 403) {
                const data = await response.json();
                if (data && data.requires_verification) {
                    const banner = document.getElementById('verificationBanner');
                    if (banner) banner.classList.remove('d-none');
                    this.showError('loginError', data.message || 'Please verify your email.');
                    return;
                }
            }

            const data = await response.json();

            if (response.ok) {
                // Enforce role selection consistency
                if (!data.user || !data.user.role) {
                    this.showError('loginError', 'Invalid user data');
                    return;
                }
                // CEO/Admin must select admin on login; CEO dashboard is not exposed via the public login
                const expected = userRole === 'admin' ? 'admin' : userRole;
                if (expected && expected !== data.user.role) {
                    this.showError('loginError', 'Selected role does not match your account role.');
                    return;
                }

                this.token = data.token;
                this.user = data.user;

                localStorage.setItem('auth_token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));

                // Hide login page and show app
                document.getElementById('loginPage').classList.add('d-none');
                this.showApp();

                // Show verification banner if backend says verification required
                if (data.requires_verification) {
                    const banner = document.getElementById('verificationBanner');
                    if (banner) banner.classList.remove('d-none');
                }

                this.showToast('Signed in successfully', 'success');
                this.loadDashboard();
            } else {
                this.showError('loginError', data.message || 'Login failed');
                this.showToast(data.message || 'Login failed', 'error');
            }
        } catch (error) {
            this.showError('loginError', 'Network error. Please try again.');
        }
    }

    logout() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        this.token = null;
        this.user = null;
        this.showLoginModal();
        document.getElementById('app').classList.add('d-none');
        this.showToast('Logged out', 'success');
    }

    showApp() {
        const login = document.getElementById('loginPage');
        if (login) login.classList.add('d-none');
        document.getElementById('app').classList.remove('d-none');
        this.updateUserInterface();
    }

    updateUserInterface() {
        if (this.user) {
            const userInfo = document.getElementById('userInfo');
            if (userInfo) userInfo.textContent = `Welcome, ${this.user.username}`;

            // Show/hide role-specific menus
            const companyMenu = document.getElementById('companyMenu');
            if (companyMenu) companyMenu.classList.toggle('d-none', this.user.role !== 'company');
            const freelancerMenu = document.getElementById('freelancerMenu');
            if (freelancerMenu) freelancerMenu.classList.toggle('d-none', this.user.role !== 'freelancer');
            const adminMenu = document.getElementById('adminMenu');
            if (adminMenu) adminMenu.classList.toggle('d-none', this.user.role !== 'admin');
            const ceoMenu = document.getElementById('ceoMenu');
            if (ceoMenu) ceoMenu.classList.toggle('d-none', this.user.role !== 'ceo');
        }
    }

    showDashboard() {
        this.currentView = 'dashboard';
        this.hideAllViews();
        document.getElementById('dashboardContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Dashboard';
        // Ensure menus reflect the current role
        this.updateUserInterface();
        this.loadDashboard();
    }

    hideAllViews() {
        const views = [
            'dashboardContent', 'companyDashboard', 'freelancerDashboard', 'adminDashboard', 'ceoDashboard',
            'jobRequestsContent', 'assignmentsContent', 'deliverablesContent', 'usersContent'
        ];

        views.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('d-none');
        });
    }

    async loadDashboard() {
        if (!this.user) return;

        // Ensure only one main view is visible to avoid overlap on first load
        this.hideAllViews();

        try {
            switch (this.user.role) {
                case 'company':
                    await this.loadCompanyDashboard();
                    break;
                case 'freelancer':
                    await this.loadFreelancerDashboard();
                    break;
                case 'admin':
                    await this.loadAdminDashboard();
                    break;
                case 'ceo':
                    await this.loadCeoDashboard();
                    break;
            }
        } catch (error) {
            console.error('Error loading dashboard:', error);
        }
    }

    async loadCompanyDashboard() {
        document.getElementById('companyDashboard').classList.remove('d-none');
        document.getElementById('dashboardContent').classList.add('d-none');

        try {
            const [jobsRes, assignsRes, delivsRes] = await Promise.allSettled([
                this.apiCall('/job-requests'),
                this.apiCall('/assignments'),
                this.apiCall('/deliverables')
            ]);

            if (jobsRes.status === 'fulfilled') {
                const jobRequests = jobsRes.value || [];
                const jobCountEl = document.getElementById('companyJobCount');
                if (jobCountEl) jobCountEl.textContent = jobRequests.length;
                this.displayJobRequests(jobRequests.slice(0, 5));
            } else {
                const list = document.getElementById('companyJobRequestsList');
                if (list) list.innerHTML = '<div class="text-center text-muted">Unable to load job requests.</div>';
            }

            // Hide admin dashboard to avoid any overlap
            const adminDash = document.getElementById('adminDashboard');
            if (adminDash) adminDash.classList.add('d-none');

            if (assignsRes.status === 'fulfilled') {
                const assignments = assignsRes.value || [];
                const freelancerIds = [...new Set(assignments.map(a => a.freelancer_id))];
                const countEl = document.getElementById('companyFreelancerCount');
                if (countEl) countEl.textContent = freelancerIds.length;
            }

            if (delivsRes.status === 'fulfilled') {
                const deliverables = delivsRes.value || [];
                const pendingDeliverables = deliverables.filter(d => d.status === 'pending');
                const pendingEl = document.getElementById('companyPendingCount');
                if (pendingEl) pendingEl.textContent = pendingDeliverables.length;
                const completedDeliverables = deliverables.filter(d => d.status === 'approved');
                const completedEl = document.getElementById('companyCompletedCount');
                if (completedEl) completedEl.textContent = completedDeliverables.length;
            }
        } catch (error) {
            console.error('Error loading company dashboard:', error);
            this.showToast('Failed to load company dashboard', 'error');
        }
    }

    async loadFreelancerDashboard() {
        document.getElementById('freelancerDashboard').classList.remove('d-none');
        document.getElementById('dashboardContent').classList.add('d-none');

        try {
            // Load assignments for freelancer
            const assignments = await this.apiCall('/freelancer/assignments');
            const assignmentCountEl = document.getElementById('freelancerAssignmentCount');
            if (assignmentCountEl) assignmentCountEl.textContent = assignments.length;

            // Load deliverables
            const deliverables = await this.apiCall('/deliverables');
            const completedDeliverables = deliverables.filter(d => d.status === 'approved');
            const freelancerCompletedEl = document.getElementById('freelancerCompletedCount');
            if (freelancerCompletedEl) freelancerCompletedEl.textContent = completedDeliverables.length;

            const pendingDeliverables = deliverables.filter(d => d.status === 'pending');
            const freelancerPendingEl = document.getElementById('freelancerPendingCount');
            if (freelancerPendingEl) freelancerPendingEl.textContent = pendingDeliverables.length;

            // Display assignments
            this.displayAssignments(assignments);

        } catch (error) {
            console.error('Error loading freelancer dashboard:', error);
        }
    }

    async loadCeoDashboard() {
        // Show CEO dashboard container and hide generic dashboard
        const ceo = document.getElementById('ceoDashboard');
        if (ceo) ceo.classList.remove('d-none');
        const generic = document.getElementById('dashboardContent');
        if (generic) generic.classList.add('d-none');
        // Reuse admin analytics endpoints for now
        try {
            const [users, jobs, active, completed] = await Promise.all([
                this.apiCall('/analytics/user-growth'),
                this.apiCall('/analytics'),
                this.apiCall('/analytics/monthly-jobs'),
                this.apiCall('/analytics/job-distribution')
            ]);
            const u = document.getElementById('ceoUserCount'); if (u) u.textContent = (users?.total_users ?? 0);
            const j = document.getElementById('ceoJobCount'); if (j) j.textContent = (jobs?.total_jobs ?? 0);
            const a = document.getElementById('ceoActiveCount'); if (a) a.textContent = (jobs?.active_projects ?? 0);
            const c = document.getElementById('ceoCompletedCount'); if (c) c.textContent = (jobs?.completed_projects ?? 0);
            // Charts (optional, reuse IDs if present)
            if (window.Chart) {
                const ctx1 = document.getElementById('ceoUserChart');
                if (ctx1) new Chart(ctx1, { type: 'doughnut', data: { labels: ['Companies','Freelancers','Admins','CEO'], datasets: [{ data: [jobs?.companies ?? 0, jobs?.freelancers ?? 0, jobs?.admins ?? 0, 1], backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444'] }] } });
                const ctx2 = document.getElementById('ceoJobChart');
                if (ctx2) new Chart(ctx2, { type: 'doughnut', data: { labels: ['Open','Assigned','Completed','Rejected'], datasets: [{ data: [jobs?.open ?? 0, jobs?.active_projects ?? 0, jobs?.completed_projects ?? 0, jobs?.rejected ?? 0], backgroundColor: ['#60a5fa','#fbbf24','#34d399','#f87171'] }] } });
            }
        } catch (e) {
            console.error('CEO dashboard load failed', e);
        }
    }

    async loadAdminDashboard() {
        document.getElementById('adminDashboard').classList.remove('d-none');
        document.getElementById('dashboardContent').classList.add('d-none');

        // Show skeletons in admin metrics
        const setSkel = (id) => { const el = document.getElementById(id); if (el) el.innerHTML = '<span class="skeleton skeleton-title" style="display:inline-block;width:60px;height:24px"></span>'; };
        setSkel('adminUserCount'); setSkel('adminJobCount'); setSkel('adminActiveCount'); setSkel('adminCompletedCount');

        try {
            // Load users
            const users = await this.apiCall('/users');
            document.getElementById('adminUserCount').textContent = users.length;

            // Load job requests (admin should get all)
            const jobRequests = await this.apiCall('/job-requests');
            document.getElementById('adminJobCount').textContent = jobRequests.length;

            // Load assignments
            const assignments = await this.apiCall('/assignments');
            document.getElementById('adminActiveCount').textContent = assignments.length;

            // Load deliverables
            const deliverables = await this.apiCall('/deliverables');
            const completedDeliverables = deliverables.filter(d => d.status === 'approved');
            document.getElementById('adminCompletedCount').textContent = completedDeliverables.length;

            // Create charts
            this.createUserChart(users);
            this.createJobChart(jobRequests, assignments);

        } catch (error) {
            console.error('Error loading admin dashboard:', error);
            const dash = document.getElementById('dashboardContent');
            if (dash) dash.insertAdjacentHTML('afterbegin', this.errorCard('Failed to load admin dashboard data.'));
        }
    }

    displayJobRequests(jobRequests) {
        const container = document.getElementById('companyJobRequestsList');

        if (!container) return;

        if (!Array.isArray(jobRequests)) {
            container.innerHTML = this.errorCard('Failed to load job requests.');
            return;
        }

        if (jobRequests.length === 0) {
            container.innerHTML = this.emptyState('No job requests found.', 'Create your first job request to get started.', 'fa-clipboard-list');
            return;
        }

        const html = jobRequests.map(job => `
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title">${job.description}</h6>
                            <p class="card-text text-muted mb-1">Deadline: ${new Date(job.deadline).toLocaleDateString()}</p>
                            <span class="status-badge status-${job.status}">${job.status}</span>
                        </div>
                        <div class="btn-group gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="app.viewJobRequest(${job.id})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="app.assignFreelancer(${job.id})" title="Assign">
                                <i class="fas fa-user-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="app.deleteJobRequest(${job.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        container.innerHTML = html;
    }

    // Simple empty and error card helpers used across views
    emptyState(title, msg, icon = 'fa-info-circle') {
        return `
            <div class="card text-center p-4">
                <div class="card-body">
                    <i class="fas ${icon} fa-2x mb-2 text-muted"></i>
                    <h6 class="mb-1">${title}</h6>
                    <p class="text-muted mb-0">${msg}</p>
                </div>
            </div>`;
    }
    errorCard(msg) {
        return `
            <div class="card border-0" role="alert" aria-live="assertive">
                <div class="card-body bg-light border border-danger rounded">
                    <i class="fas fa-triangle-exclamation text-danger me-2"></i>${msg}
                </div>
            </div>`;
    }

    displayAssignments(assignments) {
        const container = document.getElementById('freelancerAssignmentsList');

        if (assignments.length === 0) {
            container.innerHTML = '<p class="text-muted text-center">No assignments found.</p>';
            return;
        }

        const html = assignments.map(assignment => `
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title">${assignment.job_request?.description || assignment.jobRequest?.description || 'Job Request'}</h6>
                            <p class="card-text text-muted mb-1">Assigned: ${new Date(assignment.created_at).toLocaleDateString()}</p>
                            <span class="status-badge status-assigned">Assigned</span>
                        </div>
                        <div class="btn-group gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="app.viewAssignment(${assignment.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="app.submitDeliverable(${assignment.id})">
                                <i class="fas fa-upload"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="app.updateStatus(${assignment.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        container.innerHTML = html;
    }

    async loadCeoDashboard() {
        const ceoDash = document.getElementById('ceoDashboard');
        if (ceoDash) ceoDash.classList.remove('d-none');
        const dash = document.getElementById('dashboardContent');
        if (dash) dash.classList.add('d-none');
        const title = document.getElementById('pageTitle');
        if (title) title.textContent = 'CEO Dashboard';

        try {
            const [users, jobs, assignments, deliverables] = await Promise.all([
                this.apiCall('/users'),
                this.apiCall('/job-requests'),
                this.apiCall('/assignments'),
                this.apiCall('/deliverables')
            ]);
            const setText = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = String(val); };
            setText('ceoUserCount', Array.isArray(users) ? users.length : 0);
            setText('ceoJobCount', Array.isArray(jobs) ? jobs.length : 0);
            setText('ceoActiveCount', Array.isArray(assignments) ? assignments.length : 0);
            const approved = (Array.isArray(deliverables) ? deliverables : []).filter(d => (d.status||'').toLowerCase()==='approved').length;
            setText('ceoCompletedCount', approved);
        } catch (e) {
            this.showToast('Failed to load CEO overview', 'error');
        }
    }

    createUserChart(users) {
        const ctx = document.getElementById('userChart').getContext('2d');

        const roleCounts = users.reduce((acc, user) => {
            acc[user.role] = (acc[user.role] || 0) + 1;
            return acc;
        }, {});

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(roleCounts),
                datasets: [{
                    data: Object.values(roleCounts),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    createJobChart(jobRequests, assignments) {
        const el = document.getElementById('jobChart');
        if (!el) return;
        const ctx = el.getContext('2d');

        const statusCounts = {
            open: jobRequests.filter(j => (j.status || '').toLowerCase() === 'pending').length,
            assigned: assignments.length,
            completed: jobRequests.filter(j => (j.status || '').toLowerCase() === 'completed').length
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Open', 'Assigned', 'Completed'],
                datasets: [{
                    label: 'Jobs',
                    data: Object.values(statusCounts),
                    backgroundColor: ['#ffc107', '#17a2b8', '#28a745']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Navigation methods
    async showJobRequests() {
        this.currentView = 'jobRequests';
        this.hideAllViews();
        document.getElementById('jobRequestsContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Job Requests';
        await this.loadJobRequestsPage();
    }

    async showAssignments() {
        this.currentView = 'assignments';
        this.hideAllViews();
        document.getElementById('assignmentsContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Assignments';
        await this.loadAssignmentsPage();
    }

    async showTrackProgress() {
        this.currentView = 'trackProgress';
        this.hideAllViews();
        document.getElementById('assignmentsContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Track Progress';
        await this.loadAssignmentsPage();
    }

    async showMyAssignments() {
        this.currentView = 'myAssignments';
        this.hideAllViews();
        document.getElementById('freelancerDashboard').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'My Assignments';
        // The freelancer dashboard already populates assignments via loadFreelancerDashboard
    }

    async showSubmitDeliverable() {
        this.currentView = 'submitDeliverable';
        this.hideAllViews();
        document.getElementById('deliverablesContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Submit Deliverable';
        await this.loadDeliverablesPage();
    }

    async showUpdateStatus() {
        this.currentView = 'updateStatus';
        this.hideAllViews();
        document.getElementById('deliverablesContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Update Status';
        await this.loadDeliverablesPage();
    }

    async showManageUsers() {
        this.currentView = 'manageUsers';
        this.hideAllViews();
        document.getElementById('usersContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Manage Users';
        await this.loadUsersPage();
    }

    async showOverseeJobs() {
        this.currentView = 'overseeJobs';
        this.hideAllViews();
        document.getElementById('jobRequestsContent').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Oversee Jobs';
        await this.loadJobRequestsPage();
    }

    showAnalytics() {
        this.currentView = 'analytics';
        this.hideAllViews();
        document.getElementById('adminDashboard').classList.remove('d-none');
        document.getElementById('pageTitle').textContent = 'Analytics';
    }

    // Page data loaders
    async loadJobRequestsPage() {
        const container = document.getElementById('jobRequestsContent');
        container.innerHTML = '<div class="text-center text-muted py-4">Loading job requests...</div>';
        try {
            const jobs = await this.apiCall('/job-requests');
            if (!jobs || jobs.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-4">No job requests found.</div>';
                return;
            }
            container.innerHTML = `
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" id="openCreateJobBtn"><i class="fas fa-plus me-2"></i>Create Job Request</button>
                </div>
                <div class="row" id="jobRequestsGrid"></div>
            `;
            const grid = document.getElementById('jobRequestsGrid');
            grid.innerHTML = jobs.map(j => `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">${j.description}</h6>
                            <p class="text-muted mb-1">Deadline: ${new Date(j.deadline).toLocaleDateString()}</p>
                            <span class="status-badge status-${j.status}">${j.status}</span>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-end gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="app.viewJobRequest(${j.id})"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>
            `).join('');
            const btn = document.getElementById('openCreateJobBtn');
            if (btn) btn.addEventListener('click', () => this.showCreateJobModal());
        } catch (e) {
            container.innerHTML = `<div class="alert alert-danger">Failed to load job requests: ${e.message}</div>`;
        }
    }

    async loadAssignmentsPage() {
        const container = document.getElementById('assignmentsContent');
        container.innerHTML = '<div class="text-center text-muted py-4">Loading assignments...</div>';
        try {
            const assignments = await this.apiCall('/assignments');
            if (!assignments || assignments.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-4">No assignments found.</div>';
                return;
            }
            container.innerHTML = '<div class="row" id="assignmentsGrid"></div>';
            const grid = document.getElementById('assignmentsGrid');
            grid.innerHTML = assignments.map(a => `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">${a.job_request?.description || a.jobRequest?.description || 'Assignment'}</h6>
                            <p class="text-muted mb-1">Assigned: ${new Date(a.created_at).toLocaleDateString()}</p>
                            <span class="status-badge status-assigned">Assigned</span>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-end gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="app.viewAssignment(${a.id})"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (e) {
            container.innerHTML = `<div class="alert alert-danger">Failed to load assignments: ${e.message}</div>`;
        }
    }

    async loadDeliverablesPage() {
        const container = document.getElementById('deliverablesContent');
        container.innerHTML = '<div class="text-center text-muted py-4">Loading deliverables...</div>';
        try {
            const deliverables = await this.apiCall('/deliverables');
            if (!deliverables || deliverables.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-4">No deliverables found.</div>';
                return;
            }
            container.innerHTML = '<div class="row" id="deliverablesGrid"></div>';
            const grid = document.getElementById('deliverablesGrid');
            grid.innerHTML = deliverables.map(d => `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">${d.content || 'Deliverable'}</h6>
                            <p class="text-muted mb-1">Submitted: ${d.submitted_on ? new Date(d.submitted_on).toLocaleDateString() : 'N/A'}</p>
                            <span class="status-badge status-${d.status}">${d.status}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (e) {
            container.innerHTML = `<div class="alert alert-danger">Failed to load deliverables: ${e.message}</div>`;
        }
    }

    async loadUsersPage() {
        const container = document.getElementById('usersContent');
        container.innerHTML = '<div class="text-center text-muted py-4">Loading users...</div>';
        try {
            const users = await this.apiCall('/users');
            if (!users || users.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-4">No users found.</div>';
                return;
            }
            container.innerHTML = '<div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Username</th><th>Email</th><th>Role</th></tr></thead><tbody id="usersTableBody"></tbody></table></div>';
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = users.map(u => `
                <tr>
                    <td>${u.username}</td>
                    <td>${u.email}</td>
                    <td><span class="badge bg-secondary text-uppercase">${u.role}</span></td>
                </tr>
            `).join('');
        } catch (e) {
            container.innerHTML = `<div class="alert alert-danger">Failed to load users: ${e.message}</div>`;
        }
    }

    // API helper method
    async apiCall(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                'Authorization': `Bearer ${this.token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...options.headers
            }
        };

        // If sending FormData, let the browser set the Content-Type (multipart/form-data with boundary)
        if (options && options.body instanceof FormData) {
            delete defaultOptions.headers['Content-Type'];
        }

        // Show top progress bar start
        this.progressStart();

        const response = await fetch(`${this.apiBaseUrl}${endpoint}`, {
            ...defaultOptions,
            ...options
        });

        // Finish progress bar
        this.progressDone();

        if (response.status === 401) {
            this.logout();
            throw new Error('Unauthorized');
        }

        // Try to parse JSON for better error detail
        let data;
        const text = await response.text();
        try { data = text ? JSON.parse(text) : null; } catch (_) { data = text; }

        if (!response.ok) {
            const message = (data && (data.message || data.error)) || `API call failed: ${response.status}`;
            this.showToast(message, 'error');
            throw new Error(message);
        }

        return data;
    }

    showError(elementId, message) {
        const element = document.getElementById(elementId);
        if (elementId === 'loginError') {
            // For login error, update the span inside the alert
            const messageSpan = document.getElementById('errorMessage');
            if (messageSpan) {
                messageSpan.textContent = message;
            }
        } else {
            element.textContent = message;
        }
        element.classList.remove('d-none');
        setTimeout(() => element.classList.add('d-none'), 5000);
    }

    // Company registration methods
    showCompanyRegisterModal() {
        const modal = new bootstrap.Modal(document.getElementById('companyRegisterModal'));
        modal.show();
    }

    async registerCompany() {
        const registerBtn = document.getElementById('registerBtn');
        try {
            // Get form data
            const formData = {
                username: document.getElementById('username')?.value?.trim(),
                company_name: document.getElementById('companyName')?.value?.trim(),
                contact_person: document.getElementById('contactPerson')?.value?.trim(),
                email: document.getElementById('companyEmail')?.value?.trim(),
                phone: document.getElementById('companyPhone')?.value?.trim(),
                address: document.getElementById('companyAddress')?.value?.trim(),
                industry: document.getElementById('companyIndustry')?.value?.trim(),
                company_size: document.getElementById('companySize')?.value?.trim(),
                description: document.getElementById('companyDescription')?.value?.trim(),
                password: document.getElementById('companyPassword')?.value,
                password_confirmation: document.getElementById('confirmPassword')?.value
            };

            // Validate form
            if (!this.validateRegistrationForm(formData)) return;

            // Show loading state (guarded)
            const spinner = registerBtn?.querySelector('.spinner-border');
            const btnText = registerBtn?.querySelector('span:not(.spinner-border)');
            if (registerBtn) registerBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = 'Registering...';

            const response = await fetch(`${this.apiBaseUrl}/auth/register/company`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('registerSuccessMessage').textContent = 'Company registered successfully! Please check your email and click the verification link to activate your account.';
                document.getElementById('registerSuccess').classList.remove('d-none');
                document.getElementById('registerError').classList.add('d-none');
                document.getElementById('companyRegisterForm').reset();
                setTimeout(() => {
                    const modalEl = document.getElementById('companyRegisterModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                    document.getElementById('registerSuccess').classList.add('d-none');
                }, 3000);
            } else {
                this.showRegistrationError(data.message || 'Registration failed. Please try again.');
            }
        } catch (error) {
            this.showRegistrationError('Network error. Please try again.');
        } finally {
            if (registerBtn) registerBtn.disabled = false;
            const spinner = registerBtn?.querySelector('.spinner-border');
            const btnText = registerBtn?.querySelector('span:not(.spinner-border)');
            if (spinner) spinner.classList.add('d-none');
            if (btnText) btnText.textContent = 'Register Company';
        }
    }

    validateRegistrationForm(formData) {
        // Clear previous errors
        document.getElementById('registerError').classList.add('d-none');

        // Check required fields
        const requiredFields = ['username', 'company_name', 'contact_person', 'email', 'phone', 'address', 'industry', 'company_size', 'description', 'password'];
        for (const field of requiredFields) {
            if (!formData[field] || formData[field].trim() === '') {
                this.showRegistrationError('All fields are required.');
                return false;
            }
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            this.showRegistrationError('Please enter a valid email address.');
            return false;
        }

        // Validate password length
        if (formData.password.length < 8) {
            this.showRegistrationError('Password must be at least 8 characters long.');
            return false;
        }

        // Check password confirmation
        if (formData.password !== formData.password_confirmation) {
            this.showRegistrationError('Passwords do not match.');
            return false;
        }

        return true;
    }

    showRegistrationError(message) {
        document.getElementById('registerErrorMessage').textContent = message;
        document.getElementById('registerError').classList.remove('d-none');
        document.getElementById('registerSuccess').classList.add('d-none');
    }

    // Password validation methods
    validatePassword() {
        const password = document.getElementById('companyPassword').value;
        const requirementsContainer = document.getElementById('passwordRequirements');

        if (password.length > 0) {
            requirementsContainer.classList.remove('d-none');
        } else {
            requirementsContainer.classList.add('d-none');
            return;
        }

        // Check each requirement
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
        };

        // Update UI for each requirement
        Object.keys(requirements).forEach(req => {
            const element = document.querySelector(`[data-requirement="${req}"]`);
            if (element) {
                element.classList.toggle('valid', requirements[req]);
                element.classList.toggle('invalid', !requirements[req]);
            }
        });

        // Also validate password match when password changes
        this.validatePasswordMatch();
    }

    validatePasswordMatch() {
        const password = document.getElementById('companyPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const matchIndicator = document.getElementById('passwordMatchIndicator');

        if (confirmPassword.length > 0) {
            matchIndicator.classList.remove('d-none');
            const matches = password === confirmPassword;
            matchIndicator.classList.toggle('valid', matches);
            matchIndicator.classList.toggle('invalid', !matches);

            const icon = matchIndicator.querySelector('i');
            const text = matchIndicator.querySelector('small');

            if (matches) {
                icon.className = 'fas fa-check text-success me-1';
                text.textContent = 'Passwords match';
                text.className = 'text-success';
            } else {
                icon.className = 'fas fa-times text-danger me-1';
                text.textContent = 'Passwords do not match';
                text.className = 'text-danger';
            }
        } else {
            matchIndicator.classList.add('d-none');
        }
    }

    // Job Request Management
    async viewJobRequest(id) {
        try {
            const jobRequest = await this.apiCall(`/job-requests/${id}`);
            const modal = new bootstrap.Modal(document.getElementById('viewJobModal'));
            const content = document.getElementById('jobDetailsContent');

            content.innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <h5>${jobRequest.description}</h5>
                        <p><strong>Deadline:</strong> ${new Date(jobRequest.deadline).toLocaleDateString()}</p>
                        <p><strong>Status:</strong> <span class="status-badge status-${jobRequest.status}">${jobRequest.status}</span></p>
                        <p><strong>Created:</strong> ${new Date(jobRequest.created_at).toLocaleDateString()}</p>
                        ${jobRequest.company ? `<p><strong>Company:</strong> ${jobRequest.company.company_name}</p>` : ''}
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid gap-2">
                            ${this.user.role === 'company' ? `
                                <button class="btn btn-primary" onclick="app.assignFreelancer(${jobRequest.id})">
                                    <i class="fas fa-user-plus me-2"></i>Assign Freelancer
                                </button>
                            ` : ''}
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;

            modal.show();
        } catch (error) {
            this.showToast('Error loading job request details', 'error');
        }
    }

    async assignFreelancer(jobId) {
        try {
            // Load available freelancers
            const freelancers = await this.apiCall('/freelancers');
            if (!Array.isArray(freelancers) || freelancers.length === 0) {
                this.showError('assignFreelancerError', 'No freelancers available');
                return;
            }
            const select = document.getElementById('assignFreelancer');
            select.innerHTML = '<option value="">Select Freelancer</option>';

            freelancers.forEach(freelancer => {
                const option = document.createElement('option');
                option.value = freelancer.id;
                const uname = freelancer.user ? freelancer.user.username : `Freelancer #${freelancer.id}`;
                const skills = freelancer.skills || freelancer.skillset || '';
                option.textContent = `${uname}${skills ? ' - ' + skills : ''}`;
                select.appendChild(option);
            });

            // Set job ID
            document.getElementById('assignJobId').value = jobId;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('assignFreelancerModal'));
            modal.show();

            // Handle form submission
            document.getElementById('assignFreelancerBtn').onclick = async () => {
                const freelancerId = select.value;
                if (!freelancerId) {
                    this.showError('assignFreelancerError', 'Please select a freelancer');
                    return;
                }

                try {
                    // Need company_id per backend validation — fetch the company id of the logged-in user
                    const me = this.user;
                    const companies = await this.apiCall('/companies');
                    const myCompany = Array.isArray(companies)
                        ? companies.find(c => c.user_id === me.id)
                        : null;
                    if (!myCompany) throw new Error('Company profile not found');

                    await this.apiCall('/assignments', {
                        method: 'POST',
                        body: JSON.stringify({
                            job_request_id: jobId,
                            freelancer_id: Number(freelancerId),
                            company_id: myCompany.id
                        })
                    });

                    modal.hide();
                    this.showToast('Freelancer assigned successfully', 'success');
                    this.loadDashboard(); // Refresh dashboard
                } catch (error) {
                    this.showError('assignFreelancerError', error.message || 'Failed to assign freelancer');
                }
            };
        } catch (error) {
            this.showToast('Error loading freelancers', 'error');
        }
    }

    // Assignment Management
    async viewAssignment(id) {
        try {
            const assignment = await this.apiCall(`/assignments/${id}`);
            const modal = new bootstrap.Modal(document.getElementById('viewAssignmentModal'));
            const content = document.getElementById('assignmentDetailsContent');

            content.innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <h5>Assignment Details</h5>
                        <p><strong>Job:</strong> ${assignment.job_request?.description || assignment.jobRequest?.description || 'N/A'}</p>
                        <p><strong>Freelancer:</strong> ${assignment.freelancer?.user?.username || 'N/A'}</p>
                        <p><strong>Status:</strong> <span class="status-badge status-assigned">${assignment.status}</span></p>
                        <p><strong>Assigned:</strong> ${new Date(assignment.created_at).toLocaleDateString()}</p>
                        <p><strong>Deadline:</strong> ${assignment.job_request ? new Date(assignment.job_request.deadline).toLocaleDateString() : (assignment.jobRequest ? new Date(assignment.jobRequest.deadline).toLocaleDateString() : 'N/A')}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid gap-2">
                            ${this.user.role === 'freelancer' ? `
                                <button class="btn btn-success" onclick="app.submitDeliverable(${assignment.id})">
                                    <i class="fas fa-upload me-2"></i>Submit Deliverable
                                </button>
                                <button class="btn btn-info" onclick="app.updateStatus(${assignment.id})">
                                    <i class="fas fa-edit me-2"></i>Update Status
                                </button>
                            ` : ''}
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;

            modal.show();
        } catch (error) {
            this.showToast('Error loading assignment details', 'error');
        }
    }

    // Deliverable Management
    async submitDeliverable(assignmentId) {
        try {
            // Load assignment details for the form
            const assignment = await this.apiCall(`/assignments/${assignmentId}`);
            const select = document.getElementById('deliverableAssignment');
            select.innerHTML = `<option value="${assignment.id}" selected>${assignment.job_request?.description || assignment.jobRequest?.description || 'Assignment'}</option>`;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('submitDeliverableModal'));
            modal.show();

            // Handle form submission
            document.getElementById('submitDeliverableBtn').onclick = async () => {
                const formData = new FormData();
                formData.append('assignment_id', assignmentId);
                formData.append('content', document.getElementById('deliverableContent').value);
                formData.append('submitted_on', document.getElementById('deliverableSubmittedOn').value);

                const fileInput = document.getElementById('deliverableFile');
                if (fileInput.files[0]) {
                    formData.append('file', fileInput.files[0]);
                }

                // Validate required fields
                if (!document.getElementById('deliverableContent').value ||
                    !document.getElementById('deliverableSubmittedOn').value) {
                    this.showError('submitDeliverableError', 'Please fill in all required fields');
                    return;
                }

                try {
                    await this.apiCall('/deliverables', {
                        method: 'POST',
                        headers: {}, // Let browser set content-type for FormData
                        body: formData
                    });

                    modal.hide();
                    this.showToast('Deliverable submitted successfully', 'success');
                    this.loadDashboard(); // Refresh dashboard
                } catch (error) {
                    this.showError('submitDeliverableError', 'Failed to submit deliverable');
                }
            };
        } catch (error) {
            this.showToast('Error loading assignment', 'error');
        }
    }

    // Status Update Management
    async updateStatus(assignmentId) {
        try {
            // Load assignment details
            const assignment = await this.apiCall(`/assignments/${assignmentId}`);
            const select = document.getElementById('statusAssignment');
            select.innerHTML = `<option value="${assignment.id}" selected>${assignment.job_request?.description || assignment.jobRequest?.description || 'Assignment'}</option>`;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
            modal.show();

            // Handle form submission
            document.getElementById('updateStatusBtn').onclick = async () => {
                const status = document.getElementById('statusUpdate').value;
                const notes = document.getElementById('statusNotes').value;
                const updatedOn = document.getElementById('statusUpdatedOn').value;

                if (!status || !updatedOn) {
                    this.showError('updateStatusError', 'Please fill in all required fields');
                    return;
                }

                try {
                    await this.apiCall('/status-updates', {
                        method: 'POST',
                        body: JSON.stringify({
                            assignment_id: assignmentId,
                            status: status,
                            notes: notes,
                            updated_on: updatedOn
                        })
                    });

                    modal.hide();
                    this.showToast('Status updated successfully', 'success');
                    this.loadDashboard(); // Refresh dashboard
                } catch (error) {
                    this.showError('updateStatusError', 'Failed to update status');
                }
            };
        } catch (error) {
            this.showToast('Error loading assignment', 'error');
        }
    }

    // Job Request Creation
    async createJobRequest() {
        const formData = {
            description: document.getElementById('jobDescription').value,
            deadline: document.getElementById('jobDeadline').value,
            status: document.getElementById('jobStatus').value
        };

        // Validate required fields
        if (!formData.description || !formData.deadline) {
            this.showError('createJobError', 'Please fill in all required fields');
            return;
        }

        try {
            await this.apiCall('/job-requests', {
                method: 'POST',
                body: JSON.stringify(formData)
            });

            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('createJobModal'));
            modal.hide();
            document.getElementById('createJobForm').reset();

            this.showToast('Job request created successfully', 'success');
            this.loadDashboard(); // Refresh dashboard
        } catch (error) {
            this.showError('createJobError', 'Failed to create job request');
        }
    }

    // Delete job request (company-only)
    async deleteJobRequest(jobId) {
        if (!confirm('Are you sure you want to delete this job request?')) return;
        try {
            await this.apiCall(`/job-requests/${jobId}`, { method: 'DELETE' });
            this.showToast('Job request deleted', 'success');
            this.loadDashboard();
        } catch (error) {
            this.showToast('Failed to delete job request', 'error');
        }
    }

    // Modal management methods
    showCreateJobModal() {
        const modal = new bootstrap.Modal(document.getElementById('createJobModal'));
        modal.show();
    }

    // Toast notification system
    showToast(message, type = 'success') {
        const toast = document.getElementById(type === 'success' ? 'successToast' : 'errorToast');
        const messageElement = document.getElementById(type === 'success' ? 'successToastMessage' : 'errorToastMessage');

        messageElement.textContent = message;

        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
    }

    // Simple top progress bar
    progressStart() {
        let bar = document.getElementById('top-progress-bar');
        if (!bar) {
            bar = document.createElement('div');
            bar.id = 'top-progress-bar';
            bar.innerHTML = '<div id="top-progress-peg"></div>';
            document.body.appendChild(bar);
        }
        bar.style.opacity = '1';
        bar.style.width = '20%';
        this._progressTimer && clearInterval(this._progressTimer);
        this._progressTimer = setInterval(() => {
            const w = parseFloat(bar.style.width);
            if (w < 90) bar.style.width = (w + Math.random() * 10).toFixed(1) + '%';
        }, 200);
    }

    progressDone() {
        const bar = document.getElementById('top-progress-bar');
        if (!bar) return;
        clearInterval(this._progressTimer);
        bar.style.width = '100%';
        setTimeout(() => { bar.style.opacity = '0'; }, 200);
        setTimeout(() => { bar.style.width = '0'; }, 500);
    }
}

// Initialize the app reliably whether DOMContentLoaded has fired or not
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.app = new FreelanceApp();
    });
} else {
    window.app = new FreelanceApp();
}
