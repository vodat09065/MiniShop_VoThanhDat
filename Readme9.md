# TỔNG HỢP CÁC CÂU HỎI CẦN TRẢ LỜI - LAB 9

1. **$limit, $page, $offset dùng để làm gì?**
   - `$limit`: Số lượng bản ghi (sản phẩm, người dùng...) muốn hiển thị trên một trang.
   - `$page`: Trang hiện tại mà người dùng đang xem.
   - `$offset`: Vị trí bắt đầu lấy dữ liệu trong cơ sở dữ liệu (tính toán dựa trên $page và $limit).

2. **Vì sao cần ceil() khi tính $totalPages?**
   - Hàm `ceil()` dùng để làm tròn lên. Nếu có 25 sản phẩm và limit là 10, phép chia 25/10 = 2.5, làm tròn lên thành 3 trang để chứa hết 5 sản phẩm lẻ còn lại.

3. **LIMIT và OFFSET trong SQL có tác dụng gì?**
   - `LIMIT`: Giới hạn số lượng bản ghi trả về từ câu truy vấn.
   - `OFFSET`: Bỏ qua số lượng bản ghi được chỉ định trước khi bắt đầu lấy dữ liệu.

4. **Vì sao khi chuyển trang phải giữ limit trên URL?**
   - Để đảm bảo khi chuyển sang trang khác, hệ thống vẫn nhớ thiết lập số dòng hiển thị (ví dụ 20 dòng/trang) mà người dùng đã chọn trước đó, tránh bị đưa về mặc định (10 dòng/trang).

5. **Vì sao khi tìm kiếm phải giữ keyword khi chuyển trang?**
   - Để kết quả hiển thị ở các trang tiếp theo vẫn là dữ liệu thuộc về từ khóa tìm kiếm đó. Nếu mất keyword, hệ thống sẽ phân trang cho toàn bộ dữ liệu.

6. **count() dùng để làm gì trong chức năng phân trang?**
   - Dùng để lấy tổng số bản ghi (tổng số sản phẩm) có trong database (hoặc tổng số kết quả khớp với tìm kiếm) nhằm tính toán tổng số trang `$totalPages`.

7. **Vì sao nên tái sử dụng getPage() thay vì tạo getPageByKeyword() riêng?**
   - Để code gọn gàng, tránh lặp lại (DRY - Don't Repeat Yourself). Hàm `getPage()` có thể nhận tham số mặc định `$keyword = ""`, giúp xử lý cả hai trường hợp có hoặc không có từ khóa tìm kiếm.

8. **Khi tìm kiếm không có kết quả thì $totalPages có giá trị bao nhiêu?**
   - $totalPages sẽ có giá trị bằng 0 (do tổng số record = 0, chia cho limit sẽ bằng 0).

9. **sort dùng để làm gì?**
   - Dùng để xác định tiêu chí sắp xếp dữ liệu (ví dụ: theo tên A-Z, giá tăng dần, giá giảm dần...) giúp người dùng dễ dàng tìm được thông tin mong muốn.

10. **Khi kết hợp tìm kiếm + sắp xếp + phân trang, những tham số nào cần được giữ trên URL?**
    - Cần giữ lại tất cả 3 tham số: `keyword` (từ khóa tìm kiếm), `sort` (tiêu chí sắp xếp), và `limit` (số dòng/trang) để trạng thái không bị mất khi chuyển trang.
