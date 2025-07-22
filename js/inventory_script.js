let searchTimeout;
let stockCheckTimeout;

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const filter = this.value.toLowerCase();
        const table = document.getElementById('inventoryTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0) {
                const stockNumber = cells[0].textContent.toLowerCase();
                const description = cells[1].textContent.toLowerCase();
                const unit = cells[2].textContent.toLowerCase();

                if (stockNumber.includes(filter) || description.includes(filter) || unit.includes(filter)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    }, 300);
});

// Stock number check functionality
document.getElementById('add_stock_number').addEventListener('input', function() {
    const stockNumber = this.value.trim();
    const statusDiv = document.getElementById('stock_status');
    
    // Clear previous timeout
    clearTimeout(stockCheckTimeout);
    
    if (stockNumber === '') {
        clearAddForm();
        statusDiv.innerHTML = '';
        return;
    }
    
    // Set timeout for stock check
    stockCheckTimeout = setTimeout(() => {
        checkStockNumber(stockNumber);
    }, 500);
});

function checkStockNumber(stockNumber) {
    const statusDiv = document.getElementById('stock_status');
    statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking stock number...';
    
    fetch('?action=check_stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `stock_number=${encodeURIComponent(stockNumber)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            // Stock number exists - populate fields but allow unit cost to be editable
            statusDiv.innerHTML = '<i class="fas fa-info-circle text-info"></i> Existing item found. You can add different unit cost.';
            statusDiv.className = 'stock-status info';
            
            document.getElementById('add_description').value = data.item.description;
            document.getElementById('add_unit').value = data.item.unit;
            document.getElementById('add_reorder_point').value = data.item.reorder_point;
            document.getElementById('add_unit_cost').value = data.item.unit_cost;
            
            // Make only description, unit, and reorder_point readonly
            document.getElementById('add_description').readOnly = true;
            document.getElementById('add_unit').readOnly = true;
            document.getElementById('add_reorder_point').readOnly = true;
            document.getElementById('add_unit_cost').readOnly = false; // Allow editing unit cost
            
            // Keep quantity field editable but clear it
            document.getElementById('add_quantity_on_hand').value = '';
            document.getElementById('add_quantity_on_hand').focus();
        } else {
            // Stock number doesn't exist - enable all fields for new item
            statusDiv.innerHTML = '<i class="fas fa-plus-circle text-success"></i> New item - fill in all details.';
            statusDiv.className = 'stock-status success';
            
            enableAddFormFields();
        }
    })
    .catch(error => {
        statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-error"></i> Error checking stock number.';
        statusDiv.className = 'stock-status error';
        console.error('Error:', error);
    });
}

function clearAddForm() {
    // Clear all form fields except stock number
    document.getElementById('add_description').value = '';
    document.getElementById('add_unit').value = '';
    document.getElementById('add_reorder_point').value = '';
    document.getElementById('add_unit_cost').value = '';
    document.getElementById('add_quantity_on_hand').value = '';
    
    // Make fields readonly
    document.getElementById('add_description').readOnly = true;
    document.getElementById('add_unit').readOnly = true;
    document.getElementById('add_reorder_point').readOnly = true;
    document.getElementById('add_unit_cost').readOnly = true; // This will be changed when stock exists
}

function enableAddFormFields() {
    // Enable all fields for new item entry
    document.getElementById('add_description').readOnly = false;
    document.getElementById('add_unit').readOnly = false;
    document.getElementById('add_reorder_point').readOnly = false;
    document.getElementById('add_unit_cost').readOnly = false; // Keep this enabled
    
    // Clear fields
    document.getElementById('add_description').value = '';
    document.getElementById('add_unit').value = '';
    document.getElementById('add_reorder_point').value = '';
    document.getElementById('add_unit_cost').value = '';
    document.getElementById('add_quantity_on_hand').value = '';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
    // Reset form when closing
    document.getElementById('addForm').reset();
    clearAddForm();
    document.getElementById('stock_status').innerHTML = '';
}

