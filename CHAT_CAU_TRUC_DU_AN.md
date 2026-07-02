# Realtime Chat — Cấu trúc dự án (các file quan trọng)

Tài liệu liệt kê **những file/thư mục cốt lõi** của hệ thống chat và chức năng của chúng.
Các file mặc định của framework, file build, vendor... không quan trọng nên **được lược bỏ**.

Hệ thống gồm 2 dự án:
- `realtime-chat-api` — Backend **Laravel 12** (REST API + broadcasting)
- `realtime-chat-frontend` — Frontend **Vue 3 + Vite**

---

## A. BACKEND — `realtime-chat-api`

```
realtime-chat-api/
├── app/
│   ├── Models/
│   │   ├── User.php              # Model người dùng
│   │   ├── Message.php           # Model tin nhắn (1-1 và nhóm)
│   │   └── Group.php             # Model nhóm chat
│   ├── Events/
│   │   ├── MessageSent.php       # Event broadcast tin 1-1
│   │   └── GroupMessageSent.php  # Event broadcast tin nhóm
│   └── Http/Controllers/
│       └── GroupController.php   # Xử lý nghiệp vụ nhóm
├── routes/
│   ├── api.php                   # Toàn bộ REST API endpoint
│   └── channels.php              # Khai báo broadcast channel
├── config/
│   ├── broadcasting.php          # Cấu hình Pusher (driver realtime)
│   ├── cors.php                  # Cấu hình CORS (origin được phép)
│   └── sanctum.php               # Cấu hình token API
├── database/
│   ├── migrations/               # Định nghĩa bảng DB
│   └── seeders/UserSeeder.php    # Tạo user demo
├── bootstrap/app.php             # Nối routes + middleware (Laravel 12)
└── .env                          # Biến môi trường (DB, Pusher, CORS)
```

### A.1. Models (`app/Models/`)

| File | Chức năng |
|------|-----------|
| **User.php** | Người dùng. Có accessor `avatar_url` (sinh URL ảnh từ path), quan hệ `groups()` (nhiều-nhiều với Group). Dùng `HasApiTokens` cho Sanctum. |
| **Message.php** | Tin nhắn. Fillable: `sender_id`, `receiver_id`, `group_id`, `message`. Quan hệ `sender()`, `receiver()`, `group()`. Phân biệt tin 1-1 (`receiver_id`) và tin nhóm (`group_id`). |
| **Group.php** | Nhóm chat. Quan hệ `members()` (nhiều-nhiều qua bảng `group_user`), `creator()`, `messages()`. Accessor `avatar_url`. |

### A.2. Routes (`routes/`)

| File | Chức năng |
|------|-----------|
| **api.php** | **Trái tim của API.** Chứa toàn bộ endpoint: register, login, lấy user/danh sách user, gửi & xem tin 1-1, và `POST /pusher/auth` (phân quyền kênh realtime). Group route trỏ tới `GroupController`. |
| **channels.php** | Khai báo broadcast channel `chat.{id}` cùng hàm kiểm tra quyền (dùng cho cơ chế channel của Laravel Echo/Broadcast). |

> **Endpoint chính trong `api.php`:**
> - `POST /register`, `POST /login` → xác thực, trả token Sanctum.
> - `GET /user`, `GET /users` → thông tin user / danh sách user.
> - `POST /messages`, `GET /messages/{userId}` → gửi & lấy lịch sử chat 1-1.
> - `GET|POST /groups`, `GET|POST /groups/{group}/messages` → nhóm (qua GroupController).
> - `POST /pusher/auth` → cấp chữ ký cho client subscribe private channel.

### A.3. Realtime / Events (`app/Events/`)

| File | Chức năng |
|------|-----------|
| **MessageSent.php** | Event tin **1-1**. Implements `ShouldBroadcastNow` (đẩy ngay, không qua queue). `broadcastOn()` → kênh `chat.{receiverId}`; `broadcastAs()` → `message.sent`. Payload gồm id, sender, message, avatar, `created_at`. |
| **GroupMessageSent.php** | Event tin **nhóm**. Tương tự, nhưng `broadcastOn()` → kênh `group.{groupId}`; `broadcastAs()` → `group.message.sent`. Payload có thêm `sender_name`. |

> Luồng realtime: Controller/route gọi `broadcast(new MessageSent(...))` → Laravel đẩy lên
> **Pusher** → Pusher đẩy xuống client qua **WebSocket**.

### A.4. Controllers (`app/Http/Controllers/`)

| File | Chức năng |
|------|-----------|
| **GroupController.php** | Nghiệp vụ nhóm: `index` (nhóm của tôi), `store` (tạo nhóm + gán thành viên), `messages` (lịch sử nhóm), `sendMessage` (gửi + broadcast). Có `authorizeMember()` chặn người ngoài nhóm (HTTP 403). |

### A.5. Config (`config/`)

| File | Chức năng |
|------|-----------|
| **broadcasting.php** | Khai báo driver broadcast = **pusher** và đọc khóa Pusher từ `.env`. Quyết định realtime chạy qua đâu. |
| **cors.php** | Origin frontend được phép gọi API. Đọc từ env `CORS_ALLOWED_ORIGINS` (đã siết, không còn `*`). |
| **sanctum.php** | Cấu hình xác thực token API (stateful domains, expiration...). |

