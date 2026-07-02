import { createRouter, createWebHistory } from 'vue-router'

import MainLayout from '../layouts/MainLayout.vue'

import RegisterPage from '../pages/RegisterPage.vue'
import LoginPage from '../pages/LoginPage.vue'
import ChatPage from '../pages/ChatPage.vue'
import { isLoggedIn } from '../services/auth'

const router = createRouter({

    history: createWebHistory(),

    routes: [

        {
            path: '/',

            component: MainLayout,

            children: [

                {
                    path: '',
                    redirect: '/chat',
                },

                {
                    path: 'register',
                    component: RegisterPage,
                    meta: { guestOnly: true },
                },

                {
                    path: 'login',
                    component: LoginPage,
                    meta: { guestOnly: true },
                },

                {
                    path: 'chat',
                    component: ChatPage,
                    meta: { requiresAuth: true },
                },
            ],
        },
    ],
})

// Chặn vào trang cần đăng nhập khi chưa login,
// và chặn vào login/register khi đã đăng nhập.
router.beforeEach((to) => {

    const loggedIn = isLoggedIn()

    if (to.meta.requiresAuth && !loggedIn) {
        return '/login'
    }

    if (to.meta.guestOnly && loggedIn) {
        return '/chat'
    }
})

export default router
