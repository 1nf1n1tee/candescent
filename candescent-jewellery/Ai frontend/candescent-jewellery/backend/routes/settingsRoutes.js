const express = require('express');
const router = express.Router();
const {
  getSettings,
  updateDeliveryCharges,
  updateDiscount,
  uploadLogo,
  addCarouselImage,
  deleteCarouselImage
} = require('../controllers/settingsController');
const { protect } = require('../middleware/auth');
const upload = require('../middleware/upload');

router.get('/', getSettings);
router.put('/delivery-charges', protect, updateDeliveryCharges);
router.put('/discount', protect, updateDiscount);
router.post('/logo', protect, upload.single('logo'), uploadLogo);
router.post('/carousel', protect, upload.single('image'), addCarouselImage);
router.delete('/carousel/:imageId', protect, deleteCarouselImage);

module.exports = router;
