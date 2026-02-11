const Order = require('../models/Order');

// Generate unique order number
const generateOrderNumber = async () => {
  const prefix = 'CND';
  const timestamp = Date.now().toString().slice(-8);
  const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
  
  let orderNumber = `${prefix}${timestamp}${random}`;
  
  // Check if order number already exists
  let existingOrder = await Order.findOne({ orderNumber });
  
  // If exists, generate a new one recursively
  if (existingOrder) {
    return generateOrderNumber();
  }
  
  return orderNumber;
};

module.exports = { generateOrderNumber };
