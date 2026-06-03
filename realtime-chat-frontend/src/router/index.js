import { createRouter, createWebHistory } from 'vue-router'

import MainLayout from '../layouts/MainLayout.vue'

import RegisterPage from '../pages/RegisterPage.vue'
import LoginPage from '../pages/LoginPage.vue'
import ChatPage from '../pages/ChatPage.vue'
const router = createRouter({

    history: createWebHistory(),

    routes: [

        {
            path: '/',

            component: MainLayout,

            children: [

                {
                    path: 'register',
                    component: RegisterPage,
                },

                {
                    path: 'login',
                    component: LoginPage,
                },
            ],
        },
        {
            path: '/chat',
            component: ChatPage,
        },
    ],
})

export default router