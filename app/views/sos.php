<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOS MAP - Hệ Thống Cứu Hộ</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:#f0f2f5; color: #333; }
        .container { max-width:1100px; margin:20px auto; padding: 0 15px; }
        
        .top { background:white; padding:25px; border-radius:15px; margin-bottom:20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #dc2626; margin-bottom: 15px; font-size: 24px; }
        
        textarea { 
            width:100%; height:100px; padding:15px; border-radius:10px; 
            border:1px solid #ddd; margin-bottom:15px; resize: none; font-size: 16px;
        }
        
        button { 
            padding:12px 25px; border:none; background:#dc2626; color:white; 
            border-radius:8px; cursor:pointer; font-weight: bold; transition: 0.2s;
        }
        button:hover { background: #b91c1c; }

        #map { width:100%; height:450px; border-radius:15px; margin-bottom:25px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 1; }

        .list .item { 
            background:white; padding:20px; border-radius:12px; 
            margin-bottom:15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-left: 5px solid #dc2626;
        }

        .danger { color:#dc2626; font-size:18px; font-weight:bold; margin-bottom:8px; }
        .small { color:#666; font-size: 14px; line-height:1.6; }

        /* Hiệu ứng nhấp nháy cho SOS mới */
        .new-sos { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }

        .notify {
            position:fixed; top:20px; right:20px; background:#dc2626; 
            color:white; padding:15px 25px; border-radius:10px; z-index:10000; 
            display:none; font-weight: bold; box-shadow:0 4px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

<div class="notify" id="notify">🚨 Có tín hiệu SOS mới gần bạn!</div>

<audio id="alertSound">
    <!-- Đảm bảo đường dẫn này đúng trong project của bạn -->
    <source src="/2026_SOS/public/assets/sounds/war-horn-alert.mp3" type="audio/mpeg">
</audio>

<div class="container">
    <div class="top">
        <h1>🚨 HỆ THỐNG CỨU HỘ SOS</h1>
        <form method="POST" action="/2026_SOS/public/sos/store">
            <textarea name="message" placeholder="Mô tả tình trạng nguy hiểm của bạn (Ví dụ: Tai nạn giao thông, hỏa hoạn...)" required></textarea>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <button type="submit">PHÁT TÍN HIỆU CẤP CỨU</button>
        </form>
    </div>

    <div id="map"></div>

    <div class="list" id="sos-container">
        <!-- Danh sách SOS sẽ được load qua AJAX (Hàm loadSOS) -->
        <p style="text-align:center; color:gray;">Đang xác định vị trí và tải dữ liệu...</p>
    </div>
</div>

<!-- Nạp Leaflet JS trước khi viết script tùy chỉnh -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map;
let userLat = 0;
let userLng = 0;
let markers = [];
let lastSOSCount = 0;

// 1. Lấy vị trí người dùng
navigator.geolocation.getCurrentPosition(
    function(position){
        userLat = position.coords.latitude;
        userLng = position.coords.longitude;

        document.getElementById('latitude').value = userLat;
        document.getElementById('longitude').value = userLng;

        initMap();
        loadSOS(); // Load lần đầu
    },
    function(){
        alert("Lỗi: Vui lòng bật GPS để sử dụng hệ thống cứu hộ.");
        // Vị trí mặc định nếu lỗi (Ví dụ: Hà Nội)
        userLat = 21.0285; userLng = 105.8542;
        initMap();
    }
);

// 2. Khởi tạo bản đồ
function initMap(){
    map = L.map('map').setView([userLat, userLng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marker vị trí của bạn (Màu xanh)
    const iconYou = L.circleMarker([userLat, userLng], {
        color: 'blue', radius: 10, fillOpacity: 0.8
    }).addTo(map).bindPopup('<b>Vị trí của bạn</b>').openPopup();
}

// 3. Xóa Marker cũ để cập nhật mới
function clearMarkers(){
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
}

// 4. Tải dữ liệu SOS
function loadSOS(){
    // Tải danh sách HTML để hiển thị phía dưới
    fetch(`/2026_SOS/public/sos/list?lat=${userLat}&lng=${userLng}`)
    .then(response => response.text())
    .then(data => {
        document.getElementById('sos-container').innerHTML = data;
    });

    // Tải JSON để vẽ Marker lên bản đồ
    fetch(`/2026_SOS/public/sos/json_data?lat=${userLat}&lng=${userLng}`) // Bạn cần tạo route trả về JSON này
    .then(response => response.json())
    .then(data => {
        clearMarkers();
        data.forEach(item => {
            let m = L.marker([item.latitude, item.longitude])
                     .addTo(map)
                     .bindPopup(`<b>Nguy hiểm:</b> ${item.message}<br><b>Gửi bởi:</b> ${item.name}`);
            markers.push(m);
        });

        // Kiểm tra nếu có SOS mới để thông báo
        if(lastSOSCount !== 0 && data.length > lastSOSCount){
            showNotification();
        }
        lastSOSCount = data.length;
    })
    .catch(err => console.log("Chưa có API JSON hoặc lỗi kết nối"));
}

function showNotification(){
    let notify = document.getElementById('notify');
    notify.style.display = 'block';
    
    let sound = document.getElementById('alertSound');
    sound.play().catch(e => console.log("Trình duyệt chặn tự động phát âm thanh"));

    setTimeout(() => { notify.style.display = 'none'; }, 5000);
}

// Cập nhật mỗi 5 giây
setInterval(loadSOS, 5000);

// 5. Tham gia hỗ trợ
function joinSOS(id) {
    fetch('/2026_SOS/public/sos/join', {
        method:'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `sos_id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        alert('Cảm ơn bạn đã tham gia hỗ trợ!');
        loadSOS();
    });
}
</script>
</body>
</html>