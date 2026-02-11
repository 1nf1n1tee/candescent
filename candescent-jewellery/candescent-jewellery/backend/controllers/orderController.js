const Order = require('../models/Order');
const Product = require('../models/Product');
const Settings = require('../models/Settings');
const { generateOrderNumber } = require('../utils/orderUtils');
const { generateInvoicePDF } = require('../utils/pdfGenerator');

// @desc    Create new order
// @route   POST /api/orders
// @access  Public
const createOrder = async (req, res) => {
  try {
    const { customerName, customerPhone, customerAddress, deliveryType, items } = req.body;

    // Validate delivery type
    if (!['Inside Dhaka', 'Outside Dhaka'].includes(deliveryType)) {
      return res.status(400).json({ message: 'Invalid delivery type' });
    }

    // Get settings for delivery charges and discount
    const settings = await Settings.getSettings();
    const deliveryCharge = deliveryType === 'Inside Dhaka' 
      ? settings.deliveryCharges.insideDhaka 
      : settings.deliveryCharges.outsideDhaka;
    const discount = settings.discount || 0;

    // Validate and process items
    let subtotal = 0;
    const orderItems = [];

    for (const item of items) {
      const product = await Product.findById(item.productId);
      
      if (!product) {
        return res.status(404).json({ message: `Product not found: ${item.productId}` });
      }

      if (product.stock < item.quantity) {
        return res.status(400).json({ 
          message: `Insufficient stock for ${product.name}. Available: ${product.stock}` 
        });
      }

      const itemSubtotal = product.price * item.quantity;
      subtotal += itemSubtotal;

      orderItems.push({
        product: product._id,
        productName: product.name,
        productPrice: product.price,
        quantity: item.quantity,
        subtotal: itemSubtotal
      });

      // Reduce stock
      product.stock -= item.quantity;
      await product.save();
    }

    const totalAmount = subtotal + deliveryCharge - discount;
    const orderNumber = await generateOrderNumber();

    const order = await Order.create({
      orderNumber,
      customerName,
      customerPhone,
      customerAddress,
      deliveryType,
      items: orderItems,
      subtotal,
      deliveryCharge,
      discount,
      totalAmount,
      status: 'Pending'
    });

    const populatedOrder = await Order.findById(order._id).populate('items.product');
    res.status(201).json(populatedOrder);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Get all orders
// @route   GET /api/orders
// @access  Private (Admin)
const getOrders = async (req, res) => {
  try {
    const orders = await Order.find()
      .populate('items.product')
      .sort({ createdAt: -1 });
    
    res.json(orders);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Get single order
// @route   GET /api/orders/:id
// @access  Private (Admin)
const getOrderById = async (req, res) => {
  try {
    const order = await Order.findById(req.params.id).populate('items.product');
    
    if (!order) {
      return res.status(404).json({ message: 'Order not found' });
    }
    
    res.json(order);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Update order status
// @route   PUT /api/orders/:id/status
// @access  Private (Admin)
const updateOrderStatus = async (req, res) => {
  try {
    const { status } = req.body;
    
    if (!['Pending', 'Shipped', 'Delivered'].includes(status)) {
      return res.status(400).json({ message: 'Invalid status' });
    }

    const order = await Order.findById(req.params.id);
    
    if (!order) {
      return res.status(404).json({ message: 'Order not found' });
    }

    order.status = status;
    await order.save();
    
    const updatedOrder = await Order.findById(order._id).populate('items.product');
    res.json(updatedOrder);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Generate order invoice PDF
// @route   GET /api/orders/:id/invoice
// @access  Private (Admin)
const getOrderInvoice = async (req, res) => {
  try {
    const order = await Order.findById(req.params.id).populate('items.product');
    
    if (!order) {
      return res.status(404).json({ message: 'Order not found' });
    }

    const settings = await Settings.getSettings();
    const pdfBuffer = await generateInvoicePDF(order, settings);

    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `attachment; filename=invoice-${order.orderNumber}.pdf`);
    res.send(pdfBuffer);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

module.exports = {
  createOrder,
  getOrders,
  getOrderById,
  updateOrderStatus,
  getOrderInvoice
};
