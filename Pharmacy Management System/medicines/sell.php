<?php 
include("../includes/header.php"); 
if(!$conn){die("Please login first.");} 
?>

<!-- ✅ SweetAlert2 CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.sell-container {
    display: flex;
    gap: 20px;
    margin: 0 auto;
    max-width: 98%;
    align-items: stretch;
    justify-content: space-between;
}
.panel {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    height: 80vh;
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}
.panel.left, .panel.middle, .panel.right {
    flex-basis: 32%;
}
.panel-content {
    flex-grow: 1;
    overflow-y: auto;
    padding-right: 10px;
}
.medicine-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 10px;
    background-color: #f9f9f9;
    cursor: pointer;
    transition: background-color 0.2s, box-shadow 0.2s;
}
.medicine-item:hover {
    background-color: #e0f7fa;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.medicine-item.out-of-stock {
    background-color: #ffebee;
    border: 1px solid #e53935;
    color: #b71c1c;
    cursor: not-allowed;
    opacity: 0.8;
}
.medicine-item.out-of-stock:hover {
    background-color: #ffcdd2;
}
.medicine-item .info span {
    color: #37474f;
    font-size: 14px;
    display: block;
}
#suggestions div {
    background-color: #fff;
    padding: 5px 10px;
    cursor: pointer;
    border: 1px solid #ccc;
    border-top: none;
}
#suggestions {
    position: absolute;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ccc;
    display: none;
    z-index: 10;
    background: #fff;
}
</style>

<div class="sell-container">
    <div class="panel left">
        <h3>All Medicines</h3>
        <div id="medicineList" class="panel-content">Loading...</div>
    </div>
    <div class="panel middle">
        <h3>Add Medicine to Cart</h3>
        <form id="medicineForm" onsubmit="return addMedicine();">
            <div style="position: relative; margin-bottom: 20px;">
                <label for="selectedMedicine">Search Medicine:</label>
                <input type="text" id="selectedMedicine" required oninput="searchMedicine(this.value)" autocomplete="off">
                <div id="suggestions"></div>
                <input type="hidden" id="selectedMedicineId">
                <input type="hidden" id="selectedMedicinePrice">
            </div>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" min="1" required>
            <button type="submit" style="margin-top: 10px;">Add to Cart</button>
        </form>
    </div>
    <div class="panel right">
        <h3>Cart</h3>
        <div id="cartTable-container" class="panel-content">
            <table id="cartTable" style="width:100%;">
                <thead><tr><th>Medicine</th><th>Qty</th><th>Price</th><th>Subtotal</th><th>Remove</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
        <h3 id="totalPrice" style="margin-top: auto; padding-top: 15px;">Total: 0.00</h3>
        <button onclick="submitSale()">Submit Sale</button>
    </div>
</div>

<script>
let medicines = [], cart = [];
const USER_DB_NAME = "<?php echo isset($_SESSION['database_name']) ? htmlspecialchars($_SESSION['database_name']) : ''; ?>";
const BASE_IMAGE_PATH = `../uploads/${USER_DB_NAME}/`;

document.addEventListener('DOMContentLoaded', function () {
    fetch('fetch_medicines.php')
        .then(r => r.json())
        .then(d => { medicines = d; loadMedicines(); })
        .catch(e => console.error(e));
});

function loadMedicines() {
    const l = document.getElementById('medicineList');
    l.innerHTML = '';
    if (!medicines || medicines.length === 0) {
        l.innerHTML = '<p style="text-align:center;color:#757575;">No medicines found.</p>';
        return;
    }
    medicines.forEach(m => {
        const d = document.createElement('div');
        const i = m.image_path ?
            `<img src="${BASE_IMAGE_PATH}${m.image_path}" alt="${m.name}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">` :
            `<img src="../images/janata.ico" alt="N/A" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">`;
        d.innerHTML = `${i}<div class="info"><strong>${m.name} (${m.companyName})</strong><span>Stock: ${m.quantity}</span><span>Price: ${parseFloat(m.price).toFixed(2)}</span></div>`;
        d.className = 'medicine-item';
        if (m.quantity > 0) d.onclick = () => selectMedicine(m);
        else {
            d.classList.add('out-of-stock');
            d.title = "Out of Stock";
        }
        l.appendChild(d);
    });
}

function selectMedicine(m) {
    document.getElementById('selectedMedicine').value = m.name;
    document.getElementById('selectedMedicineId').value = m.medicine_pk;
    document.getElementById('selectedMedicinePrice').value = m.price;
    document.getElementById('suggestions').style.display = 'none';
}

function searchMedicine(q) {
    let s = document.getElementById('suggestions');
    s.innerHTML = '';
    if (q.length < 1) {
        s.style.display = 'none';
        return;
    }
    let M = medicines.filter(m => m.name.toLowerCase().includes(q.toLowerCase()));
    M.forEach(m => {
        let d = document.createElement('div');
        d.innerText = `${m.name} (Stock: ${m.quantity})`;
        if (m.quantity > 0) d.onclick = () => selectMedicine(m);
        s.appendChild(d);
    });
    s.style.display = M.length ? 'block' : 'none';
}

function addMedicine() {
    let i = document.getElementById('selectedMedicineId').value,
        q = document.getElementById('quantity').value;
    if (!i || !q || q <= 0) return false;
    let m = medicines.find(m => m.medicine_pk == i);
    if (parseInt(q) > m.quantity) {
        typeof Swal !== 'undefined' ?
            Swal.fire('Out of Stock!', `Only ${m.quantity} items available.`, 'warning') :
            alert(`Out of Stock! Only ${m.quantity} items available.`);
        return false;
    }
    cart.push({ id: i, name: m.name, price: parseFloat(m.price), quantity: parseInt(q) });
    updateCart();
    document.getElementById('medicineForm').reset();
    document.getElementById('suggestions').style.display = 'none';
    return false;
}

function updateCart() {
    let t = document.querySelector('#cartTable tbody');
    t.innerHTML = '';
    let o = 0;
    cart.forEach((i, x) => {
        let e = i.price * i.quantity;
        o += e;
        t.innerHTML += `<tr><td>${i.name}</td><td>${i.quantity}</td><td>${i.price.toFixed(2)}</td><td>${e.toFixed(2)}</td><td><button onclick="removeItem(${x})">❌</button></td></tr>`;
    });
    document.getElementById('totalPrice').innerText = 'Total: ' + o.toFixed(2);
}

function removeItem(i) {
    cart.splice(i, 1);
    updateCart();
}

function submitSale() {
    if (cart.length === 0) {
        Swal.fire('Cart is Empty!', 'Please add medicines to cart first.', 'warning');
        return;
    }
    document.getElementById('totalPrice').innerText = 'Processing...';
    fetch('save_sale.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(cart)
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error: ' + response.status);
        return response.text();
    })
    .then(data => {
        if (data.toLowerCase().includes("successful")) {
            Swal.fire({
                icon: 'success',
                title: 'Sale Successful!',
                html: `<strong>${data}</strong>`,
                confirmButtonText: 'View Bill',
                confirmButtonColor: '#3085d6',
                background: '#f4f9ff',
                backdrop: `rgba(0,0,123,0.4)`
            }).then(() => {
                window.location.href = '../bills/view.php';
            });
        } else {
            Swal.fire('Error!', data, 'error');
            updateCart();
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        Swal.fire('Request Failed!', 'Could not connect to the server.', 'error');
        updateCart();
    });
}
</script>

<?php include("../includes/footer.php"); ?>
