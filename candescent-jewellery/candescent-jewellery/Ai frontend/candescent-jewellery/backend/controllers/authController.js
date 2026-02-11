const jwt = require('jsonwebtoken');
const Admin = require('../models/Admin');

// Generate JWT token
const generateToken = (id) => {
  return jwt.sign({ id }, process.env.JWT_SECRET, {
    expiresIn: '30d'
  });
};

// @desc    Register admin (only for initial setup)
// @route   POST /api/auth/register
// @access  Public (should be restricted in production)
const registerAdmin = async (req, res) => {
  try {
    const { email, password } = req.body;

    // Check if admin already exists
    const adminExists = await Admin.findOne({ email });
    if (adminExists) {
      return res.status(400).json({ message: 'Admin already exists' });
    }

    // Check if this is the first admin (only allow one admin)
    const adminCount = await Admin.countDocuments();
    if (adminCount > 0) {
      return res.status(403).json({ message: 'Admin account already exists. Only one admin allowed.' });
    }

    // Create admin
    const admin = await Admin.create({
      email,
      password
    });

    if (admin) {
      res.status(201).json({
        _id: admin._id,
        email: admin.email,
        token: generateToken(admin._id)
      });
    }
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Login admin
// @route   POST /api/auth/login
// @access  Public
const loginAdmin = async (req, res) => {
  try {
    const { email, password } = req.body;

    // Check for admin
    const admin = await Admin.findOne({ email });

    if (admin && (await admin.comparePassword(password))) {
      res.json({
        _id: admin._id,
        email: admin.email,
        token: generateToken(admin._id)
      });
    } else {
      res.status(401).json({ message: 'Invalid email or password' });
    }
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Get admin profile
// @route   GET /api/auth/profile
// @access  Private
const getAdminProfile = async (req, res) => {
  try {
    const admin = await Admin.findById(req.admin._id).select('-password');
    res.json(admin);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

module.exports = {
  registerAdmin,
  loginAdmin,
  getAdminProfile
};
