const Settings = require('../models/Settings');
const Image = require('../models/Image');

// @desc    Get settings
// @route   GET /api/settings
// @access  Public
const getSettings = async (req, res) => {
  try {
    const settings = await Settings.getSettings();
    const populatedSettings = await Settings.findById(settings._id)
      .populate('logo')
      .populate('carouselImages');
    
    res.json(populatedSettings);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Update delivery charges
// @route   PUT /api/settings/delivery-charges
// @access  Private (Admin)
const updateDeliveryCharges = async (req, res) => {
  try {
    const { insideDhaka, outsideDhaka } = req.body;
    
    const settings = await Settings.getSettings();
    
    if (insideDhaka !== undefined) {
      settings.deliveryCharges.insideDhaka = insideDhaka;
    }
    if (outsideDhaka !== undefined) {
      settings.deliveryCharges.outsideDhaka = outsideDhaka;
    }
    
    await settings.save();
    
    const updatedSettings = await Settings.findById(settings._id)
      .populate('logo')
      .populate('carouselImages');
    
    res.json(updatedSettings);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Update discount
// @route   PUT /api/settings/discount
// @access  Private (Admin)
const updateDiscount = async (req, res) => {
  try {
    const { discount } = req.body;
    
    const settings = await Settings.getSettings();
    settings.discount = discount;
    await settings.save();
    
    const updatedSettings = await Settings.findById(settings._id)
      .populate('logo')
      .populate('carouselImages');
    
    res.json(updatedSettings);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Upload logo
// @route   POST /api/settings/logo
// @access  Private (Admin)
const uploadLogo = async (req, res) => {
  try {
    if (!req.file) {
      return res.status(400).json({ message: 'No file uploaded' });
    }

    const settings = await Settings.getSettings();
    
    // Delete old logo if exists
    if (settings.logo) {
      await Image.findByIdAndDelete(settings.logo);
    }

    // Create new logo image
    const image = await Image.create({
      filename: req.file.originalname,
      contentType: req.file.mimetype,
      data: req.file.buffer,
      size: req.file.size
    });

    settings.logo = image._id;
    await settings.save();
    
    const updatedSettings = await Settings.findById(settings._id)
      .populate('logo')
      .populate('carouselImages');
    
    res.json(updatedSettings);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Add carousel image
// @route   POST /api/settings/carousel
// @access  Private (Admin)
const addCarouselImage = async (req, res) => {
  try {
    if (!req.file) {
      return res.status(400).json({ message: 'No file uploaded' });
    }

    const settings = await Settings.getSettings();

    const image = await Image.create({
      filename: req.file.originalname,
      contentType: req.file.mimetype,
      data: req.file.buffer,
      size: req.file.size
    });

    settings.carouselImages.push(image._id);
    await settings.save();
    
    const updatedSettings = await Settings.findById(settings._id)
      .populate('logo')
      .populate('carouselImages');
    
    res.json(updatedSettings);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// @desc    Delete carousel image
// @route   DELETE /api/settings/carousel/:imageId
// @access  Private (Admin)
const deleteCarouselImage = async (req, res) => {
  try {
    const settings = await Settings.getSettings();
    
    // Remove image from carousel array
    settings.carouselImages = settings.carouselImages.filter(
      img => img.toString() !== req.params.imageId
    );
    
    // Delete image from database
    await Image.findByIdAndDelete(req.params.imageId);
    await settings.save();
    
    const updatedSettings = await Settings.findById(settings._id)
      .populate('logo')
      .populate('carouselImages');
    
    res.json(updatedSettings);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

module.exports = {
  getSettings,
  updateDeliveryCharges,
  updateDiscount,
  uploadLogo,
  addCarouselImage,
  deleteCarouselImage
};