### A.6. Database (`database/`)

| Phần | Chức năng |
|------|-----------|
| **migrations/** | Định nghĩa schema. Quan trọng: `create_messages_table`, `create_groups_table`, `create_group_user_table`, `add_group_id_to_messages_table`, `add_avatar_to_users_table`. |
| **seeders/UserSeeder.php** | Tạo 8 user demo (mật khẩu `123456`) kèm avatar từ `public/storage/image`. |

### A.7. Cấu hình hệ thống

| File | Chức năng |
|------|-----------|
| **bootstrap/app.php** | (Laravel 12) Nối các file route (`api.php`, `channels.php`...), bật `statefulApi()`, loại CSRF cho `api/*`. |
| **.env** | Biến môi trường: kết nối MySQL, khóa Pusher (`PUSHER_*`), `BROADCAST_CONNECTION`, `CORS_ALLOWED_ORIGINS`, `SANCTUM_STATEFUL_DOMAINS`. |

---

## B. FRONTEND — `realtime-chat-frontend`

```
realtime-chat-frontend/
├── src/
│   ├── main.js                   # Điểm khởi động app
│   ├── App.vue                   # Component gốc (chứa router-view)
│   ├── style.css                 # Style toàn cục (theme, reset, font)
│   ├── router/index.js           # Định tuyến + route guard
│   ├── layouts/MainLayout.vue    # Khung header chung
│   ├── pages/
│   │   ├── LoginPage.vue         # Trang đăng nhập
│   │   ├── RegisterPage.vue      # Trang đăng ký
│   │   └── ChatPage.vue          # Trang chat chính
│   └── services/
│       ├── api.js                # Axios client (gắn token tự động)
│       ├── auth.js               # Quản lý trạng thái đăng nhập
│       └── echo.js               # Kết nối realtime (Laravel Echo + Pusher)
└── index.html                    # HTML gốc
```

### B.1. Khởi động & định tuyến

| File | Chức năng |
|------|-----------|
| **main.js** | Tạo Vue app, gắn router, import `style.css`, mount vào `#app`. |
| **App.vue** | Component gốc — chỉ render `<router-view />`. |
| **router/index.js** | Khai báo route (`/login`, `/register`, `/chat`) và **route guard**: chưa đăng nhập → ép về `/login`; đã đăng nhập vào trang khách → ép về `/chat`. |

### B.2. Services (`src/services/`) — phần lõi

| File | Chức năng |
|------|-----------|
| **echo.js** | **Trái tim realtime ở client.** Khởi tạo Laravel Echo + Pusher, mở kết nối WebSocket. `authorizer` gọi `POST /api/pusher/auth` để xin quyền vào private channel. |
| **api.js** | Axios instance (baseURL = API). Interceptor **tự đính kèm** `Authorization: Bearer <token>` vào mọi request. |
| **auth.js** | Quản lý đăng nhập: `currentUser` (state **reactive** dùng chung), `setAuth`, `logout`, `getToken`, `isLoggedIn`, `fetchUser`. Lưu phiên ở `localStorage`. |

### B.3. Giao diện (`src/pages/`, `src/layouts/`)

| File | Chức năng |
|------|-----------|
| **pages/ChatPage.vue** | **Màn hình chat chính.** Sidebar (tab Trực tiếp/Nhóm + tìm kiếm), khung tin nhắn (bubble + timestamp + avatar), ô nhập, modal tạo nhóm. **Subscribe & lắng nghe** sự kiện realtime (`chat.{id}`, `group.{id}`), chống trùng tin theo `id`. |
| **pages/LoginPage.vue** | Form đăng nhập: ghi nhớ email, hiện/ẩn mật khẩu, gọi `setAuth` rồi vào `/chat`. |
| **pages/RegisterPage.vue** | Form đăng ký + upload avatar; đăng ký xong **auto-login** vào `/chat`. |
| **layouts/MainLayout.vue** | Header chung (logo, avatar + tên user, nút đăng xuất). Dùng `currentUser` reactive nên cập nhật tức thì sau login/logout. |
| **style.css** | Biến màu theme, reset CSS, font hệ thống, thanh cuộn — áp dụng toàn app. |

---

## C. Bản đồ nhanh "muốn sửa X thì vào đâu"

| Muốn làm gì | File cần mở |
|-------------|-------------|
| Thêm/sửa endpoint API | `realtime-chat-api/routes/api.php` |
| Sửa nghiệp vụ nhóm | `app/Http/Controllers/GroupController.php` |
| Đổi dữ liệu gửi qua realtime | `app/Events/MessageSent.php`, `GroupMessageSent.php` |
| Phân quyền kênh realtime | `routes/api.php` (`/pusher/auth`), `routes/channels.php` |
| Đổi cấu hình Pusher | `.env` (`PUSHER_*`), `config/broadcasting.php` |
| Đổi origin được phép | `.env` (`CORS_ALLOWED_ORIGINS`), `config/cors.php` |
| Sửa kết nối realtime phía client | `src/services/echo.js` |
| Sửa giao diện chat | `src/pages/ChatPage.vue` |
| Sửa luồng đăng nhập | `src/services/auth.js`, `src/pages/LoginPage.vue`, `src/router/index.js` |
| Đổi màu sắc / theme | `src/style.css` |
