# Realtime Chat — Những gì nên làm để cải thiện

Danh sách đề xuất cho tính năng chat của `realtime-chat-api` và `realtime-chat-frontend`.
Sắp xếp theo độ ưu tiên. Mỗi mục ghi rõ **vấn đề** và **hướng làm**.

---

## ✅ Đã hoàn thành (cập nhật mới nhất)

- **Route guard frontend** — chưa login vào `/chat` tự về `/login`; đã login vào
  `/login`/`/register` tự về `/chat`. (Sửa luôn lỗi phải reload mới vào được form login.)
- **Auth reactive + ghi nhớ đăng nhập** — `currentUser` reactive, header cập nhật ngay
  sau login/logout không cần reload; ghi nhớ email; phiên lưu `localStorage`.
- **Auto-login sau khi đăng ký** — API register trả token, frontend `setAuth` rồi vào `/chat`.
- **Hiển thị thời gian tin nhắn** — bong bóng có timestamp; event broadcast kèm `created_at`.
- **Redesign giao diện** — Messenger/Telegram style (login, register, header, chat, modal),
  ô tìm kiếm, hiện/ẩn mật khẩu, empty state.
- **Dùng instance `api` thống nhất** — ChatPage gọi qua service `api` (interceptor tự gắn token).
- **Seed 8 user demo** (mật khẩu `123456`) kèm avatar.

---

## 🔴 Nhóm A — Bảo mật & Đúng đắn (nên làm sớm)

### A2. Xóa endpoint test `/api/send-test`
- **Vấn đề:** Route test hardcode `private-chat.2`, rò rỉ thông tin & không cần thiết.
- **Hướng làm:** Xóa khỏi `routes/api.php` trước khi lên production.

### A3. Bỏ hardcode key/secret Pusher
- **Vấn đề:** `src/services/echo.js` hardcode `key: 'cf7f5eea4abfbc14c529'`;
  `.env` backend để lộ `PUSHER_APP_SECRET` trong repo.
- **Hướng làm:**
  - Frontend: dùng `import.meta.env.VITE_PUSHER_APP_KEY` (đã có sẵn biến trong `.env`).
  - Backend: đảm bảo `.env` nằm trong `.gitignore`, đổi secret nếu đã commit lộ.

### A4. Validate đầu vào endpoint tin nhắn 1-1
- **Vấn đề:** `POST /api/messages` chưa validate `receiver_id`, `message`
  (khác với group đã validate).
- **Hướng làm:** Thêm `$request->validate([...])`, kiểm tra `receiver_id` tồn tại,
  không cho tự gửi cho chính mình.

### A5. Phân quyền xem lịch sử chuẩn hơn
- **Vấn đề:** `GET /api/messages/{userId}` chỉ dựa vào quan hệ sender/receiver — ổn,
  nhưng nên chặn `userId` không hợp lệ.
- **Hướng làm:** Validate `userId` tồn tại trong bảng `users`.

---

## 🟡 Nhóm B — Trải nghiệm người dùng

### B3. Sắp xếp danh sách theo hoạt động mới nhất
- **Vấn đề:** Danh sách user/nhóm theo thứ tự tĩnh; chưa có "tin nhắn cuối".
- **Hướng làm:** API trả kèm tin nhắn gần nhất + thời gian; sắp hội thoại có tin mới lên đầu,
  hiển thị preview tin cuối dưới tên (như Messenger).

### B4. Quản lý nhóm
- **Thiếu:** thêm/xóa thành viên, rời nhóm, đổi tên, upload avatar nhóm.
- **Hướng làm:** Bổ sung endpoint + UI (chỉ người tạo hoặc admin nhóm được sửa).

### B5. Trạng thái online & "đang gõ" & "đã đọc"
- **Hướng làm:** Dùng **presence channel** của Pusher cho online;
  thêm event typing; cột `read_at` cho trạng thái đã đọc.

### B6. Thông báo tin nhắn mới
- **Hướng làm:** Badge tổng số chưa đọc trên link/header;
  có thể thêm âm thanh / Web Notification.

### B7. UX nhỏ còn lại
- Nút "cuộn xuống" khi đang xem tin cũ mà có tin mới đến.
- Spinner/skeleton khi đang tải lịch sử tin nhắn.
- Phân cách theo ngày (hôm nay / hôm qua / ngày cụ thể) trong khung chat.

---

## 🟢 Nhóm C — Kỹ thuật & Bảo trì

### C1. Refactor `routes/api.php` sang Controller
- **Vấn đề:** Logic register/login/messages viết dạng closure trong route file → khó test.
- **Hướng làm:** Tách `AuthController`, `MessageController` (group đã có `GroupController`).

### C2. Phân trang lịch sử tin nhắn
- **Vấn đề:** `GET /messages/...` và `/groups/{id}/messages` load **toàn bộ** tin.
- **Hướng làm:** Dùng `paginate()` hoặc cursor; frontend load thêm khi cuộn lên.

### C3. Form Request thay vì validate inline
- **Hướng làm:** Tạo `StoreMessageRequest`, `StoreGroupRequest` cho gọn và tái sử dụng.

### C4. Xử lý lỗi & phản hồi đồng nhất
- **Vấn đề:** Vẫn còn vài chỗ `catch` chỉ `console.log` (gửi tin, tạo nhóm).
- **Hướng làm:** Hiển thị toast/thông báo lỗi cho người dùng; chuẩn hóa format JSON lỗi.

### C5. Index database
- **Hướng làm:** Thêm composite index `(sender_id, receiver_id)` và `(group_id, id)`
  trên bảng `messages` để truy vấn lịch sử nhanh hơn.

### C6. Kiểm thử tự động
- **Hướng làm:** Viết feature test (PHPUnit) cho login, gửi tin 1-1, tạo nhóm,
  phân quyền nhóm.

---

## Gợi ý thứ tự thực hiện tiếp theo

1. **A2, A3** — bảo mật, làm nhanh.
2. **A4, A5** — siết validate đầu vào.
3. **B3, B7** — hoàn thiện trải nghiệm chat cho giống app thật.
4. **C2, C5** — hiệu năng khi dữ liệu lớn dần.
5. **B4, B5** — tính năng nâng cao (quản lý nhóm + presence/online).
6. **C1, C3, C6** — củng cố chất lượng code lâu dài.
