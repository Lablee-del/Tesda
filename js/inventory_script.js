// Utility functions
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

function setButtonLoading(button, isLoading) {
    const originalText = button.dataset.originalText || button.innerHTML;
    
    if (isLoading) {
        button.dataset.originalText = originalText;
        button.innerHTML = '<div class="loading"></div>' + originalText;
        button.disabled = true;
    } else {
        button.innerHTML = originalText;
        button.disabled = false;
        delete button.dataset.originalText;
    }
}

function formatCurrency(amount) {
    return '₱ ' + parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// CRUD Operations
function addItem(formData) {
    return fetch('inventory.php?action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            sessionStorage.setItem('itemAdded', 'true');
            window.location.reload(); 
        
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while adding the item', 'error');
    });
}


function updateItem(formData) {
    return fetch('inventory.php?action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            updateRowInTable(data.item);
            document.getElementById('editModal').style.display = 'none';
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating the item', 'error');
    });
}

function deleteItem(itemId) {
    if (!confirm('Are you sure you want to delete this item?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', itemId);

    fetch('inventory.php?action=delete', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            removeRowFromTable(itemId);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while deleting the item', 'error');
    });
}

// Table manipulation functions
function addRowToTable(item) {
    const tableBody = document.querySelector('#inventoryTable tbody');
    const totalCost = item.quantity_on_hand * item.unit_cost;
    const statusClass = item.quantity_on_hand <= item.reorder_point ? 'status-low' : 'status-normal';
    
    // Check if "No inventory data found" row exists and remove it
    const noDataRow = tableBody.querySelector('tr td[colspan="9"]');
    if (noDataRow) {
        noDataRow.parentElement.remove();
    }
    
    const newRow = document.createElement('tr');
    newRow.setAttribute('data-id', item.item_id);
    newRow.innerHTML = `
        <td><strong>${item.stock_number}</strong></td>
        <td>${item.description}</td>
        <td>${item.unit}</td>
        <td>
            <span class="${statusClass}">
                ${item.quantity_on_hand}
            </span>
        </td>
        <td class="currency">${formatCurrency(item.unit_cost)}</td>
        <td class="currency">${formatCurrency(totalCost)}</td>
        <td>${item.reorder_point}</td>
        <td>${formatDate(item.created_at)}</td>
        <td>
            <button 
                class="btn edit-btn" 
                onclick="openEditModal(this)"
                data-id="${item.item_id}"
                data-stock_number="${item.stock_number}"
                data-description="${item.description}"
                data-unit="${item.unit}"
                data-reorder_point="${item.reorder_point}"
                data-unit_cost="${item.unit_cost}"
                data-quantity_on_hand="${item.quantity_on_hand}"
                title="Edit Item"
            >
                <i class="fas fa-edit"></i> Edit
            </button>
            <button 
                class="btn delete-btn" 
                onclick="deleteItem(${item.item_id})" 
                title="Delete Item"
            >
                <i class="fas fa-trash"></i> Delete
            </button>
        </td>
    `;
    
    tableBody.appendChild(newRow);
}

function updateRowInTable(item) {
    const row = document.querySelector(`tr[data-id="${item.item_id}"]`);
    if (row) {
        const totalCost = item.quantity_on_hand * item.unit_cost;
        const statusClass = item.quantity_on_hand <= item.reorder_point ? 'status-low' : 'status-normal';
        
        const cells = row.querySelectorAll('td');
        cells[0].innerHTML = `<strong>${item.stock_number}</strong>`;
        cells[1].textContent = item.description;
        cells[2].textContent = item.unit;
        cells[3].innerHTML = `<span class="${statusClass}">${item.quantity_on_hand}</span>`;
        cells[4].textContent = formatCurrency(item.unit_cost);
        cells[5].textContent = formatCurrency(totalCost);
        cells[6].textContent = item.reorder_point;
        
        // Update the edit button data attributes
        const editBtn = row.querySelector('.edit-btn');
        editBtn.setAttribute('data-stock_number', item.stock_number);
        editBtn.setAttribute('data-description', item.description);
        editBtn.setAttribute('data-unit', item.unit);
        editBtn.setAttribute('data-reorder_point', item.reorder_point);
        editBtn.setAttribute('data-unit_cost', item.unit_cost);
        editBtn.setAttribute('data-quantity_on_hand', item.quantity_on_hand);
    }
}

function removeRowFromTable(itemId) {
    const row = document.querySelector(`tr[data-id="${itemId}"]`);
    if (row) {
        row.remove();
        
        // Check if table is empty and add "No data" row
        const tableBody = document.querySelector('#inventoryTable tbody');
        if (tableBody.children.length === 0) {
            const noDataRow = document.createElement('tr');
            noDataRow.innerHTML = `
                <td colspan="9" style="text-align: center; color: #666; font-style: italic;">
                    <i class="fas fa-inbox"></i> No inventory data found.
                </td>
            `;
            tableBody.appendChild(noDataRow);
        }
    }
}

// Modal functions
function openEditModal(button) {
    const modal = document.getElementById('editModal');
    
    // Populate form fields
    document.getElementById('edit_item_id').value = button.getAttribute('data-id');
    document.getElementById('edit_stock_number').value = button.getAttribute('data-stock_number');
    document.getElementById('edit_description').value = button.getAttribute('data-description');
    document.getElementById('edit_unit').value = button.getAttribute('data-unit');
    document.getElementById('edit_reorder_point').value = button.getAttribute('data-reorder_point');
    document.getElementById('edit_unit_cost').value = button.getAttribute('data-unit_cost');
    document.getElementById('edit_quantity_on_hand').value = button.getAttribute('data-quantity_on_hand');

    modal.style.display = 'block';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    if (sessionStorage.getItem('itemAdded') === 'true') {
        showNotification('Item added successfully!', 'success');
        sessionStorage.removeItem('itemAdded'); // clear after showing
    }
    // Add form submission
    document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, true);
        
        const formData = new FormData(this);
        
        addItem(formData).finally(() => {
            setButtonLoading(submitBtn, false);
        });
    });
    
    // Edit form submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, true);
        
        const formData = new FormData(this);
        
        updateItem(formData).finally(() => {
            setButtonLoading(submitBtn, false);
        });
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === addModal) { 
        addModal.style.display = "none"; 
    }
    if (event.target === editModal) { 
        editModal.style.display = "none"; 
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('addModal').style.display = 'none';
        document.getElementById('editModal').style.display = 'none';
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('#inventoryTable tbody tr');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        rows.forEach(row => {
            const stock = row.children[0].textContent.toLowerCase();
            const desc = row.children[1].textContent.toLowerCase();
            const unit = row.children[2].textContent.toLowerCase();

            const match = stock.includes(keyword) || desc.includes(keyword) || unit.includes(keyword);
            row.style.display = match ? '' : 'none';
        });
    });
});
