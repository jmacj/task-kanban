import axios from 'axios';

/**
 *
 * @type {AxiosInstance}
 */
const api = axios.create({
    baseURL: '/api',
});

export default {
    /**
     * @param registrationData
     * @returns {Promise<AxiosResponse<any>>}
     */
    register(registrationData) {
        return api.post('/user/store', registrationData);
    },
    /**
     * @param credentials
     * @returns {Promise<AxiosResponse<any>>}
     */
    login(credentials) {
        return api.post(`/user/login`, credentials);
    },
    /**
     * @returns {Promise<AxiosResponse<any>>}
     */
    getTasks() {
        return api.get('/tasks');
    },

    /**
     * @param taskData
     * @returns {Promise<AxiosResponse<any>>}
     */
    createTask(taskData) {
        return api.post('/task/store', taskData);
    },

    /**
     * @param taskId
     * @param taskData
     * @returns {Promise<AxiosResponse<any>>}
     */
    updateTask(taskId, taskData) {
        return api.put(`/task/${taskId}`, taskData);
    },

    /**
     * @param taskId
     * @returns {Promise<AxiosResponse<any>>}
     */
    deleteTask(taskId) {
        return api.delete(`/task/${taskId}`);
    },
};
