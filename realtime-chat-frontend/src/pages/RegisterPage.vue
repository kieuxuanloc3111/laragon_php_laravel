<script setup>
import { ref } from 'vue'
import api from '../services/api'

const name = ref('')
const email = ref('')
const password = ref('')

const avatar = ref(null)

const preview = ref('')

const handleFile = (event) => {

    avatar.value = event.target.files[0]

    preview.value = URL.createObjectURL(
        event.target.files[0]
    )
}

const register = async () => {

    try {

        const formData = new FormData()

        formData.append('name', name.value)
        formData.append('email', email.value)
        formData.append('password', password.value)
        formData.append('avatar', avatar.value)

        const response = await api.post(
            '/register',
            formData
        )

        console.log(response.data)

        alert('Register success')

    } catch (error) {

        console.log(error)
    }
}
</script>

<template>

  <div class="container">

    <h1>Register</h1>

    <input
      v-model="name"
      placeholder="Name"
    />

    <input
      v-model="email"
      placeholder="Email"
    />

    <input
      v-model="password"
      type="password"
      placeholder="Password"
    />

    <input
      type="file"
      @change="handleFile"
    />

    <img
      v-if="preview"
      :src="preview"
      class="preview"
    />

    <button @click="register">
      Register
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

.preview{
    width:100px;
    height:100px;

    object-fit:cover;
    border-radius:50%;
}

</style>