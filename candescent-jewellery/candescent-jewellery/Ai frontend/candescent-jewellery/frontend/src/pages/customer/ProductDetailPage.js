import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { FaMinus, FaPlus } from 'react-icons/fa';
import Navbar from '../../components/customer/Navbar';
import StockBar from '../../components/common/StockBar';
import Loading from '../../components/common/Loading';
import { useCart } from '../../contexts/CartContext';
import api, { getImageUrl } from '../../utils/api';
import './ProductDetailPage.css';

const ProductDetailPage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { addToCart } = useCart();
  
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedImage, setSelectedImage] = useState(0);
  const [quantity, setQuantity] = useState(1);

  useEffect(() => {
    fetchProduct();
  }, [id]);

  const fetchProduct = async () => {
    try {
      const { data } = await api.get(`/products/${id}`);
      setProduct(data);
    } catch (error) {
      console.error('Error fetching product:', error);
      toast.error('Product not found');
      navigate('/');
    } finally {
      setLoading(false);
    }
  };

  const handleQuantityChange = (change) => {
    const newQuantity = quantity + change;
    if (newQuantity >= 1 && newQuantity <= product.stock) {
      setQuantity(newQuantity);
    }
  };

  const handleAddToCart = () => {
    if (product.stock > 0) {
      addToCart(product, quantity);
      toast.success(`${quantity} ${product.name}(s) added to cart!`);
      setQuantity(1);
    }
  };

  const getStockText = () => {
    if (product.stock === 0) return 'Out of Stock';
    if (product.stock <= 4) return `Only ${product.stock} left - Limited Stock`;
    return `${product.stock} available`;
  };

  if (loading) return <Loading />;
  if (!product) return null;

  return (
    <div className="product-detail-page">
      <Navbar />
      <div className="container">
        <div className="product-detail-content">
          <div className="product-images">
            <div className="main-image">
              {product.images && product.images.length > 0 ? (
                <img
                  src={getImageUrl(product.images[selectedImage]._id)}
                  alt={product.name}
                />
              ) : (
                <div className="no-image">No Image Available</div>
              )}
            </div>
            {product.images && product.images.length > 1 && (
              <div className="image-thumbnails">
                {product.images.map((image, index) => (
                  <button
                    key={image._id}
                    className={`thumbnail ${index === selectedImage ? 'active' : ''}`}
                    onClick={() => setSelectedImage(index)}
                  >
                    <img src={getImageUrl(image._id)} alt={`${product.name} ${index + 1}`} />
                  </button>
                ))}
              </div>
            )}
          </div>

          <div className="product-details">
            <div className="sticky-info">
              <h1 className="product-title">{product.name}</h1>
              <p className="product-category">{product.category}</p>
              <div className="product-price">৳{product.price.toLocaleString()}</div>
              
              <div className="stock-info">
                <p className={`stock-status ${product.stock === 0 ? 'out' : product.stock <= 4 ? 'low' : 'high'}`}>
                  {getStockText()}
                </p>
                <StockBar stock={product.stock} />
              </div>

              <div className="product-description">
                <h3>Description</h3>
                <p>{product.description}</p>
              </div>

              {product.stock > 0 && (
                <div className="quantity-selector">
                  <label>Quantity:</label>
                  <div className="quantity-controls">
                    <button
                      onClick={() => handleQuantityChange(-1)}
                      disabled={quantity <= 1}
                      className="qty-btn"
                    >
                      <FaMinus />
                    </button>
                    <input
                      type="number"
                      value={quantity}
                      onChange={(e) => {
                        const val = parseInt(e.target.value) || 1;
                        if (val >= 1 && val <= product.stock) {
                          setQuantity(val);
                        }
                      }}
                      min="1"
                      max={product.stock}
                      className="qty-input"
                    />
                    <button
                      onClick={() => handleQuantityChange(1)}
                      disabled={quantity >= product.stock}
                      className="qty-btn"
                    >
                      <FaPlus />
                    </button>
                  </div>
                </div>
              )}

              <button
                onClick={handleAddToCart}
                disabled={product.stock === 0}
                className={`btn ${product.stock === 0 ? 'btn-secondary' : 'btn-primary'} add-to-cart-large`}
              >
                {product.stock === 0 ? 'Sold Out' : 'Add to Cart'}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetailPage;
