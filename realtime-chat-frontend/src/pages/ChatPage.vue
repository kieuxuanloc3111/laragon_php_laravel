<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import api from '../services/api'
import { getUser } from '../services/auth'
import echo from '../services/echo'

const me = getUser()

const users = ref([])
const groups = ref([])

const activeTab = ref('direct') // 'direct' | 'group'
const search = ref('')

// Hội thoại đang mở: { type, id, name, avatar_url, subtitle }
const conversation = ref(null)

const messages = ref([])
const text = ref('')

// Số tin chưa đọc theo từng hội thoại, key dạng 'direct-3' / 'group-7'
const unread = ref({})

// Modal tạo nhóm
const showCreateGroup = ref(false)
const newGroupName = ref('')
const selectedMembers = ref([])

const bodyRef = ref(null)

const subscribedChannels = []

const convKey = (type, id) => `${type}-${id}`

const initial = (name) => (name || '?').charAt(0).toUpperCase()

const formatTime = (iso) => {
    if (!iso) return ''
    const d = new Date(iso)
    return d.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
}

// ----- Lọc theo ô tìm kiếm -----

const filteredUsers = computed(() =>
    users.value.filter(u =>
        u.name.toLowerCase().includes(search.value.toLowerCase())
    )
)

const filteredGroups = computed(() =>
    groups.value.filter(g =>
        g.name.toLowerCase().includes(search.value.toLowerCase())
    )
)

// ----- Tải dữ liệu -----

const loadUsers = async () => {
    const { data } = await api.get('/users')
    users.value = data
}

const loadGroups = async () => {
    const { data } = await api.get('/groups')
    groups.value = data
    data.forEach(subscribeGroup)
}

const normalize = (raw) => ({
    id: raw.id,
    sender_id: raw.sender_id,
    message: raw.message,
    avatar_url: raw.avatar_url ?? raw.sender?.avatar_url,
    sender_name: raw.sender_name ?? raw.sender?.name,
    created_at: raw.created_at ?? new Date().toISOString(),
})

const loadMessages = async () => {
    if (!conversation.value) return

    const url = conversation.value.type === 'direct'
        ? `/messages/${conversation.value.id}`
        : `/groups/${conversation.value.id}/messages`

    const { data } = await api.get(url)
    messages.value = data.map(normalize)
    scrollToBottom()
}

// ----- Mở hội thoại -----

const openConversation = (type, id, name, avatar_url, subtitle) => {
    conversation.value = { type, id, name, avatar_url, subtitle }
    delete unread.value[convKey(type, id)]
    loadMessages()
}

const openDirect = (u) =>
    openConversation('direct', u.id, u.name, u.avatar_url, u.email)

const openGroup = (g) =>
    openConversation(
        'group', g.id, g.name, g.avatar_url,
        `${g.members_count ?? g.members?.length ?? ''} thành viên`
    )

const isActive = (type, id) =>
    conversation.value?.type === type && conversation.value?.id === id

// ----- Gửi tin -----

const sendMessage = async () => {
    if (!text.value.trim() || !conversation.value) return

    const body = { message: text.value }
    let url

    if (conversation.value.type === 'direct') {
        url = '/messages'
        body.receiver_id = conversation.value.id
    } else {
        url = `/groups/${conversation.value.id}/messages`
    }

    try {
        const { data } = await api.post(url, body)
        appendMessage(normalize(data))
        text.value = ''
    } catch (error) {
        console.log(error)
    }
}

// Thêm tin vào khung hiện tại, chống trùng theo id.
const appendMessage = (msg) => {
    if (msg.id && messages.value.some(m => m.id === msg.id)) return
    messages.value.push(msg)
    scrollToBottom()
}

// ----- Tạo nhóm -----

const toggleMember = (id) => {
    const i = selectedMembers.value.indexOf(id)
    if (i === -1) selectedMembers.value.push(id)
    else selectedMembers.value.splice(i, 1)
}

const openCreateGroup = () => {
    newGroupName.value = ''
    selectedMembers.value = []
    showCreateGroup.value = true
}

