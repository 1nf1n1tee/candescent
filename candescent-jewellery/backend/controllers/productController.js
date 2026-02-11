const Product = require('../models/Product');
const Image = require('../models/Image');

// @desc    Get all products
// @route   GET /api/products
// @access  Public
const getProducts = async (req, res) => {
  try {
    const { category } = req.query;
    
    let query = {};
    if (category && category !== 'All') {
      query.category = category;
    }

    const products = await Product.find(query)
      .populate('images')
      .sort({ createdAt: -1 });
    
    res.json(products);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Get single product
// @route   GET /api/products/:id
// @access  Public
const getProductById = async (req, res) => {
  try {
    const product = await Product.findById(req.params.id).populate('images');
    
    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }
    
    res.json(product);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Get all unique categories
// @route   GET /api/products/categories/all
// @access  Public
const getCategories = async (req, res) => {
  try {
    const categories = await Product.distinct('category');
    res.json(categories);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Create product
// @route   POST /api/products
// @access  Private (Admin)
const createProduct = async (req, res) => {
  try {
    const { name, category, price, description, stock } = req.body;

    // Handle image uploads
    const imageIds = [];
    if (req.files && req.files.length > 0) {
      for (const file of req.files) {
        const image = await Image.create({
          filename: file.originalname,
          contentType: file.mimetype,
          data: file.buffer,
          size: file.size
        });
        imageIds.push(image._id);
      }
    }

    const product = await Product.create({
      name,
      category,
      price,
      description,
      stock,
      images: imageIds
    });

    const populatedProduct = await Product.findById(product._id).populate('images');
    res.status(201).json(populatedProduct);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Update product
// @route   PUT /api/products/:id
// @access  Private (Admin)
const updateProduct = async (req, res) => {
  try {
    const { name, category, price, description, stock, deleteImages } = req.body;
    
    const product = await Product.findById(req.params.id);
    
    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }

    // Update fields
    product.name = name || product.name;
    product.category = category || product.category;
    product.price = price !== undefined ? price : product.price;
    product.description = description || product.description;
    product.stock = stock !== undefined ? stock : product.stock;

    // Handle image deletion
    if (deleteImages && Array.isArray(deleteImages) && deleteImages.length > 0) {
      for (const imageId of deleteImages) {
        await Image.findByIdAndDelete(imageId);
        product.images = product.images.filter(img => img.toString() !== imageId);
      }
    }

    // Handle new image uploads
    if (req.files && req.files.length > 0) {
      const maxImages = 4;
      const currentImageCount = product.images.length;
      const availableSlots = maxImages - currentImageCount;

      if (availableSlots > 0) {
        const filesToUpload = req.files.slice(0, availableSlots);
        
        for (const file of filesToUpload) {
          const image = await Image.create({
            filename: file.originalname,
            contentType: file.mimetype,
            data: file.buffer,
            size: file.size
          });
          product.images.push(image._id);
        }
      }
    }

    await product.save();
    
    const updatedProduct = await Product.findById(product._id).populate('images');
    res.json(updatedProduct);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Delete product
// @route   DELETE /api/products/:id
// @access  Private (Admin)
const deleteProduct = async (req, res) => {
  try {
    const product = await Product.findById(req.params.id);
    
    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }

    // Delete associated images
    if (product.images && product.images.length > 0) {
      for (const imageId of product.images) {
        await Image.findByIdAndDelete(imageId);
      }
    }

    await Product.findByIdAndDelete(req.params.id);
    
    res.json({ message: 'Product deleted successfully' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

module.exports = {
  getProducts,
  getProductById,
  getCategories,
  createProduct,
  updateProduct,
  deleteProduct
};
