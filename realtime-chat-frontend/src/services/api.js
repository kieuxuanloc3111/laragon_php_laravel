import axios from 'axios'

const api = axios.create({
    baseURL: 'http://realtime-chat-api.test/api',
})

// Tự động đính kèm Bearer token vào mọi request nếu đã đăng nhập.
api.interceptors.request.use((config) => {

    const token = localStorage.getItem('token')

    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }

    return config
})

export default api
