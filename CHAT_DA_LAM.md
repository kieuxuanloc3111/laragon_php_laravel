# Realtime Chat — Những gì đã làm được

Tài liệu mô tả hiện trạng tính năng chat của hai dự án:
- **Backend:** `realtime-chat-api` (Laravel 12)
- **Frontend:** `realtime-chat-frontend` (Vue 3 + Vite)

> Cập nhật gần nhất: bổ sung redesign giao diện (Messenger/Telegram), route guard,
> auth reactive, ghi nhớ đăng nhập, auto-login sau đăng ký, timestamp tin nhắn, seed user demo.

---

## 1. Tổng quan kiến trúc

```
[Vue 3 Frontend] --- REST API (axios) ---> [Laravel 12 API]
        |                                          |
        |--- WebSocket (Laravel Echo) ---> [Pusher] <--- broadcast events
```

- Xác thực: **Laravel Sanctum** (token Bearer lưu trong `localStorage`).
- Realtime: **Pusher** + **Laravel Echo**, dùng private channel.
- Tin nhắn broadcast bằng event `ShouldBroadcastNow` (gửi đồng bộ, không cần queue).

---

## 2. Backend (`realtime-chat-api`)

### 2.1. Cơ sở dữ liệu

| Bảng | Cột chính | Ghi chú |
|------|-----------|---------|
| `users` | id, name, email, password, avatar | có accessor `avatar_url` |
| `messages` | id, sender_id, **receiver_id (nullable)**, **group_id (nullable)**, message | tin 1-1 thì có `receiver_id`, tin nhóm thì có `group_id` |
| `groups` | id, name, avatar, created_by | nhóm chat |
| `group_user` | group_id, user_id | bảng pivot thành viên (unique theo cặp) |

> Migration thêm group chat: `2026_06_18_100000_create_groups_table`,
> `2026_06_18_100001_create_group_user_table`,
> `2026_06_18_100002_add_group_id_to_messages_table`.

### 2.2. Models

- **User** (`app/Models/User.php`): accessor `avatar_url`, quan hệ `groups()` (belongsToMany).
- **Message** (`app/Models/Message.php`): fillable thêm `group_id`; quan hệ `sender()`, `receiver()`, `group()`.
- **Group** (`app/Models/Group.php`): quan hệ `members()`, `creator()`, `messages()`; accessor `avatar_url`.

### 2.3. API Endpoints

| Method | Route | Auth | Mô tả |
|--------|-------|------|-------|
| POST | `/api/register` | ❌ | Đăng ký (upload avatar bắt buộc) → **trả về token** để đăng nhập ngay |
| POST | `/api/login` | ❌ | Đăng nhập → trả về token Sanctum |
| GET | `/api/user` | ✅ | Thông tin user hiện tại |
| GET | `/api/users` | ✅ | Danh sách user khác (để chọn chat / thêm vào nhóm) |
| POST | `/api/pusher/auth` | ✅ | Phân quyền subscribe private channel |
| POST | `/api/messages` | ✅ | Gửi tin nhắn 1-1 → broadcast realtime |
| GET | `/api/messages/{userId}` | ✅ | Lịch sử chat 1-1 (2 chiều) |
| GET | `/api/groups` | ✅ | Danh sách nhóm mình tham gia |
| POST | `/api/groups` | ✅ | Tạo nhóm (người tạo tự thành thành viên) |
| GET | `/api/groups/{group}/messages` | ✅ | Lịch sử tin nhắn nhóm (chỉ thành viên) |
| POST | `/api/groups/{group}/messages` | ✅ | Gửi tin nhắn nhóm → broadcast cho cả nhóm |

> `GroupController` chặn người không phải thành viên truy cập nhóm (HTTP 403).

### 2.4. Realtime / Broadcasting

- **Event chat 1-1:** `MessageSent` → kênh `private-chat.{receiverId}`, tên sự kiện `.message.sent`.
- **Event chat nhóm:** `GroupMessageSent` → kênh `private-group.{groupId}`, tên sự kiện `.group.message.sent`.
- Cả hai event đều gửi kèm **`created_at`** để client hiển thị mốc thời gian cho tin realtime.
- **Phân quyền kênh** (`POST /api/pusher/auth`):
  - `private-chat.{id}` → chỉ đúng chủ user `id` được nghe.
  - `private-group.{id}` → chỉ thành viên của nhóm `id` được nghe.

### 2.5. CORS (đã siết)

- `config/cors.php` đọc danh sách origin từ biến môi trường `CORS_ALLOWED_ORIGINS`
  (mặc định `http://localhost:5173,http://127.0.0.1:5173`).
- Không còn để `allowed_origins = ['*']`. Lên production chỉ cần đổi env.

### 2.6. Seed dữ liệu demo

- `database/seeders/UserSeeder.php` tạo **8 user demo**, dùng `updateOrCreate` theo email
  (chạy lại không bị trùng). Avatar lấy từ `public/storage/image`.