const createGroup = async () => {
    if (!newGroupName.value.trim()) return

    try {
        const { data } = await api.post('/groups', {
            name: newGroupName.value,
            member_ids: selectedMembers.value,
        })

        groups.value.unshift(data)
        subscribeGroup(data)

        showCreateGroup.value = false
        activeTab.value = 'group'
        openGroup(data)
    } catch (error) {
        console.log(error)
    }
}

// ----- Realtime -----

const subscribeGroup = (g) => {
    const name = `group.${g.id}`
    if (subscribedChannels.includes(name)) return
    subscribedChannels.push(name)

    echo.private(name).listen('.group.message.sent', (event) => {
        handleIncoming('group', event.message.group_id, normalize(event.message))
    })
}

const handleIncoming = (type, id, msg) => {
    if (isActive(type, id)) {
        appendMessage(msg)
    } else if (msg.sender_id !== me.id) {
        const key = convKey(type, id)
        unread.value[key] = (unread.value[key] || 0) + 1
    }
}

const scrollToBottom = () => {
    nextTick(() => {
        if (bodyRef.value) {
            bodyRef.value.scrollTop = bodyRef.value.scrollHeight
        }
    })
}

const displayName = (msg) => {
    if (msg.sender_id === me.id) return 'Bạn'
    return msg.sender_name
        || users.value.find(u => u.id === msg.sender_id)?.name
        || 'Người dùng'
}

onMounted(() => {
    loadUsers()
    loadGroups()

    // Kênh riêng của mình -> nhận tin nhắn 1-1 gửi đến.
    echo.private(`chat.${me.id}`).listen('.message.sent', (event) => {
        handleIncoming('direct', event.message.sender_id, normalize(event.message))
    })
})

onUnmounted(() => {
    echo.leave(`chat.${me.id}`)
    subscribedChannels.forEach(name => echo.leave(name))
})
</script>

