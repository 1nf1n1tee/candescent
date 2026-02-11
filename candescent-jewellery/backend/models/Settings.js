const mongoose = require('mongoose');

const settingsSchema = new mongoose.Schema({
  storeName: {
    type: String,
    default: 'Candescént'
  },
  logo: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Image'
  },
  deliveryCharges: {
    insideDhaka: {
      type: Number,
      default: 60
    },
    outsideDhaka: {
      type: Number,
      default: 120
    }
  },
  discount: {
    type: Number,
    default: 0,
    min: 0
  },
  carouselImages: [{
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Image'
  }],
  updatedAt: {
    type: Date,
    default: Date.now
  }
});

// Only allow one settings document
settingsSchema.statics.getSettings = async function() {
  let settings = await this.findOne();
  if (!settings) {
    settings = await this.create({});
  }
  return settings;
};

// Update the updatedAt timestamp before saving
settingsSchema.pre('save', function(next) {
  this.updatedAt = Date.now();
  next();
});

module.exports = mongoose.model('Settings', settingsSchema);
