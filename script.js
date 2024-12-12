// 表單驗證功能
function validateForm() {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const phone = document.getElementById('phone');
    
    let isValid = true;
    
    // 驗證姓名
    if (!name.value.trim()) {
        showError(name, '請輸入客戶姓名');
        isValid = false;
    }
    
    // 驗證電子郵件格式
    if (email.value) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value)) {
            showError(email, '請輸入有效的電子郵件地址');
            isValid = false;
        }
    }
    
    // 驗證電話號碼格式
    if (phone.value) {
        const phonePattern = /^[0-9+()-]{8,20}$/;
        if (!phonePattern.test(phone.value)) {
            showError(phone, '請輸入有效的電話號碼');
            isValid = false;
        }
    }
    
    return isValid;
}

// 顯示錯誤訊息
function showError(element, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    // 移除已存在的錯誤訊息
    const existingError = element.parentElement.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    element.parentElement.appendChild(errorDiv);
    element.classList.add('error');
    
    // 3秒後自動移除錯誤訊息
    setTimeout(() => {
        errorDiv.remove();
        element.classList.remove('error');
    }, 3000);
}

// 即時搜尋功能
let searchTimeout;
function handleSearch(input) {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(() => {
        const searchTerm = input.value.trim();
        if (searchTerm.length >= 2) {
            fetch(`search_customers.php?term=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    updateSearchResults(data);
                })
                .catch(error => console.error('搜尋錯誤:', error));
        }
    }, 300);
}

// 更新搜尋結果
function updateSearchResults(results) {
    const resultsContainer = document.getElementById('searchResults');
    if (!resultsContainer) return;
    
    resultsContainer.innerHTML = '';
    
    if (results.length === 0) {
        resultsContainer.innerHTML = '<p>沒有找到相關結果</p>';
        return;
    }
    
    const table = document.createElement('table');
    table.innerHTML = `
        <thead>
            <tr>
                <th>姓名</th>
                <th>電話</th>
                <th>電子郵件</th>
                <th>公司</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            ${results.map(customer => `
                <tr>
                    <td>${escapeHtml(customer.name)}</td>
                    <td>${escapeHtml(customer.phone)}</td>
                    <td>${escapeHtml(customer.email)}</td>
                    <td>${escapeHtml(customer.company_name)}</td>
                    <td>
                        <a href="edit_customer.php?id=${customer.id}" class="btn btn-primary">編輯</a>
                        <button onclick="confirmDelete(${customer.id})" class="btn btn-danger">刪除</button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    
    resultsContainer.appendChild(table);
}

// HTML 轉義函數
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// 確認刪除
function confirmDelete(id) {
    if (confirm('確定要刪除這個客戶資料嗎？此操作無法復原。')) {
        window.location.href = `delete_customer.php?id=${id}`;
    }
}

// 表格排序功能
function sortTable(columnIndex) {
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        return aValue.localeCompare(bValue, 'zh-TW');
    });
    
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
}

// 文件載入完成後執行
document.addEventListener('DOMContentLoaded', function() {
    // 綁定表單驗證
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }
    
    // 綁定搜尋功能
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            handleSearch(this);
        });
    }
}); 