<template>
<div class="chat-layout">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="sidebar-top">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input v-model="search" placeholder="Tìm kiếm..." />
            </div>
        </div>

        <div class="tabs">
            <button
                :class="{ active: activeTab === 'direct' }"
                @click="activeTab = 'direct'"
            >Trực tiếp</button>
            <button
                :class="{ active: activeTab === 'group' }"
                @click="activeTab = 'group'"
            >Nhóm</button>
        </div>

        <!-- Danh sách user -->
        <div v-if="activeTab === 'direct'" class="conv-list">
            <div
                v-for="u in filteredUsers"
                :key="u.id"
                class="conv-item"
                :class="{ active: isActive('direct', u.id) }"
                @click="openDirect(u)"
            >
                <img :src="u.avatar_url" class="conv-avatar" alt="">
                <div class="conv-info">
                    <span class="conv-name">{{ u.name }}</span>
                    <span class="conv-sub">{{ u.email }}</span>
                </div>
                <span v-if="unread[`direct-${u.id}`]" class="badge">
                    {{ unread[`direct-${u.id}`] }}
                </span>
            </div>
            <p v-if="!filteredUsers.length" class="empty">Không có người dùng</p>
        </div>

        <!-- Danh sách nhóm -->
        <div v-else class="conv-list">
            <button class="create-group" @click="openCreateGroup">
                <span class="plus">＋</span> Tạo nhóm mới
            </button>
            <div
                v-for="g in filteredGroups"
                :key="g.id"
                class="conv-item"
                :class="{ active: isActive('group', g.id) }"
                @click="openGroup(g)"
            >
                <div class="group-avatar">{{ initial(g.name) }}</div>
                <div class="conv-info">
                    <span class="conv-name">{{ g.name }}</span>
                    <span class="conv-sub">
                        {{ g.members_count ?? g.members?.length ?? '' }} thành viên
                    </span>
                </div>
                <span v-if="unread[`group-${g.id}`]" class="badge">
                    {{ unread[`group-${g.id}`] }}
                </span>
            </div>
            <p v-if="!filteredGroups.length" class="empty">Chưa có nhóm nào</p>
        </div>
    </aside>

    <!-- Khung chat -->
    <section class="chat-main">

        <template v-if="conversation">
            <header class="chat-header">
                <img
                    v-if="conversation.type === 'direct'"
                    :src="conversation.avatar_url"
                    class="header-avatar"
                    alt=""
                >
                <div v-else class="group-avatar header-group">
                    {{ initial(conversation.name) }}
                </div>
                <div class="header-info">
                    <span class="header-name">{{ conversation.name }}</span>
                    <span class="header-sub">{{ conversation.subtitle }}</span>
                </div>
            </header>

            <div class="chat-body" ref="bodyRef">
                <div
                    v-for="(message, index) in messages"
                    :key="message.id ?? index"
                    class="msg"
                    :class="{ out: message.sender_id === me.id }"
                >
                    <img
                        v-if="message.sender_id !== me.id"
                        :src="message.avatar_url"
                        class="msg-avatar"
                        alt=""
                    >
                    <div class="msg-content">
                        <span
                            v-if="conversation.type === 'group' && message.sender_id !== me.id"
                            class="msg-sender"
                        >{{ displayName(message) }}</span>
                        <div class="bubble">{{ message.message }}</div>
                        <span class="msg-time">{{ formatTime(message.created_at) }}</span>
                    </div>
                </div>

                <p v-if="!messages.length" class="no-msg">
                    Chưa có tin nhắn. Hãy bắt đầu cuộc trò chuyện 👋
                </p>
            </div>

            <footer class="chat-input">
                <input
                    v-model="text"
                    @keyup.enter="sendMessage"
                    placeholder="Nhập tin nhắn..."
                >
                <button class="send-btn" @click="sendMessage" :disabled="!text.trim()">
                    ➤
                </button>
            </footer>
        </template>

        <div v-else class="placeholder">
            <div class="placeholder-icon">💬</div>
            <h3>Chào {{ me?.name }}!</h3>
            <p>Chọn một người hoặc nhóm bên trái để bắt đầu trò chuyện.</p>
        </div>
    </section>

    <!-- Modal tạo nhóm -->
    <div v-if="showCreateGroup" class="modal-overlay" @click.self="showCreateGroup = false">
        <div class="modal">
            <h3>Tạo nhóm mới</h3>

            <input
                v-model="newGroupName"
                placeholder="Tên nhóm"
                class="modal-input"
            >

            <p class="modal-label">Chọn thành viên</p>
            <div class="member-list">
                <label
                    v-for="u in users"
                    :key="u.id"
                    class="member-item"
                    :class="{ checked: selectedMembers.includes(u.id) }"
                >
                    <input
                        type="checkbox"
                        :checked="selectedMembers.includes(u.id)"
                        @change="toggleMember(u.id)"
                    >
                    <img :src="u.avatar_url" class="conv-avatar small" alt="">
                    <span>{{ u.name }}</span>
                </label>
            </div>

            <div class="modal-actions">
                <button class="ghost" @click="showCreateGroup = false">Hủy</button>
                <button class="primary" @click="createGroup">Tạo nhóm</button>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
.chat-layout {
    display: flex;
    height: 100%;
    background: var(--bg);
}

/* ===== Sidebar ===== */
.sidebar {
    width: 320px;
    flex-shrink: 0;
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    background: var(--bg-sidebar);
}

.sidebar-top {
    padding: 14px 14px 8px;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 30px;
    padding: 9px 14px;
}

.search-icon {
    font-size: 14px;
    opacity: 0.6;
}

.search-box input {
    border: none;
    background: transparent;
    flex: 1;
    font-size: 14px;
}

.tabs {
    display: flex;
    gap: 8px;
    padding: 4px 14px 10px;
}

.tabs button {
    flex: 1;
    padding: 9px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-muted);
    border-radius: 30px;
    transition: all 0.15s;
}

.tabs button.active {
    background: var(--primary);
    color: #fff;
}

.conv-list {
    flex: 1;
    overflow-y: auto;
    padding: 6px 8px;
}

.conv-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.12s;
}

.conv-item:hover {
    background: rgba(0, 0, 0, 0.04);
}

.conv-item.active {
    background: var(--primary-soft);
}

.conv-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    background: var(--border);
    flex-shrink: 0;
}

.conv-avatar.small {
    width: 36px;
    height: 36px;
}

.group-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #00c6ff);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
}

