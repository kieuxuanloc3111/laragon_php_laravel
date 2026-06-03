<script setup>
import { ref, onMounted } from 'vue'

import axios from 'axios'

import echo from '../services/echo'

const user = JSON.parse(
    localStorage.getItem('user')
)

const token =
    localStorage.getItem('token')

const messages = ref([])

const text = ref('')

const receiverId =
    user.id === 1 ? 2 : 1

onMounted(() => {

    const channel = echo.private(
        `chat.${user.id}`
    )

    channel.listen(
        '.message.sent',
        (event) => {
            console.log(
            'EVENT FULL:',
            event
            )

            messages.value.push({

    
            sender_id:
                event.message.sender_id,

            message:
                event.message.message,

            avatar_url:
            event.message.avatar_url,

  

            })


        }
    )
})

const sendMessage = async () => {

    if (!text.value.trim()) {
        return
    }

    try {

        await axios.post(
            'http://realtime-chat-api.test/api/messages',
            {

                receiver_id: receiverId,

                message: text.value,
            },
            {

                headers: {

                    Authorization:
                        `Bearer ${token}`,
                }
            }
        )

        messages.value.push({


        sender_id: user.id,

        message: text.value,

        avatar_url: user.avatar_url,

        })


        text.value = ''

    } catch (error) {

        console.log(error)
    }
}
</script>

<template>

<div class="chat-page">


<div class="chat-header">

    <h2>

        Chat User {{ receiverId }}

    </h2>

</div>

<div class="chat-body">

<div
    v-for="(message, index) in messages"
    :key="index"
    class="message-row"
    :class="{
        mine:
        message.sender_id === user.id
    }"
>


    <img
    :src="message.avatar_url"
    class="message-avatar"
    alt=""

    >



    <div class="bubble-wrapper">

        <span class="message-name">

            {{
                message.sender_id === user.id
                ? 'You'
                : 'Friend'
            }}

        </span>

        <div class="bubble">

            {{ message.message }}
    
        </div>

    </div>


    </div>


</div>

<div class="chat-input">

    <input
        v-model="text"
        @keyup.enter="sendMessage"
        placeholder="Type message..."
    >

    <button @click="sendMessage">

        Send

    </button>

</div>


</div>

</template>

<style scoped>

.chat-page {

    height: 100vh;

    display: flex;

    flex-direction: column;

    background: #f0f2f5;
}

.chat-header {

    padding: 15px;

    background: white;

    border-bottom: 1px solid #ddd;
}

.chat-body {

    flex: 1;

    overflow-y: auto;

    padding: 20px;

    display: flex;

    flex-direction: column;

    gap: 10px;
}

.message-row {

    display: flex;

    justify-content: flex-start;
}

.message-row.mine {

    justify-content: flex-end;
}

.bubble {

    max-width: 300px;

    padding: 12px;

    border-radius: 15px;

    background: white;
}

.message-row.mine .bubble {

    background: #0084ff;

    color: white;
}

.chat-input {

    display: flex;

    gap: 10px;

    padding: 15px;

    background: white;
}

.chat-input input {

    flex: 1;

    padding: 12px;

    border-radius: 10px;

    border: 1px solid #ccc;
}

.chat-input button {

    padding: 12px 20px;

    border: none;

    background: #0084ff;

    color: white;

    border-radius: 10px;

    cursor: pointer;
}
.message-avatar {


width: 40px;

min-width: 40px;

height: 40px;

min-height: 40px;

border-radius: 50%;

object-fit: cover;

margin-right: 8px;

background: #ddd;


}

.bubble-wrapper {


display: flex;

flex-direction: column;


}

.message-name {


font-size: 12px;

margin-bottom: 4px;

color: #666;


}

.message-row.mine .bubble-wrapper {


align-items: flex-end;


}

.message-row.mine .message-name {


color: #0084ff;


}

</style>
