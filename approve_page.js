document.addEventListener("DOMContentLoaded", () => {

    let arrApproved = [];


    const filterIcon = document.querySelector(".filter-icon");
    const sidebar = document.getElementById("filterSidebar");
    const closeSidebar = document.querySelector(".close-sidebar");
    

    // เปิด Sidebar
    filterIcon.addEventListener("click", () => {
        sidebar.classList.add("open");
    });

    // ปิด Sidebar
    closeSidebar.addEventListener("click", () => {
        sidebar.classList.remove("open");
    });

    // ปิด Sidebar เมื่อคลิกนอก Sidebar
    document.addEventListener("click", (e) => {
        if (!sidebar.contains(e.target) && !filterIcon.contains(e.target)) {
            sidebar.classList.remove("open");
        }
    });

});


document.addEventListener("DOMContentLoaded", () => {


  


    const slider = document.getElementById("priceRange");
    const priceInput = document.getElementById("priceInput");

    // อัปเดต Input เมื่อเลื่อน Slider
    slider?.addEventListener("input", () => {
        priceInput.value = slider.value; // อัปเดตค่าช่องกรอกให้ตรงกับ Slider
    });

    // อัปเดต Slider เมื่อกรอกค่าใน Input
    priceInput?.addEventListener("input", () => {
        const inputValue = parseInt(priceInput.value) || 100000;
        slider.value = Math.min(Math.max(inputValue, slider.min), slider.max); // จำกัดค่าให้อยู่ในช่วง
    });
});
function updateMinPrice() {
    var minPriceRange = document.getElementById('minPriceRange');
    var minPriceInput = document.getElementById('minPrice');
    var maxPriceRange = document.getElementById('maxPriceRange');

    minPriceInput.value = minPriceRange.value;
    if (parseInt(minPriceRange.value) > parseInt(maxPriceRange.value)) {
        maxPriceRange.value = minPriceRange.value;
        document.getElementById('maxPrice').value = minPriceRange.value;
    }
}

function updateMaxPrice() {
    var maxPriceRange = document.getElementById('maxPriceRange');
    var maxPriceInput = document.getElementById('maxPrice');
    var minPriceRange = document.getElementById('minPriceRange');

    maxPriceInput.value = maxPriceRange.value;
    if (parseInt(maxPriceRange.value) < parseInt(minPriceRange.value)) {
        minPriceRange.value = maxPriceRange.value;
        document.getElementById('minPrice').value = maxPriceRange.value;
    }
}


document.addEventListener("DOMContentLoaded", () => {
    // ตั้งค่าปฏิทิน Flatpickr สำหรับฟิลด์วันที่แรก
    flatpickr("#calendarSelect", {
        dateFormat: "d/m/Y",  // รูปแบบวันที่เป็น วัน/เดือน/ปี (เช่น 01/01/2024)
        onChange: function (selectedDates, dateStr, instance) {
            updateChart(dateStr);
        }
    });

    // ตั้งค่าปฏิทิน Flatpickr สำหรับฟิลด์วันที่ที่สอง
    flatpickr("#calendarSelect2", {
        dateFormat: "d/m/Y",  // รูปแบบวันที่เป็น วัน/เดือน/ปี (เช่น 01/01/2024)
        onChange: function (selectedDates, dateStr, instance) {
            updateChart(dateStr);
        }
    });
});

let arrApproved = [];

function checkTest(orderId, e) {


    
    //console.log("before", array);
    arrApproved = arrApproved.filter(e=>e?.orderId!==orderId);    
    arrApproved.push({orderId:orderId,  approval: e.checked});                  
    console.log("after", arrApproved);
    // console.log(orderId, e.checked );
    
}


//ปุ่มยืนยันตัวที่ติ๊กทั้งหมด
function selectAll(status) {
    // ค้นหา radio button ทุกตัวที่มี value ตามที่เลือก (อนุมัติหรือไม่อนุมัติ)
    let radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);

    radios.forEach(radio => {
        radio.checked = true; // กำหนดค่าให้เลือก
    });
}

function submitApproval() {
    let selectedOrders = [];
    document.querySelectorAll(".item-radio:checked").forEach(input => {
        let orderId = input.name.split("_")[1]; // ดึง order_id จาก name
        selectedOrders.push({
            order_id: orderId,
            approval_status: input.value
        });
    });

    if (selectedOrders.length === 0) {
        alert("กรุณาเลือกออเดอร์ก่อนกดอนุมัติ");
        return;
    }
}


// ตัวที่ส่งข้อมูลไปยัง save_order PHP

function submitApproval() {
    let selectedOrders = [];
    document.querySelectorAll(".item-radio:checked").forEach((radio) => {
        let orderId = parseInt(radio.name.replace("approval_", "").trim(), 10);
        let status = radio.value.trim();

        if (orderId > 0) {
            selectedOrders.push({
                order_id: orderId,
                approval_status: status
            });
        }
    });

    if (selectedOrders.length === 0) {
        alert("กรุณาเลือกอย่างน้อย 1 รายการ");
        return;
    }

    console.log("📌 ส่งข้อมูลไปยัง PHP:", JSON.stringify({ orders: selectedOrders }));

    fetch("save_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ orders: selectedOrders }),
    })
    .then(response => response.json())
    .then(data => {
        console.log("📌 PHP ตอบกลับ:", data);
        
        if (data.status === "success") {
            alert(data.message);
            location.reload(); // ✅ รีเฟรชหน้า
        } else {
            alert("เกิดข้อผิดพลาด: " + data.message);
            console.error("Errors:", data.errors);
        }
    })
    .catch(error => console.error("Error:", error));
}




















