const express = require('express');
const router = express.Router();
const {
  createOrder,
  getOrders,
  getOrderById,
  updateOrderStatus,
  getOrderInvoice
} = require('../controllers/orderController');
const { protect } = require('../middleware/auth');

router.post('/', createOrder);
router.get('/', protect, getOrders);
router.get('/:id', protect, getOrderById);
router.put('/:id/status', protect, updateOrderStatus);
router.get('/:id/invoice', protect, getOrderInvoice);

module.exports = router;
