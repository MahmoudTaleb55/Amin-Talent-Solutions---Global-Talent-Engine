// Frontend Integration Test Script
// Run this in the browser console to test API integration

class FrontendIntegrationTest {
    constructor() {
        this.apiBaseUrl = 'http://localhost:8000/api/v1';
        this.testResults = [];
    }

    async runAllTests() {
        console.log('🚀 Starting Frontend Integration Tests...\n');

        await this.testLoginAPI();
        await this.testCompanyRegistrationAPI();
        await this.testJobRequestsAPI();
        await this.testAssignmentsAPI();
        await this.testDeliverablesAPI();
        await this.testUsersAPI();
        await this.testCORSHeaders();

        this.displayResults();
    }

    async testLoginAPI() {
        console.log('Testing Login API...');
        try {
            const response = await fetch(`${this.apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: 'company@freelance.com',
                    password: 'password',
                    device_name: 'web-browser'
                })
            });

            const data = await response.json();
            const success = response.ok && data.token && data.user;

            this.testResults.push({
                test: 'Login API',
                status: success ? '✅ PASS' : '❌ FAIL',
                details: success ? 'Login successful' : `Failed: ${data.message || 'Unknown error'}`
            });

        } catch (error) {
            this.testResults.push({
                test: 'Login API',
                status: '❌ FAIL',
                details: `Network error: ${error.message}`
            });
        }
    }

    async testCompanyRegistrationAPI() {
        console.log('Testing Company Registration API...');
        try {
            const response = await fetch(`${this.apiBaseUrl}/auth/register/company`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: 'testcompany2',
                    email: 'testcompany2@example.com',
                    password: 'password123',
                    password_confirmation: 'password123',
                    company_name: 'Test Company 2',
                    contact_person: 'John Doe',
                    phone: '+1234567890',
                    address: '123 Test Street',
                    industry: 'Technology',
                    company_size: '10-50 employees',
                    description: 'Test company for API testing'
                })
            });

            const data = await response.json();
            const success = response.status === 201 && data.message;

            this.testResults.push({
                test: 'Company Registration API',
                status: success ? '✅ PASS' : '❌ FAIL',
                details: success ? 'Registration successful' : `Failed: ${data.message || 'Unknown error'}`
            });

        } catch (error) {
            this.testResults.push({
                test: 'Company Registration API',
                status: '❌ FAIL',
                details: `Network error: ${error.message}`
            });
        }
    }

    async testJobRequestsAPI() {
        console.log('Testing Job Requests API...');
        try {
            // First login to get token
            const loginResponse = await fetch(`${this.apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: 'company@freelance.com',
                    password: 'password',
                    device_name: 'web-browser'
                })
            });

            const loginData = await loginResponse.json();
            const token = loginData.token;

