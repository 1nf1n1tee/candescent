// ==========================================
// CANDESCÉNT JEWELLERY STORE - REACT COMPONENTS
// ==========================================
// Copy each section into its respective file path
// ==========================================

// FILE: src/components/common/ProtectedRoute.js
/*
import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';

const ProtectedRoute = ({ children }) => {
  const { admin, loading } = useAuth();
  
  if (loading) {
    return <div className="loading"><div className="spinner"></div></div>;
  }
  
  return admin ? children : <Navigate to="/admin_candescent_store" replace />;
};

export default ProtectedRoute;
*/

// ==========================================
// FILE: src/components/common/StockBar.js
/*
import React from 'react';

const StockBar = ({ stock }) => {
  const getClass = () => stock === 0 ? 'stock-out' : stock <= 4 ? 'stock-low' : 'stock-high';
  const getWidth = () => stock === 0 ? '100%' : stock <= 4 ? '40%' : '100%';
  
  return (
    <div className="stock-bar">
      <div className={`stock-bar-fill ${getClass()}`} style={{ width: getWidth() }}></div>
    </div>
  );
};

export default StockBar;
*/

// ==========================================
// FILE: src/components/common/Loading.js
/*
import React from 'react';

const Loading = () => (
  <div className="loading"><div className="spinner"></div></div>
);

export default Loading;
*/

// ==========================================
// FILE: src/components/common/Modal.js
/*
import React from 'react';
import { FaTimes } from 'react-icons/fa';

const Modal = ({ isOpen, onClose, title, children, footer }) => {
  if (!isOpen) return null;
  
  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={e => e.stopPropagation()}>
        <div className="modal-header">
          <h2>{title}</h2>
          <button className="close-modal" onClick={onClose}><FaTimes /></button>
        </div>
        <div className="modal-body">{children}</div>
        {footer && <div className="modal-footer">{footer}</div>}
      </div>
    </div>
  );
};

export default Modal;
*/

// ==========================================
// INSTRUCTIONS FOR CREATING REMAINING FILES
// ==========================================

/*
The backend is COMPLETE and ready to use!

For the frontend, you need to create the following files based on the patterns above:

CUSTOMER COMPONENTS:
1. Navbar.js - Top navigation with logo and cart
2. HeroCarousel.js - Auto-sliding banner (4 sec intervals)
3. ProductCard.js - Product display with image slideshow (2 sec), stock bar
4. ProductGrid.js - Grid layout for products
5. CategoryFilter.js - Sidebar with category buttons
6. Sidebar.js - Mobile navigation drawer
7. Cart.js - Shopping cart component

CUSTOMER PAGES:
1. HomePage.js - Main page with carousel, products, filters
2. ProductDetailPage.js - Individual product with image gallery
3. CartPage.js - Shopping cart view
4. CheckoutPage.js - Checkout form with delivery type

ADMIN COMPONENTS:
1. ProductForm.js - Add/Edit products with image upload
2. ProductList.js - Product table with CRUD
3. OrderList.js - Orders table with status
4. OrderDetails.js - Order modal with invoice
5. SettingsPanel.js - Site settings management

ADMIN PAGES:
1. AdminLogin.js - Login form
2. AdminRegister.js - Registration (one-time)
3. AdminDashboard.js - Admin panel with tabs

STYLING NOTES:
- Use className styles from global.css
- Minimalist design: #2c2c2c (primary), #f5f5f5 (secondary), #d4af37 (accent)
- Responsive: Mobile-first approach
- Icons: use react-icons (Fa*, Md*, Bs*)

API INTEGRATION:
- Import api and getImageUrl from utils/api.js
- Use CartContext for cart operations
- Use AuthContext for admin auth
- Use react-toastify for notifications

KEY FEATURES TO IMPLEMENT:
1. Auto-carousel (4 second intervals)
2. Product card image rotation (2 seconds)
3. Stock bars (green > 5, orange 1-4, red 0)
4. Cart persistence (localStorage via CartContext)
5. Image upload preview
6. Invoice download
7. Order status dropdown
8. Responsive sidebar

EXAMPLE COMPONENT STRUCTURE:

```javascript
import React, { useState, useEffect } from 'react';
import api, { getImageUrl } from '../../utils/api';
import { useCart } from '../../contexts/CartContext';
import { toast } from 'react-toastify';
import StockBar from '../common/StockBar';

const ProductCard = ({ product }) => {
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const { addToCart } = useCart();
  
  useEffect(() => {
    if (product.images && product.images.length > 1) {
      const interval = setInterval(() => {
        setCurrentImageIndex(prev => (prev + 1) % product.images.length);
      }, 2000);
      return () => clearInterval(interval);
    }
  }, [product.images]);
  
  const handleAddToCart = () => {
    if (product.stock > 0) {
      addToCart(product);
      toast.success('Added to cart!');
    }
  };
  
  return (
    <div className="product-card">
      <div className="product-image">
        {product.images && product.images[currentImageIndex] && (
          <img src={getImageUrl(product.images[currentImageIndex]._id)} alt={product.name} />
        )}
      </div>
      <h3>{product.name}</h3>
      <p>৳{product.price}</p>
      <StockBar stock={product.stock} />
      <button 
        onClick={handleAddToCart} 
        disabled={product.stock === 0}
        className={product.stock === 0 ? 'btn btn-secondary' : 'btn btn-primary'}
      >
        {product.stock === 0 ? 'Sold Out' : 'Add to Cart'}
      </button>
    </div>
  );
};

export default ProductCard;
```

Use this pattern for all components. The backend API is ready to handle all requests.

To get started quickly:
1. Create the simple components first (Loading, StockBar, Modal)
2. Then create basic pages (HomePage with static content)
3. Integrate API calls
4. Add advanced features (carousel, image rotation)
5. Style with CSS

Your backend is production-ready. Build the frontend progressively!
*/
