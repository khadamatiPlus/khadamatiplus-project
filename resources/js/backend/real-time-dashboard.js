// Real-time Dashboard Updates
document.addEventListener('DOMContentLoaded', function() {
    // Initialize real-time updates
    initRealTimeUpdates();
    
    // Initialize call buttons
    initCallButtons();
});

function initRealTimeUpdates() {
    // Listen for Livewire events
    window.addEventListener('orderCreated', function(event) {
        console.log('New order created:', event.detail);
        showNotification('New Order', 'A new order has been created!', 'success');
        refreshDashboardData();
    });

    window.addEventListener('orderUpdated', function(event) {
        console.log('Order updated:', event.detail);
        showNotification('Order Updated', 'Order status has been updated!', 'info');
        refreshDashboardData();
    });

    // Auto-refresh dashboard data every 30 seconds
    setInterval(function() {
        refreshDashboardData();
    }, 30000);
}

function initCallButtons() {
    // Add click event listeners to call buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn[href^="tel:"]')) {
            e.preventDefault();
            const phoneNumber = e.target.closest('.btn[href^="tel:"]').getAttribute('href').replace('tel:', '');
            const buttonText = e.target.closest('.btn[href^="tel:"]').textContent.trim();
            
            if (confirm(`Are you sure you want to call ${buttonText} at ${phoneNumber}?`)) {
                window.location.href = `tel:${phoneNumber}`;
            }
        }
    });
}

// Global function for call confirmation
function confirmCall(phoneNumber, contactType) {
    if (!phoneNumber) {
        showNotification('Error', 'No phone number available', 'danger');
        return false;
    }
    
    const confirmed = confirm(`Are you sure you want to call ${contactType}?\n\nPhone: ${phoneNumber}\n\nThis will initiate a phone call.`);
    
    if (confirmed) {
        // Log the call attempt for admin tracking
        console.log(`Admin call initiated: ${contactType} - ${phoneNumber}`);
        
        // You can add AJAX call here to log the call attempt
        logCallAttempt(phoneNumber, contactType);
        
        return true; // Allow the href to proceed
    }
    
    return false; // Prevent the href from executing
}

// Function to log call attempts (optional)
function logCallAttempt(phoneNumber, contactType) {
    // You can implement this to log call attempts to your database
    fetch('/admin/log-call-attempt', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            phone_number: phoneNumber,
            contact_type: contactType,
            timestamp: new Date().toISOString()
        })
    }).catch(error => {
        console.log('Call logging failed:', error);
    });
}

function refreshDashboardData() {
    // Trigger Livewire component refresh
    if (window.Livewire) {
        Livewire.emit('refreshData');
    }
}

function showNotification(title, message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <strong>${title}</strong> ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(function() {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Function to make a call with confirmation
function makeCall(phoneNumber, contactType) {
    if (!phoneNumber) {
        showNotification('Error', 'No phone number available', 'danger');
        return;
    }
    
    if (confirm(`Are you sure you want to call ${contactType} at ${phoneNumber}?`)) {
        window.location.href = `tel:${phoneNumber}`;
    }
}

// WebSocket connection for real-time updates (if using WebSockets)
function initWebSocket() {
    const ws = new WebSocket('ws://localhost:8090');
    
    ws.onopen = function() {
        console.log('WebSocket connected');
    };
    
    ws.onmessage = function(event) {
        const data = JSON.parse(event.data);
        
        if (data.type === 'order_update') {
            refreshDashboardData();
            showNotification('Order Update', 'Order status has been updated!', 'info');
        }
    };
    
    ws.onerror = function(error) {
        console.error('WebSocket error:', error);
    };
    
    ws.onclose = function() {
        console.log('WebSocket disconnected');
        // Reconnect after 5 seconds
        setTimeout(initWebSocket, 5000);
    };
}

// Initialize WebSocket if needed
// initWebSocket(); 