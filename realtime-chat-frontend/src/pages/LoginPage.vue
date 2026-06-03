<script setup>
import { ref } from 'vue'
import api from '../services/api'
import { setAuth } from '../services/auth'
const email = ref('')
const password = ref('')

const login = async () => {

    try {

        const response = await api.post('/login', {
            email: email.value,
            password: password.value,
        })

        setAuth(
            response.data.token,
            response.data.user
        )
        window.location.href = '/'

        console.log(response.data)

        alert('Login success')

    } catch (error) {

        console.log(error)
    }
}
</script>

<template>

  <div class="container">

    <h1>Login</h1>

    <input
      v-model="email"
      placeholder="Email"
    />

    <input
      v-model="password"
      type="password"
      placeholder="Password"
    />

    <button @click="login">
      Login
    </button>

  </div>

</template>

<style scoped>

.container{
    width:300px;

    display:flex;
    flex-direction:column;
    gap:15px;
}

</style>