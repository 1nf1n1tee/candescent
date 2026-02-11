# Remaining Frontend Components

I've created the core architecture and many key components. Here are the remaining files you need to create:

## Already Created ✅
- Navbar (with logo, cart, sidebar)
- HeroCarousel (auto-slide 4 sec)
- ProductCard (image rotation 2 sec, stock bars)
- ProductGrid
- CategoryFilter
- HomePage
- ProductDetailPage
- All common components (StockBar, Loading, Modal, ProtectedRoute)
- All contexts (CartContext, AuthContext)
- API utilities
- Global CSS

## Still Need to Create ⚠️

### Customer Pages (3 files)
1. **CartPage.js** - Shopping cart with item management
2. **CheckoutPage.js** - Checkout form with delivery type selection

### Admin Pages (3 files)
3. **AdminLogin.js** - Admin login form
4. **AdminRegister.js** - Admin registration (one-time)
5. **AdminDashboard.js** - Main admin panel with tabs

### Admin Components (5 files)
6. **ProductForm.js** - Add/Edit products with images
7. **ProductList.js** - Product table with CRUD
8. **OrderList.js** - Orders table with status dropdown
9. **OrderDetails.js** - Order modal with invoice download
10. **SettingsPanel.js** - Site settings management

## Quick Generation Instructions

Use an AI assistant (ChatGPT/Claude) with this prompt:

"Create the following React components for an e-commerce store:

**CartPage.js**: Display cart items from CartContext, show image, name, price, quantity controls (+/-), remove button, subtotal. Show delivery type selection (Inside Dhaka / Outside Dhaka) from settings. Show order summary with subtotal, delivery charge, discount, total. 'Proceed to Checkout' button navigates to /checkout.

**CheckoutPage.js**: Form with fields: name (required), phone (required), address (required). Delivery type radio buttons (Inside/Outside Dhaka). Display cart summary. 'Place Order' button posts to /api/orders with items from cart, clears cart on success, shows success toast, navigates to home.

**AdminLogin.js**: Simple login form (email, password). POST to /api/auth/login. Store token in localStorage. Use AuthContext login function. Navigate to /admin_candescent_store/dashboard on success.

**AdminRegister.js**: Registration form (email, password, confirm password). POST to /api/auth/register. Show error if admin exists. Navigate to login on success.

**AdminDashboard.js**: Tabs component with 3 sections: Products (ProductList + ProductForm), Orders (OrderList), Settings (SettingsPanel). Logout button. Show admin email.

**ProductForm.js**: Form with name, category (creatable select), price, description, stock, multi-image upload (max 4). For edit mode, show existing images with delete option. Submit to POST/PUT /api/products with FormData.

**ProductList.js**: Table showing products with thumbnail, name, category, price, stock (with StockBar), edit/delete buttons. Fetch from /api/products. Edit opens ProductForm with pre-filled data.

**OrderList.js**: Table with order number, date, customer name, total, status dropdown (Pending/Shipped/Delivered). Clicking row opens OrderDetails modal. Status change PUTs to /api/orders/:id/status.

**OrderDetails.js**: Modal showing order number, date, customer (name, phone, address), delivery type, items table, totals. 'Download Invoice' button fetches /api/orders/:id/invoice as PDF blob.

**SettingsPanel.js**: Sections for: Logo upload, Delivery charges (Inside/Outside Dhaka inputs), Discount input, Carousel images (upload/delete). Each section has its own API endpoint.

Use: api from '../../utils/api', contexts, react-icons, react-toastify, minimalist styling from global.css"