- **Mọi user demo dùng chung mật khẩu: `123456`.**
- Email: `an@`, `binh@`, `chi@`, `dung@`, `hoa@`, `khanh@`, `lan@`, `minh@` `example.com`.
- Chạy: `php artisan db:seed --class=UserSeeder`.

---

## 3. Frontend (`realtime-chat-frontend`)

### 3.1. Routing & Route Guard (`src/router/index.js`)

- `/register`, `/login`, `/chat` đều là route con của `MainLayout`. `/` redirect sang `/chat`.
- **Route guard (`router.beforeEach`):**
  - Chưa đăng nhập mà vào `/chat` (`meta.requiresAuth`) → tự chuyển về `/login`.
  - Đã đăng nhập mà vào `/login` / `/register` (`meta.guestOnly`) → tự chuyển về `/chat`.
  - → Khắc phục lỗi crash trước đây khi vào `/chat` lúc chưa đăng nhập.

### 3.2. Services

- **`api.js`**: axios instance (`baseURL` = API) + **interceptor** tự đính kèm
  `Authorization: Bearer <token>` vào mọi request.
- **`auth.js`**:
  - `currentUser` — **state đăng nhập dạng reactive (ref) dùng chung** → header/trang tự
    cập nhật ngay sau login/logout, **không cần reload trang**.
  - `getToken`, `getUser`, `isLoggedIn`, `setAuth`, `logout`, `fetchUser`.
  - Phiên đăng nhập lưu `localStorage` → mở lại trình duyệt vẫn còn đăng nhập.
- **`echo.js`**: cấu hình Laravel Echo + Pusher, authorizer gọi `/api/pusher/auth`.

### 3.3. Giao diện (redesign theo Messenger / Telegram)

- **`src/style.css`** (global): bảng màu theme (biến CSS), reset, font hệ thống,
  thanh cuộn mảnh. Được import trong `main.js`.
- **Trang Đăng nhập / Đăng ký** (`LoginPage.vue`, `RegisterPage.vue`):
  - Card bo tròn, nền gradient, ô nhập có focus ring.
  - Login: **ghi nhớ email** (checkbox "Ghi nhớ đăng nhập"), nút **hiện/ẩn mật khẩu**,
    trạng thái loading, báo lỗi tiếng Việt.
  - Register: upload avatar dạng vòng tròn có preview; **đăng ký xong tự đăng nhập và
    vào thẳng `/chat`** (auto-login).
- **Header** (`MainLayout.vue`): top bar mảnh, logo gradient, "user chip" (avatar + tên),
  nút Đăng xuất; điều hướng bằng router (không reload).

### 3.4. Trang Chat (`src/pages/ChatPage.vue`)

- **Bỏ hardcode `receiverId`** (trước đây cố định = 1 hoặc 2).
- **Sidebar 2 tab + ô tìm kiếm:**
  - *Trực tiếp*: danh sách user (từ `/api/users`), lọc theo tên.
  - *Nhóm*: danh sách nhóm + nút **"Tạo nhóm mới"**.
- **Tạo nhóm:** modal nhập tên + tích chọn thành viên.
- **Khung chat động** kiểu Messenger:
  - Bong bóng tin có **timestamp (giờ:phút)**; avatar người gửi; tên người gửi trong nhóm.
  - Nền chấm bi mờ, tự cuộn xuống cuối, empty state thân thiện.
- **Realtime:**
  - Subscribe kênh riêng `chat.{me.id}` để nhận tin 1-1.
  - Subscribe `group.{id}` cho mọi nhóm đang tham gia.
  - **Chống trùng tin** theo `id` (người gửi cũng nhận lại broadcast của nhóm).
- **Badge tin chưa đọc** cho hội thoại/nhóm chưa mở.
- Dọn channel (`echo.leave`) khi rời trang.

---

## 4. Trạng thái kiểm thử

- ✅ `php artisan migrate` chạy thành công (3 migration mới).
- ✅ `php artisan route:list --path=api` — 12 route đăng ký đầy đủ.
- ✅ `php artisan db:seed --class=UserSeeder` — tạo 8 user demo.
- ✅ `npm run build` (frontend) build thành công, không lỗi.
- ✅ `php artisan config:clear` để nạp lại cấu hình CORS mới.

## 5. Cách chạy nhanh để test

1. Backend: đảm bảo MySQL chạy (DB `realtime_chat`), `php artisan migrate --seed`.
2. Frontend: `npm run dev` → mở `http://localhost:5173`.
3. Đăng nhập bằng user demo bất kỳ, mật khẩu `123456`.
4. Muốn test 2 user cùng máy: mở thêm **tab ẩn danh** hoặc **trình duyệt khác**
   (vì token lưu chung `localStorage` theo từng profile trình duyệt).