.conv-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.conv-name {
    font-weight: 600;
    font-size: 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conv-sub {
    font-size: 13px;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge {
    background: var(--danger);
    color: #fff;
    border-radius: 12px;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty {
    color: var(--text-muted);
    text-align: center;
    font-size: 13px;
    margin-top: 24px;
}

.create-group {
    width: 100%;
    padding: 11px;
    margin-bottom: 6px;
    border: none;
    background: var(--bg);
    color: var(--primary);
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: inset 0 0 0 1px var(--border);
}

.create-group:hover {
    background: var(--primary-soft);
}

.plus {
    font-size: 18px;
}

/* ===== Chat main ===== */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.chat-header {
    height: 70px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 20px;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
}

.header-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    background: var(--border);
}

.header-group {
    width: 44px;
    height: 44px;
    font-size: 17px;
}

.header-info {
    display: flex;
    flex-direction: column;
}

.header-name {
    font-weight: 700;
    font-size: 16px;
}

.header-sub {
    font-size: 13px;
    color: var(--text-muted);
}

.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px 20px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    background: var(--bg-chat);
    background-image: radial-gradient(rgba(0, 0, 0, 0.025) 1px, transparent 1px);
    background-size: 22px 22px;
}

.msg {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    max-width: 70%;
    margin-bottom: 6px;
}

.msg.out {
    flex-direction: row-reverse;
    align-self: flex-end;
}

.msg-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    background: var(--border);
    flex-shrink: 0;
}

.msg-content {
    display: flex;
    flex-direction: column;
}

.msg.out .msg-content {
    align-items: flex-end;
}

.msg-sender {
    font-size: 12px;
    font-weight: 600;
    color: var(--primary);
    margin: 0 0 2px 4px;
}

.bubble {
    padding: 10px 14px;
    border-radius: 18px;
    background: var(--bubble-in);
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.06);
    word-break: break-word;
    line-height: 1.4;
    font-size: 15px;
}

.msg:not(.out) .bubble {
    border-bottom-left-radius: 4px;
}

.msg.out .bubble {
    background: var(--bubble-out);
    color: #fff;
    border-bottom-right-radius: 4px;
}

.msg-time {
    font-size: 11px;
    color: var(--text-muted);
    margin: 3px 6px 0;
}

.no-msg {
    margin: auto;
    color: var(--text-muted);
    font-size: 14px;
}

.chat-input {
    flex-shrink: 0;
    display: flex;
    gap: 10px;
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    background: var(--bg);
}

.chat-input input {
    flex: 1;
    padding: 12px 18px;
    border-radius: 24px;
    border: none;
    background: var(--bg-chat);
    font-size: 15px;
}

.send-btn {
    width: 46px;
    height: 46px;
    border: none;
    background: var(--primary);
    color: #fff;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.15s, transform 0.1s;
}

.send-btn:hover:not(:disabled) {
    background: var(--primary-dark);
}

.send-btn:active:not(:disabled) {
    transform: scale(0.92);
}

.send-btn:disabled {
    opacity: 0.45;
    cursor: default;
}

.placeholder {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    text-align: center;
    padding: 20px;
}

.placeholder-icon {
    font-size: 56px;
    margin-bottom: 10px;
}

.placeholder h3 {
    margin: 0 0 6px;
    color: var(--text);
}

.placeholder p {
    margin: 0;
    max-width: 280px;
}

/* ===== Modal ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
}

.modal {
    background: var(--bg);
    padding: 24px;
    border-radius: 18px;
    width: 380px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow);
}

.modal h3 {
    margin: 0 0 16px;
}

.modal-input {
    padding: 12px 14px;
    border: 1px solid var(--border);
    border-radius: 10px;
    margin-bottom: 16px;
    font-size: 15px;
}

.modal-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-soft);
}

.modal-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin: 0 0 8px;
}

.member-list {
    flex: 1;
    overflow-y: auto;
    max-height: 280px;
    margin: 0 -6px;
    padding: 0 6px;
}

.member-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
}

.member-item:hover {
    background: rgba(0, 0, 0, 0.04);
}

.member-item.checked {
    background: var(--primary-soft);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

.modal-actions button {
    padding: 11px 22px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
}

.modal-actions .primary {
    background: var(--primary);
    color: #fff;
}

.modal-actions .ghost {
    background: var(--bg-sidebar);
    color: var(--text);
}
</style>
