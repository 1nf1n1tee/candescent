# Candescént Jewellery E-Commerce Store

A full-stack MERN e-commerce application for Candescént Jewellery Store.

## 🚀 Features

### Customer Features
- Responsive homepage with hero carousel
- Product browsing with category filtering
- Real-time product image slideshow on cards
- Stock level indicators (Green/Orange/Red bars)
- Product detail pages with image gallery
- Shopping cart with persistent storage
- Checkout with delivery type selection
- No login required for customers

### Admin Features
- Secure admin authentication (single admin account)
- Product management (CRUD operations)
- Multi-image upload (up to 4 images per product)
- Order management with status tracking
- Invoice generation (view & download PDF)
- Site settings:
  - Logo upload
  - Delivery charge configuration
  - Discount management
  - Carousel image management
- Visual stock indicators in admin panel

## 📋 Prerequisites

- Node.js (v14 or higher)
- MongoDB Atlas account
- npm or yarn

## 🛠️ Installation

### 1. Clone the repository
```bash
git clone <your-repo-url>
cd candescent-jewellery
```

### 2. Backend Setup

```bash
cd backend
npm install
```

Create `.env` file in backend directory:
```env
MONGODB_URI=your_mongodb_atlas_connection_string
JWT_SECRET=your_super_secret_jwt_key_change_this
PORT=5000
ADMIN_EMAIL=admin@candescent.com
ADMIN_PASSWORD=admin123
FRONTEND_URL=http://localhost:3000
```

Start the backend server:
```bash
npm run dev
```

The backend will run on http://localhost:5000

### 3. Frontend Setup

```bash
cd frontend
npm install
```

Create `.env` file in frontend directory:
```env
REACT_APP_API_URL=http://localhost:5000/api
```

Start the frontend:
```bash
npm start
```

The frontend will run on http://localhost:3000

## 📝 First Time Setup

### Create Admin Account

1. Start both backend and frontend servers
2. Navigate to: http://localhost:3000/admin_candescent_store/register
3. Register with your admin credentials
4. Login at: http://localhost:3000/admin_candescent_store

**Note:** Only ONE admin account can be created. Subsequent registration attempts will be rejected.

## 🎯 Usage

### Customer Flow
1. Visit http://localhost:3000
2. Browse products or filter by category
3. Click on product for details
4. Add items to cart
5. Proceed to checkout
6. Fill delivery information
7. Place order

### Admin Flow
1. Login at http://localhost:3000/admin_candescent_store
2. **Products**: Add/Edit/Delete products with images
3. **Orders**: View orders, change status, download invoices
4. **Settings**: Configure logo, delivery charges, discount, carousel

## 🗂️ Project Structure

```
candescent-jewellery/
├── backend/
│   ├── config/
│   │   └── db.js
│   ├── controllers/
│   │   ├── authController.js
│   │   ├── productController.js
│   │   ├── orderController.js
│   │   ├── settingsController.js
│   │   └── imageController.js
│   ├── middleware/
│   │   ├── auth.js
│   │   └── upload.js
│   ├── models/
│   │   ├── Admin.js
│   │   ├── Product.js
│   │   ├── Order.js
│   │   ├── Settings.js
│   │   └── Image.js
│   ├── routes/
│   │   ├── authRoutes.js
│   │   ├── productRoutes.js
│   │   ├── orderRoutes.js
│   │   ├── settingsRoutes.js
│   │   └── imageRoutes.js
│   ├── utils/
│   │   ├── orderUtils.js
│   │   └── pdfGenerator.js
│   ├── .env.example
│   ├── .gitignore
│   ├── package.json
│   └── server.js
├── frontend/
│   ├── public/
│   │   └── index.html
│   ├── src/
│   │   ├── components/
│   │   │   ├── customer/
│   │   │   ├── admin/
│   │   │   └── common/
│   │   ├── contexts/
│   │   │   ├── CartContext.js
│   │   │   └── AuthContext.js
│   │   ├── pages/
│   │   ├── styles/
│   │   │   └── global.css
│   │   ├── utils/
│   │   │   └── api.js
│   │   ├── App.js
│   │   └── index.js
│   ├── .env.example
│   ├── .gitignore
│   └── package.json
└── README.md
```

