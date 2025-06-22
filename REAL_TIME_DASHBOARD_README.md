# Real-time Dashboard Implementation

This document describes the implementation of a real-time dashboard that updates automatically when orders are created or updated through the `requestOrder` and `updateOrderStatusByCustomer` APIs.

## Overview

The real-time dashboard provides live updates of order statistics and recent orders without requiring page refresh. It uses a combination of:

- **Livewire** for reactive components
- **Event Broadcasting** for real-time notifications
- **JavaScript** for client-side updates
- **WebSocket** support (optional)

## Features

### Real-time Updates
- **Order Statistics**: Total orders, revenue, and orders by status
- **Recent Orders Table**: Shows the latest 10 orders with details
- **Auto-refresh**: Updates every 30 seconds automatically
- **Event-driven Updates**: Immediate updates when orders are created/updated

### Dashboard Components
1. **Statistics Cards**:
   - Total Customers
   - Total Merchants
   - Total Orders
   - Total Revenue
   - Requested Orders
   - Accepted Orders
   - Completed Orders
   - Cancelled Orders

2. **Recent Orders Table**:
   - Order ID
   - Customer Name
   - Service Title
   - Merchant Name
   - Status (with color-coded badges)
   - Price
   - Creation Date

## Implementation Details

### 1. Livewire Component
**File**: `app/Http/Livewire/Backend/RealTimeOrders.php`

The main Livewire component that handles:
- Data fetching and display
- Event listening for updates
- Auto-refresh functionality

### 2. Events
**Files**: 
- `app/Domains/Delivery/Events/OrderCreated.php`
- `app/Domains/Delivery/Events/OrderUpdated.php`

Events are fired when:
- A new order is created via `requestOrder` API
- Order status is updated via `updateOrderStatusByCustomer` or `updateOrderStatusByMerchant` APIs

### 3. Order Model Updates
**File**: `app/Domains/Delivery/Models/Order.php`

The Order model has been updated to:
- Fire `OrderCreated` event on creation
- Fire `OrderUpdated` event when status changes

### 4. Dashboard Controller
**File**: `app/Http/Controllers/Backend/DashboardController.php`

Updated to provide initial data for the dashboard.

### 5. JavaScript Integration
**File**: `resources/js/backend/real-time-dashboard.js`

Handles:
- Event listening
- Notification display
- Auto-refresh coordination
- WebSocket integration (optional)

## API Integration

### Order Creation
**Endpoint**: `POST /api/customer/requestOrder`

**Parameters**:
```json
{
    "service_id": 1,
    "day": "Monday",
    "time": "14:30"
}
```

**Real-time Effect**: 
- Dashboard statistics update immediately
- New order appears in recent orders table
- Notification is shown

### Order Status Update (Customer)
**Endpoint**: `POST /api/customer/updateOrderStatusByCustomer`

**Parameters**:
```json
{
    "order_id": 1,
    "status": "completed",
    "notes": "Order completed successfully"
}
```

**Real-time Effect**:
- Order status updates in the table
- Statistics counters update
- Notification is shown

### Order Status Update (Merchant)
**Endpoint**: `POST /api/merchant/updateOrderStatusByMerchant`

**Parameters**:
```json
{
    "order_id": 1,
    "status": "accepted",
    "options": [1, 2, 3]
}
```

**Real-time Effect**:
- Order status updates in the table
- Statistics counters update
- Notification is shown

## Installation and Setup

### 1. Prerequisites
- Laravel 8+ with Livewire installed
- Database with orders table
- WebSocket server (optional)

### 2. Files to Include
Make sure these files are properly included:

```php
// In composer.json (if not already included)
"require": {
    "livewire/livewire": "^2.0"
}
```

### 3. Database
Ensure the orders table has the required fields:
- `id`, `customer_id`, `merchant_id`, `service_id`
- `status`, `price`, `total_price`
- `created_at`, `updated_at`

### 4. Configuration
Update your `.env` file for broadcasting (optional):
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

## Usage

### Accessing the Dashboard
1. Navigate to `/admin/dashboard`
2. The dashboard will load with current data
3. Real-time updates will begin automatically

### Testing the Real-time Features
1. Visit `/admin/test-real-time-page` for testing instructions
2. Use the API endpoints to create/update orders
3. Watch the dashboard update in real-time

### Manual Refresh
- The dashboard auto-refreshes every 30 seconds
- You can also trigger manual refresh via JavaScript

## Customization

### Adding New Statistics
1. Update the `RealTimeOrders` Livewire component
2. Add new properties and methods
3. Update the view template

### Changing Update Frequency
Modify the interval in `resources/js/backend/real-time-dashboard.js`:
```javascript
setInterval(function() {
    refreshDashboardData();
}, 30000); // Change 30000 to desired milliseconds
```

### Custom Notifications
Update the notification function in the JavaScript file:
```javascript
function showNotification(title, message, type = 'info') {
    // Custom notification logic
}
```

## Troubleshooting

### Common Issues

1. **Livewire not updating**:
   - Check if Livewire is properly installed
   - Verify component registration
   - Check browser console for errors

2. **Events not firing**:
   - Ensure events are properly dispatched
   - Check event listeners in Livewire component
   - Verify model observers are working

3. **JavaScript errors**:
   - Check browser console
   - Verify file paths are correct
   - Ensure jQuery/Bootstrap are loaded

### Debug Mode
Enable debug mode in `.env`:
```env
APP_DEBUG=true
LIVEWIRE_DEBUG=true
```

## Performance Considerations

1. **Database Queries**: The dashboard makes several queries. Consider caching for better performance.

2. **Event Broadcasting**: Use queue workers for event processing in production.

3. **WebSocket**: For high-traffic applications, consider using WebSockets instead of polling.

4. **Caching**: Implement Redis caching for frequently accessed data.

## Security

1. **Authentication**: Ensure only authorized users can access the dashboard
2. **Authorization**: Verify user permissions for viewing order data
3. **CSRF Protection**: All Livewire requests include CSRF protection
4. **Input Validation**: API endpoints validate all input data

## Future Enhancements

1. **Real-time Charts**: Add live charts showing order trends
2. **Push Notifications**: Browser push notifications for new orders
3. **Sound Alerts**: Audio notifications for important updates
4. **Filtering**: Real-time filtering of orders by status, date, etc.
5. **Export**: Real-time export of dashboard data

## Support

For issues or questions:
1. Check the Laravel and Livewire documentation
2. Review the browser console for JavaScript errors
3. Check the Laravel logs for PHP errors
4. Verify all dependencies are properly installed 