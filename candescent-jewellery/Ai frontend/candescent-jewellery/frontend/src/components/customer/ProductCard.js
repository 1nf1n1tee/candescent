import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useCart } from '../../contexts/CartContext';
import { toast } from 'react-toastify';
import { getImageUrl } from '../../utils/api';
import StockBar from '../common/StockBar';
import './ProductCard.css';

const ProductCard = ({ product }) => {
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const { addToCart } = useCart();

  useEffect(() => {
    if (product.images && product.images.length > 1) {
      const interval = setInterval(() => {
        setCurrentImageIndex((prevIndex) => (prevIndex + 1) % product.images.length);
      }, 2000); // Rotate every 2 seconds

      return () => clearInterval(interval);
    }
  }, [product.images]);

  const handleAddToCart = (e) => {
    e.preventDefault();
    e.stopPropagation();
    
    if (product.stock > 0) {
      addToCart(product, 1);
      toast.success(`${product.name} added to cart!`);
    }
  };

  const getStockText = () => {
    if (product.stock === 0) return 'Out of Stock';
    if (product.stock <= 4) return 'Limited Stock';
    return 'In Stock';
  };

  return (
    <Link to={`/product/${product._id}`} className="product-card">
      <div className="product-image-container">
        {product.images && product.images.length > 0 ? (
          <img
            src={getImageUrl(product.images[currentImageIndex]._id)}
            alt={product.name}
            className="product-image"
          />
        ) : (
          <div className="product-image-placeholder">No Image</div>
        )}
        {product.stock <= 4 && product.stock > 0 && (
          <span className="stock-badge limited">Limited</span>
        )}
        {product.stock === 0 && (
          <span className="stock-badge out">Sold Out</span>
        )}
      </div>

      <div className="product-info">
        <h3 className="product-name">{product.name}</h3>
        <p className="product-category">{product.category}</p>
        <div className="product-price-row">
          <span className="product-price">৳{product.price.toLocaleString()}</span>
          <span className={`stock-text ${product.stock === 0 ? 'out' : product.stock <= 4 ? 'low' : 'high'}`}>
            {getStockText()}
          </span>
        </div>
        <StockBar stock={product.stock} />
        <button
          onClick={handleAddToCart}
          disabled={product.stock === 0}
          className={`btn ${product.stock === 0 ? 'btn-secondary' : 'btn-primary'} add-to-cart-btn`}
        >
          {product.stock === 0 ? 'Sold Out' : 'Add to Cart'}
        </button>
      </div>
    </Link>
  );
};

export default ProductCard;
