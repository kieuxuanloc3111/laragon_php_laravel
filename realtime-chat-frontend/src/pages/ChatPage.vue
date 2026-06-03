<script setup>
import { onMounted } from 'vue'

import echo from '../services/echo'

onMounted(() => {

    const user = JSON.parse(
        localStorage.getItem('user')
    )

    console.log('USER:', user)

    const channel = echo.private(
        `chat.${user.id}`
    )

    console.log('CHANNEL:', channel)

    channel.listen('.chat-message', (event) => {

        console.log(
            'EVENT RECEIVED:',
            event
        )

        alert(event.message)
    })

    channel.listenToAll((event, data) => {

        console.log(
            'ALL EVENTS:',
            event,
            data
        )
    })
})
</script>

<template>

<div>


<h1>Chat Page</h1>


</div>

</template>