// Add item form submission
document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('?action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        return response.text().then(text => {
            console.log('Raw response:', text); // This will help debug
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            
            if (data.updated) {
                // Refresh the page to show updated quantity
                location.reload();
            } else if (data.item) {
                // Add new row to table
                addRowToTable(data.item);
            }
            
            closeAddModal();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred while adding the item.', 'error');
        console.error('Error:', error);
    });
});


// Edit item form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('?action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Log the response to see what we're getting
        return response.text().then(text => {
            console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            document.getElementById('editModal').style.display = 'none';
            
            // Refresh the page to show updated data
            setTimeout(() => {
                location.reload();
            }, 500); // Small delay to let user see the success message
            
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred while updating the item.', 'error');
        console.error('Error:', error);
    });
});

function openEditModal(button) {
    const modal = document.getElementById('editModal');
    const itemId = button.dataset.id;
    
    document.getElementById('edit_item_id').value = itemId;
    document.getElementById('edit_stock_number').value = button.dataset.stock_number;
    document.getElementById('edit_description').value = button.dataset.description;
    document.getElementById('edit_unit').value = button.dataset.unit;
    document.getElementById('edit_reorder_point').value = button.dataset.reorder_point;
    document.getElementById('edit_unit_cost').value = button.dataset.unit_cost;
    document.getElementById('edit_quantity_on_hand').value = button.dataset.quantity_on_hand;
    
    // Check if item has multiple entries (initial + inventory entries)
    checkMultipleEntries(itemId);
    
    modal.style.display = 'block';
}
function checkMultipleEntries(itemId) {
    fetch('?action=check_entries', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}`
    })
    .then(response => response.json())
    .then(data => {
        const unitCostField = document.getElementById('edit_unit_cost');
        const quantityField = document.getElementById('edit_quantity_on_hand');
        const unitCostLabel = unitCostField.previousElementSibling;
        const quantityLabel = quantityField.previousElementSibling;
        
        if (data.hasMultipleEntries) {
            // Hide the fields and labels entirely
            unitCostField.style.display = 'none';
            quantityField.style.display = 'none';
            unitCostLabel.style.display = 'none';
            quantityLabel.style.display = 'none';
            
            // Add a note explaining why these fields are hidden
            if (!document.getElementById('multiple-entries-note')) {
                const note = document.createElement('div');
                note.id = 'multiple-entries-note';
                note.innerHTML = '<p style="color: #666; font-style: italic; margin: 10px 0;"><i class="fas fa-info-circle"></i> Unit cost and quantity cannot be edited - this item has multiple inventory entries.</p>';
                quantityLabel.parentNode.insertBefore(note, quantityLabel.nextSibling);
            }
        } else {
            // Show the fields and labels
            unitCostField.style.display = '';
            quantityField.style.display = '';
            unitCostLabel.style.display = '';
            quantityLabel.style.display = '';
            
            // Remove the note if it exists
            const note = document.getElementById('multiple-entries-note');
            if (note) note.remove();
            
            // Enable fields
            unitCostField.readOnly = false;
            quantityField.readOnly = false;
            unitCostField.style.backgroundColor = '';
            quantityField.style.backgroundColor = '';
        }
    })
    .catch(error => {
        console.error('Error checking entries:', error);
    });
}

function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        fetch('?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                removeRowFromTable(id);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('An error occurred while deleting the item.', 'error');
            console.error('Error:', error);
        });
    }
}

function addRowToTable(item) {
    const table = document.getElementById('inventoryTable').getElementsByTagName('tbody')[0];
    const totalCost = item.quantity_on_hand * item.unit_cost;
    const statusClass = item.quantity_on_hand <= item.reorder_point ? 'status-low' : 'status-normal';
    
    const newRow = table.insertRow();
    newRow.setAttribute('data-id', item.item_id);
    newRow.innerHTML = `
        <td><strong>${item.stock_number}</strong></td>
        <td>${item.description}</td>
        <td>${item.unit}</td>
        <td class="quantity-cell">
            <div class="main-quantity">
                <span class="${statusClass}">${item.total_quantity || item.quantity_on_hand}</span>
            </div>
            <div class="sub-entries" id="sub-entries-${item.item_id}">
                <!-- Will be populated on page reload -->
            </div>
        </td>
        <td class="cost-cell">
        <div class="main-cost">₱ ${Number(item.average_unit_cost || item.unit_cost).toLocaleString('en-PH', {minimumFractionDigits: 2})}${item.has_multiple_entries ? ' (average)' : ''}</div>            <div class="sub-entries" id="sub-cost-${item.item_id}"></div>
        </td>
        <td class="currency">₱ ${totalCost.toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
        <td>${item.reorder_point}</td>
        <td>${new Date(item.created_at).toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</td>
        <td>
            <button class='btn edit-btn' onclick='openEditModal(this)'
                data-id='${item.item_id}'
                data-stock_number='${item.stock_number}'
                data-description='${item.description}'
                data-unit='${item.unit}'
                data-reorder_point='${item.reorder_point}'
                data-unit_cost='${item.unit_cost}'
                data-quantity_on_hand='${item.quantity_on_hand}'
                title='Edit Item'>
                <i class='fas fa-edit'></i> Edit
            </button>
            <button class='btn delete-btn' onclick='deleteItem(${item.item_id})' title='Delete Item'>
                <i class='fas fa-trash'></i> Delete
            </button>
        </td>
    `;
}

function updateRowInTable(item) {
    const row = document.querySelector(`tr[data-id="${item.item_id}"]`);
    if (row) {
        const totalCost = item.quantity_on_hand * item.unit_cost;
        const statusClass = item.quantity_on_hand <= item.reorder_point ? 'status-low' : 'status-normal';
        
        row.innerHTML = `
            <td><strong>${item.stock_number}</strong></td>
            <td>${item.description}</td>
            <td>${item.unit}</td>
            <td class="quantity-cell">
            <div class="main-quantity">
                <span class="${statusClass}">${item.quantity_on_hand}</span>
            </div>
            <div class="sub-entries" id="sub-entries-${item.item_id}">
                <!-- Sub-entries will be preserved -->
            </div>
        </td>
            <td class="cost-cell">
                <div class="main-cost">₱ ${Number(item.average_unit_cost || item.unit_cost).toLocaleString('en-PH', {minimumFractionDigits: 2})}${item.has_multiple_entries ? ' (average)' : ''}</div>
                <div class="sub-entries" id="sub-cost-${item.item_id}"></div>
            </td>            <td class="currency">₱ ${totalCost.toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
            <td>${item.reorder_point}</td>
            <td>${new Date().toLocaleDateString('en-PH', {month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</td>
            <td>
                <button class='btn edit-btn' onclick='openEditModal(this)'
                    data-id='${item.item_id}'
                    data-stock_number='${item.stock_number}'
                    data-description='${item.description}'
                    data-unit='${item.unit}'
                    data-reorder_point='${item.reorder_point}'
                    data-unit_cost='${item.unit_cost}'
                    data-quantity_on_hand='${item.quantity_on_hand}'
                    title='Edit Item'>
                    <i class='fas fa-edit'></i> Edit
                </button>
                <button class='btn delete-btn' onclick='deleteItem(${item.item_id})' title='Delete Item'>
                    <i class='fas fa-trash'></i> Delete
                </button>
            </td>
        `;
    }
}

function removeRowFromTable(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        row.remove();
    }
}

function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}
function clearEntries(itemId) {
    if (confirm('Are you sure you want to clear all inventory entries? This will reset the item to its initial quantity and cost.')) {
        fetch('?action=clear_entries', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Refresh the page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('An error occurred while clearing entries.', 'error');
            console.error('Error:', error);
        });
    }
}

