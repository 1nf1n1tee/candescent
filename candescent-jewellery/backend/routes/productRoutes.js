const express = require('express');
const router = express.Router();
const {
  getProducts,
  getProductById,
  getCategories,
  createProduct,
  updateProduct,
  deleteProduct
} = require('../controllers/productController');
const { protect } = require('../middleware/auth');
const upload = require('../middleware/upload');

router.get('/', getProducts);
router.get('/categories/all', getCategories);
router.get('/:id', getProductById);
router.post('/', protect, upload.array('images', 4), createProduct);
router.put('/:id', protect, upload.array('images', 4), updateProduct);
router.delete('/:id', protect, deleteProduct);

module.exports = router;
