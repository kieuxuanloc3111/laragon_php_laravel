<script setup>
import { RouterLink } from 'vue-router'
import { onMounted, ref } from 'vue'
import {
    getUser,
    logout,fetchUser
} from '../services/auth'

const user = ref(getUser())

const handleLogout = () => {

    logout()

    window.location.reload()
}
onMounted(async () => {

    user.value = await fetchUser()
})
</script>

<template>

  <div>

    <header class="header">

      <template v-if="user">

        <img
          :src="user.avatar_url"
          class="avatar"
        >

        <span>
          {{ user.name }}
        </span>

        <button @click="handleLogout">
          Logout
        </button>

      </template>

      <template v-else>

        <RouterLink to="/register">
          Register
        </RouterLink>

        <RouterLink to="/login">
          Login
        </RouterLink>

      </template>

    </header>

    <main class="main">
      <router-view />
    </main>

  </div>

</template>

<style scoped>

.header{
    padding:20px;

    border-bottom:1px solid #ccc;

    display:flex;
    align-items:center;
    gap:15px;
}

.main{
    padding:20px;
}

.avatar{
    width:50px;
    height:50px;

    border-radius:50%;

    object-fit:cover;
}

</style>