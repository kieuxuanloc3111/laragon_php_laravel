import axios from 'axios'

const api = axios.create({
    baseURL: 'http://realtime-chat-api.test/api',
})

export default api