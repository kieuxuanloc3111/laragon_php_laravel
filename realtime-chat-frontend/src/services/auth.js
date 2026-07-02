import { ref } from 'vue'
import api from './api'

// Đọc user đã lưu trong localStorage (bền vững qua các lần mở lại trình duyệt).
const readStoredUser = () => {
    const raw = localStorage.getItem('user')

    if (!raw || raw === 'undefined') return null

    try {
        return JSON.parse(raw)
    } catch {
        return null
    }
}

// State đăng nhập dạng reactive: mọi component dùng chung,
// đổi ở đây thì header / trang tự cập nhật ngay (không cần reload).
export const currentUser = ref(readStoredUser())

export const getToken = () => localStorage.getItem('token')

export const getUser = () => currentUser.value

export const isLoggedIn = () => !!localStorage.getItem('token')

export const setAuth = (token, user) => {
    localStorage.setItem('token', token)
    localStorage.setItem('user', JSON.stringify(user))
    currentUser.value = user
}

export const logout = () => {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    currentUser.value = null
}

// Lấy lại thông tin user mới nhất từ server (token được interceptor tự gắn).
export const fetchUser = async () => {
    if (!getToken()) return null

    try {
        const { data } = await api.get('/user')

        localStorage.setItem('user', JSON.stringify(data))
        currentUser.value = data

        return data
    } catch {
        logout()
        return null
    }
}
