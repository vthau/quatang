    
    Xin chào, cảm ơn bạn đã tham gia phần test ứng viên của Công ty CPCN Geckoso !

    1. Hướng dẫn sơ bộ
        + Để chạy được source này, máy các bạn cần phải cài
            - nodejs
            - composer
            - php7.2 

        + Khuyến nghị: nên sử dụng xampp (windows) - wampp (macos) - lampp (linux) 
    
        + Chạy tiếp các lệnh bên dưới để cài các phần còn thiếu
            - npm install
            - composer install

        + Đổi tên file .env.example thành .env , sau đó mở file này lên 
    và tùy chỉnh kết nối tới database của bạn
            DB_HOST=127.0.0.1
            DB_PORT=3306            => port của MySQL
            DB_DATABASE=laravel     => tên database của bạn
            DB_USERNAME=root        => thay đổi nếu cần
            DB_PASSWORD=            => thay đổi nếu cần

        + Tạo key mới
            php artisan key:generate

        + Khi tạo database nhớ chỉnh định dạng = utf8mb4_unicode_ci 
    sau đó import file source_db.sql vào database vừa tạo

        + Tiến hành clear cache nếu cần thiết
            php artisan cache:clear
            php artisan view:clear
            php artisan route:clear
            php artisan config:clear
            php artisan key:generate
            php artisan config:cache

        + Source đã được thêm file index.php nên các bạn không cần phải chạy php artisan serve
    có thể vào browser chạy trực tiếp website bằng link
            - ví dụ: 
                localhost/quatang hay 127.0.0.1/quatang (do mình đặt folder cha = quatang)
            nếu vẫn không vào được, các bạn có thể tiến hành clear cache trước với route
                localhost/quatang/clear-cache

    2. Hướng dẫn deploy
        + sau khi hoàn thành bài test, các bạn export toàn bộ database ra file sql 
    và nén toàn bộ folder source code thành 1 file
        + sau đó các bạn vào thư mục hướng_dẫn_deploy để xem hướng dẫn chi tiết bằng hình ảnh
        + thông tin truy cập vào server deploy đã được nhân sự gửi cho các bạn trước đó

        + nếu sau khi deploy vẫn không thể vào website, các bạn chú ý xem thư mục 
    đã có file .htaccess tương ứng chưa và gõ đường dẫn clear cache vào browser trước
        ví dụ:
            http://demo1.geckoso.com/clear-cache
        sau đó mới truy cập lại vào đường dẫn chính
            http://demo1.geckoso.com


    +++ Chúc các bạn thành công!!!

