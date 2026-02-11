const Image = require('../models/Image');

// @desc    Get image by ID
// @route   GET /api/images/:id
// @access  Public
const getImage = async (req, res) => {
  try {
    const image = await Image.findById(req.params.id);
    
    if (!image) {
      return res.status(404).json({ message: 'Image not found' });
    }

    res.set('Content-Type', image.contentType);
    res.set('Cache-Control', 'public, max-age=31536000'); // Cache for 1 year
    res.send(image.data);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

module.exports = {
  getImage
};
