<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { setAuth } from '../services/auth'

const router = useRouter()

const email = ref('')
const password = ref('')
const remember = ref(true)
const showPassword = ref(false)
const loading = ref(false)
const error = ref('')

onMounted(() => {
    // Ghi nhớ email lần đăng nhập trước cho tiện.
    email.value = localStorage.getItem('remembered_email') || ''
})

const login = async () => {
    error.value = ''
    loading.value = true

    try {
        const response = await api.post('/login', {
            email: email.value,
            password: password.value,
        })

        if (remember.value) {
            localStorage.setItem('remembered_email', email.value)
        } else {
            localStorage.removeItem('remembered_email')
        }

        setAuth(response.data.token, response.data.user)

        // Điều hướng SPA, không reload -> header cập nhật ngay.
        router.push('/chat')

    } catch (e) {
        error.value = e.response?.data?.message || 'Email hoặc mật khẩu không đúng'
    } finally {
        loading.value = false
    }
}
</script>

<template>
  <div class="auth-wrap">
    <div class="auth-card">

      <div class="auth-head">
        <div class="auth-icon">💬</div>
        <h1>Chào mừng trở lại</h1>
        <p>Đăng nhập để tiếp tục trò chuyện</p>
      </div>

      <div class="field">
        <label>Email</label>
        <input
          v-model="email"
          type="email"
          placeholder="you@example.com"
          @keyup.enter="login"
        />
      </div>

      <div class="field">
        <label>Mật khẩu</label>
        <div class="password-box">
          <input
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            @keyup.enter="login"
          />
          <button
            type="button"
            class="toggle"
            @click="showPassword = !showPassword"
          >{{ showPassword ? '🙈' : '👁️' }}</button>
        </div>
      </div>

      <label class="remember">
        <input type="checkbox" v-model="remember" />
        <span>Ghi nhớ đăng nhập</span>
      </label>

      <p v-if="error" class="error">{{ error }}</p>

      <button class="submit" :disabled="loading" @click="login">
        {{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
      </button>

      <p class="switch">
        Chưa có tài khoản?
        <RouterLink to="/register">Đăng ký ngay</RouterLink>
      </p>

    </div>
  </div>
</template>

<style scoped>
.auth-wrap {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e7f0ff 0%, #f0f2f5 100%);
    padding: 20px;
}

.auth-card {
    width: 100%;
    max-width: 390px;
    background: #fff;
    border-radius: 22px;
    padding: 36px 32px;
    box-shadow: var(--shadow);
}

.auth-head {
    text-align: center;
    margin-bottom: 24px;
}

.auth-icon {
    font-size: 44px;
    margin-bottom: 8px;
}

.auth-head h1 {
    margin: 0 0 6px;
    font-size: 23px;
}

.auth-head p {
    margin: 0;
    color: var(--text-muted);
    font-size: 14px;
}

.field {
    margin-bottom: 16px;
}

.field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.field input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 15px;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.field input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-soft);
}

.password-box {
    position: relative;
}

.password-box input {
    padding-right: 46px;
}

.toggle {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 16px;
    padding: 6px;
}

.remember {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 18px;
    cursor: pointer;
    user-select: none;
}

.remember input {
    width: 16px;
    height: 16px;
    accent-color: var(--primary);
    cursor: pointer;
}

.error {
    color: var(--danger);
    font-size: 13px;
    margin: 0 0 14px;
    text-align: center;
}

.submit {
    width: 100%;
    padding: 13px;
    border: none;
    background: var(--primary);
    color: #fff;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s;
}

.submit:hover:not(:disabled) {
    background: var(--primary-dark);
}

.submit:disabled {
    opacity: 0.6;
    cursor: default;
}

.switch {
    text-align: center;
    font-size: 14px;
    color: var(--text-muted);
    margin: 18px 0 0;
}

.switch a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}
</style>