            // Test GET job requests
            const getResponse = await fetch(`${this.apiBaseUrl}/job-requests`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                }
            });

            const getData = await getResponse.json();
            const success = getResponse.ok && Array.isArray(getData);

            this.testResults.push({
                test: 'Job Requests API (GET)',
                status: success ? '✅ PASS' : '❌ FAIL',
                details: success ? `Retrieved ${getData.length} job requests` : `Failed: ${getData.message || 'Unknown error'}`
            });

        } catch (error) {
            this.testResults.push({
                test: 'Job Requests API (GET)',
                status: '❌ FAIL',
                details: `Network error: ${error.message}`
            });
        }
    }

    async testAssignmentsAPI() {
        console.log('Testing Assignments API...');
        try {
            // First login to get token
            const loginResponse = await fetch(`${this.apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: 'freelancer@freelance.com',
                    password: 'password',
                    device_name: 'web-browser'
                })
            });

            const loginData = await loginResponse.json();
            const token = loginData.token;

            // Test GET assignments
            const getResponse = await fetch(`${this.apiBaseUrl}/assignments`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                }
            });

            const getData = await getResponse.json();
            const success = getResponse.ok && Array.isArray(getData);

            this.testResults.push({
                test: 'Assignments API (GET)',
                status: success ? '✅ PASS' : '❌ FAIL',
                details: success ? `Retrieved ${getData.length} assignments` : `Failed: ${getData.message || 'Unknown error'}`
            });

        } catch (error) {
            this.testResults.push({
                test: 'Assignments API (GET)',
                status: '❌ FAIL',
                details: `Network error: ${error.message}`
            });
        }
    }

    async testDeliverablesAPI() {
        console.log('Testing Deliverables API...');
        try {
            // First login to get token
            const loginResponse = await fetch(`${this.apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: 'freelancer@freelance.com',
                    password: 'password',
                    device_name: 'web-browser'
                })
            });

            const loginData = await loginResponse.json();
            const token = loginData.token;

            // Test GET deliverables
            const getResponse = await fetch(`${this.apiBaseUrl}/deliverables`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                }
            });

            const getData = await getResponse.json();
            const success = getResponse.ok && Array.isArray(getData);

            this.testResults.push({
                test: 'Deliverables API (GET)',
                status: success ? '✅ PASS' : '❌ FAIL',
                details: success ? `Retrieved ${getData.length} deliverables` : `Failed: ${getData.message || 'Unknown error'}`
            });

        } catch (error) {
            this.testResults.push({
                test: 'Deliverables API (GET)',
                status: '❌ FAIL',
                details: `Network error: ${error.message}`
            });
        }
    }

    async testUsersAPI() {
        console.log('Testing Users API...');
        try {
            // First login to get token (admin)
            const loginResponse = await fetch(`${this.apiBaseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: 'admin@freelance.com',
                    password: 'password',
                    device_name: 'web-browser'
                })
            });

            const loginData = await loginResponse.json();
            const token = loginData.token;

            // Test GET users
            const getResponse = await fetch(`${this.apiBaseUrl}/users`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                }
            });

            const getData = await getResponse.json();
            const success = getResponse.ok && Array.isArray(getData);

            this.testResults.push({
                test: 'Users API (GET)',
                status: success ? '✅ PASS' : '❌ FAIL',
                details: success ? `Retrieved ${getData.length} users` : `Failed: ${getData.message || 'Unknown error'}`
            });

        } catch (error) {
            this.testResults.push({
                test: 'Users API (GET)',
                status: '❌ FAIL',
                details: `Network error: ${error.message}`
            });
        }
    }

    async testCORSHeaders() {
        console.log('Testing CORS Headers...');
        try {
            const response = await fetch(`${this.apiBaseUrl}/auth/login`, {
                method: 'OPTIONS',
                headers: {
                    'Origin': 'http://localhost:3000',
                    'Access-Control-Request-Method': 'POST',
                    'Access-Control-Request-Headers': 'Content-Type'
                }
            });

            const corsHeaders = {
                'Access-Control-Allow-Origin': response.headers.get('Access-Control-Allow-Origin'),
                'Access-Control-Allow-Methods': response.headers.get('Access-Control-Allow-Methods'),
                'Access-Control-Allow-Headers': response.headers.get('Access-Control-Allow-Headers')
            };

            const hasCORS = corsHeaders['Access-Control-Allow-Origin'] !== null;

            this.testResults.push({
                test: 'CORS Headers',
                status: hasCORS ? '✅ PASS' : '❌ FAIL',
                details: hasCORS ? 'CORS headers present' : 'CORS headers missing'
            });

        } catch (error) {
            this.testResults.push({
                test: 'CORS Headers',
                status: '❌ FAIL',
                details: `Network error: ${error.message}`
            });
        }
    }

    displayResults() {
        console.log('\n📊 Test Results Summary:');
        console.log('========================');

        this.testResults.forEach(result => {
            console.log(`${result.test}: ${result.status}`);
            console.log(`   ${result.details}`);
            console.log('');
        });

        const passed = this.testResults.filter(r => r.status.includes('PASS')).length;
        const total = this.testResults.length;

        console.log(`🎯 Overall: ${passed}/${total} tests passed`);

        if (passed === total) {
            console.log('🎉 All tests passed! Frontend-backend integration is working correctly.');
        } else {
            console.log('⚠️  Some tests failed. Please check the details above.');
        }
    }
}

// Run the tests
const tester = new FrontendIntegrationTest();
tester.runAllTests();
