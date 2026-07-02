<script setup>
import { RouterLink, useRouter } from 'vue-router'
import { onMounted } from 'vue'
import {
    currentUser as user,
    logout, fetchUser
} from '../services/auth'

const router = useRouter()

const handleLogout = () => {
    logout()
    router.push('/login')
}

onMounted(fetchUser)
</script>

<template>
  <div class="shell">

    <header class="topbar">

      <RouterLink to="/" class="brand">
        <span class="brand-logo">💬</span>
        <span class="brand-name">ChatRealtime</span>
      </RouterLink>

      <div class="topbar-right">

        <template v-if="user">
          <div class="user-chip">
            <img :src="user.avatar_url" class="user-avatar" alt="">
            <span class="user-name">{{ user.name }}</span>
          </div>
          <button class="logout-btn" @click="handleLogout">
            Đăng xuất
          </button>
        </template>

        <template v-else>
          <RouterLink to="/login" class="nav-link">Đăng nhập</RouterLink>
          <RouterLink to="/register" class="nav-link primary">Đăng ký</RouterLink>
        </template>

      </div>
    </header>

    <main class="content">
      <router-view />
    </main>

  </div>
</template>

<style scoped>
.shell {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.topbar {
    height: 60px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
    z-index: 10;
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: var(--text);
}

.brand-logo {
    font-size: 24px;
}

.brand-name {
    font-weight: 800;
    font-size: 18px;
    background: linear-gradient(90deg, var(--primary), #00c6ff);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 12px 4px 4px;
    background: var(--bg-sidebar);
    border-radius: 30px;
}

.user-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    object-fit: cover;
    background: var(--border);
}

.user-name {
    font-weight: 600;
    font-size: 14px;
}

.logout-btn {
    padding: 8px 16px;
    border: none;
    background: var(--bg-sidebar);
    color: var(--text);
    border-radius: 30px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: background 0.15s;
}

.logout-btn:hover {
    background: #e4e6eb;
}

.nav-link {
    text-decoration: none;
    color: var(--text);
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 30px;
}

.nav-link.primary {
    background: var(--primary);
    color: #fff;
}

.content {
    flex: 1;
    min-height: 0;
    overflow: hidden;
}
</style>