## 🔌 API Endpoints

### Authentication
- POST `/api/auth/register` - Register admin (one-time)
- POST `/api/auth/login` - Admin login
- GET `/api/auth/profile` - Get admin profile (protected)

### Products
- GET `/api/products` - Get all products (with optional category filter)
- GET `/api/products/:id` - Get single product
- GET `/api/products/categories/all` - Get all categories
- POST `/api/products` - Create product (protected, with images)
- PUT `/api/products/:id` - Update product (protected, with images)
- DELETE `/api/products/:id` - Delete product (protected)

### Orders
- POST `/api/orders` - Create order
- GET `/api/orders` - Get all orders (protected)
- GET `/api/orders/:id` - Get single order (protected)
- PUT `/api/orders/:id/status` - Update order status (protected)
- GET `/api/orders/:id/invoice` - Download invoice PDF (protected)

### Settings
- GET `/api/settings` - Get settings
- PUT `/api/settings/delivery-charges` - Update delivery charges (protected)
- PUT `/api/settings/discount` - Update discount (protected)
- POST `/api/settings/logo` - Upload logo (protected)
- POST `/api/settings/carousel` - Add carousel image (protected)
- DELETE `/api/settings/carousel/:imageId` - Delete carousel image (protected)

### Images
- GET `/api/images/:id` - Get image by ID

## 🎨 Design Features

- **Minimalist & Elegant**: Clean design with sophisticated color palette
- **Responsive**: Works on desktop, tablet, and mobile
- **User-Friendly**: Intuitive navigation and clear CTAs
- **Stock Indicators**: Visual color-coded stock bars
- **Auto-Carousel**: Hero images auto-slide every 4 seconds
- **Product Slideshow**: Product images cycle every 2 seconds

## 🔐 Security Features

- JWT-based authentication
- Password hashing with bcrypt
- Protected admin routes
- Single admin account restriction
- Secret admin path (`/admin_candescent_store`)

## 💾 Data Storage

- **Database**: MongoDB Atlas
- **Images**: Stored as binary data in MongoDB (can be migrated to Cloudinary later)
- **Cart**: Local storage (persists across sessions)

## 🚢 Deployment

### Backend Deployment (Render/Railway)
1. Push code to GitHub
2. Connect repository to Render/Railway
3. Set environment variables
4. Deploy

### Frontend Deployment (Vercel/Netlify)
1. Push code to GitHub
2. Connect repository to Vercel/Netlify
3. Set environment variable: `REACT_APP_API_URL=<your-backend-url>/api`
4. Deploy

## 📦 Dependencies

### Backend
- express - Web framework
- mongoose - MongoDB ODM
- bcryptjs - Password hashing
- jsonwebtoken - JWT authentication
- multer - File uploads
- pdfkit - PDF generation
- cors - CORS middleware
- dotenv - Environment variables

### Frontend
- react - UI library
- react-router-dom - Routing
- axios - HTTP client
- react-toastify - Notifications
- react-icons - Icon library

## 🐛 Troubleshooting

### MongoDB Connection Issues
- Verify MongoDB Atlas connection string
- Check IP whitelist in MongoDB Atlas
- Ensure database user has correct permissions

### Image Upload Issues
- Check file size (max 5MB)
- Verify supported formats: JPG, JPEG, PNG, WEBP
- Ensure MongoDB has sufficient storage

### CORS Errors
- Verify FRONTEND_URL in backend .env
- Check API_URL in frontend .env

## 📧 Support

For issues or questions, please create an issue in the GitHub repository.

## 📄 License

This project is licensed under the ISC License.
