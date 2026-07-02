<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { setAuth } from '../services/auth'

const router = useRouter()

const name = ref('')
const email = ref('')
const password = ref('')
const avatar = ref(null)
const preview = ref('')
const loading = ref(false)
const error = ref('')

const handleFile = (event) => {
    const file = event.target.files[0]
    if (!file) return
    avatar.value = file
    preview.value = URL.createObjectURL(file)
}

const register = async () => {
    error.value = ''
    loading.value = true

    try {
        const formData = new FormData()
        formData.append('name', name.value)
        formData.append('email', email.value)
        formData.append('password', password.value)
        formData.append('avatar', avatar.value)

        const { data } = await api.post('/register', formData)

        // Đăng ký xong đăng nhập luôn, vào thẳng trang chat.
        setAuth(data.token, data.user)
        router.push('/chat')

    } catch (e) {
        error.value = e.response?.data?.message
            || 'Đăng ký thất bại, vui lòng kiểm tra lại thông tin'
    } finally {
        loading.value = false
    }
}
</script>

<template>
  <div class="auth-wrap">
    <div class="auth-card">

      <div class="auth-head">
        <div class="auth-icon">✨</div>
        <h1>Tạo tài khoản</h1>
        <p>Tham gia trò chuyện cùng mọi người</p>
      </div>

      <div class="avatar-upload">
        <label class="avatar-circle">
          <img v-if="preview" :src="preview" alt="">
          <span v-else class="avatar-placeholder">＋</span>
          <input type="file" accept="image/*" @change="handleFile" hidden />
        </label>
        <span class="avatar-hint">Chọn ảnh đại diện</span>
      </div>

      <div class="field">
        <label>Tên hiển thị</label>
        <input v-model="name" placeholder="Nguyễn Văn A" />
      </div>

      <div class="field">
        <label>Email</label>
        <input v-model="email" type="email" placeholder="you@example.com" />
      </div>

      <div class="field">
        <label>Mật khẩu</label>
        <input v-model="password" type="password" placeholder="Tối thiểu 6 ký tự" />
      </div>

      <p v-if="error" class="error">{{ error }}</p>

      <button class="submit" :disabled="loading" @click="register">
        {{ loading ? 'Đang tạo...' : 'Đăng ký' }}
      </button>

      <p class="switch">
        Đã có tài khoản?
        <RouterLink to="/login">Đăng nhập</RouterLink>
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
    overflow-y: auto;
}

.auth-card {
    width: 100%;
    max-width: 380px;
    background: #fff;
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow);
    margin: auto;
}

.auth-head {
    text-align: center;
    margin-bottom: 20px;
}

.auth-icon {
    font-size: 38px;
    margin-bottom: 8px;
}

.auth-head h1 {
    margin: 0 0 6px;
    font-size: 22px;
}

.auth-head p {
    margin: 0;
    color: var(--text-muted);
    font-size: 14px;
}

.avatar-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}

.avatar-circle {
    width: 84px;
    height: 84px;
    border-radius: 50%;
    background: var(--bg-sidebar);
    border: 2px dashed var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
}

.avatar-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    font-size: 30px;
    color: var(--primary);
}

.avatar-hint {
    font-size: 13px;
    color: var(--text-muted);
}

.field {
    margin-bottom: 14px;
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
