<?php 
include_once 'conn.php';

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// กำหนดค่าเริ่มต้นถ้าไม่มีค่า
if ($start_date == '') {
    $start_date = date('Y-m-01'); // วันที่ 1 ของเดือนปัจจุบัน
}
if ($end_date == '') {
    $end_date = date('Y-m-d'); // วันที่ปัจจุบัน
}

// คำสั่ง SQL พร้อมตัวกรองวันที่
$sql = "SELECT * FROM `order` 
        WHERE order_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

?>

<!-- รับค่าจากฟอร์ม -->


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styletable.css">
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

  




    <title>อนุมัติคำสั่งซื้อ/เช่า</title>
</head>

<body>
    <header class="header">
        <div class="logo-section">
            <img src="img/logo.jpg" alt="" class="logo">
            <h1 href="ceo_home_page.html" style="font-family: Itim;">CEO - HOME</h1>
        </div>
        <nav class="nav" style="margin-left: 20%;">
            <a href="approve_page.html" class="nav-item active">อนุมัติคำสั่งซื้อ/เช่า</a>
            <a href="approve_claim_page.html" class="nav-item">อนุมัติเคลม</a>
            <a href="summary_page.html" class="nav-item">สรุปยอดขาย</a>
            <a href="case_report_page.html" class="nav-item">ดูสรุปรายงานเคส</a>
            <a href="history_fixed_page.html" class="nav-item">ประวัติการส่งซ่อมรถและอุปกรณ์การแพทย์</a>
            <a href="static_car_page.html" class="nav-item">สถิติการใช้งานรถ</a>
        </nav>
    </header>
    <br>

    <div class="search-section">

        <!-- <div class="search-container">
            <input type="text" placeholder="ระบุชื่อสินค้า..." class="search-input">
            <button class="search-button">
                <i class="fa-solid fa-magnifying-glass"></i> 
            </button>
        </div> -->
        <div class="filter-icon">
            <i class="fa-solid fa-filter"></i> <!-- ไอคอน Filter -->
        </div>

        <div class="filter-sidebar" id="filterSidebar">
            <div class="sidebar-header">
                <h2>ตัวกรอง</h2>
                <button class="close-sidebar">&times;</button>
            </div>

            <div class="sidebar-content">
                <label for="">ช่วงวันที่สั่งสินค้า:</label>
                <input class="month-selected" id="calendarSelect" type="date" placeholder="วัน/เดือน/ปี" value="<?= htmlspecialchars($start_date) ?>"> ถึง
                <input class="month-selected" id="calendarSelect" type="date" placeholder="วัน/เดือน/ปี" value="<?= htmlspecialchars($end_date) ?>">
                <label for="filter-quantity">จำนวน:</label>
                <select id="filter-quantity-list" class="filter-select">
                    <option value="" selected>ทั้งหมด</option>
                    <option value="ambulance">น้อยสุด-มากสุด</option>
                    <option value="event">มากสุด-น้อยสุด</option>
                </select>
                <label for="filter-price">เรียงลำดับราคา:</label>
                <select id="filter-price-list" class="filter-select">
                    <option value="" selected hidden>เลือกการจัดเรียงราคา</option>
                    <option value="">ทั้งหมด</option>
                    <option value="">น้อยสุด-มากสุด</option>
                    <option value="" selected>มากสุด-น้อยสุด</option>
                </select>
                <label for="">ช่วงราคาสินค้า:</label>
                <div class="price-range">
                    <input type="number" id="minPrice" placeholder="ต่ำสุด" min="100000" max="1000000" value="100000">
                    <input type="range" id="minPriceRange" min="100000" max="1000000" step="100" value="100000"
                        oninput="updateMinPrice()">
                    <input type="range" id="maxPriceRange" min="100000" max="1000000" step="100" value="1000000"
                        oninput="updateMaxPrice()">
                    <input type="number" id="maxPrice" placeholder="สูงสุด" min="100000" max="1000000" value="1000000">
                </div>
                <label for="equipment-filter">ประเภทอุปกรณ์:</label>
                <select id="equipment-filter-list" class="filter-select">
                    <option value="" selected hidden>ประเภทอุปกรณ์</option>
                    <option value="" selected>ทั้งหมด</option>
                    <option value="">อุปกรณ์วัดและตรวจสุขภาพ</option>
                    <option value="">อุปกรณ์ช่วยการเคลื่อนไหว</option>
                    <option value="">อุปกรณ์สำหรับการฟื้นฟูและกายภาพบำบัด</option>
                    <option value="">อุปกรณ์ดูแลสุขอนามัย</option>
                    <option value="">อุปกรณ์ช่วยหายใจและระบบทางเดินหายใจ</option>
                    <option value="">อุปกรณ์ปฐมพยาบาล</option>
                </select>
                <!-- <button id="applyFilter" class="filter-button" style="margin-top: 10px;">ใช้ตัวกรอง</button> -->
            </div>
        </div>

    </div>
    <select class="select" name="option" style="margin-left: 2%;" onchange="location = this.value;">
        <option value="approve_page.html" selected>อนุมัติคำสั่งซื้อ</option>
        <option value="approve_rent_page.html">อนุมัติการเช่า</option>
    </select>

    <main class="main-content">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>ชื่ออุปกรณ์</th>
                    <th>ชื่อผู้ซื้อ</th>
                    <th>จำนวน</th>
                    <th>ราคา (บาท)</th>
                    <th>ราคารวม (บาท)</th>
                    <th><input type="radio" onclick="selectAll('approve')" name="approval_<?= htmlspecialchars($row['order_id'] ?? '') ?>" class="item-radio" value="approve" 
                    onchange="checkTest(<?= htmlspecialchars($row['order_id'] ?? 0) ?>, 'approved')">อนุมัติทั้งหมด</th>
                    <th><input type="radio" onclick="selectAll('reject')"  name="approval_<?= htmlspecialchars($row['order_id'] ?? '') ?>" class="item-radio" value="reject" 
                    onchange="checkTest(<?= htmlspecialchars($row['order_id'] ?? 0) ?>, 'not_approved')">ไม่อนุมัติทั้งหมด</th>
                   
                </tr>
            </thead>
            <tbody>
            <tbody>
    
            </tbody>

                <?php 
                 
                 
                 $sql = "SELECT * FROM `order` a 
                 inner join equipment e on a.equipment_id = e.equipment_id 
                 inner join member m on a.member_id = m.member_id 
                 WHERE a.order_type = 'ซื้อ'
                 AND (a.order_approve IS NULL OR a.order_approve = '') ";

                 $result = $conn->query($sql);
                 if ($result->num_rows > 0):  
                    while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr id="row_<?= $row['order_id'] ?>">
                            <td> <img src="img/<?= $row['equipment_image'] ?>" alt="อุปกรณ์" width="100"></td>
                            <td><?= htmlspecialchars($row['equipment_name']) ?></td>
                            <td><?= $row['member_firstname'] . ' ' . $row['member_lastname'] ?></td>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= $row['order_price'] ?></td>
                            <td><?= $row['order_total'] ?></td>
                            <td><input type="radio" name="approval_<?=$row['order_id']?>" class="item-radio" value="approve" onchange="checkTest(<?=$row['order_id']?>, 'approved')"> อนุมัติ</td>
                            <td><input type="radio" name="approval_<?=$row['order_id']?>" class="item-radio" value="reject" onchange="checkTest(<?=$row['order_id']?>, 'not_approved')">ไม่อนุมัติ</td>
                        </tr>
                    <?php endwhile;
                else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; font-weight: bold; color: red;">ไม่มีรายการอนุมัติ</td>
                    </tr>
                <?php endif; ?>

            <tbody>  
        </table>
                    
        

        <!-- **จำ** เมื่อกดปุ่มยืนยันหน้ายังคงอยู่หน้าเดิม -->
        <form action="save_order.php" method="post" id="approveForm">
            <div class="button-group-2">
                <button onclick="submitApproval(); return false;" class="btn btn-approve" >ยืนยัน</button>
            </div>
            

        </form>

    </main>

    

    <script src="approve_page.js">
    </script>
    <script  lang="javascript">
    
    </script>
</body>

</html>